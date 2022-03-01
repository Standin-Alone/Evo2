<script>
   $(document).ready(function() {

        App.init();

        // DashboardTotals();

        // function DashboardTotals(){
        //     $.ajax({
        //         type:'get',
        //         url:"",
        //             success:function(data){
        //                 for(var i=0;i<data.length;i++){ 
        //                         $('.ClaimedVouchers_total_payouts').html($.fn.dataTable.render.number(',', '.', 2, '&#8369;').display(data[i].total_payouts));
        //                         $('.ClaimedVouchers_total_count_voucher').html($.fn.dataTable.render.number(',', '.', 0, '').display(data[i].total_vouchers));
        //                     }                       
        //             },
        //             error: function (textStatus, errorThrown) {
        //                 console.log('Err');
        //             }
        //     });
        // }

        // $(document).on('click','.btn_CancellationModule_add',function(){
        //     $('#CancellationModuleDetailsModal').modal('toggle');

        // });

        CancellationModuleList();
        CancellationModuleDetails();
        GeneratedCancellationModule();

        function CancellationModuleList(){
            var TotalAmount = 0;
            var table = $('#CancellationModuleList-datatable').DataTable({ 
                destroy: true, processing: true, serverside: true, paging: false, scrollY: "200px",
                fixedHeader: {
                    header: true,
                    headerOffset: 45,
                    },
                scrollX: true,
                ajax: "{{ route('get.CancellationModuleList') }}",
                columns: [ 
                    {data: 'return_file_id', name: 'return_file_id',  title:'PROVINCE',
                        render: function(data, type, row) {
                            return '<a href="javascript:;" data-selectedfileid="'+row.return_file_id+'" class="CancellationModuleList_link" data-toggle="tooltip" data-placement="top" title="View Transaciton"><i class="fas fa-spinner fa-spin '+row.return_file_id+' pull-left m-r-10" style="display: none;"></i>'+row.province+'</a>';
                        }
                    },
                    {data: 'file_name', name: 'file_name', title:'FILE NAME'},
                    {data: 'date_uploaded', name: 'date_uploaded', title:'DATE UPLOADED'},
                    {data: 'total_amount', name: 'total_amount', render: $.fn.dataTable.render.number( ',', '.', 2, ''  ).display, title:'TOTAL AMOUNT'},

                ],
                "columnDefs": [
                        // { "width": "10px", "targets": 1 },
                        { className: "dt-right", "targets": [3] },
                ],
                footerCallback: function (row, data, start, end, display) {                                       
                    for (var i = 0; i < data.length; i++) {
                        var dataval = data[i]['total_amount'];
                        TotalAmount += Number(dataval);
                    }
                    $('.CancellationModuleList_total').html($.fn.dataTable.render.number(',', '.', 2, '&#8369;').display(TotalAmount));
                } 
            });
        }

        $(document).on('click','.CancellationModuleList_link',function(){
            var file_id = $(this).data('selectedfileid');
            CancellationModuleDetails(file_id);

        });     
        
        function CancellationModuleDetails(file_id){
            var TotalAmount = 0;
            var table = $('#CancellationModuleDetails-datatable').DataTable({ 
                destroy: true, processing: true, serverside: true, paging: false, scrollY: "200px",
                fixedHeader: {
                    header: true,
                    headerOffset: 45,
                    },
                scrollX: true,
                ajax: {
                        url:"{{ route('get.CancellationModuleDetails') }}", 
                        data:{file_id:file_id},
                    },
                columns: [ 
                    {data: 'dbp_return_id', name: 'dbp_return_id',  title:'SELECT',
                        render: function(data, type, row) {
                            return '<input type="checkbox" data-selecteddataid="'+ row.dbp_return_id +'" data-selectedfileid="'+ row.return_file_id +'" id="myCheck-'+ row.dbp_return_id +'" class="selectedname" name="selectedbatch">';
                        }
                    },
                    {data: 'rsbsa_no', name: 'rsbsa_no', title:'RSBSA NO.'},
                    {data: 'last_name', name: 'last_name', title:'LAST NAME'},
                    {data: 'first_name', name: 'first_name', title:'FIRST NAME'},
                    {data: 'middle_name', name: 'middle_name', title:'MIDDLE NAME'},
                    {data: 'province', name: 'province', title:'PROVINCE'},
                    {data: 'city_municipality', name: 'city_municipality', title:'MUNICIPALITY'},
                    {data: 'street_purok', name: 'street_purok', title:'STREET/PUROK'},
                    {data: 'dbp_status', name: 'dbp_status', title:'DBP STATUS'},
                    {data: 'amount', name: 'amount', render: $.fn.dataTable.render.number( ',', '.', 2, ''  ).display, title:'AMOUNT'},

                ],
                "columnDefs": [
                        { className: "dt-center", "targets": [0] },
                ],
                footerCallback: function (row, data, start, end, display) {                                       
                    for (var i = 0; i < data.length; i++) {
                        var dataval = data[i]['amount'];
                        TotalAmount += Number(dataval);
                    }
                    $('.CancellationModuleDetails_total').html($.fn.dataTable.render.number(',', '.', 2, '&#8369;').display(TotalAmount));
                } 
            });
        }  

        function GeneratedCancellationModule(){
            var TotalAmount = 0;
            var table = $('#GeneratedCancellationModule-datatable').DataTable({ 
                destroy: true, processing: true, serverside: true, paging: false, scrollY: "200px",
                fixedHeader: {
                    header: true,
                    headerOffset: 45,
                    },
                scrollX: true,
                ajax: "{{ route('get.GeneratedCancellationModule') }}",
                columns: [ 
                        // {data: 'rfo_file_name', name: 'rfo_file_name', title:'FILE NAME'},
                        {data: 'rfo_file_name', name: 'rfo_file_name',  title:'FILE NAME',
                            render: function(data, type, row) {
                                return '<a href="javascript:;" data-selectedrfofilename="'+row.rfo_file_name+'" class="GeneratedFileDetails_link" data-toggle="tooltip" data-placement="top" title="View Transaciton"><i class="fas fa-spinner fa-spin '+row.rfo_file_name+' pull-left m-r-10" style="display: none;"></i>'+row.rfo_file_name+'</a>';
                            }
                        },
                        {data: 'province', name: 'province', title:'PROVINCE'},
                        {data: 'rfo_date_created', name: 'rfo_date_created', title:'DATE CREATED'},                       
                        {data: 'total_count', name: 'total_count', render: $.fn.dataTable.render.number( ',', '.', 0, ''  ).display, title:'NO. OF BENEFICIARIES'},
                        {data: 'total_amount', name: 'total_amount', render: $.fn.dataTable.render.number( ',', '.', 2, ''  ).display, title:'TOTAL AMOUNT'},
                        {data: 'return_file_id', name: 'return_file_id',  title:'ACTION',
                        render: function(data, type, row) {
                            if(row.isdownloaded == 1){
                                return '<a href="{{route("download.CancellationModuleTexfile")}}?file_name='+row.rfo_file_name+'" target="_blank" class="btn btn-danger"><span class="fa fa-download"></span><i class="fas fa-spinner fa-spin '+row.return_file_id+' pull-left m-r-10" style="display: none;"></i> Re-Download</a>';
                            }else{
                                return '<a href="{{route("download.CancellationModuleTexfile")}}?file_name='+row.rfo_file_name+'" target="_blank" class="btn btn-primary"><span class="fa fa-download"></span><i class="fas fa-spinner fa-spin '+row.return_file_id+' pull-left m-r-10" style="display: none;"></i> Download</a>';
                            }                            
                        }
                    },

                ],
                "columnDefs": [
                        // { "width": "10px", "targets": 1 },
                        { className: "dt-center", "targets": [5] },
                ],
                footerCallback: function (row, data, start, end, display) {                                       
                    for (var i = 0; i < data.length; i++) {
                        var dataval = data[i]['total_amount'];
                        TotalAmount += Number(dataval);
                    }
                    // $('.GeneratedCancellationModule_total').html($.fn.dataTable.render.number(',', '.', 2, '&#8369;').display(TotalAmount));
                } 
            });
        }  

        $(document).on('click','.GeneratedFileDetails_link',function(){
            $('#GeneratedFileDetailsModal').modal('toggle');
            var rfo_file_name = $(this).data('selectedrfofilename');
            GeneratedFileDetails(rfo_file_name);
        });  

        function GeneratedFileDetails(rfo_file_name){
            var TotalAmount = 0;
            var table = $('#GeneratedFileDetails-datatable').DataTable({ 
                destroy: true, processing: true, serverside: true, paging: false, scrollY: "200px",
                fixedHeader: {
                    header: true,
                    headerOffset: 45,
                    },
                scrollX: true,
                ajax: {
                        url:"{{ route('get.GeneratedFileDetails') }}", 
                        data:{rfo_file_name:rfo_file_name},
                    },
                columns: [
                    {data: 'rsbsa_no', name: 'rsbsa_no', title:'RSBSA NO.'},
                    {data: 'last_name', name: 'last_name', title:'LAST NAME'},
                    {data: 'first_name', name: 'first_name', title:'FIRST NAME'},
                    {data: 'middle_name', name: 'middle_name', title:'MIDDLE NAME'},
                    {data: 'province', name: 'province', title:'PROVINCE'},
                    {data: 'city_municipality', name: 'city_municipality', title:'MUNICIPALITY'},
                    {data: 'street_purok', name: 'street_purok', title:'STREET/PUROK'},
                    {data: 'dbp_status', name: 'dbp_status', title:'DBP STATUS'},
                    {data: 'amount', name: 'amount', render: $.fn.dataTable.render.number( ',', '.', 2, ''  ).display, title:'AMOUNT'},

                ],
                "columnDefs": [
                        { className: "dt-center", "targets": [0] },
                ],
                footerCallback: function (row, data, start, end, display) {                                       
                    for (var i = 0; i < data.length; i++) {
                        var dataval = data[i]['amount'];
                        TotalAmount += Number(dataval);
                    }
                    $('.GeneratedFileDetails_total').html($.fn.dataTable.render.number(',', '.', 2, '&#8369;').display(TotalAmount));
                } 
            });
        }  



        $(document).on('click','.CancellationModuleDetails_btn',function(){
            var selecteddataid = $('.selectedname:checked').map(function(){
                return $(this).data('selecteddataid');}).get().join(',');
            var selectedfileid = $('.selectedname:checked').map(function(){
                return $(this).data('selectedfileid');}).get().join(',');
            var _token = $("input[name=token]").val();
                Swal.fire({
                    title: 'Are you sure',
                    text: "You want to Submit all selected farmers for cancellation?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, Submit it!',
                    allowOutsideClick: false
                    }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type:'post',
                            url:"{{ route('submit.CancellationModuleDetails') }}",
                            data:{selecteddataid:selecteddataid,selectedfileid:selectedfileid,_token:_token},
                            success:function(data){                         
                                Swal.fire({
                                    allowOutsideClick: false,
                                    title:'Submitted!',
                                    text:'Your Request successfully Submitted!',
                                    icon:'success'
                                });
                                CancellationModuleList();
                                CancellationModuleDetails();
                                GeneratedCancellationModule();
                        },
                        error: function (textStatus, errorThrown) {
                                console.log('Err');
                            }
                        });        
                    }
                });
        }); 

   });
</script>

