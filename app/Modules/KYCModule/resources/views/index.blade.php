@extends('global.base')
@section('title', 'Supplier Registration')




{{-- import in this section your css files --}}
@section('page-css')
    <link href="assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
    <link href="assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css" rel="stylesheet" />
    <link href="assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css" rel="stylesheet" />
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

                    fd.append('_token','{{csrf_token()}}')                    
                    fd.append('file',$("input[name='file']")[0].files[0])
                    $(".import-btn").prop('disabled',true)
                    // check if confirm
                    if (confirm) {                       
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
                                        $(".import-btn").prop('disabled',false)                                    
                                    });
                                }else{
                                    swal("Error!Wrong excel format.", {
                                            icon: "error",
                                        });
                                    $(".import-btn").prop('disabled',false) 
                                }
                                
                            },
                            error:function(response){
                                console.warn(response);
                                $(".import-btn").prop('disabled',false)
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
    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-right">
        <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
        <li class="breadcrumb-item"><a href="javascript:;">Page Options</a></li>
        <li class="breadcrumb-item active">Blank Page</li>
    </ol>
    <!-- end breadcrumb -->
    <!-- begin page-header -->
    <h1 class="page-header">Blank Page <small>header small text goes here...</small></h1>
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
                    <h4 class="panel-title">KYC Import</h4>
                </div>
                <form id="ImportForm" method="POST">
                    @csrf
                    <div class="panel-body border">

                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Import Excel</label> <span id='reqcatnameadd'></span>
                                <input type="file" name="file" accept=".xlsx" class="form-control" required="true">
                            </div>
                        </div>
                        
                    </div>

                    <div class="panel-footer">
                        <div class="pull-right">
                            <button type='submit' class='btn btn-lime import-btn' >
                                <i class='fa fa-cloud-download-alt'></i> Import
                            </button>
                        </div>
                    </div>
                </form>
                <br>
            </div>


     
        </div>
    </div>
    <!-- end panel -->
@endsection
