<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Deals
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-5">

                <div class="container mt-4">
                    <div class="d-flex justify-content-end w-full">
                        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addModal">Add Deal</button>
                    </div>

                    <div class="Deals-item">
                      <div class="overflow-auto">
                        <table class="table table-bordered">
                          <thead class="thead-dark">
                            <tr>
                              <th>S.No</th>
                              <th>Deal Name</th>
                              <th>Client</th>
                              <th>Description</th>
                              <th>Work Type</th>
                              <th>Source Type</th>
                              <th>Deal Date</th>
                              <th>Deal Created</th>
                              <th>Deal Version</th>
                              <th>Deal Value</th>
                              <th>Payments</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                                <td>1</td>
                                <td>Monika</td>
                                <td>wl Ashma</td>
                                <td>FP</td>
                                <td>FP</td>
                                <td>WhatsApp</td>
                                <td>01-01-2020</td>
                                <td>01-01-2020</td>
                                <td>Original</td>
                                <td>100</td>
                                <td>First</td>
                                <td>
                                    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editModal"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#addModal"><i class="fas fa-plus"></i></button>
                                    <button class="btn btn-sm btn-info" onclick="toggleSubTable(this)"><i class="fas fa-chevron-down"></i></button>
                                </td>
                            </tr>
                            <tr class="sub-table" style="display: none;">
                                <td colspan="12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th>Payment Date</th>
                                                    <th>Amount</th>
                                                    <!-- Add more columns as needed -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Payment data will be dynamically inserted here -->
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        </tbody>

                        </table>
                      </div>
                    </div>

                  </div>

                  <!-- Add deal modal -->
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
                            <form id="addDealPaymentForm">
                                <div class="modal-body">
                                    <div class="container mx-auto py-8">
                                        <div class="flex justify-center col-md-12">
                                            <!-- Deals Section -->
                                            <div class="w-full row col-md-8 bg-white rounded-lg shadow-lg p-6 mr-4">
                                                <div class="form-group col-md-6">
                                                    <label for="name">Deal Name</label>
                                                    <input type="text" id="name" name="name" class="form-control" required>
                                                    <p></p>

                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="client_id">Client Name</label>
                                                    <select id="client_id" name="client_id" class="form-control">
                                                        <option value="WhatsApp">WhatsApp</option>
                                                        <option value="Facebook">Facebook</option>
                                                        <option value="Email">Email</option>
                                                        <option value="Others">Others</option>
                                                    </select>
                                                    <p></p>

                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="work_type">Work Type</label>
                                                    <select id="work_type" name="work_type" class="form-control">
                                                        <option value="FP">Initial Payment</option>
                                                        <option value="IP">Final Payment</option>
                                                    </select>
                                                    <p></p>

                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="deal_value">Deal Value</label>
                                                    <input type="number" id="deal_value" name="deal_value" class="form-control" required>
                                                    <p></p>

                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="deal_date">Deal Date</label>
                                                    <input type="date" id="deal_date" name="deal_date" class="form-control" required>
                                                    <p></p>

                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="due_date">Due Date</label>
                                                    <input type="date" id="due_date" name="due_date" class="form-control" required>
                                                    <p></p>

                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="remarks">Remarks</label>
                                                    <textarea id="remarks" name="remarks" class="form-control" required></textarea>
                                                    <p></p>

                                                </div>
                                            </div>

                                            <!-- Payment Section -->
                                            <div class="w-full col-md-4 bg-green-100 rounded-lg shadow-lg p-6">
                                                <h2 class="text-xl font-semibold mb-4 text-center">Add Payment</h2>
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
                                                    <label for="receipt">Receipt</label>
                                                    <div id="receipt" class="dropzone dz-clickable"></div>
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
                        <form id="addDealPaymentForm">
                            <div class="modal-body">
                                <div class="container mx-auto py-8">
                                    <div class="flex justify-center col-md-12">
                                        <!-- Deals Section -->
                                        <div class="w-full row col-md-12 bg-white rounded-lg shadow-lg p-6 mr-4">
                                            <div class="form-group col-md-6">
                                                <label for="edit-name">Deal Name</label>
                                                <input type="text" id="edit-name" name="name" class="form-control" required>
                                                <p></p>

                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="edit-client_id">Client Name</label>
                                                <select id="edit-client_id" name="client_id" class="form-control">
                                                    <option value="WhatsApp">WhatsApp</option>
                                                    <option value="Facebook">Facebook</option>
                                                    <option value="Email">Email</option>
                                                    <option value="Others">Others</option>
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
                var element = $("#dealForm");
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
        </script>
    @endsection
</x-app-layout>
