<div class="panel panel-inverse">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i
                    class="fa fa-expand mt-1"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i
                    class="fa fa-redo mt-1"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i
                    class="fa fa-minus mt-1"></i></a>
        </div>
        <h4 class="panel-title">LIST OF CHECKLIST FOR ACCOUNT APPROVAL</h4>
    </div>
    <div class="panel-body">
        <br>
        <br>
        {{-- Account activation with program permission --}}
        <table id="" class="table table-bordered text-center" style="width:100%">
        <table id="account_activation_datatable" class="table table-bordered text-center" style="width:100%">
            <thead class="table-header">
                <tr>
                    <th>COMPANY NAME</th>
                    <th>COMPANY ADDRESS</th>
                    <th>PROGRAM</th>
                    <th>EMAIL</th>
                    <th>FULLNAME</th>
                    <th>CONTACT NO.</th>
                    <th>REGION</th>
                    <th>STATUS</th>
                    <th>ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                {{-- <tr>
                    <td style="font-size:12px;">BROWN STAR RICE</td>
                    <td style="font-size:12px;">#112 brown Bayan Quezon city</td>
                    <td style="font-size:12px;">Rice Resiliency Project 2</td>
                    <td style="font-size:12px;">anniemariane@gmail.com</td>
                    <td style="font-size:12px;">ANNIE MARIANE</td>
                    <td style="font-size:12px;">09169382460</td>
                    <td style="font-size:12px;">REGION I ILOCOS REGION</td>
                    <td><span class="badge" style="background-color: rgba(53, 175, 245, 0.17); color: #086ff5!important; font-size: 12px;"> <b>FOR CHECKING</b> </span></td>
                    <td style="font-size:12px;">
                        <a href="#" id="" type="button" class="btn btn-xs btn-outline-info">
                            <i class="fa fa-cog"></i> Update Checklist
                        </a>
                    </td>
                </tr>
                <tr>
                    <td style="font-size:12px;">CHICKEN MISMO BAYAN</td>
                    <td style="font-size:12px;">#119 itlogan Bayan Quezon city</td>
                    <td style="font-size:12px;">Rice Resiliency Project 2</td>
                    <td style="font-size:12px;">luzviminda@gmail.com</td>
                    <td style="font-size:12px;">MARIE LUZVIMINDA</td>
                    <td style="font-size:12px;">09169382460</td>
                    <td style="font-size:12px;">REGION I ILOCOS REGION</td>
                    <td><span class="badge" style="background-color: rgba(53, 175, 245, 0.17); color: #086ff5!important; font-size: 12px;"> <b>FOR CHECKING</b> </span></td>
                    <td style="font-size:12px;">
                        <a href="#" id="" type="button" class="btn btn-xs btn-outline-info">
                            <i class="fa fa-cog"></i> Update Checklist
                        </a>
                    </td>
                </tr>
                <tr>
                    <td style="font-size:12px;">COUNTRY BEEF</td>
                    <td style="font-size:12px;">#118 country chicken valenzuela city</td>
                    <td style="font-size:12px;">Rice Resiliency Project 2</td>
                    <td style="font-size:12px;">hannaco@gmail.com</td>
                    <td style="font-size:12px;">HANNAH CO</td>
                    <td style="font-size:12px;">09169382460</td>
                    <td style="font-size:12px;">REGION I ILOCOS REGION</td>
                    <td><span class="badge" style="background-color: rgba(53, 175, 245, 0.17); color: #086ff5!important; font-size: 12px;"> <b>FOR CHECKING</b> </span></td>
                    <td style="font-size:12px;">
                        <a href="#" id="" type="button" class="btn btn-xs btn-outline-info">
                            <i class="fa fa-cog"></i> Update Checklist
                        </a>
                    </td>
                </tr>
                <tr>
                    <td style="font-size:12px;">MOON STAR RICE</td>
                    <td style="font-size:12px;">#115 moon magic rice makati city</td>
                    <td style="font-size:12px;">Rice Resiliency Project 2</td>
                    <td style="font-size:12px;">willydomingo@gmail.com</td>
                    <td style="font-size:12px;">WILLY DOMINGO</td>
                    <td style="font-size:12px;">09169382460</td>
                    <td style="font-size:12px;">REGION I ILOCOS REGION</td>
                    <td><span class="badge" style="background-color: rgba(53, 175, 245, 0.17); color: #086ff5!important; font-size: 12px;"> <b>FOR CHECKING</b> </span></td>
                    <td style="font-size:12px;">
                        <a href="#" id="" type="button" class="btn btn-xs btn-outline-info">
                            <i class="fa fa-cog"></i> Update Checklist
                        </a>
                    </td>
                </tr> --}}
            </tbody>
            <tfoot>
            </tfoot>
        </table>

        {{-- Update modal --}}
        <div class="modal fade" id="update_user_status_modal">
            <div class="modal-dialog modal-lg">
                {{-- action="{{ route('rfo_approval_module.create_user_checklist_details') }}" --}}
                <form id="update_user_status_form" method="POST" action="">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}
                    <span class="error_form"></span>
                    <div class="modal-content">
                        <div class="modal-header" style="background-color:#21a8dd;">
                            <h4 class="modal-title" style="color: white;">UPDATE USER ACCOUNT STATUS <br> <span
                                    class="user_name" style="font-size: 14px;">Name: <span> </h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                                style="color: white">×</button>
                        </div>
                        <div id="update_user_modal_body" class="modal-body">
                            <div class="note note-warning">
                                <div class="note-icon"><i class="fas fa-lightbulb"></i></div>
                                <div class="note-content">
                                    <h4 style="margin-bottom: 0px; font-size: 14px;"> <i
                                            class="fas fa-star-of-life"></i> All the requirement listed below should be
                                        completed before the approval or activation of the account. </h4>
                                    <br>
                                    <h4 style="margin-bottom: 0px; font-size: 14px;"> <i
                                            class="fas fa-star-of-life"></i> However, the checklist may/can still be
                                        save even if the requirements are not yet completed. You may comeback and check
                                        the listed requirement you have left so that the account may proceed for
                                        activation. </h4>
                                </div>
                            </div>

                            <div class="col-lg-12">

                                {{-- Checklist --}}
                                <div class="inbox">
                                    {{-- Content of <div class="item"></div> --}}
                                </div>

                                {{-- input SRN --}}
                                {{-- <div class="srn_input_class mt-3 mb-4">
                                    <div class="srn_input_div">
                                        <div class="form-group row">
                                            <label class="col-md-3 text-md-right col-form-label">SRN: <span
                                                    class="text-danger">*</span> </label>
                                            <div class="col-md-7">
                                                <input id="srn" type="text" name="srn" placeholder="Enter SRN"
                                                    class="form-control" />
                                                <span class="error_msg"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}

                            </div>
                        </div>
                        <div class="modal-footer">
                            <a href="javascript:;" class="btn btn-outline-danger" data-dismiss="modal">CLOSE</a>
                            <button type="submit" id="btnSaveChecklist" class="btn btn-outline-success"
                                hidden>SAVE</button>

                            <button type="submit" id="btnActiveApprove" class="btn btn-outline-success"
                                hidden>ACTIVE</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Select Supplier Group Modal --}}
        {{-- <div class="modal fade" id="supplier_group_modal">
            <div class="modal-dialog modal-lg">

                <form id="supplier_group_modal_form" method="GET" action="">
                    {{ csrf_field() }}

                    <span class="error_form"></span>
                    <div class="modal-content">
                        <div class="modal-header" style="background-color:#21a8dd;">
                            <h4 class="modal-title" style="color: white;">Select Supplier Group</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                                style="color: white">×</button>
                        </div>
                        <div id="supplier_group_modal_body" class="modal-body">
                            <div class="col-lg-12">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Supplier Group: <span class="text-danger">*</span></label>
                                    <select id="select_supplier_group" class="form-control select_supplier_group" name="select_supplier_group">
                                        <option value="">-- Select --</option>
                                        @foreach ($supp_group as $sg)
                                            <option value="{{ $sg->supplier_group_id }}">{{ $sg->group_name }}</option>
                                        @endforeach  
                                    </select> 
                                    <span class="error_msg"></span>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <a href="javascript:;" class="btn btn-outline-danger" data-dismiss="modal">CLOSE</a>
                            <button type="submit" id="btnSaveSupplierGroup" class="btn btn-outline-success">SUBMIT</button>
                        </div>
                    </div>
                </form>
            </div>
        </div> --}}
    </div>
</div>
