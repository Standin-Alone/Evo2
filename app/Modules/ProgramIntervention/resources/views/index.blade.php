@extends('global.base')
@section('title', "Program Intervention Management")




{{--  import in this section your css files--}}
@section('page-css')
    <link href="assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
    <link href="assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css" rel="stylesheet" />
    <link href="assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css" rel="stylesheet" />
    <link href="assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet" />

    <link href="assets/plugins/parsley/src/parsley.css" rel="stylesheet" />
@endsection




{{--  import in this section your javascript files  --}}
@section('page-js')
    <script src="assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/plugins/gritter/js/jquery.gritter.js"></script>
    <script src="assets/plugins/bootstrap-sweetalert/sweetalert.min.js"></script>
    <script src="assets/js/demo/ui-modal-notification.demo.min.js"></script>
    <script src="assets/plugins/DataTables/media/js/jquery.dataTables.js"></script>
    <script src="assets/plugins/DataTables/media/js/dataTables.bootstrap.min.js"></script>
    <script src="assets/plugins/DataTables/extensions/Responsive/js/dataTables.responsive.min.js"></script>
    <script src="assets/js/demo/table-manage-default.demo.min.js"></script>


    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <!-- <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" /> -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js"></script>
    
    <script src="assets/plugins/pace/pace.min.js"></script>

    <script type="text/javascript">
        var token = "{{csrf_token()}}";

        $.fn.serializeObject = function(){
            var o = {};
            var a = this.serializeArray();
            $.each(a, function() {
                if (o[this.name] !== undefined) {
                    if (!o[this.name].push) {
                        o[this.name] = [o[this.name]];
                    }
                    o[this.name].push(this.value || '');
                } else {
                    o[this.name] = this.value || '';
                }
            });
            return o;
        };
        

        $('#range').daterangepicker({
            autoUpdateInput: false,
            showDropdowns: true,
            minYear: 2019,
            //maxYear: parseInt(moment().format('YYYY'),10),
            linkedCalendars: false,
            locale: {
                cancelLabel: 'Clear'
            }
        });

        $('#range').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('MMMM D, YYYY') + ' - ' + picker.endDate.format('MMMM D, YYYY'));
        });

        $('#range').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });


        $('#intervention_form').on('submit', function(e) {
            e.preventDefault();
            var form               = $(this);
            var form_objects       = form.serializeObject();
            form_objects['_token'] = token;

            form.parsley().validate();

            if (form.parsley().isValid()){
                //console.log(form_objects);

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'A new intervention will be created, do you want to proceed?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, proceed!'
                }).then((result) => {
                    if(result.isConfirmed){
                         $.ajax({
                            url: '{{route("create-intervention")}}',
                            type: 'post',
                            data: form_objects,
                            dataType: 'json',
                            success: function(r){
                                //console.log(result);

                                if(r.result == 'success'){
                                    Swal.fire({
                                        title: 'Thank you!',
                                        text: 'A new intervention has been created to help our agriculture heroes!',
                                        icon: 'success',
                                        confirmButtonColor: '#3085d6'
                                    }).then((res) => {
                                        if(res.isConfirmed){
                                            console.log('reload me');
                                            setTimeout(function() {
                                                location.reload();
                                            }, 200);
                                        }
                                    });
                                }
                            }
                        });
                    }
                });
            }

            //return false;
        });

        $('#amount').on('keyup', function(e) {
            // skip for arrow keys
            if (event.which >= 37 && event.which <= 40) {
                event.preventDefault();
            }

            var currentVal = $(this).val();
            var testDecimal = testDecimals(currentVal);
            if (testDecimal.length > 1) {
                console.log("You cannot enter more than one decimal point");
                currentVal = currentVal.slice(0, -1);
            }
            $(this).val(replaceCommas(currentVal));
        });

        function testDecimals(currentVal) {
            var count;
            currentVal.match(/\./g) === null ? count = 0 : count = currentVal.match(/\./g);
            return count;
        }

        function replaceCommas(number) {
            var components = number.toString().split(".");
            if (components.length === 1)
                components[0] = number;
            components[0] = components[0].replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            if (components.length === 2)
                components[1] = components[1].replace(/\D/g, "");
            return components.join(".");
        }
    
    </script>
