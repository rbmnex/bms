<!DOCTYPE html>
<html lang="en">
<head>

     <title>BMS</title>
<!--
Hydro Template
http://www.templatemo.com/tm-509-hydro
-->
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=Edge">
     <meta name="description" content="">
     <meta name="keywords" content="">
     <meta name="author" content="">
     <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

     <link rel="stylesheet" href="{{ asset('landingtemplate/css/bootstrap.min.css')}}">
     <link rel="stylesheet" href="{{ asset('landingtemplate/css/magnific-popup.css')}}">
     <link rel="stylesheet" href="{{ asset('landingtemplate/css/font-awesome.min.css')}}">

     <!-- MAIN CSS -->
     <link rel="stylesheet" href="{{ asset('landingtemplate/css/templatemo-style.css') }}">
</head>
<body>

     <!-- PRE LOADER -->
     <section class="preloader">
          <div class="spinner">
               <span class="spinner-rotate"></span>
          </div>
     </section>


     <!-- MENU -->
     <section class="navbar custom-navbar navbar-fixed-top" role="navigation">
          <div class="container">

               <div class="navbar-header">
                    <button class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                         <span class="icon icon-bar"></span>
                         <span class="icon icon-bar"></span>
                         <span class="icon icon-bar"></span>
                    </button>

                    <!-- lOGO TEXT HERE -->
                    {{-- <a href="index.html" class="navbar-brand">EASPOSH</a> --}}
               </div>

               <!-- MENU LINKS -->


          </div>
     </section>


     <!-- HOME -->
     <section id="home" data-stellar-background-ratio="0.5">
          <div class="overlay"></div>
          <div class="container">
               <div class="row">

                    <div class="col-md-8 col-sm-12">
                         <div class="home-info">
                                <h1>
                                  <span style="color:orange;font-weight:bold">BRIDGE MANAGEMENT SYSTEM</span>
                                  <br>
                                  <span>RECORD INVENTORY SYSTEM FOR BRIDGE ASSET AND CONDITION  ON THE FEDERAL ROAD</span>
                                  <br>
                                  <br>
                                  <span style="color:orange;">(BMS)</span>
                                </h1>
                                <span><a href="{{url('/login-sso')}}" style="color:blue; border-color: blue;" class="btn section-btn smoothScroll">Log Masuk</a></span>
                                {{-- <span>
                                    Website
                                    <small><a href="https://spb.jkr.gov.my">https://spb.jkr.gov.my</a></small>
                                </span> --}}
                         </div>
                    </div>
               </div>
          </div>
     </section>

     <!-- SCRIPTS -->
     <script src="{{ asset('landingtemplate/js/jquery.js') }}"></script>
     <script src="{{ asset('landingtemplate/js/bootstrap.min.js') }}"></script>
     <script src="{{ asset('landingtemplate/js/jquery.stellar.min.js') }}"></script>
     <script src="{{ asset('landingtemplate/js/jquery.magnific-popup.min.js') }}"></script>
     <script src="{{ asset('landingtemplate/js/smoothscroll.js') }}"></script>
     <script src="{{ asset('landingtemplate/js/custom.js') }}"></script>

</body>
</html>
