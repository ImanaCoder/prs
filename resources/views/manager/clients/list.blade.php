<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg text-gray-800 dark:text-gray-200 leading-tight">
            Clients
        </h2>
    </x-slot>

    <div class="py-12" style="font-size:12px;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-5">

                <div class="container mt-4">
                    <div class="d-flex justify-content-end w-full">
                        <button class="btn btn-primary mb-3 " style="font-size:12px;" data-toggle="modal" data-target="#addClient">Add Client</button>
                    </div>

                    <div class="card">
                        <form action="" method="get">
                            <div class="card-header">
                                <div class="card-title">
                                    <button type="button" onclick="window.location.href='{{ route('clients.index') }}' " class="btn btn-default btn-sm">Reset</button>
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
                                    <th>Client Name</th>
                                    <th>Nationality</th>
                                    <th>Contact </th>
                                    <th>Email</th>
                                    <th>Added Date</th>
                                    <th>Action</th>

                                  </tr>
                                </thead>
                                <tbody>
                                  @if ($clients->isNotEmpty())

                                  @foreach ($clients as $client)
                                  <tr>

                                      <td>{{ $client->id }}</td>
                                      <td>{{ $client->name }}</td>
                                      <td>{{ $client->nationality }}</td>
                                      <td>{{ $client->contact }}</td>
                                      <td>{{ $client->email }}</td>
                                      <td>{{ \Carbon\Carbon::parse($client->created_at)->format('jS F, Y h:i A') }}</td>
                                      <td>
                                          <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editClient" onclick="editClient('{{ $client->id }}')" ><i class="fas fa-edit"></i></button>
                                      </td>
                                  </tr>
                                  @endforeach
                                  @endif

                              </tbody>

                              </table>
                            </div>
                          </div>
                    </div>

                </div>



                <div class="modal fade" id="addClient" tabindex="-1" role="dialog" aria-labelledby="addClientLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document"> <!-- modal-lg for large modal -->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addClientLabel" style="font-size:16px">Add Client</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form id="addClientForm">
                                <div class="modal-body">
                                    <div class="container mx-auto py-8">
                                        <div class="flex justify-center col-md-12">
                                            <!-- Client Section -->
                                            <div class="w-full rounded-lg shadow-lg p-6">
                                                <div hidden class=" form-group col-md-12">
                                                    <label for="user_id">User Id</label>
                                                    <input readonly type="text" id="user_id" name="user_id" class="form-control" required value="{{ Auth::id() }}">
                                                    <p></p>

                                                </div>
                                                <div class="form-group col-md-12">
                                                    <label for="name">Name</label>
                                                    <input type="text" id="name" name="name" class="form-control" required>
                                                    <p></p>

                                                </div>
                                                <div class="form-group col-md-12">
                                                    <label for="email">Email</label>
                                                    <input type="text" id="email" name="email" class="form-control" required>
                                                    <p></p>

                                                </div>
                                                <div class="form-group col-md-12">
                                                    <label for="contact">Contact</label>
                                                    <input type="tel" id="contact" name="contact" class="form-control" required>
                                                    <p></p>

                                                </div>
                                                <div class="form-group col-md-12">
                                                    <label for="nationality">Nationality</label>
                                                    <input type="text" id="nationality" name="nationality" class="form-control" required>
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

                <div class="modal fade" id="editClient" tabindex="-1" role="dialog" aria-labelledby="editClientLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document"> <!-- modal-lg for large modal -->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editClientLabel" style="font-size:16px">Edit Client <span id="client-id"></span></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form id="editClientForm">
                                <div class="modal-body">
                                    <div class="container mx-auto py-8">
                                        <div class="flex justify-center col-md-12">

                                            <div class="w-full rounded-lg shadow-lg p-6">
                                                <div class="form-group col-md-12">
                                                    <label for="edit-user_id">User Id</label>
                                                    <input readonly type="text" id="edit-user_id" name="user_id" class="form-control" required value="{{ Auth::id() }}">
                                                    <p></p>

                                                </div>
                                                <div class="form-group col-md-12">
                                                    <label for="edit-client_id">Id</label>
                                                    <input readonly type="text" id="edit-client_id" name="client_id" class="form-control" required>
                                                    <p></p>

                                                </div>
                                                <div class="form-group col-md-12">
                                                    <label for="edit-name">Name</label>
                                                    <input type="text" id="edit-name" name="name" class="form-control" required>
                                                    <p></p>

                                                </div>
                                                <div class="form-group col-md-12">
                                                    <label for="edit-email">Email</label>
                                                    <input type="text" id="edit-email" name="email" class="form-control" required>
                                                    <p></p>

                                                </div>
                                                <div class="form-group col-md-12">
                                                    <label for="edit-contact">Contact</label>
                                                    <input type="tel" id="edit-contact" name="contact" class="form-control" required>
                                                    <p></p>

                                                </div>
                                                <div class="form-group col-md-12">
                                                    <label for="edit-nationality">Nationality</label>
                                                    <input type="text" id="edit-nationality" name="nationality" class="form-control" required>
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


            $("#addClientForm").submit(function(event){
                event.preventDefault();
                var element = $("#addClientForm");
                $("button[type=submit]").prop('disabled',true);

                $.ajax({
                    url:'{{ route('clients.store') }}',
                    type:'post',
                    data: element.serializeArray(),
                    dataType: 'json',
                    success: function(response){
                        $("button[type=submit]").prop('disabled',false);

                        if(response["status"] == true) {
                            $(".error").removeClass('invalid-feedback');
                            $('input[type="text"], select').removeClass('is-invalid');
                            window.location.href="{{ route('clients.index') }}";

                        }else{

                            var errors = response['errors'];
                            handleFieldError('name', errors);
                            handleFieldError('email', errors);
                            handleFieldError('contact', errors);
                            handleFieldError('nationality', errors);


                        }
                    },
                    error: function(jqXHR, exception){
                        console.log("केहि गलति भयो!");
                    }
                });
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

            function handleEditFieldError(fieldName, errors) {
                var fieldElement = $("#edit-" + fieldName);
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
            function editClient($id) {
                $('#editClientForm')[0].reset(); // Reset the form before populating new data
                var url = '{{ route('clients.edit', ['id' => ':id']) }}'.replace(':id', $id);
                // Make AJAX request to fetch client data
                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        // Populate the edit form fields with fetched data

                        $('#edit-client_id').val(response.client.id);
                        $('#edit-name').val(response.client.name);
                        $('#edit-email').val(response.client.email);
                        $('#edit-contact').val(response.client.contact);
                        $('#edit-nationality').val(response.client.nationality);

                        // Show the edit modal
                        $('#editClient').modal('show');
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('Error fetching client data:', errorThrown);
                    }
                });
            }

            $("#editClientForm").submit(function(event){
                event.preventDefault();
                var element = $("#editClientForm");
                $("button[type=submit]").prop('disabled',true);
                var clientId=$('#edit-client_id').val();
                $.ajax({
                    url:'{{ route('clients.update', ['id' => ':id']) }}'.replace(':id', clientId),
                    type:'put',
                    data: element.serializeArray(),
                    dataType: 'json',
                    success: function(response){
                        $("button[type=submit]").prop('disabled',false);

                        if(response["status"] == true) {
                            $(".error").removeClass('invalid-feedback');
                            $('input[type="text"], select').removeClass('is-invalid');
                            window.location.href="{{ route('clients.index') }}";

                        }else{

                            var errors = response['errors'];
                            handleEditFieldError('name', errors);
                            handleEditFieldError('email', errors);
                            handleEditFieldError('contact', errors);
                            handleEditFieldError('nationality', errors);


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

        </script>
    @endsection
</x-app-layout>
