<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Image;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    function category(){
        $categories = Category::all();
        $trashed = Category::onlyTrashed()->get();
        return view('admin.category.category', [
            'categories' => $categories,
            'trashed' => $trashed,
        ]);
    }

    function category_store(CategoryRequest $request){
        $category_id = Category::insertGetId([
            'category_name'=>$request->category_name,
            'added_by'=>Auth::id(),
            'created_at'=>Carbon::now(),
        ]);

        $category_image = $request->category_image;
        $extension = $category_image->getClientOriginalExtension();
        $after_replace = str_replace(' ', '-', $request->category_name);
        $file_name = Str::lower($after_replace).'-'.rand(100000, 199999).'.'.$extension;
        Image::make($category_image)->resize(300, 200)->save(public_path('uploads/category/'.$file_name));

        Category::find($category_id)->update([
            'category_image'=>$file_name,
        ]);
        return back();
    }

    function category_delete($category_id){
        Category::find($category_id)->delete();
        return back();
    }

    function category_restore($category_id){
        Category::onlyTrashed()->find($category_id)->restore();
        return back();
    }
    function category_force_delete($category_id){
        $category_img = Category::onlyTrashed()->where('id', $category_id)->first()->category_image;
        $delete_from = public_path('uploads/category/'.$category_img);
        unlink($delete_from);

        Category::onlyTrashed()->find($category_id)->forceDelete();
        return back();
    }

    function category_edit($category_id){
        $category_info = Category::find($category_id);
        return view('admin.category.edit', [
            'category_info'=>$category_info,
        ]);
    }

    function category_update(Request $request){
        if($request->category_image == ''){
            Category::find($request->category_id)->update([
                'category_name'=>$request->category_name,
                'added_by'=>Auth::id(),
                'updated_at'=>Carbon::now(),
            ]);
            return back();
        }
        else{
            $category_image_del = Category::where('id', $request->category_id)->first()->category_image;
            $delete_from = public_path('uploads/category/'.$category_image_del);
            unlink($delete_from);

            $uploaded_img = $request->category_image;
            $extension = $uploaded_img->getClientOriginalExtension();
            $file_name = Str::lower($request->category_name).'-'.rand(100000, 199999).'.'.$extension;

            Image::make($uploaded_img)->resize(300, 200)->save(public_path('uploads/category/'.$file_name));

            Category::find($request->category_id)->update([
                'category_name'=>$request->category_name,
                'category_image'=>$file_name,
                'added_by'=>Auth::id(),
                'updated_at'=>Carbon::now(),
            ]);

            return back();
        }
    }
}
