<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    function search(Request $request)
    {
        $data = $request->all();

        $sorting = 'created_at';
        $type = 'DESC';

        if (!empty($data['sort']) && $data['sort'] != '' && $data['sort'] != 'undefined') {
            if ($data['sort'] == 1) {
                $sorting = 'product_name';
                $type = 'ASC';
            } else if ($data['sort'] == 2) {
                $sorting = 'product_name';
                $type = 'DESC';
            } else if ($data['sort'] == 3) {
                $sorting = 'after_discount';
                $type = 'ASC';
            } else if ($data['sort'] == 4) {
                $sorting = 'after_discount';
                $type = 'DESC';
            }
        }



        $search_products = Product::where(function ($q) use ($data) {
            $min = 0;
            if (!empty($data['min']) && $data['min'] != '' && $data['min'] != 'undefined') {
                $min = $data['min'];
            } else {
                $min = 1;
            }

            if (!empty($data['q']) && $data['q'] != '' && $data['q'] != 'undefined') {
                $q->where(function ($q) use ($data) {
                    $q->where('product_name', 'like', '%' . $data['q'] . '%');
                    $q->OrWhere('long_desp', 'like', '%' . $data['q'] . '%');
                });
            }
            if (!empty($data['min']) && $data['min'] != '' && $data['min'] != 'undefined' || !empty($data['max']) && $data['max'] != '' && $data['max'] != 'undefined') {
                $q->whereBetween('after_discount', [$min, $data['max']]);
            }
            if (!empty($data['category_id']) && $data['category_id'] != '' && $data['category_id'] != 'undefined') {
                $q->where('category_id', $data['category_id']);
            }
            if (!empty($data['brand']) && $data['brand'] != '' && $data['brand'] != 'undefined') {
                $q->where('brand', $data['brand']);
            }
            if (!empty($data['color_id']) && $data['color_id'] != '' && $data['color_id'] != 'undefined' || !empty($data['size_id']) && $data['size_id'] != '' && $data['size_id'] != 'undefined') {
                $q->whereHas('rel_to_inventories', function ($q) use ($data) {
                    if (!empty($data['color_id']) && $data['color_id'] != '' && $data['color_id'] != 'undefined') {
                        $q->whereHas('rel_to_color', function ($q) use ($data) {
                            $q->where('colors.id', $data['color_id']);
                        });
                    }
                    if (!empty($data['size_id']) && $data['size_id'] != '' && $data['size_id'] != 'undefined') {
                        $q->whereHas('rel_to_size', function ($q) use ($data) {
                            $q->where('sizes.id', $data['size_id']);
                        });
                    }
                });
            }
        })->orderBy($sorting, $type)->get();

        $categories = Category::all();
        $colors = Color::all();
        $sizes = Size::all();
        $brands = Brand::all();
        return view('frontend.search', [
            'search_products' => $search_products,
            'categories' => $categories,
            'colors' => $colors,
            'sizes' => $sizes,
            'brands' => $brands,
        ]);
    }
}
