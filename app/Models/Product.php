<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'product_id', 'available_stocks', 'price_per_unit', 'tax_percentage'];

    // Define the relationship with BillItem
    public function billItems()
    {
        return $this->hasMany(BillItem::class);
    }
}