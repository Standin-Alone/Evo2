@extends('global.base')
@section('title', "Supplier Main Branch")

{{--  import in this section your css files--}}
@section('page-css')
<link href="assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
<link href="assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css" rel="stylesheet" />
<link href="assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css" rel="stylesheet" />
<link href="https://cdn.datatables.net/1.11.0/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css" rel="stylesheet" />
<link href="https://cdn.datatables.net/rowgroup/1.1.3/css/rowGroup.dataTables.min.css" rel="stylesheet">
<link href="assets/pgv/backend-style.css" rel="stylesheet">
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
<script src="https://cdn.datatables.net/1.11.0/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/rowgroup/1.1.3/js/dataTables.rowGroup.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="assets/pgv/backend-script.js"></script>

<script type="text/javascript">
    $(document).ready(function() {

        SupplierMainBranchList();

        function SupplierMainBranchList(){             
            var table = $('#SupplierMainBranchList-datatable').DataTable({ 
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    responsive: true,   
                    scrollY: "350px",
                    ajax: "{{ route('Viewing.MainBranch') }}",
                    columns: [
                        {data: 'date_created', name: 'approved_date', title:'DATE CREATED'},
                        {data: "group_name",name: "group_name",
                        render: function(data,type,row) {
                                return '<a href="javascript:void(0);" data-selectedgroupid="' + row.supplier_group_id + '" class="selectedmainbranchlink"><i class="fas fa-spinner fa-spin '+ row.supplier_group_id +' pull-left m-r-10" style="display: none;"></i>' + data + '</a>';                          
                        }, title: 'MAIN BRANCH NAME'},
                        {data: 'address', name: 'total_records', title:'ADDRESS'}, 
                        {data: 'supplier_group_id', name: 'supplier_group_id',  title:'ACTION',
                            render: function(data, type, row) {
                                return '<a href="javascript:;" data-selectedgroupid="'+row.supplier_group_id+'" data-selectedgroupname="'+row.group_name+'" data-selectedaddress="'+row.address+'" class="btn btn-xs btn-outline-info Edit_MainBranch_Button"><i class="fas fa-spinner fa-spin '+row.supplier_group_id+' pull-left m-r-10" style="display: none;"></i><span class="fa fa-edit"></span> Edit</a>|'+
                                    '<a href="javascript:;" data-selectedgroupid="'+row.supplier_group_id+'" class="btn btn-xs btn-outline-danger Remove_MainBranch_Button"><i class="fas fa-spinner fa-spin '+row.supplier_group_id+' pull-left m-r-10" style="display: none;"></i><span class="fa fa-trash"></span> Remove</a>';
                            }
                        }                   
                    ],
                });
        }        

        function SupplierSubMainBranchList(group_id){             
            var table = $('#SupplierSubMainBranchList-datatable').DataTable({ 
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    responsive: true,   
                    scrollY: "200px",
                    ajax: "{{ route('Viewing.SubMainBranch') }}" + '?group_id=' + group_id,
                    columns: [
                        {data: 'supplier_name', name: 'supplier_name', title:'SUPPLIER NAME'}, 
                        {data: 'address', name: 'total_records', title:'ADDRESS'}, 
                        {data: 'email', name: 'email', title:'EMAIL ACCOUNT'},
                        {data: 'contact', name: 'contact', title:'CONTACT NO.'},
                    ],
                });
        }

        $(document).on('click','.Create_MainBranch_Button',function(){
            SpinnerShow('Create_MainBranch_Button','btnloadingIcon');
            $('.Save_MainBranch_Button').css('display','block');
            $('.Update_MainBranch_Button').css('display','none');
            $('.txtMainBranchName').val('');
            $('.txtMainBranchAddress').val('');
            $('#Form_MainBranch_Modal').modal('toggle');  
            SpinnerHide('Create_MainBranch_Button','btnloadingIcon');
        });

        $(document).on('click','.Save_MainBranch_Button',function(){
            var group_name = $('.txtMainBranchName').val(),
                address = $('.txtMainBranchAddress').val(),
                _token = $("input[name=token]").val();
            SpinnerShow('Save_MainBranch_Button','btnloadingIcon2');
            if(group_name == "" || address == ""){
                $('.errormsg').css('display','block');
                $('.errormsg').html("<b><h4><i class='fa fa-exclamation-triangle'></i> Error!</h4></b><hr> Please Input required fields! Please try again.");
                SpinnerHide('Save_MainBranch_Button','btnloadingIcon2');
                AlertHide('errormsg');
            }else{
                Swal.fire({
                    title: 'Are you sure',
                    text: "You want to Save new main branch?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Save',
                    allowOutsideClick: false
                    }).then((result) => {
                    if (result.isConfirmed) {                        
                            $.ajax({
                                type:'post',
                                url:"{{ route('Inserting.MainBranch') }}",
                                data:{group_name:group_name,address:address,_token:_token},
                                success:function(data){ 
                                    Swal.fire({
                                            allowOutsideClick: false,
                                            title:'Created!',
                                            text:'Your New Main Branch successfully Created!',
                                            icon:'success'
                                        });                                  
                                        $('.errormsg').css('display','none');                                    
                                        var tablereset = $('#SupplierMainBranchList-datatable').DataTable();
                                        tablereset.clear().draw();
                                        SpinnerHide('Save_MainBranch_Button','btnloadingIcon2');
                                        $('#Form_MainBranch_Modal').modal('hide');  
                                        SupplierMainBranchList();                                
                            },
                            error: function (textStatus, errorThrown) {
                                    console.log('Err');
                                    SpinnerHide('Save_MainBranch_Button','btnloadingIcon2');
                                }
                            });
                        
                    }else{
                        SpinnerHide('Save_MainBranch_Button','btnloadingIcon2');
                    }
                });
            }            
        });

        $(document).on('click','.Edit_MainBranch_Button',function(){
            var selectedgroupid = $(this).data('selectedgroupid'),
                selectedgroupname = $(this).data('selectedgroupname'),
                selectedaddress = $(this).data('selectedaddress');
            SpinnerShow('Edit_MainBranch_Button',selectedgroupid);
            $('#selected_groupid').val(selectedgroupid);
            $('.txtMainBranchName').val(selectedgroupname);
            $('.txtMainBranchAddress').val(selectedaddress);
            $('.Save_MainBranch_Button').css('display','none');
            $('.Update_MainBranch_Button').css('display','block');
            $('#Form_MainBranch_Modal').modal('toggle');
            SpinnerHide('Edit_MainBranch_Button',selectedgroupid);
        });

        $(document).on('click','.Update_MainBranch_Button',function(){
            var group_id = $('#selected_groupid').val(),
                group_name = $('.txtMainBranchName').val(),
                address = $('.txtMainBranchAddress').val(),
                _token = $("input[name=token]").val();
            SpinnerShow('Update_MainBranch_Button','btnloadingIcon3');
            if(group_name == "" || address == ""){
                $('.errormsg').css('display','block');
                $('.errormsg').html("<b><h4><i class='fa fa-exclamation-triangle'></i> Error!</h4></b><hr> Please Input required fields! Please try again.");
                SpinnerHide('Update_MainBranch_Button','btnloadingIcon3');
                AlertHide('errormsg');
            }else{
                Swal.fire({
                    title: 'Are you sure',
                    text: "You want to Update selected main branch?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Update',
                    allowOutsideClick: false
                    }).then((result) => {
                    if (result.isConfirmed) {
                            $.ajax({
                                type:'post',
                                url:"{{ route('Updating.MainBranch') }}",
                                data:{group_id:group_id,group_name:group_name,address:address,_token:_token},
                                success:function(data){ 
                                    Swal.fire({
                                        allowOutsideClick: false,
                                        title:'Updated!',
                                        text:'Your selected Main Branch successfully Updated!',
                                        icon:'success'
                                    });     
                                    $('.errormsg').css('display','none');                                    
                                    var tablereset = $('#SupplierMainBranchList-datatable').DataTable();
                                    tablereset.clear().draw();
                                    SpinnerHide('Update_MainBranch_Button','btnloadingIcon2');
                                    $('#Form_MainBranch_Modal').modal('hide');  
                                    SupplierMainBranchList();                                
                            },
                            error: function (textStatus, errorThrown) {
                                    console.log('Err');
                                    SpinnerHide('Update_MainBranch_Button','btnloadingIcon3');
                                }
                            });
                        
                    }else{
                        SpinnerHide('Update_MainBranch_Button','btnloadingIcon3');
                    }
                });
            }
        });

        $(document).on('click','.Remove_MainBranch_Button',function(){
            var group_id = $(this).data('selectedgroupid'),
                _token = $("input[name=token]").val();
            SpinnerShow('Remove_MainBranch_Button',group_id); 
            Swal.fire({
                title: 'Are you sure',
                text: "You want to Remove selected main branch?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Remove',
                allowOutsideClick: false
                }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type:'post',
                        url:"{{ route('Removing.MainBranch') }}",
                        data:{group_id:group_id,_token:_token},
                        success:function(data){ 
                            Swal.fire({
                                    allowOutsideClick: false,
                                    title:'Removed!',
                                    text:'Your Selected Main Branch successfully Removed!',
                                    icon:'success'
                                });                                  
                                $('.errormsg').css('display','none');                                    
                                var tablereset = $('#SupplierMainBranchList-datatable').DataTable();
                                tablereset.clear().draw();
                                SpinnerHide('Remove_MainBranch_Button',group_id);
                                $('#Form_MainBranch_Modal').modal('hide');  
                                SupplierMainBranchList();                                
                    },
                    error: function (textStatus, errorThrown) {
                            console.log('Err');
                            SpinnerHide('Remove_MainBranch_Button',group_id);
                        }
                    });
                    
                }else{
                    SpinnerHide('Remove_MainBranch_Button',group_id);
                }
            });
        });

        $(document).on('click','.selectedmainbranchlink',function(){
            var group_id = $(this).data('selectedgroupid');
            SupplierSubMainBranchList(group_id);
            $('#View_SubBranchList_Modal').modal('toggle');  
        });

    });        
