<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Inventory;
use App\Models\Wishlist;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    function wishlist(){
        $wishlists = Wishlist::where('customer_id', Auth::guard('customerlogin')->id())->get();
        return view('frontend.wishlist', [
            'wishlists'=>$wishlists,
        ]);
    }

    function wishlist_remove($wishlist_id){
        Wishlist::find($wishlist_id)->delete();
        return back();
    }


    function cart_store(Request $request){
        if($request->abcd == 1){
            if(Auth::guard('customerlogin')->check()){
                if($request->color_id == ''){
                    $color_id = 1;
                }
                else{
                    $color_id = $request->color_id;
                }
                if($request->size_id == ''){
                    $size_id = 1;
                }
                else{
                    $size_id = $request->size_id;
                }

                if($request->quantity > Inventory::where('product_id', $request->product_id)->where('color_id', $color_id)->where('size_id', $size_id)->first()->quantity){
                    return back()->with('total_stock', 'Total Stock: '.Inventory::where('product_id', $request->product_id)->where('color_id', $color_id)->where('size_id', $size_id)->first()->quantity);
                }
                else{
                    Cart::insert([
                        'customer_id'=>Auth::guard('customerlogin')->id(),
                        'product_id'=>$request->product_id,
                        'color_id'=>$color_id,
                        'size_id'=>$size_id,
                        'quantity'=>$request->quantity,
                        'created_at'=>Carbon::now(),
                    ]);
                }

                return back()->with('cart_added', 'Cart Added successfully!');
            }
            else{
               return redirect()->route('customer.register.login')->with('login', 'Please Login to Add Cart!');
            }
        }

        else{
            if($request->color_id == ''){
                $color_id = 1;
            }
            else{
                $color_id = $request->color_id;
            }
            if($request->size_id == ''){
                $size_id = 1;
            }
            else{
                $size_id = $request->size_id;
            }
            Wishlist::insert([
                'customer_id'=>Auth::guard('customerlogin')->id(),
                'product_id'=>$request->product_id,
                'color_id'=>$color_id,
                'size_id'=>$size_id,
                'quantity'=>$request->quantity,
                'created_at'=>Carbon::now(),
            ]);
            return back()->with('wish', 'Product Added to Wishlist');
        }

    }

    function remove_cart($cart_id){
        Cart::find($cart_id)->delete();
        return back();
    }
    function clear_cart(){
        Cart::where('customer_id', Auth::guard('customerlogin')->id())->delete();
        return back();
    }

    function cart(Request $request){
        $carts = Cart::where('customer_id', Auth::guard('customerlogin')->id())->get();
        $coupon_code = $request->coupon;

        $discount = 0;
        $message = '';
        $type = '';

        if($coupon_code == ''){
            $discount = 0;
        }
        else{
            if(Coupon::where('coupon_code', $coupon_code)->exists()){
                if(Carbon::now()->format('Y-m-d') > Coupon::where('coupon_code', $coupon_code)->first()->validity){
                    $discount = 0;
                    $message = 'Coupon Code Expired!';
                }
                else{
                    $discount = Coupon::where('coupon_code', $coupon_code)->first()->amount;
                    $type = Coupon::where('coupon_code', $coupon_code)->first()->type;

                }
            }
            else{
                $discount = 0;
                $message = 'Invalid Coupon Code!';
            }
        }


        return view('frontend.cart', [
            'carts'=>$carts,
            'coupon_code'=>$coupon_code,
            'discount'=>$discount,
            'message'=>$message,
            'type'=>$type,
        ]);
    }

    function update_cart(Request $request){
        foreach($request->quantity as $cart_id=>$quantity){
            Cart::find($cart_id)->update([
                'quantity'=>$quantity,
            ]);
        }
        return back();
    }


}
