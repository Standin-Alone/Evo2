@extends('global.base')
@section('title', 'KYC Profiles')




{{-- import in this section your css files --}}
@section('page-css')
    <link href="assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
    <link href="assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css" rel="stylesheet" />
    <link href="assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css" rel="stylesheet" />

    <style>
        td { font-size: 17px; font-weight: 500 }

        
        #load-datatable > thead > tr > th {
            color:white;
            background-color: #008a8a;
            font-size: 20px;
            font-family: calibri
        }

        #load-datatable > thead > tr > th {
            color:white;
            font-size: 20px;
            background-color: #008a8a;
            font-weight: bold
        }
        #load-datatable> thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
            padding: 5px !important;
        }           
    </style>
@endsection




{{-- import in this section your javascript files --}}
@section('page-js')
    <script src="assets/plugins/gritter/js/jquery.gritter.js"></script>
    <script src="assets/plugins/bootstrap-sweetalert/sweetalert.min.js"></script>
    <script src="assets/js/demo/ui-modal-notification.demo.min.js"></script>
    <script src="assets/plugins/DataTables/media/js/jquery.dataTables.js"></script>
    <script src="assets/plugins/DataTables/media/js/dataTables.bootstrap.min.js"></script>
    <script src="assets/plugins/DataTables/extensions/Responsive/js/dataTables.responsive.min.js"></script>
    <script src="assets/js/demo/table-manage-default.demo.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/jquery.validate.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/additional-methods.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/additional-methods.min.js"></script>



    <script>
        $(document).ready(function(){

                load_datatable = $("#load-datatable").DataTable({
                                serverSide:true,
                                ajax: {"url":"{{route('kyc.show')}}","type":'get'},
                                columns:[
                                        {data:'rsbsa_no',title:'RSBSA Number'},
                                        {data:'fintech_provider',title:'Provider',orderable:false},
                                        {data:'full_name',title:'Name',orderable:false},
                                        {data:'address',title:'Address',orderable:false}
                                        
                                ]

                            })
                            

            // import file
            $("#ImportForm").validate({

            rules:{                
                file:{
                    required:true,
                    accept: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel"
                }
            },
            messages:{                
                file:{
                    required: '<div class="text-danger">Please select file to upload.</div>',
                    accept: '<div class="text-danger">Please upload valid files formats .xlsx, . xls only.</div>'
                }
            },
            submitHandler: function(){
                let fd = this;
                
                
                
                swal({
                title: "Wait!",
                text: "Are you sure you want to import this file?",
                icon: "warning",
                buttons: true,
                dangerMode: false,
                })
                .then((confirm) => {
                    let fd = new FormData();
                    let get_provider = $("#provider option:selected").val();
                    fd.append('provider',get_provider)
                    fd.append('_token','{{csrf_token()}}')                    
                    fd.append('file',$("input[name='file']")[0].files[0])
                    $(".import-btn").prop('disabled',true)
                    // check if confirm
                    if (confirm) {         
                        $(".import-btn").html('<i class="fas fa-circle-notch fa-spin"></i> Importing');                 
                        $.ajax({
                            url:"{{route('import-kyc')}}",
                            type:'post',
                            data: fd,
                            processData:false,
                            contentType:false,
                            success:function(response){             
                                //      
                                parses_result = JSON.parse(response)
                                total_rows_inserted = parses_result['total_rows_inserted'];
                                total_rows = parses_result['total_rows'];
                                
                                if(parses_result['message'] == 'true'){
                                    swal(total_rows_inserted + ' out of ' + total_rows + ' rows has been successfully inserted.', {
                                        icon: "success",
                                    }).then(()=>{                    
                                        
                                        // check if it has error data;
                                        if(parses_result['error_data'].length > 0 ){
                                            $("#ErrorDataModal").modal('show');
                                            $("#error-datatable").DataTable({
                                                data:parses_result['error_data'],
                                                columns:[
                                                    {data:'rsbsa_no',title:'RSBSA Number'},
                                                    {data:'fintech_provider',title:'Provider',orderable:false},
                                                    {title:'Name',orderable:false,render:function(data,type,row){
                                                        return row.first_name + ' ' + row.last_name;
                                                    }},
                                                    {data:'remarks',title:'Remarks',orderable:false}
                                                    

                                                ]
                                            });
                                        }

                                        $("#ImportForm")[0].reset();
                                        $(".import-btn").prop('disabled',false)      
                                        $(".import-btn").html('<i class="fas fa-cloud-download-alt "></i> Import');                                 
                                        $("#load-datatable").DataTable().ajax.reload();
                                    });
                                }else{
                                    swal("Error!Wrong excel format.", {
                                            icon: "error",
                                        });
                                    $("#ImportForm")[0].reset();
                                    $(".import-btn").prop('disabled',false) 
                                    $(".import-btn").html('<i class="fas fa-cloud-download-alt "></i> Import');   
                                }
                                
                            },
                            error:function(response){
                                console.warn(response);
                                $("#ImportForm")[0].reset();
                                $(".import-btn").prop('disabled',false)
                                $(".import-btn").html('<i class="fas fa-cloud-download-alt"></i> Import');   
                            }   
                        })
                        
                    } else {
                        $(".import-btn").prop('disabled',false)                            
                        swal("Operation Cancelled.", {
                            icon: "error",
                        });
                    }
                });
            }
            })
        })

    </script>
