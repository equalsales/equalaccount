@extends('layout')
@section('content')

<link rel="stylesheet" href="{{ asset('content/plugins/sweetalert2/sweetalert2.min.css') }}">
<script src="{{ url('content/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ url('content/equal/getlist.js') }}"></script>
<script src="{{ url('content/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ url('content/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ url('content/plugins/jquery-validation/additional-methods.min.js') }}"></script>
<script src="{{ url('content/plugins/select2/js/select2.full.min.js') }}"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="card card-info">
    <div class="card-header">

        @if($data->id > 0)
        <h3 class="card-title"><strong>Journal Book Entry [{{ $data->username }}] [{{ date('d-m-Y', strtotime($data->created_at))}}] </strong>
            <strong style="margin-left:950px;">EDIT MODE</strong>
        </h3>
        @else
        <h3 class="card-title"><strong>Journal Book Entry</strong><strong style="margin-left:1150px;">ADD MODE</strong></h3>
        @endif
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <!-- <form class="form-horizontal" method="post" action="/addHsncode" id='hsnform' enctype="multipart/form-data"> -->

    <form class="form-horizontal" method="post" action="/addjv" id='jvform'>
        @csrf
        <div class="modal fade" id="modal-xl">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Select Sales Bill From List</h4>
                        <input type="hidden" type='number' id="RowIndex2" name="RowIndex2" value="0">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table" id="os_datatable">
                            <thead>
                                <tr>
                                    <th>Action</th>
                                    <th>#</th>
                                    <th>Serial</th>
                                    <th>SrChr</th>
                                    <th>Date</th>
                                    <th>NetAmt</th>
                                    <th>Balance</th>
                                    <th>BillId</th>
                                    <th>Code</th>
                                    <th>BillNo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Dynamic Body -->
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-info" onclick='GetSaleBillOsInfo(event);'>Okay</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->


        <input type="hidden" class="form-control" id="id" placeholder="id" name="id" value='{{ $data->id }}'>
        <input type="hidden" class="form-control" id="showid" placeholder="showid" name="showid" value='{{ $show }}'>
        <div class="card-body">

            <div class="form-group row">
                <label for="inputEmail3" class="col-sm-1 col-form-label">Serial</label>
                <div class="col-sm-1">
                    <input type="text" class="form-control" id="srchr" name="srchr" value='{{ $data->srchr }}'>
                </div>
                <div class="col-sm-3">
                    <input type="number" class="form-control" id="serial" name=" serial" value='{{ $data->serial }}' decimal='0' onblur='serialvld(this);' onfocus='getmaxserial(this);' style="text-align:right;">
                </div>
                @error('serial')
                <div>{{ $message }}</div>
                @enderror
                <label for="inputEmail3" class="col-sm-1 col-form-label">Date</label>
                <div class="col-sm-2">
                    <input type="date" class="form-control" id="date" name="date" value='{{ $data->date }}'>
                </div>
            </div>
            <div class="form-group row">
                <label for="debitac" class="col-sm-1 col-form-label">Debit A/C</label>
                <div class="col-sm-3">
                    <select class="form-control select2" name="drid" id="drid">
                        @if ($data->id > 0)
                        <option value='{{ $data->drid }}'>{{ $data->drparty }}</option>
                        @else
                        <option value='0'>--- Select Debit A/C ---</option>
                        @endif
                    </select>
                </div>
                <div class="col-sm-2">
                    <label for="drballbl" class="col-sm col-form-label" id='drbal' style="color: blue"></label>
                </div>
                <label for="creditac" class="col-sm-1 col-form-label">Credit A/C</label>
                <div class="col-sm-3">
                    <select class="form-control select2" name="crid" id="crid">
                        @if ($data->id > 0)
                        <option value='{{ $data->crid }}'>{{ $data->crparty }}</option>
                        @else
                        <option value='0'>--- Select Credit A/C---</option>
                        @endif
                    </select>
                </div>
                <div class="col-sm-2">
                    <label for="crballbl" class="col-sm col-form-label" id='crbal' style="color: blue"></label>
                </div>
            </div>
            <div class="form-group row">
                <label for="inputEmail3" class="col-sm-1 col-form-label">Net Amt</label>
                <div class="col-sm-4">
                    <input type="number" class="form-control" id="netamt" style="text-align:right ;" name="netamt" value='{{ $data->netamt }}' decimal='2' onblur='TextBoxVld(this);' style="text-align:right;">
                </div>
                <label for="inputEmail3" class="col-sm-1 col-form-label">Remarks</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="remarks" style="text-align:left ;" name="remarks" value='{{ $data->remarks }}'>
                </div>
            </div>

            <div class="form-group row">
                <label for="inputEmail3" class="col-sm-1 col-form-label">Adj Mode</label>
                <div class="col-sm-4">
                    <select id="adjmode" name="adjmode" class="form-control select2">
                        <option value="">--Select Adj Mode--</option>
                        <option value="CREDIT">CREDIT</option>
                        <option value="DEBIT">DEBIT</option>
                        @if ($data->mode == '')

                        @else
                        <option value="{{ $data->mode }}" selected>{{ $data->mode }}</option>
                        @endif
                    </select>
                </div>
                <label for="inputEmail3" class="col-sm-1 col-form-label">Type</label>
                <div class="col-sm-4">
                    <select id="vchrtype" name="vchrtype" class="form-control select2" onchange="VChrTypeChange(this);">
                        <option value="">--Select Type--</option>
                        <option value="AGAINST BILL">AGAINST BILL</option>
                        <option value="ADVANCE">ADVANCE</option>
                        @if ($data->vchrtype == '')
                        {{-- <option value="">--Select CASH/BANK--</option>
                                --}}
                        @else
                        <option value="{{ $data->vchrtype }}" selected>{{ $data->vchrtype }}</option>
                        @endif
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label for="inputEmail3" class="col-sm-1 col-form-label">Ref(%)</label>
                <div class="col-sm-4">
                    <input type="number" class="form-control" id="refper" style="text-align:right ;" name="refper" value='{{ $data->refper }}' decimal='2' onblur='TextBoxVld(this);' style="text-align:right;">
                </div>
                <label for="inputEmail3" class="col-sm-1 col-form-label">Ref Amt</label>
                <div class="col-sm-4">
                    <input type="number" class="form-control" id="refamt" style="text-align:right ;" name="refamt" value='{{ $data->refamt }}' decimal='2' onblur='TextBoxVld(this);' style="text-align:right;">
                </div>
            </div>
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="billdetailstab" data-toggle="pill" href="#custom-content-below-home" role="tab" aria-controls="custom-content-below-home" aria-selected="true">
                                Bill Details</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="extratab" data-toggle="pill" href="#custom-content-below-profile" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Extra</a>
                        </li>
                    </ul>

                    <div class="tab-content" id="custom-content-below-tabContent">
                        <div class="tab-pane fade show active" id="custom-content-below-home" role="tabpanel" aria-labelledby="billdetailstab">
                            <div style="overflow-x:auto;">
                                <table class="table" id="jvdet_table">
                                    <thead>
                                        <tr>
                                            <th>Action</th>
                                            <th>#</th>
                                            <th>Module</th>
                                            <th>Serial</th>
                                            <th>Bill No</th>
                                            <th>Bill Date</th>
                                            <th>Bill Amount</th>
                                            <th>Balance</th>
                                            <th>AdjustAmt</th>
                                            <th>BillId</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="custom-content-below-profile" role="tabpanel" aria-labelledby="extratab">
                            Extras
                        </div>
                    </div>

                </div>
            </div>



            <!-- /.card-body -->
            <div class="row">
                <div class="col-6">
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">Trn Type</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="remarks2" name="remarks2" value='{{ $data->remarks2 }}'>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="button" name="submit" class="btn btn-info" onclick='Click_Save(event);'>Save</button>
                        <a class="btn btn-default btn-close" href="{{ route('jvmst.jvlist') }}">Cancel</a>
                    </div>
                </div>
                <div class="col-6">
                    <div class="table-responsive">
                        <table class="table" id='gridtotal'>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- /.card-footer -->
    </form>
