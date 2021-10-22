<div class="panel panel-primary col-md-6">
    <div class="panel-heading">Select by Agency</div>
    <div class="panel-body border">
        <div class="form-group">
          <label for=""></label>
          <select data-column="1" class="form-control filter-select" name="filter_program" id="filter_program">
            <option value="">-- Select Agency --</option>
                @foreach ($agency as $a)
                    <option value="{{$a->agency_shortname}}">{{$a->agency_shortname}}</option>
                @endforeach
          </select>
        </div>
    </div>
</div>
<div class="panel panel-primary col-md-6">
    <div class="panel-heading">Select by Region</div>
    <div class="panel-body border">
        <div class="form-group">
          <label for=""></label>
          <select data-column="2" class="form-control filter-select" name="filter_region" id="filter_region">
              <option value="">-- Select Region --</option>
                @foreach ($region as $r)
                    <option value="{{$r->region}}">{{$r->region}}</option>
                @endforeach
          </select>
        </div>
    </div>
</div>