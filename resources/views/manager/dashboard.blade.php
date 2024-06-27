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


                                                <div class="tab-content">
                                                    <div id="denied" class="tab-pane fade show active">
                                                        <div class="card-body">

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
                                                                            <div class="text-muted">
                                                                                {{ \Carbon\Carbon::parse($payment['created_at'])->translatedFormat('j F, Y g:i A') }}
                                                                            </div>                                                                            </div>
                                                                    </div>
                                                                    <div class="text-right clearfix">
                                                                        <strong>${{ number_format($payment['payment_value'], 2) }}</strong>
                                                                    </div>
                                                                </div>
                                                            @endforeach

                                                        @else
                                                            <div class="alert alert-info">No payments available.</div>
                                                        @endif
                                                        </div>
                                                        <div class="card-footer clearfix">
                                                            {{ $rejectedPayments->links() }}

                                                        </div>
                                                    </div>
                                                    <div id="pending" class="tab-pane fade">
                                                        <div class="card-body">

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
                                                                            <div class="text-muted">
                                                                                {{ \Carbon\Carbon::parse($payment['created_at'])->translatedFormat('j F, Y g:i A') }}
                                                                            </div>                                                                            </div>
                                                                    </div>
                                                                    <div class="text-right">
                                                                        <strong>${{ number_format($payment['payment_value'], 2) }}</strong>
                                                                    </div>
                                                                </div>
                                                            @endforeach

                                                        @else
                                                            <div class="alert alert-info">No payments available.</div>
                                                        @endif
                                                    </div>
                                                    <div class="card-footer clearfix">
                                                        {{ $pendingPayments->links() }}

                                                    </div>

                                                    </div>
                                                    <div id="verified" class="tab-pane fade">
                                                        <div class="card-body">
                                                            @if ($verifiedPayments->isNotEmpty())


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
                                                                                <div class="text-muted">
                                                                                    {{ \Carbon\Carbon::parse($payment['created_at'])->translatedFormat('j F, Y g:i A') }}
                                                                                </div>                                                                                </div>
                                                                        </div>
                                                                        <div class="d-flex justify-content-end clearfix">
                                                                            <strong>${{ number_format($payment['payment_value'], 2) }}</strong>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            @else
                                                            <div class="alert alert-info">No payments available.</div>
                                                            @endif
                                                        </div>
                                                        <div class="card-footer clearfix">
                                                            {{ $verifiedPayments->links() }}

                                                        </div>
                                                    </div>
                                                    <div id="all" class="tab-pane fade">
                                                        <div class="card-body">
                                                            @if ($payments->isNotEmpty())


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
                                                                                <div class="text-muted">
                                                                                    {{ \Carbon\Carbon::parse($payment['created_at'])->translatedFormat('j F, Y g:i A') }}
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="text-right">
                                                                            <strong>${{ number_format($payment['payment_value'], 2) }}</strong>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            @else
                                                                <div class="alert alert-info">No payments available.</div>
                                                            @endif
                                                        </div>
                                                        <div class="card-footer clearfix">
                                                            {{ $payments->links() }}

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
                                            <div class="card-header">
                                                <div class="card-title">Source Type</div>
                                                <div class="card-tools justify-content-end d-flex flex-wrap align-items-center">
                                                    <input type="text" id="daterange_textbox_v1" name="top_works_daterange_v1" class="form-control" readonly value="{{ request()->input('top_works_daterange_v1', date('Y-m-d')) }}" style="width:200px" />
                                                    <p class="mx-2"> VS</p>
                                                    <input type="text" id="daterange_textbox_v2" name="top_works_daterange_v2" class="form-control" readonly value="{{ request()->input('top_works_daterange_v2', date('Y-m-d')) }}" style="width:200px" />

                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="container mt-4">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Work Type</th>
                                                                            <th>Change</th>
                                                                            <th>V2 Count</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($jsonList as $item)
                                                                        <tr>
                                                                            <td>{{ $item['name'] }}</td>
                                                                            <td class="row">
                                                                                {{ $item['v1Count'] }} Deals
                                                                                <div class="ml-2">
                                                                                    @if ($item['increase_or_decrease'] > 0)
                                                                                    <span class="text-success">
                                                                                        <i class="fas fa-arrow-up"></i> Increased
                                                                                    </span>
                                                                                @elseif ($item['increase_or_decrease'] < 0)
                                                                                    <span class="text-danger">
                                                                                        <i class="fas fa-arrow-down"></i> Decreased
                                                                                    </span>
                                                                                @else
                                                                                    <span class="text-info">No Change</span>
                                                                                @endif
                                                                                <br>({{ number_format($item['percentage'], 2) }}%)
                                                                                </div>
                                                                            </td>
                                                                            <td>{{ $item['v2Count'] }} Deals</td>
                                                                        </tr>
                                                                        @endforeach

                                                                    </tbody>
                                                                </table>
                                                            </div>

                                                        </div>
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
                        format: 'YYYY/MM/DD'
                    },
                }, function(start, end) {
                    $('#daterange_textbox').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
                    $('#dashboardForm').submit();
                });


            });

            $(document).ready(function() {
                $('#daterange_textbox_v1').daterangepicker({
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
                    $('#daterange_textbox_v1').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
                    $('#dashboardForm').submit();
                });


            });

            $(document).ready(function() {
                $('#daterange_textbox_v2').daterangepicker({
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
                    $('#daterange_textbox_v2').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
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

        // Ensure the active tab is retained when clicking pagination links
        $(document).ready(function() {
            // Get the current active tab from local storage, if available
            var activeTab = localStorage.getItem('activeTab');
            if (activeTab) {
                $('.nav-link').removeClass('active'); // Remove active class from all nav links
                $('.tab-pane').removeClass('show active'); // Remove show and active classes from all tab panes
                $(activeTab).addClass('show active'); // Add show and active classes to the stored active tab
                $('a[href="' + activeTab + '"]').addClass('active'); // Add active class to the corresponding nav link
            }

            // Store the active tab in local storage when a tab is clicked
            $('.nav-link').click(function() {
                var tabId = $(this).attr('href');
                localStorage.setItem('activeTab', tabId);
            });
        });
        </script>
    @endsection
</x-app-layout>


