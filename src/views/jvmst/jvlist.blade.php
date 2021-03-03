@extends('layout')
@section('contentcss')
<link rel="stylesheet" href="{{ asset('content/plugins/sweetalert2/sweetalert2.min.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@endsection
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="modal fade" id="modal-default" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Journal Printing</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label for="fromserial" class="col-sm-4 col-form-label">From Serial</label>
                    <div class="col-sm-4">
                        <input type="number" class="form-control" id="fromserial" placeholder="From Serial No" name="fromserial" value='0' decimal='0' style='text-align:right'>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="toserial" class="col-sm-4 col-form-label">To Serial</label>
                    <div class="col-sm-4">
                        <input type="number" class="form-control" id="toserial" placeholder="To Serial No" name="toserial" value='0' decimal='0' style='text-align:right'>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick='printjvmulti(event);'>Print</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">JournalBook Entry List</h3>
    </div>
    <div class="row">
        <div class="col-md-1">
            <button class="btn btn-block bg-gradient-primary" onclick='Click_Add(event);' col-md-2>Add</button>
        </div>
        <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#modal-default">
            Print JV
        </button>
        <div class="col-md-1">
            <i class="fa fa-share-square" style="font-size:32px;color:black;" data-toggle="modal" onclick='sendtodashboard(event);'></i>
        </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <table id="example1_jv" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Srchr</th>
                    <th>Serial</th>
                    <th>Date</th>
                    <th>Type</th>
                    <th>DrA/C</th>
                    <th>CrA/C</th>
                    <th>Id</th>
                    <th>Action</th>
                </tr>
            </thead>

        </table>
    </div>
    <!-- /.card-body -->
</div>
@endsection
<script src="{{ url('content/plugins/jquery/jquery.min.js') }}"></script>
<script type="text/javascript">
    function _formload() {
        $('#example1_jv').DataTable({
            stateSave: true
            , "processing": true
            , "serverSide": true
            , "ajax": "{{ route('jvmst.jvlist') }}"
            , "columnDefs": [
                {
                    "targets": [0,1,2,3,4,5,6,]
                    , "searchable": false
                }
                ,]
            , "columns": [{
                    "data": "srchr"
                }
                , {
                    "data": "serial"
                }
                , {
                    "data": "date"
                }
                , {
                    "data": "vchrtype"
                }
                , {
                    "data": "drac"
                }
                , {
                    "data": "crac"
                }
                , {
                    "data": "id"
                }
                , {
                    "data": "action"
                    , "name": "action"
                    , orderable: false
                }
            , ]
        });
        $(document).on("click", "#example1_jv td:nth-child(1)", function() {
            var table = $('#example1_jv').DataTable();
            var data = table.row(this).data();
            window.open("{{ url('viewjv') }}" + '/' + data['id']);
        });
    }



    function deletejv(id) {
        if (confirm('Are you sure you want to Delete This Journal Book Entry ??!!!')) {
            window.location.href = "{{ url('deletejv') }}" + '/' + id;
        }
    }

    function editjv(id) {
        window.location.href = "{{ url('editjv') }}" + '/' + id;
    }

    function printjv(id) {
        window.location.href = "{{ url('printjv') }}" + '/' + id;
    }

    function Click_Add(event) {
        window.location.href = "{{ url('addjv') }}";
    }

    function sendtodashboard() {
        let _token = $('meta[name="csrf-token"]').attr("content");
        let route = 'jvlist';
        let modulename = 'Journal Book';
        let type = 'Module';
        let srl = 34;
        createshortcut(route, modulename, type, srl, _token)
    }

    function printjvmulti(event) {
        event.preventDefault();
        let fromserial = parseFloat($('#fromserial').val());
        let toserial = parseFloat($('#toserial').val());

        if (toserial == 0 || fromserial == 0) {
            alert('Print Not Allowed...');
        } else {
            let mode = getQueryVariable("mode");
            let id = 0;
            url = '/printjv/ ' + id + '?fromserial=' + fromserial + '&toserial=' + toserial;
            window.open(url, "_blank");
            $('#modal-default').modal('hide');
        }
    }

</script>
