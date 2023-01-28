<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRegisterRequest;
use App\Models\CustomerEmailVerify;
use App\Models\Customerlogin;
use App\Notifications\CustomerEmailVerifyNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class CustomerRegisterController extends Controller
{
    function customer_store(CustomerRegisterRequest $request)
    {
        Customerlogin::insert([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'created_at' => Carbon::now(),
        ]);

        $customer = Customerlogin::where('email', $request->email)->firstOrFail();
        $customer_info = CustomerEmailVerify::create([
            'customer_id' => $customer->id,
            'token' => uniqid(),
            'created_at' => Carbon::now(),
        ]);
        Notification::send($customer, new CustomerEmailVerifyNotification($customer_info));

        return back()->withVerify('We Have Sent You a Email Verification Link! Please Check your Email');

        // if(Auth::guard('customerlogin')->attempt(['email'=>$request->email, 'password'=>$request->password])){
        //     return redirect('/')->with('success_login', 'Customer Registered Success!');
        // }

    }
    function customer_email_verify($token)
    {
        $customer = CustomerEmailVerify::where('token', $token)->firstOrFail();
        Customerlogin::find($customer->customer_id)->update([
            'email_verified_at' => Carbon::now()->format('Y-m-d'),
        ]);
        $customer->delete();
        return back()->withVerifysuccess('Your Email Verified Successfully! Now You can Login');
    }
}
