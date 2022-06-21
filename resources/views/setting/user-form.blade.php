@extends('layouts.main')

@section('head')

@endsection

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="card border-light shadow-sm components-section">
            <div class="card-header border-bottom border-light">
                <h3 class="h5 mb-0">User Form</h3>
            </div>
            <div class="card-body">
                <form method="POST" id="userForm" action="{{route('save.user')}}">
                    @csrf
                    <div class="row">
                        <div class="col">
                            <div class="form-group mb-1">
                                <label for="name">Name <span style="color : red;">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon2"><span class="fas fa-user"></span></span>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" style="text-transform: uppercase" placeholder="your name" id="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                </div>
                                <div class="invalid-feedback" style="display:block;">@error('name') {{$message}} @enderror</div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group mb-1">
                                <label for="ic_no">IC No. <span style="color : red;">*</span></label>
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
                                <label for="email">Email <span style="color : red;">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon3"><span class="fas fa-envelope"></span></span>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="example@company.com" id="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                </div>
                                <div class="invalid-feedback" style="display:block;">@error('email') {{$message}} @enderror</div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group mb-1">
                                <label for="phone">Phone</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon3"><span class="fas fa-phone"></span></span>
                                    <input type="number" class="form-control @error('number') is-invalid @enderror" name="phone" placeholder="0101110101" id="phone" value="{{ old('phone') }}" required autocomplete="phone" autofocus>
                                </div>
                                <div class="invalid-feedback" style="display:block;">@error('phone') {{$message}} @enderror</div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-1">
                        <label for="gender">Gender <span style="color : red;">*</span></label>
                        <div class="col-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" value="Male" name="gender" id="genderMale">
                                <label class="form-check-label" for="gender">
                                    <span class="fas fa-male fa-2x"></span>
                                </label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" value="Female" name="gender" id="genderFemale">
                                <label class="form-check-label" for="gender">
                                    <span class="fas fa-female fa-2x"></span>
                                </label>
                            </div>
                        </div>
                        <div class="invalid-feedback" style="display:block;">@error('gender') {{$message}} @enderror</div>

                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group mb-1">
                                <label for="position">Position</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon3"><span class="fas fa-user-tie"></span></span>
                                    <select class="form-select @error('position') is-invalid @enderror" name="position" id="sltPosition" aria-label="">
                                        <option value="" selected>Please select</option>
                                        @isset($positions)
                                        @foreach($positions as $item)
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                        @endisset
                                    </select>
                                </div>

                                <div class="invalid-feedback" style="display:block;">@error('position') {{$message}} @enderror</div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group mb-1">
                                <label for="department">Department</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon3"><span class="fas fa-sitemap"></span></span>
                                    <select class="form-select @error('department') is-invalid @enderror" name="department" id="sltDeprt" aria-label="">
                                        <option value="" selected>Please select</option>
                                        @isset($departments)
                                        @foreach($departments as $item)
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                        @endisset
                                    </select>
                                </div>
                                <div class="invalid-feedback" style="display:block;">@error('department') {{$message}} @enderror</div>
                            </div>
                        </div>
                    </div>
                    <div class="row controlRow">
                        <div class="col">
                            <div class="form-group mb-1">
                                <label for="state">State <span style="color : red;">*</span></label>
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
                            <label for="district">District <span style="color : red;">*</span></label>
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
                            <div class="form-group mb-1">
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

                    <div class="row my-2 controlRow" id="controlRow">
                        <div class="col">
                            <div class="form-group">
                                <label for="role">Role(s)</label>
                                @isset($roles)
                                @foreach($roles as $item)
                                <div class="form-check">
                                    <input class="form-check-input" name="role" type="checkbox" value="{{ $item->id }}" id="roleChk-{{ $item->id }}">
                                    <label class="form-check-label" for="defaultCheck10">
                                        {{ $item->display_name }}
                                    </label>
                                </div>
                                @endforeach
                                @endisset
                                <input type="hidden" name="rolelist" id="rolelistid">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="enabled"></label>
                                <div class="form-check">
                                    <input class="form-check-input" name="enabled" type="checkbox" value="1" id="enabledChk">
                                    <label class="form-check-label" for="enabled">Enabled?</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="show" id="showHdn">
                    <input type="hidden" name="user_id" id="userIdTxt">
                    <button class="btn btn-primary" type="submit"><span class="far fa-paper-plane mr-2"></span>Submit</button>
                    <a class="btn btn-primary" id="backBtn" href="{{route('user.list')}}"><span class="fas fa-backward mr-2"></span>Back</a>
                    <button id="deleteBtn" class="btn btn-primary" name="deleteButton" type="button" onclick="deleteUser();">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')

