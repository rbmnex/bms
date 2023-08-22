<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fontawesome -->
    <link type="text/css" href="{{asset('vendor/@fortawesome/fontawesome-free/css/all.min.css')}}" rel="stylesheet">


    <link type="text/css" href="{{asset('vendor/notyf/notyf.min.css')}}" rel="stylesheet">


    <!-- Volt CSS -->
    <link type="text/css" href="{{asset('css/volt.css')}}" rel="stylesheet">

    @yield('head')
    <style>
        .form-file-label {
            border: 0.5px solid #144997;
        }

        input[type=text] {
            border: 1.5px solid #144997;
        }

        input[type=search] {
            border: 1.5px solid #144997;
        }

        input[type=number] {
            border: 1.5px solid #144997;
        }

        input[type=file] {
            border: 0.5px solid #144997;
        }

        input[type=checkbox] {
            border: 1.5px solid #144997;
        }

        input[type=email] {
            border: 1.5px solid #144997;
        }

        input[type=radio] {
            border: 1.5px solid #144997;
        }

        select {
            border: 1.5px solid #144997 !important;
        }

        textarea {
            border: 1.5px solid #144997 !important;
        }

        .form-control-plaintext {
            border: 1.5px solid #144997 !important;
        }

        .input-group-text {
            border: 1.5px solid #144997;
        }

        .datepicker-input {
            border: 1.5px solid #144997;
        }

        .form-file-input {
            border: 0.5px solid #144997;
        }

        .accordion .card {
            margin-bottom: 0rem;
        }

        input[data-readonly] {
            pointer-events: none;
        }

        .custom-file-label {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .select-info {
            padding-left: 35px;
        }
        #indicator {
         position: absolute;
         top: 0;
         bottom: 0;
         left: 0;
         right: 0;
         margin: auto;
         border: 10px solid grey;
         border-radius: 50%;
         border-top: 10px solid red;
         width: 100px;
         height: 100px;
         animation: spin 1s linear infinite;
      }
      @keyframes spin {
         0% {
            -webkit-transform: rotate(0deg);
            transform: rotate(0deg);
         }
         100% {
            -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
         }
      }
    </style>
</head>

<body>
    <nav class="navbar navbar-dark navbar-theme-primary px-4 col-12 d-md-none">
        <a class="navbar-brand mr-lg-5" href="#">
            <img class="navbar-brand-dark" src="{{asset('images/jkr_logo.png')}}" alt="JKR logo" /> <img class="navbar-brand-light" alt="Volt logo" />
        </a>
        <div class="d-flex align-items-center">
            <button class="navbar-toggler d-md-none collapsed" type="button" data-toggle="collapse" data-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>
    <div class="container-fluid bg-soft">
        <div class="row">
            <div class="col-12">
                @include('common.sidebar')
                <main class="content" style="height:100%;">
                    @include('common.header')

                    @yield('content')


                    <div class="modal fade" id="modal-msg" tabindex="-1" role="dialog" aria-labelledby="modal-msg" aria-hidden="false">
                        <div class="modal-dialog modal-dialog-centered modal-s" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h2 class="h6 modal-title">Message</h2>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                @if (session('message'))
                                    <span>{{ session('message') }}</span>
                                @endif
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-link text-danger ml-auto" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script src="{{asset('js/jquery-3.7.0.min.js')}}" ></script>
                    <script src="{{asset('vendor/popper.js/dist/umd/popper.min.js')}}"></script>
                    <script src="{{asset('vendor/bootstrap/dist/js/bootstrap.min.js')}}"></script>
                    <script src="{{asset('vendor/smooth-scroll/dist/smooth-scroll.polyfills.min.js')}}"></script>
                    <script src="{{asset('js/volt.js')}}"></script>
                    <!-- Vendor JS -->
                    <script src="{{asset('vendor/onscreen/dist/on-screen.umd.min.js')}}"></script>
                    <script>
                        $('body').append('<div style = "" id = "indicator"> <div class="loader"> </div> </div>');
                        $(window).on('load', function () {
                            setTimeout(removeLoader, 2000);
                        });
                        function removeLoader() {
                            $("#indicator").fadeOut(1000, function () {
                                $("#indicator").remove();
                            });
                        }
                    </script>
                    @yield('js')

                    @include('common.footer')
                </main>
            </div>
        </div>
    </div>
</body>

</html>