@endsection






@section('content')
    
    <!-- end breadcrumb -->
    <!-- begin page-header -->
    <h1 class="page-header">KYC Profiles </h1>
    <!-- end page-header -->

    <!-- begin panel -->
    <div class="panel panel-inverse">
        <div class="panel-heading">
            <h4 class="panel-title">KYC</h4>
        </div>
        {{-- KYC FORM PANEL --}}
       
        <div class="panel-body">
            <div class="panel panel-primary col-md-6">
                <div class="panel-heading">
                    <h4 class="panel-title">Import KYC Profiles</h4>
                </div>
                <form id="ImportForm" method="POST">
                    @csrf
                    <div class="panel-body border">

                        <div class="col-lg-12 ">
                            <div class="form-group">
                                <label >Select Fintech Provider</label> <span style="color:red">*</span>
                                <select class="form-control" name="provider" id="provider" >
                                    <option selected disabled value="">Select Provider</option>                                        
                                        <option  value="SPTI">SPTI</option>
                                        <option  value="UMSI">UMSI</option>                                    
                                </select>
                            </div>                              
                        </div>


                        
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Import Excel</label><span style="color:red">*</span>
                                <input type="file" name="file" accept=".xlsx" class="form-control" required="true">
                            </div>
                        </div>



                        <div class="col-lg-12">
                            <div class="form-group text-right">
                                <button type='submit' class='btn btn-lime import-btn' >
                                    <i class='fa fa-cloud-download-alt'></i> Import
                                </button>      
                            </div>
                        </div>                        
                    </div>
                
                </form>
                <br>
            </div>




            <!-- #modal-list of not inserted data to database from excel -->
            <div class="modal fade" id="ErrorDataModal"  data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog" style="max-width: 40%">                    
                        <div class="modal-content">
                            <div class="modal-header" style="background-color: #ff5b57">
                                <h4 class="modal-title update-modal-title" style="color: white">Unsuccessful Imported Data</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                            </div>
                            <div class="modal-body">
                                {{--modal body start--}}          

                                <table id="error-datatable" class="table table-hover" style="width:100%">            
                                    <thead>                                        
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>

                                {{--modal body end--}}
                            </div>
                            <div class="modal-footer">
                                <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Close</a>                                
                            </div>
                        </div>                    
                </div>
            </div>



            <table id="load-datatable" class="table table-striped table-bordered">            
                <thead>                                    
                </thead>
                <tbody>                
                </tbody>
            </table>
    
        </div>

    </div>
    <!-- end panel -->
@endsection
