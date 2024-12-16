<!DOCTYPE html>
<meta name="csrf-token" content="{{ csrf_token() }}">
<html>
<head>
    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }

        .add-btn {
            float: right;
            margin-bottom: 20px;
        }
    </style>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}">
</head>
<body>    
    <div class="container mt-4">
        <div class="text-center mb-4">
            <h5>Billing Page</h5>
        </div>

        <!-- Customer Email -->
        <div class="row mb-4">
            <div class="col-12">
                <b>Customer Email:</b> {{ $bill->customer_email }}
            </div>
        </div>

        <!-- Billing Info -->
        <div class="row mb-4">
            <div class="col-12">
                <h2>Billing Info</h2>
                <div class="add-btn">
                    <a href="{{ route('bill.index') }}" class="btn btn-primary">Billing List</a>
                </div>

                <!-- Billing List Table -->
                <table class="table table-bordered mt-3">
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Unit Price</th>
                            <th>Quantity</th>
                            <th>Purchase Price</th>
                            <th>Tax % for Item</th>
                            <th>Tax Payable for Item</th>
                            <th>Total Price of the Item</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $total_price_without_tax = 0;
                        $total_tax_payable = 0;
                        $netprice_for_purchased_item = 0;
                        $balance_amount = 0;
                        @endphp
                        @if(count($billsItems) > 0)
                        @foreach($billsItems as $item)
                        <tr>
                            <td>{{ $item->product_id }}</td>
                            <td>{{ $item->product->price_per_unit }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $item->purchase_price }}</td>
                            <td>{{ $item->product->tax_percentage }}</td>
                            <td>{{ $item->tax_amount }}</td>
                            <td>{{ $item->purchase_price + $item->tax_amount }}</td>
                        </tr>
                        @php
                        $total_price_without_tax += $item->purchase_price;
                        $total_tax_payable += $item->tax_amount;
                        @endphp
                        @endforeach
                        @else
                        <tr>
                            <td colspan="7" class="text-center">No Records!</td>
                        </tr>
                        @endif
                        @php
                        $netprice_for_purchased_item = $total_price_without_tax + $total_tax_payable;
                        @endphp
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Summary Section -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div>
                    <b>Total Price without Tax:</b> {{ $total_price_without_tax }}
                </div>
                <div>
                    <b>Total Tax Payable:</b> {{ $total_tax_payable }}
                </div>
                <div>
                    <b>Net Price of Purchased Items:</b> {{ $netprice_for_purchased_item }}
                </div>
                <div>
                    <b>Rounded Down Net Price:</b> {{ number_format($netprice_for_purchased_item, 2) }}
                </div>
                <div>
                    <b>Balance Payable to Customer:</b>
                    @php
                    $balance_amount = ($bill->cash_paid - $netprice_for_purchased_item) ? number_format($bill->cash_paid - $netprice_for_purchased_item, 2) : '0.00';
                    @endphp
                    {{ $balance_amount }}
                </div>
            </div>
        </div>

        
        <!-- Denomination Breakdown -->
        <div class="row mb-4">
            <div class="col-12">
                <h5>Balance Denomination</h5>
                @php
                $denominations = Config::get('custom.denominations');
                $balance_amount = preg_replace('/[^\d.]/', '', $balance_amount);
                $balance_amount = (float) $balance_amount;
                $balance_amount = ceil($balance_amount);
                $balance_amount = (int) $balance_amount;
                $result = collect($denominations)->mapWithKeys(function ($denomination) use (&$balance_amount) {
                    $count = intdiv($balance_amount, $denomination);
                    $balance_amount %= $denomination;
                    return [$denomination => $count];
                });
                @endphp

                <table class="table">
                    <thead>
                        <tr>
                            <th>Denomination</th>
                            <th>Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($result as $denomination => $count)
                            @if($count > 0)
                            <tr>
                                <td>{{ $denomination }}</td>
                                <td>{{ $count }}</td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <!-- jQuery JS -->
    <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
    <!-- Toastr JS -->
    <script src="{{ asset('assets/js/toastr.min.js') }}"></script>

    <script>
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error("{{ $error }}");
            @endforeach
        @endif

        @if(Session::has('success'))
            toastr.success("{{ Session::get('success') }}");
        @elseif(Session::has('error'))
            toastr.error("{{ Session::get('error') }}");
        @endif
    </script>
</body>
</html>
