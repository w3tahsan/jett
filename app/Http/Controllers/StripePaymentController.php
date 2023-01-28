<?php

namespace App\Http\Controllers;

use App\Models\Stripeorder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Session;
use Stripe;
use App\Mail\InvoiceMail;
use App\Models\BillingDetails;
use App\Models\Cart;
use App\Models\City;
use App\Models\Country;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Support\Facades\Auth;
use Str;
use Illuminate\Support\Facades\Mail;

class StripePaymentController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function stripe()
    {
        $data = session('data');
        $stripe_id = Stripeorder::insertGetId([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'company'=>$data['company'],
            'zip'=>$data['zip'],
            'country_id'=>$data['country_id'],
            'city_id'=>$data['city_id'],
            'notes'=>$data['notes'],
            'charge'=>$data['charge'],
            'payment_method'=>$data['payment_method'],
            'sub_total'=>$data['sub_total'],
            'discount'=>$data['discount'],
            'customer_id'=>$data['customer_id'],
            'total'=>$data['sub_total']+$data['charge'],
            'created_at'=>Carbon::now(),
        ]);

        return view('frontend.stripe', [
            'data' => $data,
            'stripe_id' => $stripe_id,
        ]);

    }

    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function stripePost(Request $request)
    {
        $data = Stripeorder::where('id', $request->stripe_id)->get();

        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        Stripe\Charge::create ([
                "amount" => $data->first()->total * 100,
                "currency" => "inr",
                "source" => $request->stripeToken,
                "description" => "Test payment from itsolutionstuff.com."
        ]);

        $order_id = '#'.'JETT'.'-'.rand(999999,100000);
            Order::insert([
                'order_id'=>$order_id,
                'customer_id'=>$data->first()->customer_id,
                'subtotal'=>$data->first()->sub_total,
                'discount'=>$data->first()->discount,
                'charge'=>$data->first()->charge,
                'total'=>$data->first()->total,
                'payment_method'=>$data->first()->payment_method,
                'created_at'=>Carbon::now(),
            ]);

            BillingDetails::insert([
                'order_id'=>$order_id,
                'customer_id'=>$data->first()->customer_id,
                'name'=>$data->first()->name,
                'email'=>$data->first()->email,
                'company'=>$data->first()->company,
                'phone'=>$data->first()->phone,
                'address'=>$data->first()->address,
                'zip'=>$data->first()->zip,
                'country_id'=>$data->first()->country_id,
                'city_id'=>$data->first()->city_id,
                'notes'=>$data->first()->notes,
                'created_at'=>Carbon::now(),
            ]);

            $carts = Cart::where('customer_id', $data->first()->customer_id)->get();

            foreach($carts as $cart){
                OrderProduct::insert([
                    'order_id'=>$order_id,
                    'customer_id'=>$data->first()->customer_id,
                    'product_id'=>$cart->product_id,
                    'price'=>$cart->rel_to_product->after_discount,
                    'color_id'=>$cart->color_id,
                    'size_id'=>$cart->size_id,
                    'quantity'=>$cart->quantity,
                    'created_at'=>Carbon::now(),
                ]);

                Inventory::where('product_id', $cart->product_id)->where('color_id', $cart->color_id)->where('size_id', $cart->size_id)->decrement('quantity', $cart->quantity);
            }

            // Mail::to($data->first()->email)->send(new InvoiceMail($order_id));

            //sms
            // $url = "https://bulksmsbd.net/api/smsapi";
            // $api_key = "sv4Vi9NaBbpl3pyuxwNd";
            // $senderid = "sezan";
            // $number = $data->first()->phone;
            // $message = "Congratulations! your order has been successfully placed! Please ready TK: ".$data->first()->sub_total+$data->first()->charge;

            $customer_id = $data->first()->customer_id;

            // $data = [
            //     "api_key" => $api_key,
            //     "senderid" => $senderid,
            //     "number" => $number,
            //     "message" => $message
            // ];
            // $ch = curl_init();
            // curl_setopt($ch, CURLOPT_URL, $url);
            // curl_setopt($ch, CURLOPT_POST, 1);
            // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            // $response = curl_exec($ch);
            // curl_close($ch);
            // return $response;


            Cart::where('customer_id', $customer_id)->delete();

            return redirect()->route('order.success')->withOrder($order_id);
    }
}
