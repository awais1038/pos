<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    public $table = 'customer';
    protected $fillable = ['code', 'name', 'email', 'phone', 'address'];

    public function invoice()
    {
        return $this->hasMany(Invoice::class,'customer_id');
    }

}
