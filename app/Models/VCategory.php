<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class VCategory extends Model
{
    use HasFactory;
    public $table = 'categories';
    protected $fillable = ['name', 'slug', 'parent_id','created_by'];

    public function subcategory()
    {
        return $this->hasMany(\App\Models\VCategory::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(\App\Models\VCategory::class, 'parent_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}