</div>



<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function _formload() {
        setinputprop();
        //For Required Field Validation
        $('#jvform').validate({
            rules: {
                serial: {
                    required: true
                , }
                , terms: {
                    required: true
                }
            , }
            , messages: {
                serial: {
                    required: "Please enter a name of Serial"
                    , username: "Please enter a vaild Serial"
                }
                , terms: "Please accept our terms"
            }
            , errorElement: 'span'
            , errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            }
            , highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            }
            , unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });

        if ($('#id').val() > 0) {
            //EDIT MODE
            var data = JSON.parse('{!!  json_encode($datadet) !!}');
            if (data.length > 0) {
                var ictr;
                for (ictr = 0; ictr < data.length; ictr++) {
                    insertRow(ictr);
                    var option;

                    $('#module' + ictr).val(data[ictr]['module']);
                    $('#billserial' + ictr).val(data[ictr]['billserial']);
                    $('#billno' + ictr).val(data[ictr]['billno']);
                    let xDate1 = data[ictr]['billdate']
                    var dDate = new Date(xDate1);
                    dDate = dDate.formatDate(dDate, "yyyy-MM-dd");

                    $('#billdate' + ictr).val(dDate);
                    //$('#billdate' + ictr).val(data[ictr]['billdate']);
                    $('#billamt' + ictr).val(data[ictr]['billamt']);
                    $('#adjustamt' + ictr).val(data[ictr]['adjustamt']);
                    $('#billid' + ictr).val(data[ictr]['billid']);
                    $('#id' + ictr).val(data[ictr]['id']);
                }
            } else {
                //ADD MODE
                insertRow(0);
            }
        } else {
            //ADD MODE
            insertRow(0);
        }



        let xDate = '{{ $data->date }}';
        var dDate = new Date(xDate);
        dDate = dDate.formatDate(dDate, "yyyy-MM-dd");
        $('#date').val(dDate);


        if ($('#id').val() == 0) {
            //For Add Mode
            $('#date').val(GetNowDate());
        }

        /*$('#crid').change(function() {
            if ($('#crid').val() == '-1') {
                var r = confirm("Do you want to add new ??!!");
                if (r == true) {
                    window.open("/addparty?online=Y&cid=crid", "_blank");
                }
            }
        });*/
        crpartyvld('crid');
        setcrpartyvld('crid');
        partywhn('#crid', '');
        /*$('#drid').change(function() {
            if ($('#drid').val() == '-1') {
                var r = confirm("Do you want to add new ??!!");
                if (r == true) {
                    window.open("/addparty?online=Y&cid=drid", "_blank");
                }
            }
        });*/
        drpartyvld('drid');
        setdrpartyvld('drid');
        partywhn('#drid', '');

        DisplayGridTotal();
        $('#srchr').focus();
        checkcompanylock($('#date').val(), true);
        if ($('#showid').val() == 1) {
            $('#jvform').prop("disabled", true);

            $("#jvform :input").prop("disabled", true);
            $("#save").prop("disabled", true);
        }

    }

    function insertRow(index) {
        var cRow = "";
        cRow = "<tr>" +
            "<td><a href='#' onclick= 'DeleteGridRow(this)' class='btn'><i class='fa fa-trash'></i></a></td>" +
            "<td>" + (index + 1) + "</td>" +
            "<td><select class='form-control select2' style='Width:140px' id='module" + index + "' >" +
            "<option value='SALE'>SALE</option>" +
            "<option value='SALE_RET'>SALE RETURN</option>" +
            "<option value='PURC'>PURCHASE</option>" +
            "<option value='GREYPURC'>GREY PURCHASE</option>" +
            "<option value='MILLGP'>MILL G.P.</option>" +
            "<option value='JOBBILL'>JOBBILL</option>" +
            "<option value='GENPUR'>GENERAL PURCHASE</option>" +
            "<option value='PURC_RET'>PURCHASE RETURN</option>" +
            "<option value='GENPUR_RET'>GENERAL PURCHASE RETURN</option>" +
            "</select>" +
            "</td>" +
            "<td><input type='number' name='billserial[]' id='billserial" + index +
            "' style='Width:80px;text-align:right' class='form-control' value='0' ' placeholder='Select Bill' onfocus='ShowSaleBillOsList(this," +
            index + ");' /></td>" +
            "<td><input type='text' name='billno[]' id='billno" + index +
            "' style='Width:80px; text-align:right' class='form-control'  /></td>" +
            "<td><input type='date' name='billdate[]' id='billdate" + index +
            "' style='Width:170px;text-align:right'  class='form-control'  /></td>" +
            "<td><input type='number' name='billamt[]' id='billamt" + index +
            "' style='Width:90px;text-align:right' class='form-control' value='0' /></td>" +
            "<td><input type='number' name='balance[]' id='balance" + index +
            "' style='Width:90px; text-align:right' class='form-control' value='0' /></td>" +
            "<td><input type='number' name='adjustamt[]' id='adjustamt" + index +
            "' style='Width:90px; text-align:right' class='form-control' value='0' onblur='AdjustAmtVld(event,this," +
            index +
            ");'/></td>" +
            "<td><input type='number' name='billid[]' id='billid" + index +
            "' style='text-align:right' class='form-control' value='0' disabled/></td>" +
            "<td><input type='number' name='id[]' id='id" + index +
            "' style='text-align:right' class='form-control' value='0' disabled/></td>" +
            "</tr>";
        $("#jvdet_table").append(cRow);
    }

    function DeleteGridRow(obj, index) {
        var r = confirm("Do you want to delete ???");
        if (r == true) {
            var nRowIndex = obj.parentNode.parentNode.rowIndex;
            var nTotRow = (document.getElementById("jvdet_table").rows.length) - 1;
            document.getElementById('jvdet_table').deleteRow(nRowIndex);
            if (nRowIndex == nTotRow) {
                insertRow(nTotRow - 1);
            }
            DisplayGridTotal();
        } else {
            return false;
        }
    }

    function mod_drpartyvld(data) {
        document.getElementById("drbal").innerHTML = '';
        document.getElementById("drbal").innerHTML = 'Bal : ' + data[0]["balance"];
    }

    function mod_crpartyvld(data) {
        document.getElementById("crbal").innerHTML = '';
        document.getElementById("crbal").innerHTML = 'Bal : ' + data[0]["balance"];
    }


    function Click_Save(event) {
        event.preventDefault();

        if (!findatevld($("#date").val())) {
            return;
        }
        if (!checkcompanylock($('#date').val(), false)) {
            return;
        }

        let cno = "{{ Session::get('companyid') }}";
        if (parseFloat(cno) == 0) {
            swal.fire({
                icon: 'error'
                , title: 'Need To Re-Login In EqualL...'
                , text: 'Session Expired'
            });
            return;
        }

        if ($("#serial").val() == 0) {
            swal.fire({
                icon: 'error'
                , title: 'Oops...'
                , text: 'Serial Can Not Be Empty'
            });
            $('#serial').focus();
            return;
        }

        if ($("#netamt").val() == 0) {
            swal.fire({
                icon: 'error'
                , title: 'Oops...'
                , text: 'Net Amount Can Not Be Zero'
            });
            $('#netamt').focus();
            return;
        }


        //        let nTotRow = (document.getElementById("jvdet_table").rows.length) - 1;

        let modcode = "JV";

        let id = $("#id").val();
        let serial = $("#serial").val();
        let srchr = $("#srchr").val();
        let date = $("#date").val();
        let drid = $("#drid").val();
        let crid = $("#crid").val();
        let refper = $("#refper").val();
        let refamt = $("#refamt").val();
        let mode = $("#adjmode").val();
        let vchrtype = $("#vchrtype").val();
        let netamt = $("#netamt").val();
        let remarks = $("#remarks").val();
        let remarks2 = $("#remarks2").val();
        let actmode = '';

        let _token = $('meta[name="csrf-token"]').attr('content');
        let jvdet = [];
        if (parseFloat(id) > 0) {
            actmode = 'EDIT';
        } else {
            actmode = 'ADD';
        }
        let srl = 0;

        let nTotRow = (document.getElementById("jvdet_table").rows.length) - 1;

        var table = document.getElementById('jvdet_table');

        for (iCtr = 0; iCtr < nTotRow; iCtr++) {
            var nRowIndex = (table.rows.item(iCtr + 1).cells[1].innerText) - 1;
            if (parseFloat($('#adjustamt' + nRowIndex).val()) != 0) {

                if ($('#billid' + nRowIndex).val() == 0) {
                    swal.fire({
                        icon: 'error'
                        , title: 'Oops...'
                        , text: 'Without Bill Entry Can not Save..!!'
                    });
                    $('#module' + nRowIndex).focus();
                    return;
                }

                jvdet.push({
                    srl: srl + 1,

                    module: $('#module' + nRowIndex).val()
                    , billserial: $('#billserial' + nRowIndex).val()
                    , billno: $('#billno' + nRowIndex).val()
                    , billdate: $('#billdate' + nRowIndex).val()
                    , billamt: $('#billamt' + nRowIndex).val()
                    , balance: $('#balance' + nRowIndex).val()
                    , adjustamt: $('#adjustamt' + nRowIndex).val()
                    , billid: $('#billid' + nRowIndex).val()
                    , id: $('#id' + nRowIndex).val()
                });
            }
        }

        $.ajax({
            url: "/addjvmst"
            , type: "POST"
            , async: false
            , data: {
                id: id
                , srl: srl
                , modcode: modcode
                , serial: serial
                , srchr: srchr
                , date: date
                , drid: drid
                , crid: crid
                , mode: mode
                , refper: refper
                , refamt: refamt
                , vchrtype: vchrtype
                , netamt: netamt
                , remarks: remarks
                , remarks2: remarks2
                , _token: _token
                , jvdet: jvdet
            }
            , success: function(response) {
                console.log(response);
                if (response) {
                    if (response.success) {
                        $('#id').val(response.id);
                        //window.location.href = "{{ route('jvmst.jvlist') }}";
                    } else {
                        $('#serial').addClass('is-invalid');
                        $('#srchr').addClass('is-invalid');
                        $('#serial').closest('.form-group').append(response.error);
                    }
                } else {
                    alert('error');
                }
            }
            , error: function(request) {
                alert(request.responseText);
            }
        }).done(function(response) {
            //check if response has errors object
            if (response.errors) {
                // do what you want with errors, 
            }
        });;
        id = $('#id').val();
        id = parseFloat(id);

        if (id == 0) {
            swal.fire("There is some problem in JV", "Kindly Check!", "Error")
            return;
        }

        var ntotal = jvdet.length;
        var lretval = true;
        let jvdet_insert = [];
        let lsave = false;
        for (x = 0; x < ntotal; x++) {
            lsave = false;
            if (((x + 1) % 20) == 0) {
                lsave = true;
                lretval = savejvdet(id, jvdet_insert, _token, false);
                jvdet_insert = [];
            }
            jvdet_insert.push(jvdet[x]);
        }

        lretval = savejvdet(id, jvdet_insert, _token, true);

        if (lretval) {
            if (actmode == 'ADD') {
                Swal.fire({
                    title: "Journal [ " + $('#serial').val() + " ] Saved" + 'Continue with new entry ???'
                    , text: "Start new entry!"
                    , icon: 'warning'
                    , showCancelButton: true
                    , confirmButtonColor: '#3085d6'
                    , cancelButtonColor: '#d33'
                    , confirmButtonText: 'Yes, Continue it!'
                }).then((result) => {
                    if (result.value) {


                        curi = "{{ url('addjv') }}";
                        window.location.href = curi;
                        //_formload();

                    } else {

                        swal.fire("Journal [ " + $('#serial').val() + " ] Saved", "You clicked the Saved!", "success")
                        window.location.href = "{{ route('jvmst.jvlist') }}";
                    }
                })
            } else {
                swal.fire("Journal [ " + $('#serial').val() + " ] Saved", "You clicked the Saved!", "success")
                window.location.href = "{{ route('jvmst.jvlist') }}";
            }
            /*swal.fire("Journal [ " + $('#serial').val() + " ] Saved", "You clicked the Saved!", "success")
            window.location.href = "{{ route('jvmst.jvlist') }}";*/


        }
    }

    function AdjustAmtVld(event, obj, nRowIndex) {

        var nTotRow = document.getElementById("jvdet_table").rows.length;
        var nAdjustAmt = $('#adjustamt' + nRowIndex).val();

        var actRowIndex = (obj.parentElement.closest('tr').rowIndex) - 1;
        if (((actRowIndex + 1) == (nTotRow - 1)) && (parseFloat(nAdjustAmt) != 0)) {
            insertRow(parseFloat(nRowIndex) + 1);
            var cId = '#module' + parseFloat(nRowIndex + 1);
            setFocus($(cId));

        }
        DisplayGridTotal();
    }

    function RemarksVld() {
        var cId = '#module0';
        //$(cId).select2('focus');
        //$(cId).select2('open');
        setFocus($(cId));
    }

    function DisplayGridTotal() {
        var nTotRow = (document.getElementById("jvdet_table").rows.length) - 1;
        var table = document.getElementById('jvdet_table');
        var iCtr;
        var nBillAmt, nAdjustAmt;
        nBillAmt = 0;
        nAdjustAmt = 0;
        for (iCtr = 0; iCtr < nTotRow; iCtr++) {
            var nRowIndex = (table.rows.item(iCtr + 1).cells[1].innerText) - 1;
            nBillAmt = parseFloat(nBillAmt) + parseFloat($('#billamt' + nRowIndex).val());
            nAdjustAmt = parseFloat(nAdjustAmt) + parseFloat($('#adjustamt' + nRowIndex).val());
        }
        var cTotal = "";
        var ctaxcap = 'Un-AdjustAmt'
        nNetAmt = parseFloat($('#netamt').val());
        nUnAdjustAmt = nNetAmt - nAdjustAmt;
        // if (parseFloat(nAdjustAmt) > 0) {
        //     ctaxcap = 'AdjustAmt';
        // }
        cTotal =
            "<tr>" +
            "<th style='width:50%'>AdjustAmt:</th>" +
            "<td>" + (parseFloat(nAdjustAmt)) + "</td>" +
            "</tr>" +
            "<tr>" +
            "<th>  " + ctaxcap + "  </th>" +
            "<td>" + (parseFloat(nUnAdjustAmt)) + "</td>" +
            "</tr>" +
            "<tr>" +
            "<th>NetAmt:</th>" +
            "<td><b>" + Math.round(nNetAmt) + "</b></td>" +
            "</tr>";
        $("#gridtotal").html('');
        $("#gridtotal").append(cTotal);

    }

    function VChrTypeChange(obj) {
        VChrType = $('#vchrtype').val();

        if (VChrType == "ADVANCE") {
            setFocus($('#submit'));
        } else {
            //setFocus($('#netamt'));
            setFocus($('#refper'));
        }
        return true;
    }

    function CalcAdjustAmt(nRowIndex) {

    }

    function serialvld() {
        return true;

        let id = $("#id").val();
        let serial = $("input[name=serial]").val();
        let _token = $('meta[name="csrf-token"]').attr('content');
        let saleorderdet = [];

        if (orderno == 0) {
            return true;
        }
        $.ajax({
            url: "/checkjvserial"
            , type: "POST"
            , async: false
            , data: {
                serial: $('#serial').val()
                , _token: _token
                , saleorderdet: saleorderdet
            }
            , success: function(response) {
                console.log(response);
                if (response) {
                    if (response.exist) {
                        $('#serial').addClass('is-invalid');
                        $('#serial').closest('.form-group').append(
                            'Please enter a valid serial</span>');
                        setFocus($('#serial'));
                        return false;
                    }
                }
            }
        , });
    }

    function checkserial() {
        if ($('#serial').val() == 0) {
            $('#serial').focus();
        }
    }

    function getmaxserial() {
        let id = $("#id").val();
        if (parseFloat(id) > 0) {
            return;
        }
        let serial = $("input[name=serial]").val();
        let _token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: "/getjvserial"
            , type: "POST"
            , async: false
            , data: {
                serial: $('#serial').val()
                , _token: _token
            }
            , success: function(response) {
                console.log(response);
                if (response) {
                    if (response.success) {
                        $('#serial').val(response.serial);
                        return false;
                    }
                }
            }
        , });
    }

    function ShowSaleBillOsList(obj, RowIndex) {
        let billserial = $('#billserial' + RowIndex).val();
        let module = $('#module' + RowIndex).val();
        let curl = "/getpurchasebillos";
        //GET MODULE
        if (module == 'SALE') {
            curl = "/getsalebillos";
        } else if (module == 'SALE_RET') {
            curl = "/getpurchasebillos";
        } else if (module == 'RECEIPT') {
            curl = "/getpurchasebillos";
        } else if (module == 'PAYMENT') {
            curl = "/getpurchasebillos";
        } else if (module == 'PURC_RET') {
            curl = "/getsalebillos";
        } else if (module == 'GENPUR_RET') {
            curl = "/getsalebillos";
        } else {
            curl = "/getpurchasebillos";
        }
        if (billserial == 0) {
            let partyid = $("#crid").val();
            if ($("#adjmode").val() == 'CREDIT') {
                partyid = $("#crid").val();
            } else {
                partyid = $("#drid").val();
            }

            let osdet = [];
            let _token = $('meta[name="csrf-token"]').attr('content');


            $.ajax({
                url: curl
                , type: "POST"
                , async: false
                , data: {
                    partyid: partyid
                    , module: module
                    , _token: _token
                }
                , success: function(response) {
                    console.log(response);
                    if (response) {
                        if (response.success) {
                            osdet = response.data;
                            return false;
                        }
                    }
                }
                , error: function(request) {
                    alert(request.responseText);
                }
            });

            var tableHeaderRowCount = 1;
            var table = document.getElementById('os_datatable');
            var rowCount = table.rows.length;
            for (var i = tableHeaderRowCount; i < rowCount; i++) {
                table.deleteRow(tableHeaderRowCount);
            }

            $('#RowIndex2').val(RowIndex);
            var data = osdet
            var ictr;
            let srl = 0;
            if (data.length > 0) {
                for (ictr = 0; ictr < data.length; ictr++) {
                    var option;

                    var dDate = data[ictr]['date'];
                    srl = srl + 1;
                    cRow = "<tr>" +
                        "<td><a href='#' onclick= 'SaleBillOsSelect(this)' class='btn'>Select</a></td>" +
                        "<td>" + srl + "</td>" +
                        "<td>" + data[ictr]['serial'] + "</td>" +
                        "<td>" + data[ictr]['srchr'] + "</td>" +
                        "<td>" + dDate + "</td>" +
                        "<td>" + data[ictr]['netamt'] + "</td>" +
                        "<td>" + data[ictr]['balamt'] + "</td>" +
                        "<td>" + data[ictr]['code'] + "</td>" +
                        "<td>" + data[ictr]['billid'] + "</td>" +
                        "<td>" + data[ictr]['billno'] + "</td>" +
                        "</tr>";
                    $("#os_datatable").append(cRow);

                }
                //comment end
                if ($.fn.DataTable.isDataTable("#os_datatable")) {
                    //$("#os_datatable").dataTable().fnReloadAjax

                } else {
                    $('#os_datatable').DataTable({
                        "scrollX": true
                        , "scrollY": "400px"
                        , "paging": false
                        , "lengthChange": false
                        , "searching": true
                        , "ordering": true
                        , "info": true
                        , "autoWidth": false
                        , "responsive": true
                    , });
                }
                $('#modal-xl').modal('show');
            }
        } else {

        }

    }

    function SaleBillOsSelect(obj, BillId, RowIndex) {
        let ActRowIndex = obj.parentNode.parentNode.rowIndex;
        var nTotCol = $("#os_datatable").find('tr')[ActRowIndex].cells.length; //10
        var a;

        if (obj.innerHTML == "Selected") {
            obj.innerHTML = "Select";
            $("#os_datatable").find('tr')[ActRowIndex].style.backgroundColor = 'white';
        } else {
            obj.innerHTML = "Selected";
            $("#os_datatable").find('tr')[ActRowIndex].style.backgroundColor = 'yellow';
        }
    }

    function insertbilldet(osdet) {
        var cRow = "";
        let index = $('#RowIndex2').val();
        let nTotRow = (document.getElementById("os_datatable").rows.length);
        for (iCtr = 1; iCtr < nTotRow; iCtr++) {
            nCtr += 1;
            if (nCtr > 1) {
                index += 1;
                //insertRow(index);
            }

            cRow = "<tr>" +
                "<td><a href='#' onclick= 'DeleteGridRow(this)' class='btn'><i class='fa fa-trash'></i></a></td>" +
                "<td>" + (index + 1) + "</td>" +
                "</td>" +
                "<td><input type='number' name='serial[]' id='serial" + index +
                "' style='text-align:right' class='form-control' value='0'/></td>" +
                "<td><input type='text' name='srchr[]' id='srchr" + index +
                "' style='text-align:right' class='form-control' value=''/></td>" +
                "<td><input type='date' name='date[]' id='date" + index +
                " style='Width:170px;text-align:left'  class='form-control'  /></td>" +
                "<td><input type='number' name='netamt[]' id='netamt" + index +
                "' style='text-align:right' class='form-control' value='0' /></td>" +
                "<td><input type='number' name='balance[]' id='balance" + index +
                "' style='text-align:right' class='form-control' value='0' /></td>" +
                "<td><input type='text' name='code[]' id='code" + index +
                "' style='text-align:right' class='form-control' value=''/></td>" +
                "<td><input type='number' name='code[]' id='code" + index +
                "' style='text-align:right' class='form-control' value='0' disabled/></td>" +
                "</tr>";

        }
        $("#os_datatable").append(cRow);
    }



    function GetSaleBillOsInfo(event) {
        event.preventDefault();
        //alert('1');
        /*  let index = $('#RowIndex2').val();


          let nTotRow = (document.getElementById("os_datatable").rows.length);
          let Serial, SrChr, Date, NetAmt, BalAmt, BillId;

          Serial = 0;
          Date = "";
          SrChr = "";
          NetAmt = 0;
          BalAmt = 0;
          BillId = 0;

          var nCtr = 0;
          var nAmount = $('#netamt').val();

          for (iCtr = 1; iCtr < nTotRow; iCtr++) {
              var cell = document.getElementById("os_datatable").rows.item(iCtr).cells[0];
              if (cell.innerText == "Selected") {
                  nCtr += 1;
                  if (nCtr > 1) {
                      index += 1;
                      insertRow(index);
                  }

                  Serial = document.getElementById("os_datatable").rows.item(iCtr).cells[1].innerText;
                  SrChr = document.getElementById("os_datatable").rows.item(iCtr).cells[2].innerText;
                  Date = document.getElementById("os_datatable").rows.item(iCtr).cells[3].innerText;
                  NetAmt = document.getElementById("os_datatable").rows.item(iCtr).cells[4].innerText;
                  BalAmt = document.getElementById("os_datatable").rows.item(iCtr).cells[5].innerText;
                  BillId = document.getElementById("os_datatable").rows.item(iCtr).cells[6].innerText;

                  $('#billserial' + index).val(Serial + " " + SrChr);
                  $('#billdate' + index).val(Date);
                  $('#billamt' + index).val(NetAmt);
                  $('#balance' + index).val(BalAmt);
                  $('#billid' + index).val(BillId);
                  $('#adjustamt' + index).val(Math.min(nAmount, BalAmt));

                  nAmount = nAmount - $('#adjustamt' + index).val();
              }
          }

          DisplayGridTotal();

          $('#modal-xl').modal('hide');

          //setTimeout(function () { $('#DiscRate'+index).focus(); }, 500);

          setFocus($('#discrate' + index));*/
        let index = $('#RowIndex2').val();
        index = parseFloat(index);
        let module = $('#module' + index).val();
        let nTotRow = (document.getElementById("os_datatable").rows.length);
        let Serial, SrChr, BillDate, NetAmt, BalAmt, BillId, Code, BillNo;

        Serial = 0;
        BillDate = "";
        SrChr = "";
        NetAmt = 0;
        BalAmt = 0;
        BillId = 0;
        Code = "";

        var nCtr = 0;
        var nAmount = $('#netamt').val();

        for (iCtr = 1; iCtr < nTotRow; iCtr++) {
            var cell = document.getElementById("os_datatable").rows.item(iCtr).cells[0];
            if (cell.innerText == "Selected") {
                nCtr += 1;
                if (nCtr > 1) {
                    index += 1;
                    insertRow(index);
                }

                Serial = document.getElementById("os_datatable").rows.item(iCtr).cells[2].innerText;
                SrChr = document.getElementById("os_datatable").rows.item(iCtr).cells[3].innerText;
                BillDate = document.getElementById("os_datatable").rows.item(iCtr).cells[4].innerText;
                NetAmt = document.getElementById("os_datatable").rows.item(iCtr).cells[5].innerText;
                BalAmt = document.getElementById("os_datatable").rows.item(iCtr).cells[6].innerText;
                Code = document.getElementById("os_datatable").rows.item(iCtr).cells[7].innerText;
                BillId = document.getElementById("os_datatable").rows.item(iCtr).cells[8].innerText;
                BillNo = document.getElementById("os_datatable").rows.item(iCtr).cells[9].innerText;

                //let xDate1 = data[ictr]['date'];
                //var dDate = new Date(xDate1);
                //dDate = dDate.formatDate(dDate, "yyyy-MM-dd");
                //dDate = dDate.formatDate(dDate, "dd-MM-yyyy");
                let xDate1 = document.getElementById("os_datatable").rows.item(iCtr).cells[4].innerText;
                var dDate = new Date(xDate1);
                dDate = dDate.formatDate(dDate, "yyyy-MM-dd");
                //dDate = dDate.formatDate(dDate, "dd-MM-yyyy");
                if (SrChr == '') {
                    $('#billserial' + index).val(Serial);
                } else {
                    $('#billserial' + index).val(Serial + " " + SrChr);
                }


                $('#billdate' + index).val(dDate);
                $('#billamt' + index).val(NetAmt);
                $('#balance' + index).val(BalAmt);
                $('#billid' + index).val(BillId);
                $('#billno' + index).val(BillNo);
                $('#adjustamt' + index).val(Math.min(nAmount, BalAmt));
                $('#module' + index).val(module);

                nAmount = nAmount - $('#adjustamt' + index).val();
            }
        }
        DisplayGridTotal();

        $('#modal-xl').modal('hide');

        //setTimeout(function () { $('#DiscRate'+index).focus(); }, 500);

        //setFocus($('#discrate' + index));
    }

    function savejvdet(id, jvdet_insert, _token, last) {
        let lstatus = false;
        $.ajax({
            url: "/addjvdet"
            , type: "POST"
            , async: false
            , data: {
                id: id
                , jvdet: jvdet_insert
                , last: last
                , _token: _token
            }
            , success: function(response) {
                console.log(response);
                lstatus = response.success;
            }
            , error: function(request) {
                alert(request.responseText);
            }
        });
        return lstatus;
    }

</script>
@endsection