<script type="text/javascript">
    function submit() {
        roleArray = [];
        $("input:checkbox[name=role]:checked").each(function() {
            roleArray.push(parseInt($(this).val()));
        });
        $('#rolelistid').val(JSON.stringify(roleArray));

        //    console.log(roleArray);

        //   $('#userForm').submit();
    }

    function deleteUser() {
        $('#userForm').attr("action","{{route('delete.user')}}");
        $('#userForm').submit();
    }

    function loadOffice() {
        var input = $('#sltState').val();
        if (input) {
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
        } else {
            $('#sltOffice').empty();
            $('#sltOffice').append('<option value="" selected>Please select</option>');
            $('#sltOffice').prop('disabled', true);
            /*
            $('#districtSlt').empty();
            $('#districtSlt').append('<option value="" selected>Please select</option>');
            $('#districtSlt').prop('disabled', true);
            */
        }
    }

    $(function() {
        @if (session('message'))  
        $('#modal-msg').modal('show');
        @endif

        $('#userForm').submit(function(event) {

            event.preventDefault(); //this will prevent the default submit
            var _this = $(this); //store form so it can be accessed later

            roleArray = [];
            $("input:checkbox[name=role]:checked").each(function() {
                roleArray.push(parseInt($(this).val()));
            });
            $('#rolelistid').val(JSON.stringify(roleArray));

            _this.unbind('submit').submit(); // continue the submit unbind preventDefault
        });


        @isset($show)
        $('#showHdn').val("{{$show}}");
        @if($show)
        // $('#controlRow').show();
        $('#userForm').attr("action", "{{route('update.user')}}");
        $('#menu-management').removeClass("collapsed");
        $('#menu-management').attr("aria-expanded", "true");
        $('#submenu-app-management').addClass("show");
        $('#userMgnt').addClass("active");
        @else
        $('#userForm').attr("action", "{{route('modify.user')}}");
        $('.controlRow').hide();
        $('#backBtn').hide();
        $('#deleteBtn').hide();
        @endif
        @endisset
        @isset($user)
        $('#userIdTxt').val("{{$user->id}}");
        $('#name').val("{{$user->name}}");
        $('#ic_no').val("{{$user->ic_no}}");
        $('#email').val("{{$user->email}}");
        $('#phone').val("{{$user->telephone_no}}");
        @if($user->gender == 'Male')
        $('#genderMale').attr("checked", "checked");
        @else
        $('#genderFemale').attr("checked", "checked");
        @endif
        $('#sltPosition').val("{{$user->position_id}}").attr("selected", "selected");
        $('#sltDeprt').val("{{$user->department_id}}").attr("selected", "selected");
        @isset($user->office)
        
        $('#sltState').val("{{$user->office->state_id}}").attr("selected", "selected");
        loadOffice();
        setTimeout(function() {
            $('#sltOffice').val("{{$user->office->id}}").attr("selected", "selected");
        //    $('#districtSlt').val("{{$user->district_id}}").attr("selected", "selected");
        }, 1000);
        
        @endisset
        /*
        @isset($user->state_id)
        $('#sltState').val("{{$user->state_id}}");
        loadOffice();
        setTimeout(function() {
            $('#sltOffice').val("{{$user->office_id}}").attr("selected", "selected");
        //    $('#districtSlt').val("{{$user->district_id}}").attr("selected", "selected");
        }, 1000);
        @endisset
        */
        @if($user->enabled == '1')
        $('#enabledChk').attr("checked", "checked");
        @endif
        @foreach($user->roles as $role)
        $('#roleChk-{{$role->id}}').attr("checked", "checked");
        @endforeach
        @endisset

    });
</script>
<script src="{{asset('js/user.js')}}"></script>
@endsection