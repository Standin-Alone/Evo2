<div class="panel panel-primary col-md-4">
    <div class="panel-heading">
        FILTER BY REGION
    
        <button type="button" id="reset_region_tbl_03" name="reset_region_tbl_03" class="btn btn-xs btn-icon btn-circle btn-warning reset_region_tbl_03"  title='Reset Region Filter' style="font-size= 12px; float: right;">
            <i class="fa fa-redo"></i>
        </button>
    </div>
    <div class="panel-body border">
        <div class="form-group">
          <label for=""></label>
          <select class="form-control table_03_filter_region" name="filter_region_tbl_03" id="table_03_filter_region" multiple>
            {{-- <option value="">-- All Region --</option> --}}
              @foreach ($region as $r)
                  <option value="{{$r->reg_name}}">{{$r->reg_name}}</option>
              @endforeach
          </select>
        </div>
    </div>
</div>

<div class="panel panel-primary col-md-4">
    <div class="panel-heading">
        FILTER BY PROVINCE

        <button type="button" id="reset_province_tbl_03" name="reset_province_tbl_03" class="btn btn-xs btn-icon btn-circle btn-warning reset_province_tbl_03"  title='Reset Region Filter' style="font-size= 12px; float: right;">
            <i class="fa fa-redo"></i>
        </button>
    </div>
    <div class="panel-body border">
        <div class="form-group">
            <label for=""></label>
            <select class="form-control table_03_filter_province" name="filter_province_tbl_03" id="table_03_filter_province" multiple>
                {{-- <option value="">-- All Province --</option> --}}
                @foreach ($province as $p)
                    <option value="{{$p->prov_name}}">{{$p->prov_name}}</option>
                @endforeach
            </select>
          </div>
    </div>
</div>

<div class="panel panel-primary col-md-4">
    <div class="panel-heading">
        FILTER BY MUNICIPALITY

        <button type="button" id="reset_municipality_tbl_03" name="reset_municipality_tbl_03" class="btn btn-xs btn-icon btn-circle btn-warning reset_municipality_tbl_03"  title='Reset Region Filter' style="font-size= 12px; float: right;">
            <i class="fa fa-redo"></i>
        </button>
    </div>
    <div class="panel-body border">
        <div class="form-group">
            <label for=""></label>
            <select class="form-control table_03_filter_municipality" name="filter_municipality_tbl_03" id="table_03_filter_municipality" multiple>
                {{-- <option value="">-- All Municipality --</option> --}}
                @foreach ($municipality as $m)
                    <option value="{{$m->mun_name}}">{{$m->mun_name}}</option>
                @endforeach
            </select>
          </div>
    </div>
</div>