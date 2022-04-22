@extends('global.base')
@section('title', "Mobile App Version Control")




{{--  import in this section your css files--}}
@section('page-css')
    <link href="assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
    <link href="assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css" rel="stylesheet" />
    <link href="assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css" rel="stylesheet" />

    <style>
        
        dd{
            font-size: 20
        }
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

        /* MODIFY DATATABLE WRAPPER/MOBILE VIEW NAVAGATE ROW ICON */
        .dataTables_wrapper table.dataTable.dtr-inline.collapsed > tbody > tr > td:first-child::before{
            /* background: #008a8a !important; */
            background: #008a8a !important;
            border-radius: 10px !important;
            border: none !important;
            top: 18px !important;
            left: 5px !important;
            line-height: 16px !important;
            box-shadow: none !important;
            color: #fff !important;
            font-weight: 700 !important;
            height: 16px !important;
            width: 16px !important;
            text-align: center !important;
            text-indent: 0 !important;
            font-size: 14px !important;
        }
        
        .dataTables_wrapper table.dataTable.dtr-inline.collapsed>tbody>tr.parent>td:first-child:before, 
        .dataTables_wrapper table.dataTable.dtr-inline.collapsed>tbody>tr.parent>th:first-child:before{
            /* background: #008a8a !important; */
            background: #b31515 !important;
            border-radius: 10px !important;
            border: none !important;
            top: 18px !important;
            left: 5px !important;
            line-height: 16px !important;
            box-shadow: none !important;
            color: #fff !important;
            font-weight: 700 !important;
            height: 16px !important;
            width: 16px !important;
            text-align: center !important;
            text-indent: 0 !important;
            font-size: 14px !important;
        }
    </style>
@endsection




{{--  import in this section your javascript files  --}}
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
            responsive:true,
            ajax: "{{route('mobile-version-control-show')}}",
            columns:[
                    {data:'filename',title:'File Name'},
                    {data:'version',title:"Version"},                                       
                    // {data:'id',
                    //     title:"Actions",
                    //     render: function(data,type,row){       
                        
                        

                    //         return  "<button type='button' class='btn view-modal-btn btn-outline-warning' user_id="+row['user_id']+" role_id="+row['role_id']+"  agency_loc="+row['agency_loc']+" reg="+row['reg']+" prov="+row['prov']+"  mun="+row['mun']+"  bgy="+row['bgy']+"   data-toggle='modal' data-target='#ViewModal'>"+
                    //                     "<i class='fa fa-edit'></i> Edit"+
                    //                 "</button>   "+(
                    //                 row['status'] == 1 ?
                    //                 "<button type='button' class='btn btn-outline-danger set-status-btn ' id='"+row['user_id']+"' status='"+row["status"]+"' >"+
                    //                     "<i class='fa fa-trash'></i> Disable"+
                    //                 "</button>  " :
                    //                 "<button type='button' class='btn btn-outline-success set-status-btn' id='"+row['user_id']+"' status='"+row["status"]+"' >"+
                    //                     "<i class='fa fa-undo'></i> Enable"+
                    //                 "</button> ")+(
                    //                 row['status'] == 2 ?
                    //                 "<button type='button' class='btn btn-outline-success set-block-btn ' id='"+row['user_id']+"' status='"+row["status"]+"' >"+
                    //                     "<i class='fa fa-trash'></i> Unblock"+
                    //                 "</button>  " :
                    //                 "<button type='button' class='btn btn-outline-primary set-block-btn' id='"+row['user_id']+"' status='"+row["status"]+"' >"+
                    //                     "<i class='fa fa-ban'></i> Block"+
                    //                 "</button> ")
                    //     }
                    // }
            ]

        })


        $("#AddApkForm").validate({
            rules:{
                filename:{
                    required:true,
                    accept: ".apk"
                },
                version:{
                    required:true
                }
            },
            messages:{  
                filename:{
                            required:'<div class="text-danger">Please upload apk.</div>',
                            accept: '<div class="text-danger">APK File Only</div>'
                        },
                version:{
                    required:'<div class="text-danger">Please enter version name.</div>',
                }       
            },  
            submitHandler:function(){

                let fd = new FormData();

                fd.append('file',$("input[name='file']").prop('files')[0]);
                fd.append('_token','{{csrf_token()}}');
                fd.append('version',$("input[name='version']").val());


                swal({
                    title: "Wait!",
                    text: "Are you sure you want to upload this APK?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: false,
                    })
                    .then((confirm) => {
                        if(confirm){
                            $.ajax({
                                url:"{{route('add-apk')}}",
                                type:'post',
                                data:fd,
                                contentType:false,
                                processData:false,
                                success:function(response){             
                                    //    
                                    console.warn(response);
                                    if(response == 'true'){
                                        swal("Successfully added new user.", {
                                            icon: "success",
                                        }).then(()=>{
                                            $(".add-btn").html('Add'); 
                                            $("#load-datatable").DataTable().ajax.reload();                                            
                                            $(".add-btn").prop('disabled',false);
                                            $("#AddApkForm")[0].reset();
                                        });
                                    }else{
                                        swal("Failed to add new user.", {
                                            icon: "error",
                                        }).then(()=>{
                                            $(".add-btn").html('Add');                                 
                                            $(".add-btn").prop('disabled',false);
                                            
                                        });
                                    }
                                },
                                error:function(response){
                                    $(".add-btn").prop('disabled',false);
                                }
                            })

                        }else{

                        }
                    });
            }

        })
    });


    </script>
@endsection










@section('content')

<!-- begin page-header -->
<h1 class="page-header">Mobile Application Version Control</h1>
<!-- end page-header -->

<!-- begin panel -->

<div class="row">


    <div class="col-lg-4">
        <div class="panel panel-success ">
            <form id="AddApkForm">
                @csrf
                <div class="panel-body">
                    <img src="{{url('edcel_images/mobile-app.png')}}" width="50%" height="20%" class="img-fluid mx-auto d-block"/>
                    <br>
                    <div class="form-group">
                        <label  class="control control-label">
                            File Upload
                        </label>
                        <input type="file" class="form-control" name="file"/>
                    </div>
                    <div class="form-group">
                        <label  class="control control-label">
                            Version
                        </label>
                        <input type="text" class="form-control" name="version" value/>
                    </div>                
                    <div class="form-group">
                        <button type="submit" class="form-control btn btn-success add-btn">Upload APK</button>
                    </div>                                
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-8">    
        <div class="panel panel-success p-12">            
            <div class="panel-body">
                <table id="load-datatable" class="table table-hover" style="width:100%">            
                    <thead>                                        
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- end panel -->
@endsection