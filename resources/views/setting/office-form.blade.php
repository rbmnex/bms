@extends('layouts.main')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="card border-light shadow-sm components-section">
            <div class="card-header border-bottom border-light">
                <h3 class="h5 mb-0">Office Form</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('save.office') }}" id="officeForm">
                    @csrf
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="name" class="my-1 mr-2">Name <span style="color : red;">*</span></label>
                                <input type="text" name="name" class="form-control" id="nameTxt" value="" required>
                                <div class="invalid-feedback" style="display:block;"></div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="hq"></label>
                                <div class="form-check">
                                    <input class="form-check-input" name="hq" type="checkbox" value="1" id="hqChk">
                                    <label class="form-check-label" for="hq">HQ?</label>
                                </div>
                            </div>
                        </div>
                    </div>
                   <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label class="my-1 mr-2" for="state">State <span style="color : red;">*</span></label>
                                <select class="form-select" onchange="loadDistrict();" name="state" id="stateSlt" required>
                                    <option value="" selected>Please select</option>
                                    @isset($states)
                                    @foreach($states as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                    @endisset
                                </select>
                                <div class="invalid-feedback" style="display: block;"></div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group" id="districtDiv">
                                <label class="my-1 mr-2" for="district">District <span style="color : red;">*</span></label>
                                <select class="form-select" name="district" disabled id="districtSlt">
                                    <option value="" selected>Please select</option>
                                </select>
                                <div class="invalid-feedback" style="display: block;"></div>
                            </div>
                        </div>
                   </div>
                    
                    <input type="hidden" name="id" id="idHdn">
                    <button class="btn btn-primary" type="submit"><span class="far fa-paper-plane mr-2"></span>Submit</button>
                    <a href="{{route('office.list')}}" class="btn btn-primary"><span class="fas fa-backward mr-2"></span>Back</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
    function loadDistrict() {
        var input = $('#stateSlt').val();
        if (input) {
            $.get("{{route('lookup.district')}}", { "val": input }, function(data) {
                $('#districtSlt').empty();

                $('#districtSlt').append('<option value="" selected>Please select</option>');
            /*    $('#districtSlt').rules("add", 
                {
                    required: true,
                    messages: {
                        required: "District is required"
                    }
                });
            */ 
                if (Array.isArray(data) && data.length) {


                    data.forEach(element => {
                        $('#districtSlt').append('<option value="' + element.id + '">' + element.name + '</option>');
                    });

                }
            });
            $('#districtSlt').prop('disabled', false);
        } else {
            $('#districtSlt').empty();
            $('#districtSlt').append('<option value="" selected>Please select</option>');
            $('#districtSlt').prop('disabled', true);
        }

    }
    $(function() {
        $('#menu-management').removeClass("collapsed");
        $('#menu-management').attr("aria-expanded", "true");
        $('#submenu-app-management').addClass("show");
        $('#officeMgnt').addClass("active");

        @isset($office)
        $('#idHdn').val("{{$office->id}}")
        $('#nameTxt').val("{{$office->name}}");
        $('#stateSlt').val("{{$office->state_id}}").attr("selected","selected");
        loadDistrict();
        $('#officeForm').attr("action", "{{route('update.office')}}")
        @if($office->hq == "1")
        $('#hqChk').attr("checked","checked");
        $('#districtDiv').hide();
        @endif
        @if($office->district_id)
        setTimeout(function() {
            $('#districtSlt').val("{{$office->district_id}}").attr("selected", "selected");
        }, 1000);
        @endif
        @endisset

        $('#hqChk').click(function(){
            if($('#hqChk').prop('checked')) {
                $('#districtDiv').hide();
            } else {
                $('#districtDiv').show();
            }
        });
    });
</script>
@endsection