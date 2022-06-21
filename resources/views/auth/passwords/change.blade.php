@extends('layouts.main')

@section('head')
@endsection

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="card border-light shadow-sm components-section">
            <div class="card-header border-bottom border-light">
                <h3 class="h5 mb-0">Password Form</h3>
            </div>
            <div class="card-body">
                <form method="POST" id="passwordForm" action="{{route('change.password')}}">
                    @csrf
                    <div class="form-group mb-1">
                        <label for="password">Old Password</label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon5"><span class="fas fa-unlock-alt"></span></span>
                            <input type="password" placeholder="Password" class="form-control @error('oldpassword') is-invalid @enderror" id="oldpassword" name="oldpassword" required>
                        </div>
                        <div class="invalid-feedback" style="display:block;">@error('oldpassword') {{$message}} @enderror</div>
                    </div>
                    <div class="form-group mb-1">
                        <label for="password">New Password</label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon5"><span class="fas fa-unlock-alt"></span></span>
                            <input type="password" placeholder="Password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                        </div>
                        <div class="invalid-feedback" style="display:block;">@error('password') {{$message}} @enderror</div>
                    </div>
                    <div class="form-group mb-1">
                        <label for="confirm_password">Confirm Password</label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon5"><span class="fas fa-unlock-alt"></span></span>
                            <input type="password" placeholder="Confirm Password" class="form-control" id="confirm_password" name="password_confirmation" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-block btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script lang="text/javascript">
$(function(){
    @if (session('message'))  
    $('#modal-msg').modal('show');
    @endif
});
</script>
@endsection