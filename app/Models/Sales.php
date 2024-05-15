<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;
    public $table = 'tblsales';
    protected $fillable = ['invoice_id', 'product_id', 'quantity', 'unit_price', 'sub_total'];

    public function invoiceOne()
    {
        return $this->hasOne(Invoice::class);
    }
    // public function invoice()
    // {
    //     return $this->belongsTo(Invoice::class);
    // }
    // public function product()
    // {
    //     return $this->hasMany(Product::class, 'product_id');
    // }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class,'invoice_id');
    }

    public function customer()
    {
     return $this->hasMany(Invoice::class,'customer_id');
    }

    public function products()
    {
        return $this->belongsTo(Product::class,'product_id');
    }
}
