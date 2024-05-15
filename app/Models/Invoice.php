<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    public $table = 'tblinvoice';
    protected $fillable = ['customer_id', 'payment_type', 'total_amount', 'amount_tendered', 'created_by', 'date_recorded'];

    public function sales()
    {
        return $this->hasMany(Sales::class);
    }

    public function invoice()
    {
        return $this->hasMany(Invoice::class,'customer_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class,'customer_id');
    }
}
