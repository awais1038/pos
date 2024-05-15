<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VProduct extends Model
{
    use HasFactory;
    public $table = 'product';
    protected $fillable = ['name', 'unit_id', 'category_id', 'unit_in_stock', 'unit_price', 'discount_percentage', 'created_by','image','cost','product_barcode','quantity'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function productUnit()
    {
        return $this->belongsTo(ProductUnit::class, 'unit_id');
    }

    public function product()
    {
        return $this->hasMany(Product::class, 'product_id');
    }
}
