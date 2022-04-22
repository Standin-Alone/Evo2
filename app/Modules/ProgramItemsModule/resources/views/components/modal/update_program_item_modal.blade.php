<div class="modal fade" id="update_program_item_modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#249bb6;">
                <h4 class="modal-title" style="color: white;">UPDATE PROGRAM ITEM:</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                    style="color: white">×</button>
            </div>
            <div class="modal-body update_program_item_modal_body">
                {{-- <div id="append_start_here" class="append_start_here"> --}}
                    <h4 class="mt-4" style="text-align:center">INTERVENTION ITEM</h4>

                    <div class="input_div mt-4">

                        <form id="update_program_item_form" method="POST" class="margin-bottom-0" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            {{-- {{ method_field('PATCH') }} --}}

                            <span class="error_form"></span>

                            <div id="append_start_here" class="append_start_here">

                            </div>

                            <div class="modal-footer">
                                <a href="javascript:;" class="btn btn-outline-danger" data-dismiss="modal">CLOSE</a>
                                <button type="submit" id="btnUpdatePreview" class="btn btn-outline-info">UPDATE</button>
                            </div>
                        </form>
                        
                    </div>
                {{-- </div> --}}
            </div>
        </div>
    </div>
</div>