@endsection


@section('content')
<!-- begin breadcrumb -->
<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="{{ url('program-intervention-overview') }}">Overview</a></li>
    <li class="breadcrumb-item active">Create Program</li>
    <!-- <li class="breadcrumb-item"><a href="javascript:;">Commodity Management</a></li> -->
    <!-- <li class="breadcrumb-item active">Blank Page</li> -->
</ol>
<!-- end breadcrumb -->
<!-- begin page-header -->
<h1 class="page-header">Create Program Interventions <small>Manage</small></h1>
<!-- end page-header -->

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-inverse">
            <div class="panel-heading">
                <h4 class="panel-title">Create Intervention</h4>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3"></div>
                    <div class="col-md-6">
                        <form class="form-horizontal" data-parsley-validate="true" id="intervention_form">
                            <div class="form-group row m-b-15">
                                <label class="col-md-4 col-sm-4 col-form-label" for="fullname">Program Title * :</label>
                                <div class="col-md-8 col-sm-8">
                                    <input class="form-control" type="text" id="prog_title" name="prog_title" data-parsley-required="true" autocomplete="off" />
                                </div>
                            </div>
                            <div class="form-group row m-b-15">
                                <label class="col-md-4 col-sm-4 col-form-label" for="email">Program Alias * :</label>
                                <div class="col-md-8 col-sm-8">
                                    <input class="form-control" type="text" id="prog_alias" name="prog_alias"  data-parsley-required="true" autocomplete="off"/>
                                </div>
                            </div>
                            <div class="form-group row m-b-15">
                                <label class="col-md-4 col-sm-4 col-form-label" for="email">Program Remitter ID * :</label>
                                <div class="col-md-8 col-sm-8">
                                    <input class="form-control" type="text" id="prog_remitter" name="prog_remitter"  data-parsley-required="true" autocomplete="off"/>
                                </div>
                            </div>
                            <div class="form-group row m-b-15">
                                <label class="col-md-4 col-sm-4 col-form-label" for="message">Program expiration :</label>
                                <div class="col-md-8">
                                    <div class="input-group" id="default-daterange">
                                        <input type="text" name="prog_range" id="range" class="form-control" value="" placeholder="click to select the date range" autocomplete="off" />
                                        <span class="input-group-append">
                                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row m-b-15">
                                <label class="col-md-4 col-sm-4 col-form-label" for="message">Description :</label>
                                <div class="col-md-8 col-sm-8">
                                    <textarea class="form-control" id="description" name="description" rows="4" placeholder="Tell us about the Intervention."></textarea>
                                </div>
                            </div>
                            <div class="form-group row m-b-15">
                                <label class="col-md-4 col-sm-4 col-form-label" for="message">Amount * :</label>
                                <div class="col-md-8 col-sm-8">
                                    <input class="form-control" type="text" id="amount" name="amount" data-parsley-required="true" autocomplete="off"/>
                                </div>
                            </div>
                            <div class="form-group row m-b-0">
                                <label class="col-md-4 col-sm-4 col-form-label">&nbsp;</label>
                                <div class="col-md-8 col-sm-8">
                                    <button type="submit" class="btn btn-primary">Create</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- <div class="col-lg-6">
        <div class="panel panel-inverse">
            <div class="panel-heading">
                <h4 class="panel-title">Disbursement Gauge</h4>
            </div>
            <div class="panel-body">
                <figure class="highcharts-figure">
                    <div id="container-speed" class="chart-container"></div>
                </figure>
            </div>
        </div>
    </div> -->
</div>


@endsection