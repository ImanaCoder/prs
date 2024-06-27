<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'PRS') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.css" />
		<!-- Font Awesome -->
		<link rel="stylesheet" href="{{asset('admin-assets/plugins/fontawesome-free/css/all.min.css')}}">
		<!-- Theme style -->
		<link rel="stylesheet" href="{{asset('admin-assets/css/adminlte.min.css')}}">
        <!-- Dropzone -->
        <link rel="stylesheet" href="{{asset('admin-assets/plugins/dropzone/min/dropzone.min.css')}}">
        <link rel="stylesheet" href="{{ asset('admin-assets/plugins/summernote/summernote-bs4.min.css') }}">

		<link rel="stylesheet" href="{{asset('admin-assets/css/custom.css')}}">
        <link rel="stylesheet" href="{{ asset('admin-assets/css/datetimepicker.css') }}">
        <style>
                /* Custom CSS for reducing padding in container-fluid */

            .container-fluid {
                margin-right: 0px; /* Adjust as needed */
                margin-left: 0px;  /* Adjust as needed */
            }
            /* Optional: Override other Bootstrap styles */
            @media (min-width: 1200px) {
                .container {
                    max-width: 100%; /* Ensures full width on large screens */
                }
            }
            .btn{
                font-size:12px;
            }
            h4,h5{
                font-size:20px !important;
                font-weight:700 !important;
            }

            .row{
                margin-right:0px;
                margin-left:0px;
            }

            input,select,textarea{
                font-size:12px !important;
            }

            .blue-text {
            color: blue;
            text-decoration: none;
        }
        .blue-text:hover {
            color: darkblue;
        }

        .status-circle {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: inline-block;
            text-align: center;
            line-height: 30px;
            color: white;
        }
        .status-red {
            background-color: red;
        }
        .status-green {
            background-color: green;
        }
        .status-yellow {
            background-color: yellow;
            color: black;
        }



        </style>


        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')


            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-8xl mx-auto px-4 sm:px-3 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main style="font-size:12px;">

                {{ $slot }}
            </main>
        </div>
        <!-- jQuery -->
		{{-- <script src="{{asset('admin-assets/plugins/jquery/jquery.min.js')}}"></script> --}}
        <!-- Include jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- Include Moment.js -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
        <!-- Include Date Range Picker -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.min.js"></script>

		<!-- Bootstrap 4 -->
		<script src="{{asset('admin-assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
		<!-- AdminLTE App -->
		<script src="{{asset('admin-assets/js/adminlte.min.js')}}"></script>
        <script src="{{ asset('admin-assets/plugins/summernote/summernote-bs4.min.js') }}"></script>

		<!-- AdminLTE for demo purposes -->
		{{-- <script src="{{asset('admin-assets/js/demo.js')}}"></script> --}}
        <!-- Dropzone -->
        <script src="{{asset('admin-assets/plugins/dropzone/min/dropzone.min.js')}}"></script>
        <script src="{{ asset('admin-assets/js/datetimepicker.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


        <script type="text/javascript">


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(document).ready(function(){
                $(".summernote").summernote({
                    height:250
                });
            });


        </script>

        @yield('customJs')
    </body>
</html>
