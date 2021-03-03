@extends('layout')
@section('contentcss')
<link rel="stylesheet" href="{{ asset('content/plugins/sweetalert2/sweetalert2.min.css') }}">
@endsection
@section('contentjs')
<script src="{{ url('content/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ url('content/equal/jquery.tableTotal.js') }}"></script>
<script src="{{ url('content/equal/html2pdf.js') }}"></script>
@endsection
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    @media all {
        .page-break {
            display: none;
        }
    }

    @media print {
        .page-break {
            display: block;
            page-break-before: always;
        }
    }

</style>
@php
$srl= 0;
@endphp
@foreach ($dataall as $data)

<div id="invoice">

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    {{-- <div class="callout callout-info">
                            <h5><i class="fas fa-info"></i> Note:</h5>
                            This page has been enhanced for printing. Click the print button at the bottom of the
                            invoice to test.
                        </div> --}}


                    <!-- Main content -->
                    <div class="invoice p-3 mb-3">
                        <!-- title row -->
                        <div class="row">
                            <div class="col-12">
                                <h1>
                                    <center><i class="fas fa-globe"></i> <strong>{{ $companymst->company }}</strong>
                                    </center>
                                </h1>
                                <h5>
                                    <strong>
                                        <center>{{ $companymst->addr1 }},
                                            {{ $companymst->addr2 }},{{ $companymst->addr3 }}
                                        </center>
                                        <center>{{ $companymst->city }},
                                            {{ $companymst->state }},{{ $companymst->country }}
                                        </center>
                                        <center>Phone No : {{ $companymst->phoneno }} Mobile No :
                                            {{ $companymst->mobileno }}
                                        </center>
                                        <center>Email : {{ $companymst->email }} GSTIN : {{ $companymst->gstregno }}
                                        </center>
                                        <hr style="border: 1px solid black;" />
                                        <center>
                                            JOURNAL VOUCHER
                                        </center>

                                        <hr style="border: 1px solid black;" />
                                    </strong>
                                </h5>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- info row -->
                        <div class="row invoice-info">
                            <div class="col-sm-6 invoice-col">
                                <address>
                                    DEBIT ACCOUNT : <strong style="font-size:17px">{{ $data->drac }}</strong><br>
                                    {{-- {{ $data->addr1 }}<br>
                                    {{ $data->addr2 }}<br>
                                    Phone: {{ $data->phoneno }} <br>Mobile: {{ $data->mobileno }}<br> --}}
                                    {{-- GstRegNo: <strong>{{ $data ->gstregno }}</strong><br> --}}
                                    CREDIT ACCOUNT : <strong style="font-size:15px"> {{ $data->crac }}</strong><br>
                                </address>
                            </div>
                            <!-- /.col -->

                            <div class="col-sm-6 invoice-col">
                                <address>
                                    VOUCHER : <strong> {{ $data->serial }} </strong><br>
                                    DATE : <strong> {{ $data->date }} </strong> <br>
                                    <strong style="font-size:20px">Amount : {{ $data->netamt }}</strong><br>
                                </address>
                            </div>

                        </div>
                        <div class="row invoice-info">
                            <div class="col-sm-12 invoice-col">
                                {{-- {{ $inw }} --}}
                                In Words: <strong>{{ convert_number_to_words($data->netamt) }}</strong><br>
                                Narration : <strong>{{ $data->remarks }}</strong>
                            </div>
                        </div>

                        <hr style="border: 1px solid black;" />
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-6 col-form-label">PREPARED BY</label>
                            <label for="inputEmail3" class="col-sm-4 col-form-label">FOR AJMERA FASHION </label>
                        </div>
                        <!-- Print Button-->
                    </div>
                    <!-- /.invoice -->
                </div><!-- /.col -->

            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
@if($srl < ($length-1)) <div class='page-break'>
    </div>
    @endif
    @php
    $srl+= 1;
    @endphp

    @endforeach
    <!-- this row will not appear when printing -->
    <div class="row no-print">
        <div class="col-12">
            <a href="#" target="_blank" class="btn btn-default" onclick=' printme(this,event);'><i class="fas fa-print"></i>
                Print</a>

            <button type="button" class="btn btn-primary float-right" style="margin-right: 5px;" onclick='printpdf(this,event);'>
                <i class="fas fa-download"></i> Generate PDF
            </button>
        </div>
    </div>
    @endsection
    <script src="{{ url('content/plugins/jquery/jquery.min.js') }}"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function _formload() {

        }

        function printme(obj, event) {
            event.preventDefault();

            //window.print();

            var originalTitle = document.title;
            document.title = "";
            window.print();
            document.title = originalTitle;
        }

        function printpdf(obj, event) {
            alert('m here');
            event.preventDefault();

            const element = document.getElementById("invoice");
            // Choose the element and save the PDF for our user.
            html2pdf().from(element).save();
        }

    </script>
