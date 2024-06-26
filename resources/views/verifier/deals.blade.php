<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg text-gray-800 dark:text-gray-200 leading-tight mx-xl-5 mx-2 py-3">
            Deals
        </h2>
    </x-slot>

    <div class="py-12" style="font-size:12px;">
        <div class="max-w-8xl mx-xl-5 mx-2">
            <div class=" overflow-hidden sm:rounded-lg p-xl-5 p-2">

                <div class="container mt-4">
                    @include('message')

                    <div class="card">
                        <form action="" method="get" id="searchForm">
                            <div class="card-header">
                                <div class="card-title">
                                    <button type="button" onclick="window.location.href='{{ route('verifier.dashboard') }}' " class="btn btn-default btn-sm">Reset</button>
                                </div>
                                <div class="card-tools">
                                    <div class="input-group input-group" style="width: 100%;">
                                        <select id="payment_type" name="payment_type" class="form-control">
                                            <option value="">All</option>

                                            <option value="due" {{ Request::get('payment_type') == "due" ? 'selected' : '' }}>
                                                Due
                                            </option>
                                            <option value="paid" {{ Request::get('payment_type') == "paid" ? 'selected' : '' }}>
                                                Paid
                                            </option>
                                            <option value="invalid" {{ Request::get('payment_type') == "invalid" ? 'selected' : '' }}>
                                            Invalid
                                            </option>
                                        </select>
                                        <select id="client_id" name="client_id" class="form-control">
                                            <option value="">All Clients</option>
                                            @foreach ($clients as $client)
                                                <option value="{{ $client->id }}" {{ Request::get('client_id') == $client->id ? 'selected' : '' }}>
                                                    {{ $client->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <select id="team_id" name="team_id" class="form-control">
                                            <option value="">All Teams</option>
                                            @foreach ($teams as $team)
                                                <option value="{{ $team->id }}" {{ Request::get('team_id') == $team->id ? 'selected' : '' }}>
                                                    {{ $team->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <select id="manager_id" name="manager_id" class="form-control">
                                            <option value="">All Managers</option>
                                            @foreach ($managers as $manager)
                                                <option value="{{ $manager->id }}" {{ Request::get('manager_id') == $manager->id ? 'selected' : '' }}>
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
                                          <td style="color: {{ $deal->deal_version === 'Original' ? 'green' : 'red' }}">
                                            {{ $deal->deal_version }}
                                        </td>
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
                                                  <button class="btn btn-sm btn-info" onclick="toggleSubTable(this)"><i class="far fa-caret-square-down"></i></button>

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
                                                          <th>Payment Version</th>
                                                          <th>Verification Status</th>

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
                                                            <td style="color: {{ $payment->payment_version === 'Original' ? 'green' : 'red' }}">
                                                                {{ $payment->payment_version }}
                                                            </td>
                                                            <td>
                                                                @if ($payment->status == 0)
                                                                    <svg class="text-danger h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                    </svg>
                                                                @elseif ($payment->status == 1)
                                                                    <svg class="text-success-500 h-6 w-6 text-success" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                    </svg>
                                                                @else
                                                                    <svg class="text-info h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6M12 4a8 8 0 1 0 8 8 8 8 0 0 0-8-8z"></path>
                                                                    </svg>

                                                                @endif
                                                            </td>
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
                                                                  <button class="btn btn-sm btn-primary" onclick="approvePayment('{{ $payment->id }}')"><i class="fas fa-edit"></i></button>

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

                <div class="modal fade" id="approvePayment" tabindex="-1" role="dialog" aria-labelledby="approvePaymentLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="approvePaymentLabel">Approve Payment <span id="payment-id"></span></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form id="approvePaymentForm">
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
                                            <div class="row mt-3 w-full col-md-12 rounded-lg shadow-lg p-6">
                                                <div class="form-group col-md-6 col-12">
                                                    <label for="status">Status</label>
                                                    <select id="status" name="status" class="form-control">
                                                        <option value="0">Rejected</option>
                                                        <option value="1">Approved</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="verification_remarks">Remarks</label>
                                                    <textarea id="verification_remarks" name="verification_remarks" class="form-control" required></textarea>
                                                </div>
                                                <div id="verification_form_group" >
                                                    <div class="form-group col-md-6" style="display: none;">
                                                        <input type="hidden" id="verification_receipt_id" name="verification_receipt_id" value="">
                                                        <label for="verification_receipt">Verification Receipt</label>
                                                        <div id="verification_receipt" class="dropzone dz-clickable">
                                                            <div class="dz-message needsclick">
                                                                <br>Drop files here or click to upload. <br><br>
                                                            </div>
                                                        </div>
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
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>



            </div>


        </div>
    </div>
    @section('customJs')
        <script>


            // Example JavaScript specific to this page
            function toggleSubTable(button) {
                const row = button.closest('tr');
                const subTable = row.nextElementSibling;

                if (subTable.classList.contains('sub-table')) {
                    subTable.style.display = subTable.style.display === 'none' ? 'table-row' : 'none';
                }
            }

            function handleEditFieldError(fieldName, errors) {
                var fieldElement = $('#edit-' + fieldName);
                var errorElement = fieldElement.siblings('p');

                if (errors[fieldName]) {
                    fieldElement.addClass('is-invalid');
                    errorElement.addClass('invalid-feedback').html(errors[fieldName][0]);
                } else {
                    fieldElement.removeClass('is-invalid');
                    errorElement.removeClass('invalid-feedback').html('');
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

            function approvePayment($id) {

                $('#approvePaymentForm')[0].reset(); // Reset the form before populating new data
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
                        $('#verification_remarks').val(response.payment.verification_remarks);
                        $('#status').val(response.payment.status.toString());

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


                                            // Show the edit modal
                        $('#approvePayment').modal('show');
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('Error fetching client data:', errorThrown);
                    }
                });
            }

            document.getElementById('status').addEventListener('change', function() {
                var verificationFormGroup = document.getElementById('verification_form_group');
                if (this.value === '1') {
                    verificationFormGroup.style.display = 'block';
                } else {
                    verificationFormGroup.style.display = 'none';
                    $('#verification_receipt_id').val(null);

                }
            });

            Dropzone.autoDiscover = false;

            const dropzone1 = $("#verification_receipt").dropzone({
                init: function() {
                    this.on('addedfile', function(file) {
                        if (this.files.length > 1) {
                            this.removeFile(this.files[0]);
                        }
                    })

                },
                url: "{{ route('temp-images.create') }}",
                maxFiles: 1,
                paramName: 'image',
                addRemoveLinks: true,
                acceptedFiles: "image/jpeg,image/png,image/gif",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(file, response){
                    $("#verification_receipt_id"). val(response.image_id);
                    $("button[type=submit]").prop('disabled',false);

                }
            });


            $("#approvePaymentForm").submit(function(event){
                event.preventDefault();
                var element = $("#approvePaymentForm");
                $("button[type=submit]").prop('disabled',true);
                var spanElement = document.getElementById("payment_id");
                var spanContent = spanElement.innerHTML;

                $.ajax({
                    url:'{{ route('payments.verify', ['id' => ':id']) }}'.replace(':id', spanContent),
                    type:'put',
                    data: element.serializeArray(),
                    dataType: 'json',
                    success: function(response){
                        $("button[type=submit]").prop('disabled',false);

                        if(response["status"] == true) {
                            $(".error").removeClass('invalid-feedback');
                            $('input[type="text"], select').removeClass('is-invalid');
                            window.location.href="{{ route('payments.index') }}";

                        }else{

                            var errors = response['errors'];
                            console.log(errors);
                            handleEditFieldError('name', errors);
                            handleEditFieldError('client_id', errors);
                            handleEditFieldError('source_type_id', errors);
                            handleEditFieldError('work_type', errors);
                            handleEditFieldError('deal_value', errors);
                            handleEditFieldError('deal_date', errors);
                            handleEditFieldError('due_date', errors);
                            handleEditFieldError('remarks', errors);


                        }
                    },
                    error: function(jqXHR, exception){
                        console.log("केहि गलति भयो!");
                    }
                });
            });

            // jQuery document ready function to ensure DOM is fully loaded
            $(document).ready(function() {
                // Bind change event to both select elements
                $('#client_id, #team_id, #manager_id,#payment_type').change(function() {
                    // Submit the form with id 'searchForm'
                    $('#searchForm').submit();
                });
            });



        </script>
    @endsection
</x-app-layout>
