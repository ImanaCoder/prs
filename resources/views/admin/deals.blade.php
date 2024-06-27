<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg text-gray-800 dark:text-gray-200 leading-tight mx-xl-5 mx-2 py-3">
            Deals
        </h2>
    </x-slot>

    <div class="py-12" style="font-size:12px;">
        <div class="max-w-8xl mx-xl-5 mx-2">
            <div class=" overflow-hidden sm:rounded-lg p-2">

                <div class="container mt-4">

                    <div class="card">
                        <form action="" method="get" id="searchForm">
                            <div class="card-header">
                                <div class="card-title">
                                    <button type="button" onclick="window.location.href='{{ route('admin.dashboard') }}' " class="btn btn-default btn-sm">Reset</button>
                                </div>
                                <div class="card-tools">
                                    <div class="input-group input-group" style="width: 100%;">
                                        <select id="client_id" name="client_id" class="form-control">
                                            <option value="">All</option>
                                            @foreach ($clients as $client)
                                                <option value="{{ $client->id }}" {{ Request::get('client_id') == $client->id ? 'selected' : '' }}>
                                                    {{ $client->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <select id="team_id" name="team_id" class="form-control">
                                            <option value="">All</option>
                                            @foreach ($teams as $team)
                                                <option value="{{ $team->id }}" {{ Request::get('team_id') == $team->id ? 'selected' : '' }}>
                                                    {{ $team->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <input value="{{ Request::get('keyword') }}" type="text" name="keyword" class="form-control float-right" placeholder="Search" style="border-color:#ddd;">

                                        <div class="input-group-append">
                                          <button type="submit" class="btn btn-default">
                                            <i class="fas fa-search"></i>
                                          </button>
                                        </div>
                                      </div>
                                </div>
                            </div>
                        </form>
                        <div class="card-body">
                            <div class="overflow-auto">
                              <table class="table table-bordered">
                                <thead class="thead-light">
                                  <tr>
                                    <th>Id</th>
                                    <th>Deal Name</th>
                                    <th>Client</th>
                                    <th>Work Type</th>
                                    <th>Source Type</th>
                                    <th>Deal Date</th>
                                    <th>Deal Created</th>
                                    <th>Due Date</th>

                                    <th>Deal Version</th>
                                    <th>Deal Value</th>
                                    <th>Due Amount</th>

                                    <th>Payments</th>
                                    <th>Action</th>
                                  </tr>
                                </thead>
                                @php
                                function getOrdinal($number) {
                                    $ordinals = [
                                        "First", "Second", "Third", "Fourth", "Fifth",
                                        "Sixth", "Seventh", "Eighth", "Ninth", "Tenth",
                                        "Eleventh", "Twelfth", "Thirteenth"
                                    ];

                                    return ($number >= 1 && $number <= 13) ? $ordinals[$number - 1] : "Number out of range";
                                }
                                @endphp

                                <tbody>
                                  @if ($deals->isNotEmpty())

                                  @foreach ($deals as $deal)

                                  <tr>

                                          <td>{{ $deal->id }}</td>
                                          <td>{{ $deal->name }}</td>
                                          <td>{{ $deal->client->name }}</td>
                                          <td>{{ $deal->work_type }}</td>
                                          <td>{{ $deal->sourceType->name }}</td>
                                          <td>{{ \Carbon\Carbon::parse($deal->deal_date)->format('jS F, Y h:i A') }}</td>
                                          <td>{{ \Carbon\Carbon::parse($deal->created_at)->format('jS F, Y h:i A') }}</td>
                                          <td>{{ \Carbon\Carbon::parse($deal->due_date)->format('jS F, Y h:i A') }}</td>
                                          <td>{{ $deal->version }}</td>
                                          <td>{{ $deal->deal_value }}</td>
                                          <td>
                                            @php
                                                $dueAmountFormatted = number_format($deal->due_amount, 2);
                                                $dueStatusClass = '';

                                                switch ($deal->due_status) {
                                                    case 1:
                                                        $dueStatusClass = 'text-success'; // Green color for due_status 0
                                                        break;
                                                    case 2:
                                                        $dueStatusClass = 'text-danger'; // Red color for due_status 1
                                                        break;
                                                    case 0:
                                                        $dueStatusClass = 'text-orange'; // Yellow color for due_status 2
                                                        break;
                                                    default:
                                                        $dueStatusClass = '';

                                                }
                                            @endphp

                                            <span class="{{ $dueStatusClass }}" style="font-weight:700">${{ $dueAmountFormatted }}</span>
                                        </td>
                                          <td>
                                            @if ($deal->payments != null)
                                            <div class="d-flex justify-content-center align-items-center">
                                                @foreach ($deal->payments as $index => $payment)
                                                    <a href="{{ route('payments.details', $payment->id) }}">
                                                        @if ($payment->status == 0)
                                                            <svg class="text-danger h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                        @endif
                                                        @if ($payment->status == 1)
                                                            <svg class="text-success-500 h-6 w-6 text-success" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                        @endif
                                                        <span class="mr-4">{{ getOrdinal($index + 1) }}</span>

                                                    </a>
                                                @endforeach
                                            </div>
                                            @endif
                                        </td>

                                          <td >

                                              @if ($deal->payments->isNotEmpty())
                                                  <button class="btn btn-sm btn-info" onclick="toggleSubTable(this)"><i class="fas fa-chevron-down"></i></button>

                                              @endif
                                          </td>

                                  </tr>



                                  @if($deal->payments->isNotEmpty())
                                  <tr class="sub-table" style="display: none;background-color:rgb(228, 168, 131)">
                                     <td colspan="13">
                                          <div class="table-responsive">
                                              <table class="table table-bordered">
                                                  <thead >
                                                      <tr>
                                                          <th>Payment Date</th>
                                                          <th>Amount</th>
                                                          <th>Invoice</th>
                                                          <th>Remarks</th>
                                                          <th>Verified By</th>
                                                          <th>Verified At</th>
                                                          <th>Verification Receipt</th>
                                                          <th>Action</th>
                                                      </tr>
                                                  </thead>
                                                  <tbody>
                                                      @foreach ($deal->payments as $payment)

                                                          <tr>

                                                            <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('jS F, Y h:i A') }}</td>
                                                            <td>{{ $payment->payment_value }}</td>
                                                            <td><a href="{{ asset('storage/payments/'.$payment->receipt_path) }}" class="blue-text" target="_blank">{{ $payment->id }} Receipt</a> </td>
                                                            <td>{{ $payment->remarks }}</td>
                                                            <td>{{ $payment->verified_by_id ? $payment->verified_by->name : "N/A" }}</td>
                                                            <td>{{ $payment->verified_at ? \Carbon\Carbon::parse($payment->verified_at)->format('jS F, Y h:i A') : "N/A" }}</td>
                                                            <td >
                                                              @if ($payment->verification_receipt_path)
                                                                  <a href="{{ asset('storage/payments/verification-receipts/'.$payment->verification_receipt_path) }}" class="blue-text"   target="_blank">
                                                                      {{ $payment->id }} Verification Receipt
                                                                  </a>
                                                              @else
                                                                  N/A
                                                              @endif
                                                            </td>

                                                              <td>
                                                                  <button class="btn btn-sm btn-primary" onclick="viewPayment('{{ $payment->id }}')"><i class="fas fa-eye"></i></button>
                                                                  <button class="btn btn-sm btn-danger" onclick="deletePayment('{{ $payment->id }}')"><i class="fas fa-delete"></i></button>

                                                              </td>
                                                          </tr>
                                                      @endforeach

                                                  </tbody>
                                              </table>
                                          </div>
                                      </td>
                                  </tr>
                                  @endif
                                  @endforeach
                                  @endif
                                </tbody>

                              </table>
                            </div>
                        </div>
                        <div class="card-footer clearfix">
                            {!! $deals->links() !!}
                        </div>

                    </div>

                  </div>

                <div class="modal fade" id="viewPayment" tabindex="-1" role="dialog" aria-labelledby="viewPaymentLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="viewPaymentLabel">Approve Payment <span id="payment-id"></span></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form id="viewPaymentForm">
                                <div class="modal-body">
                                    <div class="container mx-auto py-8">
                                        <div class=" col-md-12">

                                            <!-- Payment Section -->
                                            <div class="row mt-3 w-full col-md-12 rounded-lg shadow-lg p-6">
                                                <div class="form-group col-md-6">
                                                    <label for="payment_id">Payment Id</label><br>
                                                    <span id="payment_id"></span>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="payment_deal_id">Deal Id</label><br>
                                                    <span id="payment_deal_id"></span>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="payment_date">Payment Date</label><br>
                                                    <span id="payment_date"></span>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="payment_value">Amount</label><br>
                                                    <span id="payment_value"></span>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="payment_remarks">Remarks</label><br>
                                                    <span id="payment_remarks"></span>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <div class="d-flex justify-content-center align-items-center">
                                                        <img id="receipt_image_path" src="" alt="payment"/>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Verification Section -->
                                            <div class="row mt-3 w-full col-md-12 rounded-lg shadow-lg p-6" id="verification_form_group" style="display:none;">
                                                <div class="form-group col-md-6">
                                                    <label for="status">Status</label><br>
                                                     <span id="status"></span>

                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label for="verification_remarks">Remarks</label><br>
                                                    <span id="verification_remarks"></span>
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label for="verified_by_id">Verified By</label><br>
                                                    <span id="verified_by_id"></span>
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label for="verified_at">Verified At</label><br>
                                                    <span id="verified_at"></span>
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <div class="d-flex justify-content-center align-items-center">
                                                        <img id="verification_receipt_image_path" src="" />
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Add this modal for confirming deletion  -->
                <div class="modal fade" id="deletePaymentConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deletePaymentConfirmationModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deletePaymentConfirmationModalLabel">Confirm Deletion</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete this product?</p>
                                <form id="paymentDeleteForm" action="">
                                    <div class="form-group col-md-6 col-12">
                                        <label for="payment_id">Payment Id</label>
                                        <input readonly type="text" id="payment_id" name="payment_id" class="form-control" required>
                                        <p></p>

                                    </div>
                                    <div class="form-group col-md-6 col-12">
                                        <input type="text" id="delete" name="delete" class="form-control" required>
                                        <p></p>

                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>



            </div>


        </div>
    </div>
    @section('customJs')
        <script>

            function deletePayment(id) {
                var url = '{{ route('deals.destroy', "ID") }}';
                var newUrl = url.replace("ID", id);
                $('#payment_id').val($id);

                // Show the delete confirmation modal
                $('#deletePaymentConfirmationModal').modal('show');

                // Handle the click on the "Delete" button in the modal
                $("#editPaymentForm").submit(function(event){
                    event.preventDefault();
                    var element = $("#editPaymentForm");
                    // Close the modal
                    $('#deletePaymentConfirmationModal').modal('hide');

                    // Perform the delete action
                    $.ajax({
                        url: newUrl,
                        type: 'delete',
                        data: element.serializeArray(),
                        dataType: 'json',
                        success: function(response) {
                            $("button[type=submit]").prop('disabled', false);
                            window.location.href = "{{ route('admins.deals') }}";
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                    });
                });
            }


            // Example JavaScript specific to this page
            function toggleSubTable(button) {
                const row = button.closest('tr');
                const subTable = row.nextElementSibling;

                if (subTable.classList.contains('sub-table')) {
                    subTable.style.display = subTable.style.display === 'none' ? 'table-row' : 'none';
                }
            }

            function handleFieldError(fieldName, errors) {
                var fieldElement = $("#" + fieldName);
                var errorElement = fieldElement.siblings('p');

                if (errors[fieldName]) {
                    fieldElement.addClass('is-invalid');
                    errorElement.addClass('invalid-feedback').html(errors[fieldName][0]);
                } else {
                    fieldElement.removeClass('is-invalid');
                    errorElement.removeClass('invalid-feedback').html("");
                }
            }


            function timestampToDate(timestamp) {
                // Create a new Date object from the timestamp (milliseconds since Unix epoch)
                const date = new Date(timestamp);

                // Extract the year, month, day, hours, and minutes from the Date object
                const year = date.getFullYear();
                const monthNames = ["January", "February", "March", "April", "May", "June",
                                    "July", "August", "September", "October", "November", "December"];
                const month = monthNames[date.getMonth()]; // Get the month name
                const day = date.getDate(); // Day of the month
                let hours = date.getHours();
                const minutes = ('0' + date.getMinutes()).slice(-2); // Adding leading zero if needed
                const ampm = hours >= 12 ? 'PM' : 'AM';
                hours = hours % 12;
                hours = hours ? hours : 12; // The hour '0' should be '12'

                // Format the date in 10 March, 2022 11:00AM
                const formattedDate = `${day} ${month}, ${year} ${hours}:${minutes}${ampm}`;

                return formattedDate;
            }

            function viewPayment($id) {

                $('#viewPaymentForm')[0].reset(); // Reset the form before populating new data
                var url = '{{ route('payments.edit', ['id' => ':id']) }}'.replace(':id', $id);
                // Make AJAX request to fetch client data
                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        document.getElementById('payment_deal_id').textContent = response.payment.deal_id;
                        document.getElementById('payment_value').textContent = response.payment.payment_value;
                        document.getElementById('payment_remarks').textContent = response.payment.remarks;
                        document.getElementById('payment_id').textContent = response.payment.id;
                        document.getElementById('payment_date').textContent =timestampToDate(response.payment.payment_date);
                        document.getElementById('verification_remarks').textContent =response.payment.verification_remarks;

                        document.getElementById('verified_at').textContent =timestampToDate(response.payment.verified_at);


                        // Example imageUrl variable (replace with your dynamic URL)
                        var imageUrl = '{{ asset("storage/payments/") }}' +"/"+ response.payment.receipt_path;

                        // Select the image element by its ID or another selector
                        var imgElement = document.getElementById('receipt_image_path'); // Replace 'yourImgId' with your actual image element ID

                        // Set the src attribute of the image element
                        if (imgElement) {
                            imgElement.src = imageUrl;
                        } else {
                            console.error('Image element not found.');
                        }



                        var verificationFormGroup = document.getElementById('verification_form_group');

                        if (response.payment.verified_by_id != null ) {
                            document.getElementById('verified_by_id').textContent =response.payment.verified_by.name;
                            verificationFormGroup.style.display = 'block';
                        } else {
                            verificationFormGroup.style.display = 'none';
                        }


                        // Example imageUrl variable (replace with your dynamic URL)
                        var imageUrl = '{{ asset("storage/payments/") }}' +"/"+ response.payment.receipt_path;

                        // Select the image element by its ID or another selector
                        var imgElement = document.getElementById('receipt_image_path'); // Replace 'yourImgId' with your actual image element ID

                        // Set the src attribute of the image element
                        if (imgElement) {
                            imgElement.src = imageUrl;
                        } else {
                            console.error('Image element not found.');
                        }

                        if(response.payment.verification_receipt_path != null){
                            // Example verificationImageUrl variable (replace with your dynamic URL)
                            var verificationImageUrl = '{{ asset("storage/payments/verification-receipts") }}' +"/"+ response.payment.verification_receipt_path;

                            // Select the image element by its ID or another selector
                            var imgElement1 = document.getElementById('verification_receipt_image_path'); // Replace 'yourImgId' with your actual image element ID

                            // Set the src attribute of the image element
                            if (imgElement1) {
                                imgElement1.src = verificationImageUrl;
                            } else {
                                console.error('Image element not found.');
                            }
                        }

                        var paymentStatus = response.payment.status; // Replace with actual value or fetch from API

                        // Select the status icon placeholder element
                        var statusIconElement = document.getElementById('status');

                        // Function to set status icon based on payment status
                        function setStatusIcon(status) {
                            var iconHtml = '';

                            // Determine which SVG icon to show based on payment status
                            if (status == 1) {
                                // Success icon
                                iconHtml = '<svg class="text-success-500 h-6 w-6 text-success" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                            } else if (status == 0) {
                                // Error icon
                                iconHtml = '<svg class="text-danger h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                            } else {
                                // Warning or unknown status icon
                                iconHtml = '<svg class="text-warning h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 2h12M6 2l6 6 6-6M6 2v6m12 0V2m0 18H6m0 0v-6m12 6v-6M6 22l6-6 6 6"></path></svg>';
                            }

                            // Update the status icon element with the generated icon HTML
                            statusIconElement.innerHTML = iconHtml;
                        }

                        // Call the function to set the status icon based on the initial payment status
                        setStatusIcon(paymentStatus);

                        // Show the view modal
                        $('#viewPayment').modal('show');
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('Error fetching client data:', errorThrown);
                    }
                });
            }




            // jQuery document ready function to ensure DOM is fully loaded
            $(document).ready(function() {
                // Bind change event to both select elements
                $('#client_id, #team_id, #manager_id').change(function() {
                    // Submit the form with id 'searchForm'
                    $('#searchForm').submit();
                });
            });





        </script>
    @endsection
</x-app-layout>
