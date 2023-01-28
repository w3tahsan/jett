<?php

namespace App\Http\Controllers;

use App\Mail\InvoiceMail;
use App\Models\BillingDetails;
use App\Models\Cart;
use App\Models\City;
use App\Models\Country;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderProduct;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Str;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    function checkout(){
        $carts = Cart::where('customer_id', Auth::guard('customerlogin')->id())->get();
        $countries = Country::all();
        return view('frontend.checkout', [
            'carts'=>$carts,
            'countries'=>$countries,
        ]);
    }

    function getCity(Request $request){
        $str = '<option value="">-- Select City --</option>';
        $cities = City::where('country_id', $request->country_id)->get();

        foreach($cities as $city){
            $str .= '<option value="'.$city->id.'">'.$city->name.'</option>';
        }

        echo $str;
    }

    function order_store(Request $request){
       if($request->payment_method == 1){
            $order_id = '#'.'JETT'.'-'.rand(999999,100000);
            Order::insert([
                'order_id'=>$order_id,
                'customer_id'=>Auth::guard('customerlogin')->id(),
                'subtotal'=>$request->sub_total,
                'discount'=>$request->discount,
                'charge'=>$request->charge,
                'total'=>$request->sub_total+$request->charge,
                'payment_method'=>$request->payment_method,
                'created_at'=>Carbon::now(),
            ]);

            BillingDetails::insert([
                'order_id'=>$order_id,
                'customer_id'=>Auth::guard('customerlogin')->id(),
                'name'=>$request->name,
                'email'=>$request->email,
                'company'=>$request->company,
                'phone'=>$request->phone,
                'address'=>$request->address,
                'zip'=>$request->zip,
                'country_id'=>$request->country_id,
                'city_id'=>$request->city_id,
                'notes'=>$request->notes,
                'created_at'=>Carbon::now(),
            ]);

            $carts = Cart::where('customer_id', Auth::guard('customerlogin')->id())->get();

            foreach($carts as $cart){
                OrderProduct::insert([
                    'order_id'=>$order_id,
                    'customer_id'=>Auth::guard('customerlogin')->id(),
                    'product_id'=>$cart->product_id,
                    'price'=>$cart->rel_to_product->after_discount,
                    'color_id'=>$cart->color_id,
                    'size_id'=>$cart->size_id,
                    'quantity'=>$cart->quantity,
                    'created_at'=>Carbon::now(),
                ]);

                Inventory::where('product_id', $cart->product_id)->where('color_id', $cart->color_id)->where('size_id', $cart->size_id)->decrement('quantity', $cart->quantity);
            }

            // Mail::to($request->email)->send(new InvoiceMail($order_id));

            //sms
            // $url = "https://bulksmsbd.net/api/smsapi";
            // $api_key = "sv4Vi9NaBbpl3pyuxwNd";
            // $senderid = "sezan";
            // $number = $request->phone;
            // $message = "Congratulations! your order has been successfully placed! Please ready TK: ".$request->sub_total+$request->charge;

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


            Cart::where('customer_id', Auth::guard('customerlogin')->id())->delete();

            return redirect()->route('order.success')->withOrder($order_id);
       }
       else if($request->payment_method == 2){
            $data = $request->all();
            return redirect()->route('pay')->with('data', $data);
       }
       else{
        $data = $request->all();
        return redirect()->route('stripe')->with('data', $data);
       }
    }

    function order_success(){
        if(session('order')){
            $order_id = session('order');
            return view('frontend.order_success', [
                'order_id'=>$order_id,
            ]);
        }
        else{
            abort('404');
        }

    }


}
