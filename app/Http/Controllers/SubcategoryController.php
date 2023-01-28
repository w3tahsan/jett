<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubcategoryController extends Controller
{
    function subcategory(){
        $subcategories = Subcategory::all();
        $categories = Category::all();
        return view('admin.category.subcategory', [
            'categories'=>$categories,
            'subcategories'=>$subcategories,
        ]);
    }

    function subcategory_store(Request $request){
        Subcategory::insert([
            'category_id'=>$request->category_id,
            'subcategory_name'=>$request->subcategory_name,
            'added_by'=>Auth::id(),
            'created_at'=>Carbon::now(),
        ]);
        return back();
    }

}
