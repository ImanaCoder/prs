<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg text-gray-800 dark:text-gray-200 leading-tight">
            Deals
        </h2>
    </x-slot>

    <div class="py-12" style="font-size:12px;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-5">

                <div class="container mt-4">
                    <div class="d-flex justify-content-end w-full">
                        <button class="btn btn-primary mb-3 " style="font-size:12px;" data-toggle="modal" data-target="#addModal">Add Deal</button>
                    </div>
                    <div class="card">
                        <form action="" method="get">
                            <div class="card-header">
                                <div class="card-title">
                                    <button type="button" onclick="window.location.href='{{ route('deals.index') }}' " class="btn btn-default btn-sm">Reset</button>
                                </div>
                                <div class="card-tools">
                                    <div class="input-group input-group" style="width: 250px;">
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
                                          <td class="row gap-2">
                                              <button class="btn btn-sm btn-primary"  onclick="editDeal('{{ $deal->id }}')" data-toggle="modal" data-target="#editModal"><i class="fas fa-edit"></i></button>
                                              <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#addPayment" onclick="addPayment('{{ $deal->id }}')"><i class="fas fa-plus"></i></button>
                                              @if ($deal->payments->isNotEmpty())
                                              <button class="btn btn-sm btn-info" onclick="toggleSubTable(this)"><i class="fas fa-chevron-down"></i></button>

                                              @endif
                                          </td>

                                  </tr>



                                  @if($deal->payments->isNotEmpty())
                                  <tr class="sub-table" style="display: none;background-color:rgb(203, 233, 175)">
                                      <td colspan="12">
                                          <div class="table-responsive">
                                              <table class="table table-bordered">
                                                  <thead class="thead-light">
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
                                                                  <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editPayment" onclick="editPayment('{{ $payment->id }}')"><i class="fas fa-edit"></i></button>

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

                    </card>



                  </div>

                <!-- Add deal modal -->
                <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document"> <!-- modal-lg for large modal -->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addModalLabel">Add Deal and Payment</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form id="addDealForm">
                                <div class="modal-body">
                                    <div class="container mx-auto py-8">
                                        <div class="row ">
                                            <!-- Deals Section -->
                                            <div class=" mb-3 w-full row col-md-8 bg-white rounded-lg shadow-lg p-6">
                                                <div class="form-group col-md-6 col-12">
                                                    <label for="deal_id">Deal Id</label>
                                                    <input readonly type="text" id="deal_id" name="deal_id" class="form-control" required>
                                                    <p></p>

                                                </div>
                                                <div class="form-group col-md-6 col-12">
                                                    <label for="name">Deal Name</label>
                                                    <input type="text" id="name" name="name" class="form-control" required>
                                                    <p></p>

                                                </div>
                                                <div class="form-group col-md-6 col-12">
                                                    <label for="client_id">Client Name</label>
                                                    <select id="client_id" name="client_id" class="form-control">
                                                        @foreach ($clients as $client)
                                                        <option value="{{ $client->id }}">{{ $client->name }}</option>

                                                        @endforeach

                                                    </select>
                                                    <p></p>

                                                </div>
                                                <div class="form-group col-md-6 col-12">
                                                    <label for="source_type_id">Client Name</label>
                                                    <select id="source_type_id" name="source_type_id" class="form-control">
                                                        @foreach ($sourceTypes as $source)
                                                        <option value="{{ $source->id }}">{{ $source->name }}</option>

                                                        @endforeach

                                                    </select>
                                                    <p></p>

                                                </div>
                                                <div class="form-group col-md-6 col-12">
                                                    <label for="work_type">Work Type</label>
                                                    <select id="work_type" name="work_type" class="form-control">
                                                        <option value="FP">Initial Payment</option>
                                                        <option value="IP">Final Payment</option>
                                                    </select>
                                                    <p></p>

                                                </div>
                                                <div class="form-group col-md-6 col-12">
                                                    <label for="deal_value">Deal Value</label>
                                                    <input type="number" id="deal_value" name="deal_value" class="form-control" required>
                                                    <p></p>

                                                </div>
                                                <div class="form-group col-md-6 col-12">
                                                    <label for="deal_date">Deal Date</label>
                                                    <input type="date" id="deal_date" name="deal_date" class="form-control" required>
                                                    <p></p>

                                                </div>
                                                <div class="form-group col-md-6 col-12">
                                                    <label for="due_date">Due Date</label>
                                                    <input type="date" id="due_date" name="due_date" class="form-control" required>
                                                    <p></p>

                                                </div>
                                                <div class="form-group col-md-6 col-12">
                                                    <label for="remarks">Remarks</label>
                                                    <textarea id="remarks" name="remarks" class="form-control" required></textarea>
                                                    <p></p>

                                                </div>
                                            </div>

                                            <!-- Payment Section -->
                                            <div class=" mb-3 w-full col-md-4 bg-green-100 rounded-lg shadow-lg p-6">
                                                <h6 class="text-md font-semibold mb-4 text-center">Add Payment</h6>
                                                <div class="form-group col-md-12">
                                                    <label for="payment_date">Payment Date</label>
                                                    <input type="date" id="payment_date" name="payment_date" class="form-control" required>
                                                    <p></p>

                                                </div>
                                                <div class="form-group col-md-12">
                                                    <label for="payment_value">Amount</label>
                                                    <input type="number" id="payment_value" name="payment_value" class="form-control" required>
                                                    <p></p>

                                                </div>
                                                <div class="form-group col-md-12">
                                                    <input type="hidden" id="receipt_id" name="receipt_id" value="">
                                                    <label for="receipt">Receipt</label>
                                                    <div id="receipt" class="dropzone dz-clickable">
                                                        <div class="dz-message needsclick">
                                                            <br>Drop files here or click to upload. <br><br>
                                                        </div>
                                                    </div>
                                                    <p></p>

                                                </div>
                                                <div class="form-group col-md-12">
                                                    <label for="payment_remarks">Remarks</label>
                                                    <textarea id="payment_remarks" name="payment_remarks" class="form-control" required></textarea>
                                                    <p></p>

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

                <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document"> <!-- modal-lg for large modal -->
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Deal</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="editDealForm">
                        <div class="modal-body">
                            <div class="container mx-auto py-8">
                                <div class="flex justify-center col-md-12">
                                    <!-- Deals Section -->
                                    <div class="w-full row col-md-12 bg-white rounded-lg shadow-lg p-6 mr-4">
                                        <div class="form-group col-md-6">
                                            <label for="edit-deal_id">Deal Id</label>
                                            <input readonly type="text" id="edit-deal_id" name="id" class="form-control" required>
                                            <p></p>

                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="edit-name">Deal Name</label>
                                            <input type="text" id="edit-name" name="name" class="form-control" required>
                                            <p></p>

                                        </div>
                                        <div class="form-group col-md-6 col-12">
                                            <label for="edit-client_id">Client Name</label>
                                            <select id="edit-client_id" name="client_id" class="form-control">
                                                @foreach ($clients as $client)
                                                <option value="{{ $client->id }}">{{ $client->name }}</option>

                                                @endforeach

                                            </select>
                                            <p></p>

                                        </div>
                                        <div class="form-group col-md-6 col-12">
                                            <label for="edit-source_type_id">Client Name</label>
                                            <select id="edit-source_type_id" name="source_type_id" class="form-control">
                                                @foreach ($sourceTypes as $source)
                                                <option value="{{ $source->id }}">{{ $source->name }}</option>

                                                @endforeach

                                            </select>
                                            <p></p>

                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="edit-work_type">Work Type</label>
                                            <select id="edit-work_type" name="work_type" class="form-control">
                                                <option value="FP">Initial Payment</option>
                                                <option value="IP">Final Payment</option>
                                            </select>
                                            <p></p>

                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="edit-deal_value">Deal Value</label>
                                            <input type="number" id="edit-deal_value" name="deal_value" class="form-control" required>
                                            <p></p>

                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="edit-deal_date">Deal Date</label>
                                            <input type="date" id="edit-deal_date" name="deal_date" class="form-control" required>
                                            <p></p>

                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="edit-due_date">Due Date</label>
                                            <input type="date" id="edit-due_date" name="due_date" class="form-control" required>
                                            <p></p>

                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="edit-remarks">Remarks</label>
                                            <textarea id="edit-remarks" name="remarks" class="form-control" required></textarea>
                                            <p></p>

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

                <div class="modal fade" id="addPayment" tabindex="-1" role="dialog" aria-labelledby="addPaymentLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document"> <!-- modal-lg for large modal -->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addPaymentLabel">Add Payment for Deal <span class="payment-deal"></span></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form id="addPaymentForm">
                                <div class="modal-body">
                                    <div class="container mx-auto py-8">
                                        <div class="flex justify-center col-md-12">

                                            <!-- Payment Section -->
                                            <div class=" row w-full col-md-12 rounded-lg shadow-lg p-6">
                                                <div class="form-group col-md-6">
                                                    <label for="deal-deal_id">Deal Id</label>
                                                    <input readonly type="text" id="deal-deal_id" name="deal_id" class="form-control" required>
                                                    <p></p>

                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="deal-payment_date">Payment Date</label>
                                                    <input type="date" id="deal-payment_date" name="payment_date" class="form-control" required>
                                                    <p></p>

                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="deal-payment_value">Amount</label>
                                                    <input type="number" id="deal-payment_value" name="payment_value" class="form-control" required>
                                                    <p></p>

                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label for="deal-payment_remarks">Remarks</label>
                                                    <textarea id="deal-payment_remarks" name="remarks" class="form-control" required></textarea>
                                                    <p></p>

                                                </div>
                                                <div class="form-group col-md-6">
                                                    <input type="hidden" id="deal-receipt_id" name="receipt_id" value="">
                                                    <label for="deal-receipt">Receipt</label>
                                                    <div id="deal-receipt" class="dropzone dz-clickable">
                                                        <div class="dz-message needsclick">
                                                            <br>Drop files here or click to upload. <br><br>
                                                        </div>
                                                    </div>
                                                    <p></p>

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

                <div class="modal fade" id="editPayment" tabindex="-1" role="dialog" aria-labelledby="editPaymentLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document"> <!-- modal-lg for large modal -->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editPaymentLabel">Edit Payment <span id="payment-id"></span></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form id="editPaymentForm">
                                <div class="modal-body">
                                    <div class="container mx-auto py-8">
                                        <div class="flex justify-center col-md-12">

                                            <!-- Payment Section -->
                                            <div class=" row w-full col-md-12 rounded-lg shadow-lg p-6">
                                                <div class="form-group col-md-6">
                                                    <label for="edit-payment_id">Payment Id</label>
                                                    <input type="text" readonly id="edit-payment_id" name="id" class="form-control" required>
                                                    <p></p>

                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="edit-payment_deal_id">Deal Id</label>
                                                    <input readonly type="text" id="edit-payment_deal_id" name="deal_id" class="form-control" required>
                                                    <p></p>

                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="edit-payment_date">Payment Date</label>
                                                    <input type="date" id="edit-payment_date" name="payment_date" class="form-control" required>
                                                    <p></p>

                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="edit-payment_value">Amount</label>
                                                    <input type="number" id="edit-payment_value" name="payment_value" class="form-control" required>
                                                    <p></p>

                                                </div>


                                                <div class="form-group col-md-6">
                                                    <label for="edit-payment_remarks">Remarks</label>
                                                    <textarea id="edit-payment_remarks" name="remarks" class="form-control" required></textarea>
                                                    <p></p>

                                                </div>
                                                <div class="form-group col-md-6">
                                                    <input type="hidden" id="edit-receipt_id" name="receipt_id" value="">
                                                    <label for="edit-receipt">Receipt</label>
                                                    <div id="edit-receipt" class="dropzone dz-clickable">
                                                        <div class="dz-message needsclick">
                                                            <br>Drop files here or click to upload. <br><br>
                                                        </div>
                                                    </div>
                                                    <p></p>

                                                </div>
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <img id="receipt_image_path" src="" alt="payment"/>
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

            $("#addDealForm").submit(function(event){
                event.preventDefault();
                var element = $("#addDealForm");
                $("button[type=submit]").prop('disabled',true);

                $.ajax({
                    url:'{{ route('deals.store') }}',
                    type:'post',
                    data: element.serializeArray(),
                    dataType: 'json',
                    success: function(response){
                        $("button[type=submit]").prop('disabled',false);

                        if(response["status"] == true) {
                            $(".error").removeClass('invalid-feedback');
                            $('input[type="text"], select').removeClass('is-invalid');
                            window.location.href="{{ route('deals.index') }}";




                        }else{

                            var errors = response['errors'];
                            handleFieldError('name', errors);
                            handleFieldError('client_id', errors);
                            handleFieldError('work_type', errors);
                            handleFieldError('source_type_id', errors);
                            handleFieldError('deal_value', errors);
                            handleFieldError('deal_date', errors);
                            handleFieldError('due_date', errors);
                            handleFieldError('remarks', errors);
                            handleFieldError('payment_value', errors);
                            handleFieldError('payment_date', errors);
                            handleFieldError('payment_remarks', errors);
                            handleFieldError('receipt_id', errors);


                        }
                    },
                    error: function(jqXHR, exception){
                        console.log("केहि गलति भयो!");
                    }
                })
            });

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

            // Add an event listener to handle click on edit button
            function editDeal($id) {

                $('#editDealForm')[0].reset(); // Reset the form before populating new data
                var url = '{{ route('deals.edit', ['id' => ':id']) }}'.replace(':id', $id);
                // Make AJAX request to fetch client data
                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        // Populate the edit form fields with fetched data
                        $('#edit-name').val(response.deal.name);
                        $('#edit-deal_id').val(response.deal.id);
                        $('#edit-client_id').val(response.deal.client_id);
                        $('#edit-work_type').val(response.deal.work_type);
                        $('#edit-deal_value').val(response.deal.deal_value);
                        $('#edit-deal_date').val(timestampToDate(response.deal.deal_date));
                        $('#edit-due_date').val(timestampToDate(response.deal.due_date));
                        $('#edit-remarks').val(response.deal.remarks);

                        // Show the edit modal
                        $('#editModal').modal('show');
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('Error fetching client data:', errorThrown);
                    }
                });
            }

            function timestampToDate(timestamp) {
                // Create a new Date object from the timestamp (milliseconds since Unix epoch)
                const date = new Date(timestamp);

                // Extract the year, month, and day from the Date object
                const year = date.getFullYear();
                // Months are zero-indexed, so we add 1 to get the correct month
                const month = ('0' + (date.getMonth() + 1)).slice(-2); // Adding leading zero if needed
                const day = ('0' + date.getDate()).slice(-2); // Adding leading zero if needed

                // Format the date in YYYY-MM-DD
                const formattedDate = `${year}-${month}-${day}`;

                return formattedDate;
            }

            $("#editDealForm").submit(function(event){
                event.preventDefault();
                var element = $("#editDealForm");
                $("button[type=submit]").prop('disabled',true);
                var dealId=$('#edit-deal_id').val();

                $.ajax({
                    url:'{{ route('deals.update', ['id' => ':id']) }}'.replace(':id', dealId),
                    type:'put',
                    data: element.serializeArray(),
                    dataType: 'json',
                    success: function(response){
                        $("button[type=submit]").prop('disabled',false);

                        if(response["status"] == true) {
                            $(".error").removeClass('invalid-feedback');
                            $('input[type="text"], select').removeClass('is-invalid');
                            window.location.href="{{ route('deals.index') }}";

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

            Dropzone.autoDiscover = false;
            const dropzone = $("#receipt").dropzone({
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
                    $("#receipt_id"). val(response.image_id);
                }
            });

            const dropzone1 = $("#deal-receipt").dropzone({
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
                    $("#deal-receipt_id"). val(response.image_id);
                }
            });

            const dropzone2 = $("#edit-receipt").dropzone({
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
                    $("#edit-receipt_id"). val(response.image_id);
                }
            });

            function addPayment($id) {
                console.log($id);
                $('#deal-deal_id').val($id);
                $('#addPayment').modal('show');

            }


            $("#addPaymentForm").submit(function(event){
                event.preventDefault();
                var element = $("#addPaymentForm");
                $("button[type=submit]").prop('disabled',true);

                $.ajax({
                    url:'{{ route('payments.store') }}',
                    type:'post',
                    data: element.serializeArray(),
                    dataType: 'json',
                    success: function(response){
                        $("button[type=submit]").prop('disabled',false);

                        if(response["status"] == true) {
                            $(".error").removeClass('invalid-feedback');
                            $('input[type="text"], select').removeClass('is-invalid');
                            window.location.href="{{ route('deals.index') }}";




                        }else{

                            var errors = response['errors'];
                            handleFieldError('name', errors);
                            handleFieldError('client_id', errors);
                            handleFieldError('source_type_id', errors);
                            handleFieldError('work_type', errors);
                            handleFieldError('deal_value', errors);
                            handleFieldError('deal_date', errors);
                            handleFieldError('due_date', errors);
                            handleFieldError('remarks', errors);


                        }
                    },
                    error: function(jqXHR, exception){
                        console.log("केहि गलति भयो!");
                    }
                })
            });

            // Add an event listener to handle click on edit button
            function editPayment($id) {

                $('#editPaymentForm')[0].reset(); // Reset the form before populating new data
                var url = '{{ route('payments.edit', ['id' => ':id']) }}'.replace(':id', $id);
                // Make AJAX request to fetch client data
                    $.ajax({
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            // Populate the edit form fields with fetched data
                            $('#edit-payment_deal_id').val(response.payment.deal_id);

                            $('#edit-payment_value').val(response.payment.payment_value);
                            $('#edit-payment_remarks').val(response.payment.remarks);
                            $('#edit-payment_id').val(response.payment.id);
                            $('#edit-payment_date').val(timestampToDate(response.payment.payment_date));
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
                            $('#editPayment').modal('show');
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log('Error fetching client data:', errorThrown);
                        }
                    });
                }

            $("#editPaymentForm").submit(function(event){
                event.preventDefault();
                var element = $("#editPaymentForm");
                $("button[type=submit]").prop('disabled',true);
                var dealId=$('#edit-payment_id').val();

                $.ajax({
                    url:'{{ route('payments.update', ['id' => ':id']) }}'.replace(':id', dealId),
                    type:'put',
                    data: element.serializeArray(),
                    dataType: 'json',
                    success: function(response){
                        $("button[type=submit]").prop('disabled',false);

                        if(response["status"] == true) {
                            $(".error").removeClass('invalid-feedback');
                            $('input[type="text"], select').removeClass('is-invalid');
                            window.location.href="{{ route('deals.index') }}";

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
        </script>
    @endsection
</x-app-layout>
