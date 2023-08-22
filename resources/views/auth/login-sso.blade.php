@extends('layouts.app')

@section('content')

<div class="row justify-content-center form-bg-image" data-background-lg="">
    <div class="col-12 d-flex align-items-center justify-content-center">
        <div class="signin-inner my-3 my-lg-0 bg-white shadow-soft border rounded border-light p-4 p-lg-5 w-100 fmxw-500">
            <div class="text-center text-md-center mb-4 mt-md-0">
                <h1 class="mb-0 h3">Bridge Management System</h1>
            </div>
            <form action="{{ route('login') }}" id="bmsSsoFrm" method="POST" class="mt-4">
            @csrf
                <!-- Form -->
                <div class="form-group mb-4">
                    <label for="ic">Your Identification Number</label>
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon1"><span class="fas fa-address-card"></span></span>
                        <input type="text" class="form-control @error('ic_no') is-invalid @enderror" placeholder="991122023333" id="ic_no" value="{{ old('ic_no') }}" name="ic_no" required autocomplete="off" autofocus>
                    </div>
                    <div class="invalid-feedback" style="display:block;">
                        @error('ic_no') {{ $message }} @enderror
                    </div>
                </div>
                <!-- End of Form -->
                <div class="form-group" style="display:none">
                    <!-- Form -->
                    <div class="form-group mb-4">
                        <label for="password">Your Password</label>
                        <div class="input-group" >
                            <span class="input-group-text" id="basic-addon2"><span class="fas fa-unlock-alt"></span></span>
                            <input type="password" placeholder="Password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required autocomplete="off" value="password">
                            <input type="hidden" name="sso" value="YES">
                        </div>
                        <div class="invalid-feedback" style="display:block;">
                            @error('password') {{ $message }} @enderror
                        </div>
                    </div>
                    <!-- End of Form -->
                    <!--
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="defaultCheck5">
                                <label class="form-check-label" for="defaultCheck5">
                                    Remember me
                                </label>
                            </div>
                            <div><a href="#" class="small text-right">Lost password?</a></div>
                        </div>
                    -->
                </div>
                <button type="submit" class="btn btn-block btn-primary">Login</button>
            </form>
            <div class="d-flex justify-content-center align-items-center mt-4">
                <span class="font-weight-normal">
                    Not registered?
                    <a href="./register" class="font-weight-bold">Create account</a>
                </span>
            </div>
        </div>
    </div>
</div>

@endsection