</script>

@endsection

@section('content')
<input type="hidden" id="selectedProgramDesc" value="{{session('Default_Program_Desc')}}">
<input type="hidden" id="selectedProgramId" value="{{session('Default_Program_Id')}}">
<input type="hidden" id="selected_groupid" value="">

<div class="row">
    <div class="col-md-8">
        <div class="input-group">
            <h1 class="page-header">Supplier Main Branch</h1>                       
        </div>
    </div>
    <div class="col-md-4">
        <ol class="breadcrumb pull-right">
            <li class="breadcrumb-item"><a href="{{ route('main.home') }}">Home Page</a></li>
            <li class="breadcrumb-item active">Supplier Main Branch</li>
        </ol>   
    </div>
</div>

<div class="row">
    <div class="col-xl-12 ui-sortable">       
        <div class="pull-right">                              
            <a href="javascript:;" class="btn btn-lg btn-primary Create_MainBranch_Button">  
                <i class="fa-2x fas fa-spinner fa-spin btnloadingIcon pull-left m-r-10" style="display: none;"></i>                                  
                <i class="fa fa-home fa-2x pull-left m-r-10 text-black"></i>
                <b class="Create_MainBranch_Title"> Create Main Branch</b><br />
                <small>Click Here</small>
            </a>
        </div>
    </div>
