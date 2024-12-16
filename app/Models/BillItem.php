<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillItem extends Model
{
    protected $fillable = ['bill_id', 'product_id', 'quantity', 'purchase_price', 'tax_amount'];

    // Define the relationship with Bill
    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }

    // Define the relationship with Product
    public function product()
    {
         return $this->belongsTo(Product::class, 'product_id', 'product_id');
        
    }
}