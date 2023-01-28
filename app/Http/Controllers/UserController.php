<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;
use Image;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    function users(){
        $users = User::where('id', '!=', Auth::id())->get();
        $total_user = User::count();
        return view('admin.users.user', compact('users', 'total_user'));
    }

    function delete($user_id){
        User::find($user_id)->delete();
        return back();
    }

    function profile(){
        return view('admin.users.profile');
    }

    function name_update(Request $request){
       User::find(Auth::id())->update([
            'name'=>$request->name,
       ]);
       return back();
    }

    function pass_update(Request $request){
        $request->validate([
            'old_password'=>'required',
            'password'=>Password::min(8)
            ->letters()
            ->numbers()
            ->symbols(),
            'password'=>'required|confirmed',
            'password_confirmation'=>'required',
        ],[
            'password.confirmed'=>'new pass r confirm pass same de!',
        ]);


        if(Hash::check($request->old_password, Auth::user()->password)){
            User::find(Auth::id())->update([
                'password'=>bcrypt($request->password),
            ]);
            return back()->with('success', 'Password Updated!');
        }
        else{
            return back()->with('wrong_pass', 'Old Password Does not Match!!');
        }
    }

    function photo_update(Request $request){
        $request->validate([
            'image'=>'required|file|max:1024',
            'image'=>'mimes:jpg,bmp,png,gif',
        ]);

        $upload_file = $request->image;
        if(Auth::user()->image == null){
           $extension = $upload_file->getClientOriginalExtension();
           $file_name = Auth::id().'.'.$extension;
           Image::make($upload_file)->resize(300, 200)->save(public_path('uploads/user/'.$file_name));

           User::find(Auth::id())->update([
                'image'=>$file_name,
           ]);
           return back();
        }
        else{

            $delete_from = public_path('uploads/user/'.Auth::user()->image);
            unlink($delete_from);

            $extension = $upload_file->getClientOriginalExtension();
            $file_name = Auth::id().'.'.$extension;
            Image::make($upload_file)->resize(300, 200)->save(public_path('uploads/user/'.$file_name));

            User::find(Auth::id())->update([
                    'image'=>$file_name,
            ]);
            return back();
        }
    }

    function user_register(Request $request){
        User::insert([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'created_at'=>Carbon::now(),
        ]);
        return back();
    }
}
