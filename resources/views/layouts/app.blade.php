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

    <!-- Notyf -->
    <link type="text/css" href="{{asset('vendor/notyf/notyf.min.css')}}" rel="stylesheet">

    <!-- Volt CSS -->
    <link type="text/css" href="{{asset('css/volt.css')}}" rel="stylesheet">

    <script src="{{asset('vendor/popper.js/dist/umd/popper.min.js')}}"></script>
    <script src="{{asset('vendor/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('js/jquery-3.7.0.min.js')}}" ></script>
    <!-- Vendor JS -->
    <script src="{{asset('vendor/onscreen/dist/on-screen.umd.min.js')}}"></script>

    <!-- Slider -->
    <script src="{{asset('vendor/nouislider/distribute/nouislider.min.js')}}"></script>

    <!-- Jarallax -->
    <script src="{{asset('vendor/jarallax/dist/jarallax.min.js')}}"></script>

    <!-- Smooth scroll -->
    <script src="{{asset('vendor/smooth-scroll/dist/smooth-scroll.polyfills.min.js')}}"></script>

    <!-- Count up -->
    <script src="{{asset('vendor/countup.js/dist/countUp.umd.js')}}"></script>

    <!-- Notyf -->
    <script src="{{asset('vendor/notyf/notyf.min.js')}}"></script>

    <!-- Simplebar -->
    <script src="{{asset('vendor/simplebar/dist/simplebar.min.js')}}"></script>

    <!-- Github buttons -->
    <script src="{{asset('js/button.js')}}"></script>

    <!-- Volt JS -->
    <script src="{{asset('js/volt.js')}}"></script>
    <style>
        .bgimg-1,
        .bgimg-2,
        .bgimg-3 {
            position: relative;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;

        }

        .bgimg-1 {
            background-image: url("{{ asset('images/bridge.jpg') }}");
            height: 100%;
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

<body class="bg-soft">
    <main>
        <section class="vh-lg-100 d-flex align-items-center bgimg-1">
            <div class="container">
                @yield('content')
            </div>
        </section>
        @yield('js')
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
    </main>

</body>

</html>
