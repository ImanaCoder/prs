<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg text-gray-800 dark:text-gray-200 leading-tight mx-xl-5 mx-2 py-3">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <form action="" id="dashboardForm">
            <div class="max-w-8xl mx-xl-5 mx-2 px-lg-5">
                <div class=" overflow-hidden sm:rounded-lg py-2">
                    <!-- Sales Summary -->
                    <section class=" ">
                        <div class="row col-md-12" style="padding:0px">
                            <div class="col-md-6 mb-4" style="padding-left: 0px">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h3 class="card-title">Today Total Sales</h3>
                                                <p class="card-text" style="font-size: 60px; margin-left: 50px;">{{ $salesCountToday }}</p>
                                            </div>
                                            <div class="card-tools">
                                                <select id="today_team_id" name="today_team_id"  class="form-control mb-2" style="width:200px">
                                                    <option value="">All</option>
                                                    @foreach ($teams as $team)
                                                        <option value="{{ $team->id }}" {{ Request::get('team_id') == $team->id ? 'selected' : '' }}>
                                                            {{ $team->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <input type="date" name="today" class="form-control" value="{{ request()->input('today', date('Y-m-d')) }}"  onchange="submitForm()" />

                                            </div>
                                        </div>
                                        <div class="table-responsive overflow-auto">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Name</th>
                                                        <th>Client Name</th>
                                                        <th>Work Type</th>
                                                        <th>Deal Value</th>
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
                                            <div class="d-flex justify-content-center">
                                                {{ $salesToday->links() }}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4" style="padding-right: 0px">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h3 class="card-title">Custom Total Sales</h3>
                                                <p class="card-text" style="font-size: 60px; margin-left: 50px;">{{ $totalSales }}</p>
                                            </div>
                                            <div class="card-tools" >
                                                <select id="team_id" name="team_id" class="form-control mb-2" style="width:200px">
                                                    <option value="">All</option>
                                                    @foreach ($teams as $team)
                                                        <option value="{{ $team->id }}" {{ Request::get('team_id') == $team->id ? 'selected' : '' }}>
                                                            {{ $team->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <input type="text" id="daterange_textbox" name="sales_daterange" class="form-control" readonly value="{{ request()->input('sales_daterange', date('Y-m-d')) }}" style="width:200px" />




                                            </div>
                                        </div>

                                        <div class="table-responsive overflow-auto">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Name</th>
                                                        <th>Client Name</th>
                                                        <th>Work Type</th>
                                                        <th>Deal Value</th>
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
                                            <div class="d-flex justify-content-center">
                                                {{ $salesThisMonth->links() }}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>


                    <!-- Additional Info -->
                    <section class="additional-info">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-tools">
                                 <select id="manager_id" name="manager_id" class="form-control">
                                    <option value="">All</option>
                                    @foreach ($managers as $manager)
                                        <option value="{{ $manager->id }}" {{ Request::get('manager_id') == $manager->id ? 'selected' : '' }}>
                                            {{ $manager->name }}
                                        </option>
                                    @endforeach
                                </select>

                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">

                                    <!-- Payment Activities -->
                                    <div class="col-md-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <h3 class="card-title mb-0">Payment Activities</h3>
                                                        </div>
                                                        <div class="card-tools">
                                                            <ul class="nav nav-pills mb-0" id="paymentTabs">
                                                                <li class="nav-item">
                                                                    <a class="nav-link active" data-toggle="tab" href="#denied">Denied</a>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <a class="nav-link" data-toggle="tab" href="#pending">Pending</a>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <a class="nav-link" data-toggle="tab" href="#verified">Verified</a>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <a class="nav-link" data-toggle="tab" href="#all">All</a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="card-body">

                                                    <div class="tab-content">
                                                        <div id="denied" class="tab-pane fade show active">
                                                            @if ($rejectedPayments->isNotEmpty())
                                                                @foreach ($rejectedPayments as $payment)
                                                                    <div class="d-flex justify-content-between align-items-center mb-3 p-3 border rounded">
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="status-circle
                                                                                @if ($payment['status'] == 0) status-red
                                                                                @elseif ($payment['status'] == 1) status-green
                                                                                @elseif ($payment['status'] == 3) status-yellow
                                                                                @endif
                                                                            ">
                                                                                <i class="fas fa-info"></i>
                                                                            </div>
                                                                            <div class="ml-3">
                                                                                <div>
                                                                                    @if ($payment['remarks'])
                                                                                        {{ $payment['remarks'] }}
                                                                                    @elseif ($payment['verification_remarks'])
                                                                                        {{ $payment['verification_remarks'] }}
                                                                                    @endif
                                                                                </div>
                                                                                <div class="text-muted">{{ $payment['created_at'] }}</div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="text-right">
                                                                            <strong>${{ number_format($payment['payment_value'], 2) }}</strong>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                                <div class="d-flex justify-content-center">
                                                                    {{ $rejectedPayments->links() }}
                                                                </div>
                                                            @else
                                                                <div class="alert alert-info">No payments available.</div>
                                                            @endif
                                                        </div>
                                                        <div id="pending" class="tab-pane fade">
                                                            @if ($pendingPayments->isNotEmpty())
                                                                @foreach ($pendingPayments as $payment)
                                                                    <div class="d-flex justify-content-between align-items-center mb-3 p-3 border rounded">
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="status-circle
                                                                                @if ($payment['status'] == 0) status-red
                                                                                @elseif ($payment['status'] == 1) status-green
                                                                                @elseif ($payment['status'] == 3) status-yellow
                                                                                @endif
                                                                            ">
                                                                                <i class="fas fa-info"></i>
                                                                            </div>
                                                                            <div class="ml-3">
                                                                                <div>
                                                                                    @if ($payment['remarks'])
                                                                                        {{ $payment['remarks'] }}
                                                                                    @elseif ($payment['verification_remarks'])
                                                                                        {{ $payment['verification_remarks'] }}
                                                                                    @endif
                                                                                </div>
                                                                                <div class="text-muted">{{ $payment['created_at'] }}</div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="text-right">
                                                                            <strong>${{ number_format($payment['payment_value'], 2) }}</strong>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                                <div class="d-flex justify-content-center">
                                                                    {{ $pendingPayments->links() }}
                                                                </div>
                                                            @else
                                                                <div class="alert alert-info">No payments available.</div>
                                                            @endif
                                                        </div>
                                                        <div id="verified" class="tab-pane fade">
                                                            @foreach ($verifiedPayments as $payment)
                                                                <div class="d-flex justify-content-between align-items-center mb-3 p-3 border rounded">
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="status-circle
                                                                            @if ($payment['status'] == 0) status-red
                                                                            @elseif ($payment['status'] == 1) status-green
                                                                            @elseif ($payment['status'] == 3) status-yellow
                                                                            @endif
                                                                        ">
                                                                            <i class="fas fa-info"></i>
                                                                        </div>
                                                                        <div class="ml-3">
                                                                            <div>
                                                                                @if ($payment['remarks'])
                                                                                    {{ $payment['remarks'] }}
                                                                                @elseif ($payment['verification_remarks'])
                                                                                    {{ $payment['verification_remarks'] }}
                                                                                @endif
                                                                            </div>
                                                                            <div class="text-muted">{{ $payment['created_at'] }}</div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="text-right">
                                                                        <strong>${{ number_format($payment['payment_value'], 2) }}</strong>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                            <div class="d-flex justify-content-center">
                                                                {{ $verifiedPayments->links() }}
                                                            </div>
                                                        </div>
                                                        <div id="all" class="tab-pane fade">
                                                            @foreach ($payments as $payment)
                                                                <div class="d-flex justify-content-between align-items-center mb-3 p-3 border rounded">
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="status-circle
                                                                            @if ($payment['status'] == 0) status-red
                                                                            @elseif ($payment['status'] == 1) status-green
                                                                            @elseif ($payment['status'] == 3) status-yellow
                                                                                                                                @endif
                                                                        ">
                                                                            <i class="fas fa-info"></i>
                                                                        </div>
                                                                        <div class="ml-3">
                                                                            <div>
                                                                                @if ($payment['remarks'])
                                                                                    {{ $payment['remarks'] }}
                                                                                @elseif ($payment['verification_remarks'])
                                                                                    {{ $payment['verification_remarks'] }}
                                                                                @endif
                                                                            </div>
                                                                            <div class="text-muted">{{ $payment['created_at'] }}</div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="text-right">
                                                                        <strong>${{ number_format($payment['payment_value'], 2) }}</strong>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                            <div class="d-flex justify-content-center">
                                                                {{ $payments->links() }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="card-title">Source Type</div>
                                            </div>
                                            <div class="card-body">
                                                <canvas id="pieChart" width="400" height="400"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <div class="card">
                                            <div class="card-body">
                                                <h3 class="card-title">Top Works</h3>
                                                <!-- Content for top works -->
                                            </div>
                                        </div>
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
                        format: 'YYYY-MM-DD'
                    },
                }, function(start, end) {
                    $('#daterange_textbox').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
                    $('#dashboardForm').submit();
                });


            });

            document.addEventListener('DOMContentLoaded', function () {
            var data = @json($data); // Convert PHP array to JavaScript array

            // Extract labels and percentages
            var labels = data.map(function(item) {
                return item[0];
            });

            var percentages = data.map(function(item) {
                return item[1];
            });

            // Remove first element (labels)
            labels.shift();
            percentages.shift();

            // Rendering pie chart
            var ctx = document.getElementById('pieChart').getContext('2d');
            var pieChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: percentages,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.7)',
                            'rgba(54, 162, 235, 0.7)',
                            'rgba(255, 206, 86, 0.7)',
                            'rgba(75, 192, 192, 0.7)',
                            'rgba(153, 102, 255, 0.7)',
                            'rgba(255, 159, 64, 0.7)',
                            'rgba(255, 99, 132, 0.7)',
                            'rgba(54, 162, 235, 0.7)',
                            'rgba(255, 206, 86, 0.7)',
                            'rgba(75, 192, 192, 0.7)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return tooltipItem.label + ': ' + tooltipItem.raw.toFixed(2) + '%';
                                }
                            }
                        }
                    }
                }
            });
        });

        function submitForm() {
            document.getElementById('dashboardForm').submit();
        }
        </script>
    @endsection
</x-app-layout>


