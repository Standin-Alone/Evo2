<div class="modal fade" id="create_new_program_item_modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#6C9738;">
                <h4 class="modal-title" style="color: white;">CREATE NEW PROGRAM ITEM:</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                    style="color: white">×</button>
            </div>
            <div class="modal-body create_program_item_modal_body">
                <h4 class="mt-4" style="text-align:center">INTERVENTION ITEM</h4>

                <div class="input_div mt-4">
                    <form id="create_program_item" method="POST" class="margin-bottom-0" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <span class="error_form"></span>

                        <div class="row"> 
                            <div class="col-md-5">
                                {{-- Item Profile --}}
                                <div class="profilepic mx-auto d-block">
                                    <img id="previewImg" class="profilepic__image" src="{{ asset('public/images/img_upload_freepik.jpg') }}">
                                    <div class="profilepic__content upload-button">
                                        <span class="profilepic__icon"><i class="fa fa-camera fa-2x"></i></span>
                                        <span class="profilepic__text">Upload </span>
                                        <br /> Item Photo</span>
                                    </div>
                                    <input class="file-upload" name="item_profile" type="file" accept="image/png,image/gif,image/jpeg" />
                                    <span class="error_msg"></span>
                                </div>
                                {{-- <label class="form-label">Item Image: <span class="text-danger">*</span></label> --}}
                            </div>

                            {{-- Item Name --}}
                            <div class="col-md-7">
                                <h5 style="">ITEM DETAILS:</h5>

                                <div class="form-group">
                                    <label class="form-label">Item Name: <span class="text-danger">*</span></label>
                                    <input id="item_name" name="item_name" placeholder="Enter Item Name"
                                        class="form-control" type="text">
                                    <span class="error_msg"></span>
                                </div>

                                <div class="form-group">
                                    <label class="form-label"> Unit of Measure: 
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" id="unit_measure" name="unit_measure"
                                        placeholder="Enter Unit of Measurement" class="form-control">
                                    <span class="error_msg"></span>
                                </div>

                                {{-- <div class="form-group">
                                    <label class="form-label"> Ceiling Amount: 
                                        <span class="text-danger">*</span> 
                                    </label>
                                    <input type="text" id="ceiling_amount" name="ceiling_amount"
                                        placeholder="Enter Ceiling Amount" class="form-control">
                                    <span class="error_msg"></span>
                                </div> --}}

                                <div class="form-group">
                                    <label class="form-label">Region: <span class="text-danger">*</span></label>
                                    <select class="form-control selectProgramRegion" id="selectProgramRegion"
                                        name="selectProgramRegion">
                                        <option value="" selected>-- Select Region --</option>
                                        @foreach ($regions as $region)
                                            <option value="{{ $region->reg_code }}">{{ $region->reg_name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="error_msg"></span>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Province: <span class="text-danger">*</span></label>
                                    <select class="form-control selectProgramProvince" id="selectProgramProvince"
                                        name="selectProgramProvince">
                                        {{-- <option value="" selected>-- Select Province--</option> --}}
                                    </select>
                                    <span class="error_msg"></span>
                                </div>
                                
                            </div>
                        </div>

                        <div class="modal-footer">
                            <a href="javascript:;" class="btn btn-outline-danger" data-dismiss="modal">CLOSE</a>
                            <button type="submit" id="btnPreview" class="btn btn-outline-success">SAVE</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>