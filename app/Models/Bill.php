<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    protected $fillable = ['customer_email','tax_payable', 'total_price', 'net_price', 'cash_paid', 'balance'];

    // Define the relationship with BillItem
    public function billItems()
    {
        return $this->hasMany(BillItem::class);
    }
}
