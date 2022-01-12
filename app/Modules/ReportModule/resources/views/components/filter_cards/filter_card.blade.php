<div class="panel panel-primary col-md-4">
    <div class="panel-heading">Filter by Program</div>
    <div class="panel-body border">
        <div class="form-group">
          <label for=""></label>
          <select data-column="1" class="form-control filter-select" name="filter_program" id="filter_program">
            <option value="">-- Select Program --</option>
                @foreach ($programs as $program)
                    <option value="{{$program->description}}">{{$program->description}}</option>
                @endforeach
          </select>
        </div>
    </div>
</div>
<div class="panel panel-primary col-md-4">
    <div class="panel-heading">Filter by Region</div>
    <div class="panel-body border">
        <div class="form-group">
          <label for=""></label>
          <select data-column="2" class="form-control filter-select" name="filter_region" id="filter_region">
              <option value="">-- All Region --</option>
                @foreach ($region as $r)
                    <option value="{{$r->reg_name}}">{{$r->reg_name}}</option>
                @endforeach
          </select>
        </div>
    </div>
</div>
<div class="panel panel-primary col-md-4">
    <div class="panel-heading">Filter by Province</div>
    <div class="panel-body border">
        <div class="form-group">
            <label for=""></label>
            <select data-column="3" class="form-control filter-select" name="filter_province" id="filter_province">
                <option value="">-- All Province --</option>
                @foreach ($province as $p)
                    <option value="{{$p->prov_name}}">{{$p->prov_name}}</option>
                @endforeach
            </select>
          </div>
    </div>
</div>