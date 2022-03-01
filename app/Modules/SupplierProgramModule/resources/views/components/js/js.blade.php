{{-- datatable responsive --}}
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.js"></script>

{{-- datatable buttons --}}
<script src="https://cdn.datatables.net/buttons/2.0.0/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.colVis.min.js"></script>

{{-- Select2 plugin --}}
<script src="{{url('assets/plugins/select2/dist/js/select2.min.js')}}"></script>

{{-- sweet alert 2 --}}
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        $(".select_program_item").select2({
            width: '100%',
            dropdownParent: $("#setup_modal")
        });
        $('#select_program_item').on('change', function () {
            var program_item_value = $('#select_program_item').val();
        });
    });

    $('#setup_program').ready(function(){

        $('#setup_program').validate({
            errorClass: "invalid",
               validClass: "valid",
            rules: {
                select_program:{
                    required: true,
                },

                select_program_item:{
                    required: true,
                },
            },
            messages: {
                select_program: '<div class="text-danger">*Please select a program!</div>',
                select_program_item: '<div class="text-danger">*Please select a program item!</div>',
            },

            // Customize placement of created message error labels.
            errorPlacement: function(error, element) {
                error.appendTo( element.parent().find(".error_msg") );
                $('span.error_form').remove();
            }
        });

    });

    $(document).on('click', '#setupBtn', function () {
        var supplier_id = $(this).data('supplier_id');

        $(document).on('submit', 'form#setup_program', function(e){

            e.preventDefault();

            // console.log(supplier_id);

            var program_name_text = $('#select_program').find(":selected").text();

            var program_id_value =$('#select_program').val();

            var program_item_name_text = $('#select_program_item').find(":selected").text();

            var program_item_id_value = $('#select_program_item').val();

            var route = "{{ route('supplier_program_module.preview_form') }}";

            $('#setup_modal').modal('hide');

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: 'POST',
                url: route,
                data: {
                        program_id_value:program_id_value,
                        program_name_text:program_name_text,
                        program_item_id_value:program_item_id_value,
                        program_item_name_text:program_item_name_text,
                },
                success: function(datas){
                    var program_items = datas[0];
                    var program_id = datas[1];
                    var program_name = datas[2];

                    var arr_prog_item = [];
                    var arr_prog_id = [];
                    var arr_prog_name = [];

                    var html = "";

                    html += '<div class="col-lg-12">';

                    html +=     '<table id="preview_table" class="table table-bordered text-center" style="width:100%;">';
                    html +=         '<thead class="table-header">';
                    html +=             '<tr>';
                    html +=                 '<th style="color: white !important; width: 100px !important;"> PROGRAM </th>';
                    html +=                 '<th style="color: white !important; width: 100px !important;"> PROGRAM ITEM </th>';
                    html +=                 '<th style="color: white !important; width: 100px !important;"> UNIT MEASURE </th>';
                    html +=                 '<th style="color: white !important; width: 100px !important;"> CEILING AMOUNT </th>';
                    html +=             '</tr>';
                    html +=         '</thead>';
                    html +=         '<tbody>';

                    // console.log(supplier_id);

                    $.each(program_items, function (key, value) {
                        arr_prog_item.push(value.item_id);
                        $.each(program_id, function (key2, value2){
                            arr_prog_id.push(value2.program_id);
                            $.each(program_name, function (key3, value3){
                                html += '<input type="hidden" class="form-control" value="'+ value2.program_id +'">';
                                html += '<input type="hidden" class="form-control" value="'+ value.item_id +'">';

                                html +=             '<tr>';
                                html +=                 '<td style="color: white !important; width: 100px !important;"> <div class="form-group"> <input type="text" class="form-control input_tbl" value="'+ value3.program +'" readonly/> </div> </td>';
                                html +=                 '<td style="color: white !important; width: 100px !important;"> <div class="form-group"> <input type="text" class="form-control input_tbl" value="'+ value.item_name +'" readonly/> </div> </td>';
                                html +=                 '<td style="color: white !important; width: 100px !important;"> <div class="form-group"> <input type="text" class="form-control input_tbl" value="'+ value.unit_measure +'" readonly/> </div> </td>';
                                html +=                 '<td style="color: white !important; width: 100px !important;"> <div class="form-group"> <input type="number" class="form-control input_tbl" value="'+ value.ceiling_amount +'" readonly/> </div> </td>';
                                html +=             '</tr>';
                            });
                        });
                    });

                    html +=         '</tbody>';
                    html +=     '</table>';
                    html += '</div>';

                    var arr_prog_id = arr_prog_id;
                    var arr_prog_item = arr_prog_item;

                    // add content from another url
                    $("#preview_setup_modal #preview_modal_body").append(html);

                    // open the other modal
                    $("#preview_setup_modal").modal("show");

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        method: 'POST',
                        url: "{{ route('supplier_program_module.get_program_id_and_program_item_id') }}",
                        data: {
                                supplier_id:supplier_id,
                                arr_prog_id:arr_prog_id,
                                arr_prog_item:arr_prog_item,
                        },

                        success: function(datas){
                            var datas;

                            var program_id = datas[0];

                            var program_item = datas[1];

                            var supplier_id = datas[2];

                            $("form#create_program").on('submit', function(e){
                                e.preventDefault();

                                var route = "{{ route('supplier_program_module.submit_created_setup') }}";

                                $.ajax({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    method: 'POST',
                                    url: route,
                                    data: {
                                            program_id:program_id,
                                            program_item:program_item,
                                            supplier_id:supplier_id,
                                    },
                                    success: function(success_response){
                                        Swal.fire({
                                            position: 'center',
                                            icon: 'success',
                                            title: success_response.message,
                                            showConfirmButton: true,
                                            // timer: 1500
                                        }).then(function(){
                                            window.location.href = "{{route('supplier_program_module.list_of_suppliers')}}";
                                        });
                                    },
                                });
                            });
                        }
                    });
                },
            });
        });

    });


    $('#setup_modal').on('hidden.bs.modal', function(e)
    {
        // if preview modal is on show = 1: do not remove selected option value
        // else  remove selected option value

        var isshown = $('#preview_setup_modal').hasClass('show');

        if(isshown == false){
            $("#setup_modal_body select").val("");
        }
    });

    $('#preview_setup_modal').on('hidden.bs.modal', function(e)
    {
        $("#setup_modal").modal("show");

        $('#preview_table').remove();
    });
</script>
