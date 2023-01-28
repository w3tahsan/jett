<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    function rel_to_product(){
        return $this->belongsTo(Product::class, 'product_id');
    }
    function rel_to_color(){
        return $this->belongsTo(Color::class, 'color_id');
    }
    function rel_to_size(){
        return $this->belongsTo(Size::class, 'size_id');
    }
}
