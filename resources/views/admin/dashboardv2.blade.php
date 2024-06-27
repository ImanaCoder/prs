<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg text-gray-800 dark:text-gray-200 leading-tight mx-xl-5 mx-2 py-3">
            Dashboard V2
        </h2>
    </x-slot>

    <div class="py-12">
        <form action="" id="dashboardForm">
            <div class="max-w-8xl mx-xl-5 mx-2">
                <div class="d-flex justify-content-end w-full">

                    <select id="team_id" name="team_id" class="form-control mb-2" style="width:200px">
                        <option value="">All Teams</option>
                        @foreach ($teams as $team)
                            <option value="{{ $team->id }}" {{ Request::get('team_id') == $team->id ? 'selected' : '' }}>
                                {{ $team->name }}
                            </option>
                        @endforeach
                    </select>
                    <select id="manager_id" name="manager_id" class="form-control mb-2" style="width:200px">
                        <option value="">All Managers</option>
                        @foreach ($managers as $manager)
                            <option value="{{ $manager->id }}" {{ Request::get('manager_id') == $manager->id ? 'selected' : '' }}>
                                {{ $manager->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="overflow-hidden sm:rounded-lg pt-2">
                    <!-- Sales Summary -->
                    <section>
                        <div class="row ">
                            <div class="col-md-6 mb-2">
                                <div class="card" >
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h3 class="card-title">Today Total Sales</h3>
                                        </div>

                                    </div>
                                    <div class="card-body">

                                        <div class="table-responsive overflow-auto">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Name</th>
                                                        <th>Client Name</th>
                                                        <th>Work Type</th>
                                                        <th>Deal Value</th>
                                                        <th>Due Amount</th>
                                                        <th>Deal Date</th>
                                                        <th>Due Date</th>
                                                        <th>Remarks</th>
                                                        <th>Deal Version</th>
                                                        <th>Status</th>
                                                        <th>Created At</th>
                                                        <th>Updated At</th>
                                                        <th>Due Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($salesToday as $deal)
                                                    <tr>
                                                        <td>{{ $deal->id }}</td>
                                                        <td>{{ $deal->name }}</td>
                                                        <td>{{ $deal->client->name }}</td>
                                                        <td>{{ $deal->work_type }}</td>
                                                        <td>${{ number_format($deal->deal_value, 2) }}</td>
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

                                                        <td>{{ date('Y-m-d', strtotime($deal->deal_date)) }}</td>
                                                        <td>{{ date('Y-m-d', strtotime($deal->due_date)) }}</td>
                                                        <td>{{ $deal->remarks }}</td>
                                                        <td>{{ $deal->deal_version }}</td>
                                                        <td>{{ $deal->status }}</td>
                                                        <td>{{ date('Y-m-d H:i:s', strtotime($deal->created_at)) }}</td>
                                                        <td>{{ date('Y-m-d H:i:s', strtotime($deal->updated_at)) }}</td>
                                                        <td>${{ number_format($deal->due_amount, 2) }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>

                                        </div>

                                    </div>
                                    <div class="card-footer clearfix">
                                        {{ $salesToday->links() }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2"  >
                                <div class="card" style="background-color:bisque">
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h3 class="card-title">Custom Total Sales</h3>
                                        </div>
                                        <div class="card-tools" >
                                            <input type="text" id="daterange_textbox" name="sales_daterange" class="form-control" readonly value="{{ request()->input('sales_daterange', date('Y-m-d')) }}" style="width:200px" />
                                        </div>
                                    </div>
                                    <div class="card-body">


                                        <div class="table-responsive overflow-auto">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Name</th>
                                                        <th>Client Name</th>
                                                        <th>Work Type</th>
                                                        <th>Deal Value</th>
                                                        <th>Due Amount</th>
                                                        <th>Deal Date</th>
                                                        <th>Due Date</th>
                                                        <th>Remarks</th>
                                                        <th>Deal Version</th>
                                                        <th>Status</th>
                                                        <th>Created At</th>
                                                        <th>Updated At</th>
                                                        <th>Due Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($salesThisMonth as $deal)
                                                    <tr>
                                                        <td>{{ $deal->id }}</td>
                                                        <td>{{ $deal->name }}</td>
                                                        <td>{{ $deal->client->name }}</td>
                                                        <td>{{ $deal->work_type }}</td>
                                                        <td>${{ number_format($deal->deal_value, 2) }}</td>
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

                                                            <span style="font-weight: 700" class="{{ $dueStatusClass }}">${{ $dueAmountFormatted }}</span>
                                                        </td>
                                                                                                                <td>{{ date('Y-m-d', strtotime($deal->deal_date)) }}</td>
                                                        <td>{{ date('Y-m-d', strtotime($deal->due_date)) }}</td>
                                                        <td>{{ $deal->remarks }}</td>
                                                        <td>{{ $deal->deal_version }}</td>
                                                        <td>{{ $deal->status }}</td>
                                                        <td>{{ date('Y-m-d H:i:s', strtotime($deal->created_at)) }}</td>
                                                        <td>{{ date('Y-m-d H:i:s', strtotime($deal->updated_at)) }}</td>
                                                        <td>${{ number_format($deal->due_amount, 2) }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>

                                        </div>

                                    </div>
                                    <div class="card-footer clearfix">
                                        {!! $salesThisMonth->links() !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                </div>
            </div>
        </form>
    </div>

    @section('customJs')
        <script>
            $(document).ready(function() {
                $('#daterange_textbox').daterangepicker({
                    showDropdowns: true,
                    autoApply: true,
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    },
                    locale: {
                        format: 'YYYY/MM/DD'
                    },
                }, function(start, end) {
                    $('#daterange_textbox').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
                    $('#dashboardForm').submit();
                });


            });

            // jQuery document ready function to ensure DOM is fully loaded
            $(document).ready(function() {
                // Bind change event to both select elements
                $('#team_id, #manager_id').change(function() {
                    // Submit the form with id 'searchForm'
                    $('#dashboardForm').submit();
                });
            });



        </script>
    @endsection
</x-app-layout>


