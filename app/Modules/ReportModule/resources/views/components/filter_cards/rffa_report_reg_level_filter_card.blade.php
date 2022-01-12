<div class="panel panel-primary col-md-6">
    <div class="panel-heading">
      FILTER BY REGION

      <button type="button" id="reset_reg_tbl_04" name="reset_reg_tbl_04" class="btn btn-xs btn-icon btn-circle btn-warning reset_reg_tbl_04"  title='Reset Region Filter' style="font-size= 12px; float: right;">
        <i class="fa fa-redo"></i>
      </button>
    </div>
    <div class="panel-body border">
        <div class="form-group">

          <label for=""></label>
          <select class="form-control table_04_filter_region" name="filter_region_tbl_04" id="table_04_filter_region" multiple>
            {{-- <option value="">-- All Region --</option> --}}
              @foreach ($region as $r)
                  <option value="{{$r->reg_name}}">{{$r->reg_name}}</option>
              @endforeach
          </select>
          
        </div>
    </div>
</div>