<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\Product;
use App\Models\Bill;
use App\Models\BillItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;

class BillingController extends Controller
{
    public function index(Request $request)
    {
        $billingDetails = Bill::all();
        return view('bill.index', compact('billingDetails'));
    }

    public function saveBill(Request $request)
    {
        
        // Validate the input
        $validated = $request->validate([
            'customer_email' => 'required|email',
            'cash_paid' => 'required|numeric|min:0',
            'products.*.product_id' => 'required|exists:products,product_id',
            'products.*.quantity' => 'required|integer|min:1',
        ], [
            'customer_email.required' => 'The customer email is required.',
            'customer_email.email' => 'Please provide a valid email.',
            'cash_paid.required' => 'The cash amount is required.',
            'cash_paid.numeric' => 'The cash amount must be a number.',
            'cash_paid.min' => 'The cash amount must be at least 0.',
            'products.*.product_id.required' => 'Product ID is required.',
            'products.*.product_id.exists' => 'One or more products do not exist.',
            'products.*.quantity.required' => 'Quantity is required for all products.',
            'products.*.quantity.integer' => 'Quantity must be a valid number.',
            'products.*.quantity.min' => 'Quantity must be at least 1.',
        ]);
        
        // Initialize the bill
        $bill = new Bill();
        $bill->customer_email = $request->customer_email;
        $bill->total_price = 0;
        $bill->tax_payable = 0;
        $bill->net_price = 0;
        $bill->cash_paid = $request->cash_paid;
        $bill->balance = 0;
        $bill->save(); // Save to generate the `id`

        foreach ($request->products as $product) {
            $productModel = Product::where('product_id', $product['product_id'])->first();

            if (!$productModel || $productModel->available_stocks < $product['quantity']) {
                return redirect()->back()->withErrors([
                    "Product with ID {$product['product_id']} is out of stock or does not exist."
                ])->withInput(); // Passes previous input back to the form
            }

            // Continue processing if product exists and has enough stock
            $purchasePrice = $productModel->price_per_unit * $product['quantity'];
            $taxAmount = $purchasePrice * ($productModel->tax_percentage / 100);

            // Update totals for the bill
            $bill->total_price += $purchasePrice;
            $bill->tax_payable += $taxAmount;

            // Deduct stock from the product
            $productModel->available_stocks -= $product['quantity'];
            $productModel->save();

            // Create and save the BillItem
            $billItem = new BillItem();
            $billItem->bill_id = $bill->id;
            $billItem->product_id = $product['product_id'];
            $billItem->quantity = $product['quantity'];
            $billItem->purchase_price = $purchasePrice;
            $billItem->tax_amount = $taxAmount;
            $billItem->save();
        }

        // Finalize the bill
        $bill->net_price = $bill->total_price + $bill->tax_payable;
        $bill->balance = $bill->cash_paid - $bill->net_price;

        if($bill->cash_paid - $bill->net_price < 0) {
            return redirect()->back()->withErrors([
                "Cash paid amount is less than product amount"
            ])->withInput(); // Passes previous input back to the form
        }

        $bill->save();

        return redirect()->route('bill.view', ['bill_id' => $bill->id])->with('success', 'Bill generated successfully!');
    }

    //View Bill
    public function viewBill($bill_id)
    {
        //DB::enableQueryLog();
        $bill = Bill::where('id', $bill_id)->first();
        $billsItems = BillItem::with('product')->where('bill_id', $bill_id)->get();
        //dd(DB::getQueryLog());
        return view('bill.details', compact('bill','billsItems'));
    }

    //Delete Bill
    public function deleteBill($bill_id)
    {
        // Find the bill by its ID
        $bill = Bill::find($bill_id);

        if (!$bill) {
            return redirect()->back()->with('error', 'Bill not found.');
        }

        $bill->billItems()->delete();
        $bill->delete();

        return redirect()->route('bill.index')->with('success', 'Bill deleted successfully.');
    }
}

