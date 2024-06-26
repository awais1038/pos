<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductUnit extends Model
{
    use HasFactory;
    public $table = 'product_unit';
    protected $fillable = ['name'];

    public function products()
    {
        return $this->hasMany(Product::class, 'unit_id');
    }
}
