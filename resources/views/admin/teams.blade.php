<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg text-gray-800 dark:text-gray-200 leading-tight mx-xl-5 mx-2 py-3">
            Teams
        </h2>
    </x-slot>

    <div class="py-12" style="font-size:12px;">
        <div class="max-w-8xl mx-xl-5 mx-2">
            <div class=" overflow-hidden sm:rounded-lg p-2">

                <div class="container mt-4">
                    @include('message')

                    <div class="d-flex justify-content-end w-full">
                        <button class="btn btn-primary mb-3 " style="font-size:12px;" data-toggle="modal" data-target="#addTeam">Add Team</button>
                    </div>

                    <div class="card">
                        <form action="" method="get">
                            <div class="card-header">
                                <div class="card-title">
                                    <button type="button" onclick="window.location.href='{{ route('teams.index') }}' " class="btn btn-default btn-sm">Reset</button>
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
                                    <th>Team Name</th>
                                    <th>Description</th>
                                    <th>Added Date</th>
                                    <th>Action</th>

                                  </tr>
                                </thead>
                                <tbody>
                                  @if ($teams->isNotEmpty())

                                  @foreach ($teams as $team)
                                  <tr>

                                      <td>{{ $team->id }}</td>
                                      <td>{{ $team->name }}</td>

                                      <td>{{ $team->description }}</td>
                                      <td>{{ \Carbon\Carbon::parse($team->created_at)->format('jS F, Y h:i A') }}</td>
                                      <td>
                                          <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editTeam" onclick="editTeam('{{ $team->id }}')" ><i class="fas fa-edit"></i></button>
                                      </td>
                                  </tr>
                                  @endforeach
                                  @endif

                              </tbody>

                              </table>
                            </div>
                          </div>
                          <div class="card-footer clearfix">
                            {!! $teams->links() !!}
                        </div>
                    </div>

                </div>



                <div class="modal fade" id="addTeam" tabindex="-1" role="dialog" aria-labelledby="addTeamLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document"> <!-- modal-lg for large modal -->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addTeamLabel" style="font-size:16px">Add Team</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form id="addTeamForm">
                                <div class="modal-body">
                                    <div class="container mx-auto py-8">
                                        <div class="flex justify-center col-md-12">
                                            <!-- Team Section -->
                                            <div class="w-full rounded-lg shadow-lg p-6">

                                                <div class="form-group col-md-12">
                                                    <label for="name">Name</label>
                                                    <input type="text" id="name" name="name" class="form-control" required>
                                                    <p></p>

                                                </div>
                                                <div class="form-group col-md-12">
                                                    <label for="description">Description</label>
                                                    <textarea rows="4" type="text" id="description" name="description" class="form-control" required></textarea>
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

                <div class="modal fade" id="editTeam" tabindex="-1" role="dialog" aria-labelledby="editTeamLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document"> <!-- modal-lg for large modal -->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editTeamLabel" style="font-size:16px">Edit Team <span id="team-id"></span></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form id="editTeamForm">
                                <div class="modal-body">
                                    <div class="container mx-auto py-8">
                                        <div class="flex justify-center col-md-12">

                                            <div class="w-full rounded-lg shadow-lg p-6">

                                                <div class="form-group col-md-12">
                                                    <label for="edit-team_id">Id</label>
                                                    <input readonly type="text" id="edit-team_id" name="team_id" class="form-control" required>
                                                    <p></p>

                                                </div>
                                                <div class="form-group col-md-12">
                                                    <label for="edit-name">Name</label>
                                                    <input type="text" id="edit-name" name="name" class="form-control" required>
                                                    <p></p>

                                                </div>
                                                <div class="form-group col-md-12">
                                                    <label for="edit-description">Description</label>
                                                    <textarea rows='4' type="text" id="edit-description" name="description" class="form-control" required></textarea>
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


            $("#addTeamForm").submit(function(event){
                event.preventDefault();
                var element = $("#addTeamForm");
                $("button[type=submit]").prop('disabled',true);

                $.ajax({
                    url:'{{ route('teams.store') }}',
                    type:'post',
                    data: element.serializeArray(),
                    dataType: 'json',
                    success: function(response){
                        $("button[type=submit]").prop('disabled',false);

                        if(response["status"] == true) {
                            $(".error").removeClass('invalid-feedback');
                            $('input[type="text"], select').removeClass('is-invalid');
                            window.location.href="{{ route('teams.index') }}";

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
            function editTeam($id) {
                $('#editTeamForm')[0].reset(); // Reset the form before populating new data
                var url = '{{ route('teams.edit', ['id' => ':id']) }}'.replace(':id', $id);
                // Make AJAX request to fetch team data
                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        // Populate the edit form fields with fetched data

                        $('#edit-team_id').val(response.team.id);
                        $('#edit-name').val(response.team.name);
                        $('#edit-description').val(response.team.description);


                        // Show the edit modal
                        $('#editTeam').modal('show');
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('Error fetching team data:', errorThrown);
                    }
                });
            }

            $("#editTeamForm").submit(function(event){
                event.preventDefault();
                var element = $("#editTeamForm");
                $("button[type=submit]").prop('disabled',true);
                var teamId=$('#edit-team_id').val();
                $.ajax({
                    url:'{{ route('teams.update', ['id' => ':id']) }}'.replace(':id', teamId),
                    type:'put',
                    data: element.serializeArray(),
                    dataType: 'json',
                    success: function(response){
                        $("button[type=submit]").prop('disabled',false);

                        if(response["status"] == true) {
                            $(".error").removeClass('invalid-feedback');
                            $('input[type="text"], select').removeClass('is-invalid');
                            window.location.href="{{ route('teams.index') }}";

                        }else{

                            var errors = response['errors'];
                            handleEditFieldError('name', errors);
                            handleEditFieldError('description', errors);


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
