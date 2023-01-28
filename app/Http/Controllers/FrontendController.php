<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Inventory;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\ProductThumb;
use App\Models\Size;
use Illuminate\Http\Request;
use Cookie;
use Arr;

class FrontendController extends Controller
{
    function home()
    {
        $categories = Category::all();
        $products = Product::latest()->take(8)->get();
        $feat_products = Product::latest()->take(3)->get();
        $top_seeling_products = OrderProduct::groupBy('product_id')
            ->selectRaw('sum(quantity) as sum, product_id')
            ->havingRaw('sum >= 7')
            ->take(3)
            ->orderBy('sum', 'DESC')->get();

        //cookie
        $recent_viewed_product = json_decode(Cookie::get('recent_view'), true);

        if ($recent_viewed_product == NULL) {
            $recent_viewed_product = [];
            $after_unique = array_unique($recent_viewed_product);
        } else {
            $after_unique = array_reverse(array_unique($recent_viewed_product));
        }
        $recent_viewed_product = Product::find($after_unique);
        return view('frontend.index', [
            'categories' => $categories,
            'products' => $products,
            'feat_products' => $feat_products,
            'top_seeling_products' => $top_seeling_products,
            'recent_viewed_product' => $recent_viewed_product,
        ]);
    }

    function details($slug)
    {
        $product_info = Product::where('slug', $slug)->get();
        $thumbnails = ProductThumb::where('product_id', $product_info->first()->id)->get();
        $related_products = Product::where('category_id', $product_info->first()->category_id)->where('id', '!=', $product_info->first()->id)->get();
        $available_colors = Inventory::where('product_id', $product_info->first()->id)
            ->groupBy('color_id')
            ->selectRaw('count(*) as total, color_id')
            ->get();
        $available_size = Inventory::where('product_id', $product_info->first()->id)->first()->size_id;
        $sizes = Size::all();
        $reviews = OrderProduct::where('product_id', $product_info->first()->id)->where('review', '!=', null)->get();
        $total_review = OrderProduct::where('product_id', $product_info->first()->id)->where('review', '!=', null)->count();
        $total_star = OrderProduct::where('product_id', $product_info->first()->id)->where('review', '!=', null)->sum('star');

        //recent view
        $product_id = $product_info->first()->id;
        $al = Cookie::get('recent_view');
        if (!$al) {
            $al = "[]";
        }
        $all_info = json_decode($al, true);
        $all_info = Arr::prepend($all_info, $product_id);
        $recent_product_id = json_encode($all_info);
        Cookie::queue('recent_view', $recent_product_id, 1000);


        return view('frontend.details', [
            'product_info' => $product_info,
            'thumbnails' => $thumbnails,
            'related_products' => $related_products,
            'available_colors' => $available_colors,
            'sizes' => $sizes,
            'available_size' => $available_size,
            'reviews' => $reviews,
            'total_review' => $total_review,
            'total_star' => $total_star,
        ]);
    }

    function getSize(Request $request)
    {
        $sizes = Inventory::where('product_id', $request->product_id)->where('color_id', $request->color_id)->get();
        $str = '';
        foreach ($sizes as $size) {
            $str .= '<div class="form-check size-option form-option form-check-inline mb-2">
                        <input class="form-check-input" value="' . $size->rel_to_size->id . '" type="radio" name="size_id" id="size' . $size->rel_to_size->id . '">
                        <label class="form-option-label" for="size' . $size->rel_to_size->id . '">' . $size->rel_to_size->size_name . '</label>
                    </div>';
        }
        echo $str;
    }

    function customer_register_login()
    {
        return view('frontend.login');
    }

    function category_product($category_id)
    {
        $categories_info = Category::find($category_id);
        $categorized_products = Product::where('category_id', $category_id)->get();
        return view('frontend.category_product', [
            'categories_info' => $categories_info,
            'categorized_products' => $categorized_products,
        ]);
    }
}
