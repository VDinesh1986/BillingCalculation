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
        .section-scroll {
          max-height: 300px;
          overflow-y: auto;   /* Enable vertical scrolling */
          overflow-x: hidden;
        }
    </style>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.dataTables.min.css') }}">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/sweetalert2.min.css') }}">
</head>
<body>
  
    <div style="margin-left: 50px; margin-right: 50px; margin-top: 50px;">
        <h2>Billing List</h2>
        <div class="add-btn">
            <!-- Trigger the Modal -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBillingModal">
                <i data-feather="plus"></i> Add New
            </button>
        </div>

        <!-- Billing List Table -->
        <table id="billingTable" class="display">
            <thead>
                <tr>
                    <th>Customer Email</th>
                    <th>Total Price</th>
                    <th>Tax Payable</th>
                    <th>Cash Paid</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if($billingDetails)
                    @foreach($billingDetails as $list)
                        <tr>
                            <td>{{ $list->customer_email }}</td>
                            <td>{{ $list->total_price }}</td>
                            <td>{{ $list->tax_payable }}</td>
                            <td>{{ $list->cash_paid }}</td>
                            <td>
                                <a href="{{ route('bill.view', [$list->id]) }}" class="btn btn-info btn-sm">View</a>
                                <a href="{{ route('bill.delete', [$list->id]) }}" class="btn btn-danger btn-sm delete-confirm">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5">No records found.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="addBillingModal" tabindex="-1" aria-labelledby="addBillingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addBillingModalLabel">Add New Billing Entry</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Modal Form -->
                    <form method="POST" action="{{ route('bill.store') }}" id="billing-form">
                        @csrf
                        <div class="mb-3">
                            <label for="customerEmail" class="form-label"><h5>Customer Email</h5></label>
                            <input type="email" name="customer_email" id="customerEmail" class="form-control px-3" placeholder="Enter email" value="" required>
                        </div>
                         <!-- Billing Section -->
                          <div class="d-flex justify-content-between align-items-center mt-4">
                              <h5>Billing Section</h5>
                              <button type="button" class="btn btn-secondary" id="add-row">Add New</button>
                          </div>
                          
                          <div id="billing-section" class="section-scroll px-3">
                            <div class="row mb-3 align-items-center billing-row">
                                <div class="col-4">
                                    <label for="productId1" class="form-label">Product ID</label>
                                    <input type="text" name="products[0][product_id]" id="productId1" class="form-control product-id" placeholder="Enter Product ID, Ex(P001)" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-4">
                                    <label for="quantity1" class="form-label">Quantity</label>
                                    <input type="number" name="products[0][quantity]" id="quantity1" class="form-control product-quantity" placeholder="Enter Quantity" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                          </div>
                       
                          <!--Denomination-->
                          @php
                          $denominations = Config::get('custom.denominations');
                          @endphp
                          <div class="d-flex justify-content-between align-items-center mt-4">
                              <h5>Denomination</h5>
                          </div>
                          <div id="denomination-section" class="px-3">
                              <!-- First Row of Product Inputs -->
                              @foreach($denominations as $denom)
                              <div class="row mb-3 align-items-center">
                                  <div class="col-4">
                                      <label for="productId1" class="form-label">{{ $denom }}</label>
                                  </div>
                                  <div class="col-4">
                                      <input type="number" name="deno_count[]" class="form-control deno_count" placeholder="Count">
                                  </div>
                              </div>
                              @endforeach
                              <div class="row mb-3 align-items-center">
                                <div class="col-4">
                                    <label for="productId1" class="form-label"><h6>Cash paid by Customer</h6></label>
                                </div>
                                <div class="col-4">
                                    <input type="number" name="cash_paid_amount" id="cash_paid_amount" class="form-control cash_paid" placeholder="0.00" disabled required>
                                    <input type="hidden" name="cash_paid" id="cash_paid" value="">
                                </div>
                            </div>
                          </div>

                          

                        <div style="float: right; margin-top: 30px;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Genarate Bill</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>    
    <!-- jquery JS -->
    <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
    <!-- Add Toastr JS -->
    <script src="{{ asset('assets/js/toastr.min.js') }}"></script>
    <!-- DataTables JS -->
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <!-- SweetAlert2 JS -->
    <script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>

</body>
</html>
<script>
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            toastr.error("{{ $error }}");
        @endforeach
    @endif
</script>
<script>
    @if(Session::has('success'))
        toastr.success("{{ Session::get('success') }}");
    @elseif(Session::has('error'))
        toastr.error("{{ Session::get('error') }}");
    @endif
</script>
<script type="text/javascript">
   $(document).ready(function () {
        let rowIndex = 1; // Track the number of rows dynamically added

        //Onclick function for Add New button
        $('#add-row').on('click', function () {
            // Create the HTML structure for a new row
            const newRow = `
                <div class="row mb-3 align-items-center product-row" id="row-${rowIndex}">
                    <div class="col-4">
                        <input type="text" name="products[${rowIndex}][product_id]" id="productId${rowIndex}" class="form-control product-id" placeholder="Enter Product ID" required>
                    </div>
                    <div class="col-4">
                        <input type="number" name="products[${rowIndex}][quantity]" id="quantity${rowIndex}" class="form-control product-quantity" placeholder="Enter Quantity" required>
                    </div>
                    <div class="col-1 text-end">
                      <button type="button" class="btn btn-danger btn-sm delete-row">Delete</button>
                    </div>
                </div>
            `;

            // Append the new row to the billing section
            $('#billing-section').append(newRow);

            // Increment the rowIndex for future rows
            rowIndex++;
        });


        // Function to calculate the total
        function calculateTotal() {
            let total = 0;

            // Loop through each row and calculate denomination total
            $('#denomination-section .row').each(function () {
                const count = parseFloat($(this).find('.deno_count').val()) || 0; // Get count value
                const denomination = parseFloat($(this).find('.form-label').text()) || 0; // Get denomination value

                total += count * denomination; // Add to total
            });

            // Update the cash_paid input field
            $('#cash_paid_amount').val(total.toFixed(2));
            $('#cash_paid').val(total.toFixed(2));
        }

        // Trigger calculation on input change
        $(document).on('input', '.deno_count', function () {
            calculateTotal();
        });
    });

    $(document).on('click', '.delete-row', function () {
        $(this).closest('.product-row').remove();
    });

    //Datatable Pagination
    $(document).ready(function () {
        $('#billingTable').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true
        });
    });

    $(document).ready(function () {
        $(document).on('click', '.delete-confirm', function (e) {
            e.preventDefault();

            const deleteUrl = $(this).attr('href');

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Proceed with the deletion
                    window.location.href = deleteUrl;
                }
            });
        });
    });
</script>
