@extends('layouts.mail')

@section('content')
<div class="card">
    <div class="card-body">
        Dear {{$user->name}},<br/><br/>

        There task waiting for your action on {{$bridge->structure_no}} {{$bridge->name}}<br/>
        Please login to <a href="http://10.8.68.42/">BMS</a> for more info.
    </div>
</div>
@endsection