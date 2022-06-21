@extends('layouts.main')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="card border-light shadow-sm components-section">
            <div class="card-header border-bottom border-light">
                <h3 class="h5 mb-0">Lookup Form</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{route('save.lookup')}}" id="lookupForm">
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
                                <label for="category" class="my-1 mr-2">Category <span style="color : red;">*</span></label>
                                <select class="form-select" name="category" id="categorySlt" aria-label="Category" required>
                                <option value="" selected>Please select</option>
                                @isset($categories)
                                @foreach($categories as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                                @endisset
                                </select>
                                <div class="invalid-feedback" style="display:block;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col pt-4">
                            <div class="form-group">
                                <input class="form-check-input" name="enabled" type="checkbox" value="1" id="enabledChk">
                                <label for="enabled" class="my-1 mr-2 ml">Enabled?</label>
                                <div class="invalid-feedback" style="display:block;"></div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label class="my-1 mr-2" for="description">Description</label>
                                <textarea class="form-control" name="description" placeholder="" id="decriptionTxt" rows="4"></textarea>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="id" id="idHdn">
                    <button class="btn btn-primary" type="submit"><span class="far fa-paper-plane mr-2"></span>Submit</button>
                    <a class="btn btn-primary" href="{{route('lookup.list')}}"><span class="fas fa-backward mr-2"></span>Back</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
    $(function() {
        $('#menu-management').removeClass("collapsed");
        $('#menu-management').attr("aria-expanded", "true");
        $('#submenu-app-management').addClass("show");
        $('#lookupMgnt').addClass("active");

        @isset($lookup)
        $('#lookupForm').attr("action","{{route('update.lookup')}}");
        $('#idHdn').val("{{$lookup->id}}");
        $('#nameTxt').val("{{$lookup->name}}");
        $('#categorySlt').val("{{$lookup->category_id}}").attr("selected","selected");
        @if($lookup->enabled == '1')
        $('#enabledChk').attr("checked","checked");
        @endif
        $('#decriptionTxt').val("{{$lookup->description}}");
        @endisset
    });
    
</script>
@endsection