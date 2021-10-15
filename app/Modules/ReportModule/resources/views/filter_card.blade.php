<div class="panel panel-primary col-md-4">
    <div class="panel-heading">Select by Program</div>
    <div class="panel-body border">
        <div class="form-group">
          <label for=""></label>
          <select data-column="1" class="form-control filter-select" name="filter_program" id="filter_program">
                @foreach (session()->get('session_param') as $program)
                    @if (session()->get('region') != 13)
                        <option value="{{$program->description}}">{{$program->description}}</option>
                    @else
                        <option value="">-- Select Program --</option>
                        <option value="{{$program->description}}">{{$program->description}}</option>
                    @endif
                @endforeach
          </select>
        </div>
    </div>
</div>
<div class="panel panel-primary col-md-4">
    <div class="panel-heading">Select by Region</div>
    <div class="panel-body border">
        <div class="form-group">
          <label for=""></label>
          <select data-column="2" class="form-control filter-select" name="filter_region" id="filter_region">
                @foreach ($region as $r_name)
                    @if (session()->get('region') != 13)
                        <option value="{{$r_name}}">{{$r_name}}</option>
                    @else
                        <option value="">-- Select Region --</option>
                        <option value="{{$r_name}}">{{$r_name}}</option>
                    @endif     
                @endforeach
          </select>
        </div>
    </div>
</div>
<div class="panel panel-primary col-md-4">
    <div class="panel-heading">Select by Province</div>
    <div class="panel-body border">
        <div class="form-group">
            <label for=""></label>
            <select data-column="3" class="form-control filter-select" name="filter_province" id="filter_province">
                <option value="">-- Select Province --</option>
                @foreach ($province as $p_name)
                    <option value="{{$p_name}}">{{$p_name}}</option>
                @endforeach
            </select>
          </div>
    </div>
</div>