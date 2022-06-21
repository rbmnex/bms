@extends('layouts.main')

@section('head')

@endsection

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="card border-light shadow-sm components-section">
            <div class="card-header border-bottom border-light">
                <h3 class="h5 mb-0">Route Form</h3>
            </div>
            <div class="card-body">
                <form method="POST" id="routeForm" action="{{route('save.route')}}">
                    @csrf
                    <div class="form-group">
                        <label for="route_code" class="my-1 mr-2">Route No. <span style="color : red;">*</span></label>
                        <input type="text" name="route_code" class="form-control" id="routeCodeTxt" value="" required>
                        <div class="invalid-feedback" style="display:block;"></div>
                    </div>
                    <div class="form-group">
                        <label class="my-1 mr-2" for="route_type">Type <span style="color : red;">*</span></label>
                        <select class="form-select" name="route_type" id="routeTypeSlt" aria-label="Default select example" required>
                            <option value="" selected>Please select</option>
                            @isset($lookup)
                            @foreach($lookup as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach
                            @endisset
                        </select>
                        <div class="invalid-feedback" style="display:block;"></div>
                    </div>
                    <div class="form-group">
                        <label for="route_name" class="my-1 mr-2">Name <span style="color : red;">*</span></label>
                        <input type="text" name="route_name" oninput="this.value = this.value.toUpperCase()" class="form-control" id="routeNameTxt" value="" required>
                        <div class="invalid-feedback" style="display:block;"></div>
                    </div>
                    <input type="hidden" id="routeIdHdn" name="routeid" value="">
                    <div class="pt-2 px">

                        <button class="btn btn-primary" type="submit"><span class="far fa-paper-plane mr-2"></span>Submit</button>
                        <a class="btn btn-primary" href="{{route('route.list')}}"><span class="fas fa-backward mr-2"></span>Back</a>

                    </div>
            </div>
            </form>
        </div>
    </div>
</div>
</div>
@endsection

@section('js')
<script type="text/javascript">
    $(function() {
        @isset($route)
        $('#routeIdHdn').val("{{ $route->id }}");
        $('#routeCodeTxt').val("{{ $route->code }}");
        $('#routeTypeSlt').val("{{ $route->type->id }}").attr("selected", "selected");
        $('#routeNameTxt').val("{{ $route->name }}");
        $('#routeForm').attr("action", "{{ route('update.route') }}")
        @endisset

        $('#menu-registration').removeClass("collapsed");
        $('#menu-registration').attr("aria-expanded", "true");
        $('#submenu-app-registration').addClass("show");
        $('#routeList').addClass("active");
    });
</script>
@endsection