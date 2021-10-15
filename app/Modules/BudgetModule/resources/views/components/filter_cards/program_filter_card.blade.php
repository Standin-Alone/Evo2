<div class="panel panel-primary col-md-4">
    <div class="panel-heading">Select by Program</div>
    <div class="panel-body border">
        <div class="form-group">
          <label for=""></label>
          <select data-column="0" class="form-control filter_program" name="filter_program" id="filter_program">
                <option value="">-- Select Program --</option>
                @foreach ($programs as $program)
                    <option value="{{$program->title}}">{{$program->title}}</option>
                @endforeach
          </select>
        </div>
    </div>
</div>