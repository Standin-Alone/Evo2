@if (session()->get('region') != 13)
    <div class="panel panel-primary col-md-6">
        <div class="panel-heading">
            FILTER BY PROVINCE
            
            <button type="button" id="reset_prov_tbl_02" name="reset_prov_tbl_02" class="btn btn-xs btn-icon btn-circle btn-warning reset_prov_tbl_02"  title='Reset Province Filter' style="font-size= 12px; float: right;">
                <i class="fa fa-redo"></i>
            </button>
        </div>
        <div class="panel-body border">
            <div class="form-group">
                <label for=""></label>
                <select class="form-control table_02_filter_province" name="filter_province_tbl_02" id="table_02_filter_province" multiple>
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

        <button type="button" id="reset_reg_tbl_02" name="reset_reg_tbl_02" class="btn btn-xs btn-icon btn-circle btn-warning reset_reg_tbl_02"  title='Reset Region Filter' style="font-size= 12px; float: right;">
            <i class="fa fa-redo"></i>
        </button>
    </div>
    <div class="panel-body border">
        <div class="form-group">
        <label for=""></label>
        <select class="form-control table_02_filter_region" name="filter_region_tbl_02" id="table_02_filter_region" multiple>
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
        
        <button type="button" id="reset_prov_tbl_02" name="reset_prov_tbl_02" class="btn btn-xs btn-icon btn-circle btn-warning reset_prov_tbl_02"  title='Reset Province Filter' style="font-size= 12px; float: right;">
            <i class="fa fa-redo"></i>
        </button>
    </div>
    <div class="panel-body border">
        <div class="form-group">
            <label for=""></label>
            <select class="form-control table_02_filter_province" name="filter_province_tbl_02" id="table_02_filter_province" multiple>
                @foreach ($province as $p)
                    <option value="{{$p->prov_name}}">{{$p->prov_name}}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>  
@endif