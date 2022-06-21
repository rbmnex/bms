@extends('layouts.main')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="card border-light shadow-sm components-section">
            <div class="card-header border-bottom border-light">
                <h3 class="h5 mb-0">Category Form</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('save.category') }}" id="categoryForm">
                    @csrf
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="name" class="my-1 mr-2">Name <span style="color : red;">*</span></label>
                                <input type="text" name="name" class="form-control" id="nameTxt" value="" required>
                                <div class="invalid-feedback" style="display:block;"></div>
                            </div>
                        </div>
                        <div class="col pt-4">
                            <div class="form-group">
                                <input class="form-check-input" name="enabled" type="checkbox" value="1" id="enabledChk">
                                <label for="enabled" class="my-1 mr-2 ml">Enabled?</label>
                                <div class="invalid-feedback" style="display:block;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="my-1 mr-2" for="description">Description</label>
                        <textarea class="form-control" name="description" placeholder="" id="decriptionTxt" rows="4"></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                    <input type="hidden" name="id" id="idHdn">
                    <button class="btn btn-primary" type="submit"><span class="far fa-paper-plane mr-2"></span>Submit</button>
                    <a class="btn btn-primary" href="{{route('category.list')}}"><span class="fas fa-backward mr-2"></span>Back</a>
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
        $('#categoryMgnt').addClass("active");

        @isset($category)
        $('#idHdn').val("{{$category->id}}")
        $('#nameTxt').val("{{$category->name}}");
        @if($category->enabled == '1')
        $('#enabledChk').attr("checked", "checked");
        @endif
        $('#decriptionTxt').val("{{$category->description}}");
        $('#categoryForm').attr("action","{{route('update.category')}}")
        @endisset
    });
</script>
@endsection