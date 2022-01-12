@if (session()->get('region') != 13)
    <div class="panel panel-primary col-md-6">
        <div class="panel-heading">
            FILTER BY PROVINCE

            <button type="button" id="reset_prov_tbl_05" name="reset_prov_tbl_05" class="btn btn-xs btn-icon btn-circle btn-warning reset_prov_tbl_05"  title='Reset Region Filter' style="font-size= 12px; float: right;">
                <i class="fa fa-redo"></i>
            </button>
        </div>
        <div class="panel-body border">
            <div class="form-group">
                <label for=""></label>
                <select class="form-control table_05_filter_province" name="filter_province_tbl_05" id="table_05_filter_province" multiple>
                    {{-- <option value="">-- All Province --</option> --}}
                    @foreach ($province as $p)
                        <option value="{{$p->prov_name}}">{{$p->prov_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div> 
@else
    <div class="panel panel-primary col-md-6">
        <div class="panel-heading">
            FILTER BY REGION

            <button type="button" id="reset_reg_tbl_05" name="reset_reg_tbl_05" class="btn btn-xs btn-icon btn-circle btn-warning reset_reg_tbl_05"  title='Reset Region Filter' style="font-size= 12px; float: right;">
                <i class="fa fa-redo"></i>
            </button>
        </div>
        <div class="panel-body border">
            <div class="form-group">
            <label for=""></label>
            <select class="form-control table_05_filter_region" name="filter_region_tbl_05" id="table_05_filter_region" multiple>
                {{-- <option value="">-- All Region --</option> --}}
                @foreach ($region as $r)
                    <option value="{{$r->reg_name}}">{{$r->reg_name}}</option>
                @endforeach
            </select>
            </div>
        </div>
    </div>

    <div class="panel panel-primary col-md-6">
        <div class="panel-heading">
            FILTER BY PROVINCE

            <button type="button" id="reset_prov_tbl_05" name="reset_prov_tbl_05" class="btn btn-xs btn-icon btn-circle btn-warning reset_prov_tbl_05"  title='Reset Region Filter' style="font-size= 12px; float: right;">
                <i class="fa fa-redo"></i>
            </button>
        </div>
        <div class="panel-body border">
            <div class="form-group">
                <label for=""></label>
                <select class="form-control table_05_filter_province" name="filter_province_tbl_05" id="table_05_filter_province" multiple>
                    {{-- <option value="">-- All Province --</option> --}}
                    @foreach ($province as $p)
                        <option value="{{$p->prov_name}}">{{$p->prov_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>  
@endif