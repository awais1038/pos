<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VSupplier extends Model
{
    use HasFactory;
    public $table = 'supplier';
    protected $fillable = ['code', 'name', 'email', 'phone', 'address'];

    public function purchaseOrder()
    {
        return $this->hasMany(PurchaseOrder::class, 'supplier_id');
    }
}
