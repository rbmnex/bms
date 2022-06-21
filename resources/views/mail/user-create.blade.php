@extends('layouts.mail')

@section('content')
<div class="card">
    <div class="card-body">
        Dear {{$name}},<br/><br/>

        Your account created in BMS with password {{$password}}.<br/>
        Please login to <a href="http://10.8.68.42/">BMS</a> for your change password.
    </div>
</div>
@endsection