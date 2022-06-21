@extends('layouts.main')

@section('head')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.22/af-2.3.5/b-1.6.5/b-colvis-1.6.5/b-flash-1.6.5/b-html5-1.6.5/b-print-1.6.5/cr-1.5.3/fc-3.3.2/fh-3.1.7/kt-2.5.3/r-2.2.6/rg-1.1.2/rr-1.2.7/sc-2.0.3/sb-1.0.1/sp-1.2.2/sl-1.3.1/datatables.min.css" />
@endsection

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="card border-light shadow-sm components-section">
            <div class="card-header border-bottom border-light">
                <h3 class="h5 mb-0">Lookup Management</h3>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center ">
                    <a href="{{ route('lookup.form') }}" class="btn btn-primary btn-sm mr-2">
                        <span class="fas fa-plus mr-2"></span>Add Lookup
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-centered table-nowrap mb-0 rounded" id="tblListLookup">
                        <thead class="thead-light">
                            <tr>
                                <!--<th class="border-0">#</th> -->
                                <th class="border-0">Name</th>
                                <th class="border-0">Category</th>
                                <th class="border-0">Description</th>
                                <th class="border-0">Enabled?</th>
                                <th class="border-0">Action</th>
                            </tr>
                        <tbody id="tbodyR">
                        </tbody>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.22/af-2.3.5/b-1.6.5/b-colvis-1.6.5/b-flash-1.6.5/b-html5-1.6.5/b-print-1.6.5/cr-1.5.3/fc-3.3.2/fh-3.1.7/kt-2.5.3/r-2.2.6/rg-1.1.2/rr-1.2.7/sc-2.0.3/sb-1.0.1/sp-1.2.2/sl-1.3.1/datatables.min.js"></script>
<script type="text/javascript">
    $(function() {
        var table = $('#tblListLookup').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('load.lookup') }}",
            columns: [{
                    data: 'name',
                    name: 'name',
                    className: "border-0"
                },
                {
                    data: 'category',
                    name: 'category',
                    className: "border-0"

                },
                {
                    data: 'description',
                    name: 'description',
                    className: "border-0"

                },
                {
                    data: 'enabled',
                    name: 'enabled',
                    className: "border-0"

                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: "border-0"
                },
            ]
        });

        @if (session('message'))  
    $('#modal-msg').modal('show');
    @endif
    });

    $('#menu-management').removeClass("collapsed");
    $('#menu-management').attr("aria-expanded", "true");
    $('#submenu-app-management').addClass("show");
    $('#lookupMgnt').addClass("active");
</script>
@endsection