</div><br>

<div class="panel panel-success">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success reload_panel" data-click="panel-reload"><i class="fa fa-redo"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
        </div>
        <h4 class="panel-title panel-title-main btn btn-xs" style="font-weight:normal !important;"><span class="fa fa-th-large"></span> DETAILS:</h4>
        <h4 class="panel-title panel-title-sub btn btn-xs" style="font-weight:normal !important;display:none;">:</h4> 
    </div>
    <div class="panel-body">
        <table id="SupplierMainBranchList-datatable" class="table table-striped display nowrap" style="width: 100%;">
            <thead style="background-color: #008a8a"></thead>
        </table> 
    </div>
</div>

<div class="modal fade" id="Form_MainBranch_Modal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <form id="AddForm" method="POST">
            @csrf
            <div class="modal-content" style="background-color: #008a8a">
                <div class="modal-header" style="background-color: #008a8a">
                    <h4 class="modal-title" style="color: white"><i class="fa fa-info-circle"></i> Main Branch</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                </div>
                <div class="modal-body" style="background-color: #fff">
                    {{--modal body start--}}
                    
                        <div class="right-content">
                            <div class="register-content">
                                <form action="index.html" method="GET" class="margin-bottom-0">
                                    @csrf
                                    <div class="row m-b-15">
                                        <div class="col-md-12">
                                            <div class="alert alert-danger errormsg" role="alert" style="display: none;"></div>
                                        </div>
                                    </div>
                                    <div class="row m-b-15">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label class="form-label" for="txtmainbranchname">Main Branch Name: <span class="text-danger">*</span></label>
                                                <input class="form-control txtMainBranchName" type="text" id="txtmainbranchname" placeholder="Enter Main Branch Name" />
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" for="txtmainbranchaddress">Complete Address: <span class="text-danger">*</span></label>
                                                <textarea class="form-control txtMainBranchAddress" rows="3" id="txtmainbranchaddress" placeholder="Enter Complete Address"></textarea>
                                            </div>                                                                                 
                                        </div>
                                    </div>                                      
                                </form>
                            </div>
                        </div>
                        
                    {{--modal body end--}}
                </div>
                <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-success Save_MainBranch_Button"><i class="fa fa-check-circle"></i><i class="fas fa-spinner fa-spin btnloadingIcon2 pull-left m-r-10" style="display: none;"></i> Create</a>
                    <a href="javascript:;" class="btn btn-success Update_MainBranch_Button" style="display: none;"><i class="fa fa-check-circle"></i><i class="fas fa-spinner fa-spin btnloadingIcon3 pull-left m-r-10" style="display: none;"></i> Update</a>
                    <a href="javascript:;" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Close</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="View_SubBranchList_Modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <form id="AddForm" method="POST" >
            @csrf
            <div class="modal-content" style="background-color: #008a8a">
                <div class="modal-header" style="background-color: #008a8a">
                    <h4 class="modal-title" style="color: white"><i class="fa fa-info-circle"></i> Sub Branch List Details</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                </div>
                <div class="modal-body" style="background-color: #fff;">
                    <div class="right-content">
                        <div class="register-content">
                            <div class="row m-b-15">
                                <div class="col-md-12">
                                    <table id="SupplierSubMainBranchList-datatable" class="table table-striped display nowrap" style="width: 100%;">
                                        <thead style="background-color: #008a8a"></thead>
                                    </table> 
                                </div>
                            </div>
                        </div>
                    </div>                    
                </div>
                <div class="modal-footer">
                <a href="javascript:;" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Close</a>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection