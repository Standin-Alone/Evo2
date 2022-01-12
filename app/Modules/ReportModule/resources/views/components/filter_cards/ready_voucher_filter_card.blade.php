<div class="panel panel-primary col-md-6">
    <div class="panel-heading">Filter by Region</div>
    <div class="panel-body border">
        <div class="form-group">
          <label for=""></label>
          <select data-column="0" class="form-control filter-select" name="filter_region" id="filter_region">
            <option value="">-- All Region --</option>
              @foreach ($region as $r)
                  <option value="{{$r->reg_name}}">{{$r->reg_name}}</option>
              @endforeach
          </select>
        </div>
    </div>
</div>
<div class="panel panel-primary col-md-6">
    <div class="panel-heading">Filter by Province</div>
    <div class="panel-body border">
        <div class="form-group">
            <label for=""></label>
            <select data-column="1" class="form-control filter-select" name="filter_province" id="filter_province">
                <option value="">-- All Province --</option>
                @foreach ($province as $p)
                    <option value="{{$p->prov_name}}">{{$p->prov_name}}</option>
                @endforeach
            </select>
          </div>
    </div>
</div>