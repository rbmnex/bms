<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link type="text/css" href="{{asset('css/volt.css')}}" rel="stylesheet">
</head>

<body>
    @yield('content')
</body>

</html>