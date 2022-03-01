<div class="panel panel-inverse">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            {{-- <a href="#" id="" type="button" class="btn btn-xs btn-info">
                <i class="fa fa-cog"></i> Setup Program --}}
            </a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i
                    class="fa fa-expand mt-1"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i
                    class="fa fa-redo mt-1"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i
                    class="fa fa-minus mt-1"></i></a>
        </div>
        <h4 class="panel-title">SETUP PROGRAM FOR USER</h4>
    </div>
    <div class="panel-body">
        <br>
        <br>
        {{-- <table id="" class="table table-bordered text-center" style="width:100%"> --}}
        <table id="user_account_datatable" class="table table-bordered text-center" style="width:100%">
            <thead class="table-header">
                <tr>
                    <th>COMPANY NAME</th>
                    <th>COMPANY ADDRESS</th>
                    <th>EMAIL</th>
                    <th>FULLNAME</th>
                    <th>CONTACT NO.</th>
                    <th>REGION</th>
                    {{-- <th>STATUS</th> --}}
                    <th>ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                {{-- <tr>
                    <td style="font-size:12px;">CURRY STAR RICE</td>
                    <td style="font-size:12px;">#112 brown Bayan Quezon city</td>
                    <td style="font-size:12px;">josephinemariane@gmail.com</td>
                    <td style="font-size:12px;">JOSEPHINE MARIANE</td>
                    <td style="font-size:12px;">09169382460</td>
                    <td style="font-size:12px;">REGION I ILOCOS REGION</td>
                    <td style="font-size:12px;">
                        <a href="#" id="" type="button" class="btn btn-xs btn-outline-info">
                            <i class="fa fa-cog"></i> Setup Program
                        </a>
                    </td>
                </tr>
                <tr>
                    <td style="font-size:12px;">ACER MISMO BAYAN</td>
                    <td style="font-size:12px;">#119 itlogan Bayan Quezon city</td>
                    <td style="font-size:12px;">arnoldsebastian@gmail.com</td>
                    <td style="font-size:12px;">ARNOLD SEBASTIAN</td>
                    <td style="font-size:12px;">09169382460</td>
                    <td style="font-size:12px;">REGION I ILOCOS REGION</td>
                    <td style="font-size:12px;">
                        <a href="#" id="" type="button" class="btn btn-xs btn-outline-info">
                            <i class="fa fa-cog"></i> Setup Program
                        </a>
                    </td>
                </tr>
                <tr>
                    <td style="font-size:12px;">SAMSUNG BEEF</td>
                    <td style="font-size:12px;">#118 country chicken valenzuela city</td>
                    <td style="font-size:12px;">jomelcruz@gmail.com</td>
                    <td style="font-size:12px;">JOMEL CRUZ</td>
                    <td style="font-size:12px;">09169382460</td>
                    <td style="font-size:12px;">REGION I ILOCOS REGION</td>
                    <td style="font-size:12px;">
                        <a href="#" id="" type="button" class="btn btn-xs btn-outline-info">
                            <i class="fa fa-cog"></i> Setup Program
                        </a>
                    </td>
                </tr> --}}
            </tbody>
            <tfoot>
            </tfoot>
        </table>

        <!-- #modal-add -->
        <div class="modal fade" id="add_role_and_program">
            <div class="modal-dialog modal-lg">
                <form id="add_role_and_program_form" method="POST" route="">
                    {{ csrf_field() }}
                    
                    <span class="error_form"></span>
                    <div class="modal-content">
                        <div class="modal-header" style="background-color:#6C9738;">
                            <h4 class="modal-title" style="color: white;">SETUP PROGRAM: 
                            <br> 
                            <span class="user_name_value_on_setup" style="font-size: 14px;">Name: <span></h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                        </div>
                        <div class="modal-body">
                            {{--modal body start--}}
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>PROGRAM <span class="text-danger">*</span></label>
                                    <select class="form-control" name="select_program" id="select_program">
                                        <option value="">-- Select program --</option>
                                        @foreach ($programs as $p)
                                            <option value="{{$p->program_id}}">{{$p->title}}</option>
                                        @endforeach
                                    </select>
                                    <span class="error_msg"></span>
                                </div>
                            </div>
                            {{--modal body end--}}
                        </div>
                        <div class="modal-footer">
                            <a href="javascript:;" class="btn btn-outline-danger" data-dismiss="modal">CLOSE</a>
                            <button type="submit" id="setup_role_and_program_btn" class="btn btn-outline-success">SAVE</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>