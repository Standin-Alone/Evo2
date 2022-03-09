<div class="modal fade" id="update_main_branch_approval_status_modal">
    <div class="modal-dialog modal-lg">
        <form id="update_main_branch_approval_form" method="POST">
            {{ csrf_field() }}
            {{ method_field('PATCH') }}
            <span class="error_form"></span>
            <div class="modal-content">
                <div class="modal-header" style="background-color:#21a8dd;">
                    <h4 class="modal-title" style="color: white;">UPDATE STATUS <br> <span class="group_name" style="font-size: 14px;">GROUP NAME: <span> </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        style="color: white">×</button>
                </div>
                <div id="update_main_branch_modal_body" class="modal-body">
                    <div class="col-lg-12">

                        <div id="show_alert_with_apply_button_for_" class="show_alert_with_apply_button mt-3"></div>

                        <table id="preview_table" class="table table-bordered text-center" style="width:100%;">
                            <thead class="table-header">
                                <tr>
                                    <th style="color: white !important; width: 100px !important; font-size: 13px;"> STATUS </th>
                                    <th style="color: white !important; width: 100px !important; font-size: 13px;"> ACTIVE / INACTIVE </th>
                                    <th style="color: white !important; width: 100px !important; font-size: 13px;"> BLOCK / UN-BLOCK </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="color: white !important; width: 100px !important;"> 
                                        <div class="form-group">  
                                            <h4 class="approval_status_badge"></h4>
                                        </div> 
                                    </td>
                                    <td style="color: white !important; width: 100px !important;"> 
                                        <div class="form-group btn_toggle_active_and_inactive">  
                                            <input type="checkbox" id="main_branch_approve_and_disapprove" name="checkbox3" class="cm-toggle main_branch_approve_and_disapprove">
                                        </div> 
                                    </td>
                                    <td style="color: white !important; width: 100px !important;"> 
                                        <div class="form-group"> 
                                            <input type="checkbox" id="main_branch_onhold_and_unhold" name="checkbox4" class="cm-toggle2 main_branch_onhold_and_unhold" >
                                        </div> 
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-outline-danger" data-dismiss="modal">CLOSE</a>
                    {{-- <button type="submit" class="btn btn-outline-success">SAVE</button> --}}
                </div>
            </div>
        </form>
    </div>
</div>