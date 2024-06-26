<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg text-gray-800 dark:text-gray-200 leading-tight mx-xl-5 mx-2 py-3">
            Payment #{{ $payment->id }}
        </h2>
    </x-slot>

    <div class="py-12" style="font-size:12px;">
        <div class="max-w-8xl mx-xl-5 mx-2">
            <div class=" overflow-hidden sm:rounded-lg p-xl-5 p-2">

                <div class="container mt-4">

                    <div class="card">

                        <div class="card-body">
                            <div class="overflow-auto">
                                <div class="container mx-auto py-8">
                                    <div class=" col-md-12">

                                        <!-- Payment Section -->
                                        <div class="row mt-3 w-full col-md-12 rounded-lg shadow-lg p-6">
                                            <div class="form-group col-md-6">
                                                <label for="payment_id">Payment Id</label><br>
                                                <span id="payment_id">{{ $payment->id }}</span>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="payment_deal_id">Deal </label><br>
                                                <span id="payment_deal_id">{{ $payment->deal->name }}</span>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="payment_id">Client</label><br>
                                                <span id="payment_id">{{ $payment->deal->client->name }}</span>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="payment_date">Payment Date</label><br>
                                                <span id="payment_date">{{ \Carbon\Carbon::parse($payment->payment_date)->format('jS F, Y h:i A') }}</span>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="payment_date">Created At</label><br>
                                                <span id="payment_date">{{ \Carbon\Carbon::parse($payment->created_at)->format('jS F, Y h:i A') }}</span>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="payment_value">Amount</label><br>
                                                <span id="payment_value">{{ $payment->payment_value }}</span>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="payment_remarks">Remarks</label><br>
                                                <span id="payment_remarks">{{ $payment->remarks }}</span>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <img id="receipt_image_path" src="{{ asset('storage/payments/'.$payment->receipt_path) }}" />
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Verification Section -->
                                        <div class="row mt-3 w-full col-md-12 rounded-lg shadow-lg p-6">
                                            <div class="form-group col-md-6 col-12">
                                                <label for="status">Status</label><br>
                                                @if ($payment->status == 1)
                                                    <svg class="text-success-500 h-6 w-6 text-success" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                @elseif ($payment->status == 0)
                                                    <svg class="text-danger h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                @else
                                                <svg class="text-warning h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 2h12M6 2l6 6 6-6M6 2v6m12 0V2m0 18H6m0 0v-6m12 6v-6M6 22l6-6 6 6"></path>
                                                </svg>

                                                @endif
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="verification_remarks">Remarks</label><br>
                                                <span>{{ $payment->verification_remarks }}
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="verified_by_id">Remarks</label><br>
                                                <span>{{ $payment->verified_by->name }}
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="verified_at">Remarks</label><br>
                                                <span>{{ \Carbon\Carbon::parse($payment->verified_at)->format('jS F, Y h:i A') }}
                                            </div>

                                            <div class="form-group col-md-6">
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <img id="verification_receipt_image_path" src="{{ asset('storage/payments/verification-receipts/'.$payment->verification_receipt_path) }}" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                          </div>
                    </div>

                  </div>


            </div>


        </div>
    </div>

</x-app-layout>
