@extends('layouts.app')

@section('content')
<div class="row justify-content-center form-bg-image" data-background-lg="">
    <div class="col-12 d-flex align-items-center justify-content-center">
        <div class="signin-inner my-3 my-lg-0 bg-white shadow-soft border rounded border-light p-4 p-lg-4 w-100 fmxw-800">
            <div class="text-center text-md-center mb-4 mt-md-0">
                <h1 class="mb-0 h3">Create an account</h1>
            </div>
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="row">
                    <div class="col">
                        <div class="form-group mb-1">
                            <label for="name">Your Name</label>
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon2"><span class="fas fa-user"></span></span>
                                <input type="text" name="name" style="text-transform: uppercase" class="form-control @error('name') is-invalid @enderror" placeholder="your name" id="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                            </div>
                            <div class="invalid-feedback" style="display:block;">@error('name') {{$message}} @enderror</div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group mb-1">
                            <label for="name">Your IC No.</label>
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon2"><span class="fas fa-address-card"></span></span>
                                <input type="text" name="ic_no" class="form-control @error('ic_no') is-invalid @enderror" placeholder="991122023333" id="ic_no" value="{{ old('ic_no') }}" required autocomplete="ic_no" autofocus>
                            </div>
                            @error('ic_no')<div class="invalid-feedback" style="display:block;"> {{$message}} </div>@enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="form-group mb-1">
                            <label for="email">Your Email</label>
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon3"><span class="fas fa-envelope"></span></span>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="example@company.com" id="email" value="{{ old('email') }}" required autocomplete="ic_no" autofocus>
                            </div>
                            <div class="invalid-feedback" style="display:block;">@error('email') {{$message}} @enderror</div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="row mb-1">
                            <label for="gender">Your Gender</label>
                            <div class="col-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" value="Male" name="gender" id="defaultCheck10">
                                    <label class="form-check-label" for="defaultCheck10">
                                        <span class="fas fa-male fa-2x"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" value="Female" name="gender" id="defaultCheck11">
                                    <label class="form-check-label" for="defaultCheck10">
                                        <span class="fas fa-female fa-2x"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="invalid-feedback" style="display:block;">@error('gender') {{$message}} @enderror</div>
                        </div>
                    </div>
                </div>
                <!-- Form -->

                <!-- End of Form -->


                @php
                $states = Lookup::loadLookup('bms.public.state');
                @endphp
                <div class="row">
                    <div class="col">
                        <div class="form-group mb-1">
                            <label for="state">State</label>
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon3"><span class="fas fa-flag"></span></span>
                                <select onchange="loadOffice();" class="form-select @error('state') is-invalid @enderror" name="state" id="sltState" aria-label="" required>
                                    <option value="" selected>Please select</option>
                                    @isset($states)
                                    @foreach($states as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                    @endisset
                                </select>
                            </div>
                            <div class="invalid-feedback" style="display:block;">@error('state') {{$message}} @enderror</div>
                        </div>
                    </div>
                    <!--
                    <div class="col">
                        <label for="district">District</label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon3"><span class="fas fa-building"></span></span>
                            <select class="form-select @error('district') is-invalid @enderror" disabled name="district" id="districtSlt" aria-label="" required>
                                <option value="" selected>Please select</option>
                            </select>
                        </div>
                        <div class="invalid-feedback" style="display:block;">@error('district') {{$message}} @enderror</div>
                    </div>
                    -->
                    <div class="col">
                        <div class="form-group">
                            <label for="office">Office <span style="color : red;">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon3"><span class="fas fa-building"></span></span>
                                <select class="form-select @error('office') is-invalid @enderror" disabled name="office" id="sltOffice" aria-label="" required>
                                    <option value="" selected>Please select</option>
                                </select>
                            </div>
                            <div class="invalid-feedback" style="display:block;">@error('office') {{$message}} @enderror</div>
                        </div>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col">
                        <div class="form-group mb-1">
                            <label for="password">Your Password</label>
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon5"><span class="fas fa-unlock-alt"></span></span>
                                <input type="password" placeholder="Password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required autocomplete="new-password">
                            </div>
                            <div class="invalid-feedback" style="display:block;">@error('password') {{$message}} @enderror</div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group mb-1">
                            <label for="confirm_password">Confirm Password</label>
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon5"><span class="fas fa-unlock-alt"></span></span>
                                <input type="password" placeholder="Confirm Password" class="form-control" id="confirm_password" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>
                    </div>
                </div>


                <!--
                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" value="" id="terms" required>
                        <label class="form-check-label" for="terms">
                            I agree to the <a href="#">terms and conditions</a>
                        </label>
                    </div>
                    -->
                <button type="submit" class="btn btn-block btn-primary">Sign in</button>
            </form>
            <!--
            <div class="mt-3 mb-4 text-center">
                <span class="font-weight-normal">or</span>
            </div>
            <div class="btn-wrapper my-4 text-center">
                <button class="btn btn-icon-only btn-pill btn-outline-light text-facebook mr-2" type="button" aria-label="facebook button" title="facebook button">
                    <span aria-hidden="true" class="fab fa-facebook-f"></span>
                </button>
                <button class="btn btn-icon-only btn-pill btn-outline-light text-twitter mr-2" type="button" aria-label="twitter button" title="twitter button">
                    <span aria-hidden="true" class="fab fa-twitter"></span>
                </button>
                <button class="btn btn-icon-only btn-pill btn-outline-light text-facebook" type="button" aria-label="github button" title="github button">
                    <span aria-hidden="true" class="fab fa-github"></span>
                </button>
            </div>
            -->
            <div class="d-flex justify-content-center align-items-center mt-4">
                <span class="font-weight-normal">
                    Already have an account?
                    <a href="./login" class="font-weight-bold">Login here</a>
                </span>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

@section('js')
<script type="text/javascript">
    function loadOffice() {
        var input = $('#sltState').val();
        if (input) {
            /*
                $.get("{{route('lookup.district')}}", { "val": input }, function(data) {
                    $('#districtSlt').empty();

                    $('#districtSlt').append('<option value="" selected>Please select</option>');

                    if (Array.isArray(data) && data.length) {


                        data.forEach(element => {
                            $('#districtSlt').append('<option value="' + element.id + '">' + element.name + '</option>');
                        });

                    }
                });
                $('#districtSlt').prop('disabled', false);
            */
            $.get("{{route('lookup.office')}}", {
                "val": input
            }, function(data) {
                $('#sltOffice').empty();

                $('#sltOffice').append('<option value="" selected>Please select</option>');
                if (Array.isArray(data) && data.length) {


                    data.forEach(element => {
                        $('#sltOffice').append('<option value="' + element.id + '">' + element.name + '</option>');
                    });

                }
            });
            $('#sltOffice').prop('disabled', false);
        } else {
            /*
            $('#districtSlt').empty();
            $('#districtSlt').append('<option value="" selected>Please select</option>');
            $('#districtSlt').prop('disabled', true);
            */
            $('#sltOffice').empty();
            $('#sltOffice').append('<option value="" selected>Please select</option>');
            $('#sltOffice').prop('disabled', true);
        }
    }
</script>
@endsection