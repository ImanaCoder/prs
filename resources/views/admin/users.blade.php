<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg text-gray-800 dark:text-gray-200 leading-tight">
            Users
        </h2>
    </x-slot>

    <div class="py-12" style="font-size:12px;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-5">

                <div class="container mt-4">
                    <div class="d-flex justify-content-end w-full">
                        <button class="btn btn-primary mb-3 " style="font-size:12px;" data-toggle="modal" data-target="#addUser">Add User</button>
                    </div>

                    <div class="card">
                        <form action="" method="get">
                            <div class="card-header">
                                <div class="card-title">
                                    <button type="button" onclick="window.location.href='{{ route('users.index') }}' " class="btn btn-default btn-sm">Reset</button>
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
                                    <th>User Name</th>
                                    <th>Email</th>
                                    <th>Team</th>

                                    <th>Added Date</th>
                                    <th>Action</th>

                                  </tr>
                                </thead>
                                <tbody>
                                  @if ($users->isNotEmpty())

                                  @foreach ($users as $user)
                                  <tr>

                                      <td>{{ $user->id }}</td>
                                      <td>{{ $user->name }}</td>

                                      <td>{{ $user->email }}</td>
                                      <td>{{ $user->team_id ? $user->team->name : 'N/A' }}</td>

                                      <td>{{ \Carbon\Carbon::parse($user->created_at)->format('jS F, Y h:i A') }}</td>
                                      <td>
                                          <button class="btn btn-sm btn-primary"  onclick="editUser('{{ $user->id }}')" ><i class="fas fa-eye"></i></button>
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



                <div class="modal fade" id="addUser" tabindex="-1" role="dialog" aria-labelledby="addUserLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document"> <!-- modal-lg for large modal -->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addUserLabel" style="font-size:16px">Add User</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form id="addUserForm">
                                <div class="modal-body">
                                    <div class="container mx-auto py-8">
                                        <div class="flex justify-center col-md-12">
                                            <!-- User Section -->
                                            <div class="w-full rounded-lg shadow-lg p-6">

                                                <div class="form-group col-md-12">
                                                    <label for="name">Name</label>
                                                    <input type="text" id="name" name="name" class="form-control" required>
                                                    <p></p>
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <label for="email">Email</label>
                                                    <input type="email" id="email" name="email" class="form-control" required>
                                                    <p></p>
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <label for="password">Password</label>
                                                    <input type="password" id="password" name="password" class="form-control" required>
                                                    <p></p>
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <label for="password_confirmation">Confirm Password</label>
                                                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                                                    <p></p>
                                                </div>

                                                <div class="form-group col-md-12">
                                                    <label for="role_id">Role </label>
                                                    <select id="role_id" name="role_id" class="form-control">
                                                        @foreach ($roles as $role)
                                                        <option value="{{ $role->id }}">{{ $role->name }}</option>

                                                        @endforeach

                                                    </select>
                                                    <p></p>

                                                </div>
                                                <div id="teamForm" class="form-group col-md-12"  style="display: none;">
                                                    <label for="team_id">Team</label>
                                                    <select id="team_id" name="team_id" class="form-control">
                                                        @foreach ($teams as $team)
                                                        <option value="{{ $team->id }}">{{ $team->name }}</option>

                                                        @endforeach

                                                    </select>
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

                <div class="modal fade" id="editUser" tabindex="-1" role="dialog" aria-labelledby="editUserLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document"> <!-- modal-lg for large modal -->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editUserLabel" style="font-size:16px">Edit User <span id="user-id"></span></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form id="editUserForm">
                                <div class="modal-body">
                                    <div class="container mx-auto py-8">
                                        <div class="flex justify-center col-md-12">

                                            <div class="w-full rounded-lg shadow-lg p-6">

                                                <div class="form-group col-md-12">
                                                    <label for="edit-user_id">Id</label>
                                                    <input readonly type="text" id="edit-user_id" name="user_id" class="form-control" required>
                                                    <p></p>

                                                </div>
                                                <div class="form-group col-md-12">
                                                    <label for="edit-name">Name</label>
                                                    <input readonly type="text" id="edit-name" name="name" class="form-control" required>
                                                    <p></p>

                                                </div>
                                                <div class="form-group col-md-12">
                                                    <label for="edit-email">Email</label>
                                                    <input readonly type="email" id="edit-email" name="email" class="form-control" required>
                                                    <p></p>

                                                </div>

                                                <div class="form-group col-md-12">
                                                    <label for="edit-role_name">Role </label>
                                                    <input readonly type="edit-role_name" id="edit-role_name" name="email" class="form-control" required>

                                                    <p></p>

                                                </div>
                                                <div id="editTeamForm" class="form-group col-md-12"  style="display: none;">
                                                    <label for="edit-team_name">Team</label>
                                                    <input readonly type="edit-team_name" id="edit-team_name" name="email" class="form-control" required>

                                                    <p></p>

                                                </div>



                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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


            $("#addUserForm").submit(function(event){
                event.preventDefault();
                var element = $("#addUserForm");
                $("button[type=submit]").prop('disabled',true);

                $.ajax({
                    url:'{{ route('users.store') }}',
                    type:'post',
                    data: element.serializeArray(),
                    dataType: 'json',
                    success: function(response){
                        $("button[type=submit]").prop('disabled',false);

                        if(response["status"] == true) {
                            $(".error").removeClass('invalid-feedback');
                            $('input[type="text"], select').removeClass('is-invalid');
                            window.location.href="{{ route('users.index') }}";

                        }else{

                            var errors = response['errors'];
                            handleFieldError('name', errors);
                            handleFieldError('description', errors);


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
            function editUser($id) {
                $('#editUserForm')[0].reset(); // Reset the form before populating new data
                var url = '{{ route('users.edit', ['id' => ':id']) }}'.replace(':id', $id);
                // Make AJAX request to fetch user data
                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        // Populate the edit form fields with fetched data

                        $('#edit-user_id').val(response.user.id);
                        $('#edit-name').val(response.user.name);
                        $('#edit-email').val(response.user.email);

                        if (response.user.team) {
                            $('#edit-team_name').val(response.user.team.name);
                        }


                        $('#edit-role_name').val(response.user.roles[0].name);
                        var teamFormGroup = document.getElementById('editTeamForm');
                        if (response.user.roles[0].id == 3) {
                            teamFormGroup.style.display = 'block';
                        } else {
                            teamFormGroup.style.display = 'none';
                        }

                        // Show the edit modal
                        $('#editUser').modal('show');
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('Error fetching user data:', errorThrown);
                    }
                });
            }



            document.getElementById('role_id').addEventListener('change', function() {
                var teamFormGroup = document.getElementById('teamForm');
                if (this.value == 3) {
                    teamFormGroup.style.display = 'block';
                } else {
                    teamFormGroup.style.display = 'none';
                    $('#team_id').val(null);

                }
            });



        </script>
    @endsection
</x-app-layout>
