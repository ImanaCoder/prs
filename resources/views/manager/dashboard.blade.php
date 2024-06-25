<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-5">
                <!-- Sales Summary -->
                <section class="sales-summary">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h3 class="card-title">Today's Total Sales</h3>
                                        <p class="card-text" style="font-size: 60px; margin-left: 50px;">0</p>
                                        <div class="overflow-auto">
                                            <table class="table table-bordered ">
                                                <tbody>
                                                    <tr>
                                                        <td>DEALS (NO.)</td>
                                                        <td>IP (NO.)</td>
                                                        <td>FP (NO.)</td>
                                                        <td>IP (AMOUNT)</td>
                                                        <td>FP (AMOUNT)</td>
                                                        <td>DUE AMOUNT</td>
                                                        <td>CCLHUB COURSES</td>
                                                        <td>TIPS</td>
                                                    </tr>
                                                    <tr>
                                                        <td>0</td>
                                                        <td>0</td>
                                                        <td>0</td>
                                                        <td>0</td>
                                                        <td>0</td>
                                                        <td>0</td>
                                                        <td>0</td>
                                                        <td>0</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h3 class="card-title">June Total Sales</h3>
                                        <p class="card-text" style="font-size: 60px; margin-left: 50px;">0</p>
                                        <div class="overflow-auto">

                                            <table class="table table-bordered overflow-auto">
                                                <tbody>
                                                    <tr>
                                                        <td>DEALS (NO.)</td>
                                                        <td>IP (NO.)</td>
                                                        <td>FP (NO.)</td>
                                                        <td>IP (AMOUNT)</td>
                                                        <td>FP (AMOUNT)</td>
                                                        <td>DUE AMOUNT</td>
                                                        <td>CCLHUB COURSES</td>
                                                        <td>TIPS</td>
                                                    </tr>
                                                    <tr>
                                                        <td>0</td>
                                                        <td>0</td>
                                                        <td>0</td>
                                                        <td>0</td>
                                                        <td>0</td>
                                                        <td>0</td>
                                                        <td>0</td>
                                                        <td>0</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                
        
                <!-- Payment Activities -->
                <section class="payment-activities">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="mb-4">Payment Activities</h3>

                        </div>
                        <div class="card-body">
                            <ul class="nav nav-pills mb-4" id="paymentTabs">
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
                            <div class="tab-content">
                                <div id="denied" class="tab-pane fade show active">
                                    <h4>Denied Transactions</h4>
                                    <p>This is the content for Denied transactions.</p>
                                </div>
                                <div id="pending" class="tab-pane fade">
                                    <h4>Pending Transactions</h4>
                                    <p>This is the content for Pending transactions.</p>
                                </div>
                                <div id="verified" class="tab-pane fade">
                                    <h4>Verified Transactions</h4>
                                    <p>This is the content for Verified transactions.</p>
                                </div>
                                <div id="all" class="tab-pane fade">
                                    <h4>All Transactions</h4>
                                    <p>This is the content for All transactions.</p>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </section>
                
                
        
                <!-- Additional Info -->
                <section class="additional-info">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h3 class="card-title">Source Type</h3>
                                        <!-- Content for source type -->
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
                </section>
                
                    
        </div>
    </div>
</x-app-layout>
