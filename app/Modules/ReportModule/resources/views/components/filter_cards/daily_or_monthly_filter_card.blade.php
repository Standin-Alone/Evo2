<div class="row">
  <div class="panel panel-primary col-md-6">
    <div class="panel-heading">Advance Date Ranges</div>
      <div class="panel-body border">
          <form class="form-horizontal form-bordered">
            <div class="form-group row">
              <div id="reportrange" class="pull-left mt-2 mb-2" data-route="{{route('reports.rffa_custom_range')}}" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; height: 100%; width: 100%">
                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                <span></span> <b class="caret"></b>
              </div>
            </div>
        </form>
      </div>
  </div>

  <div class="panel panel-primary col-md-6">
    <div class="panel-heading">Set by Monthly</div>
    <div class="panel-body border">
        <div class="form-group">
          <label for=""></label>
          <select data-column="0" class="form-control filter_month_or_daily" name="filter_month_or_daily" id="filter_month_or_daily">
                <option value="">-- Select Monthly --</option>
                <option value="monthly">Monthly</option>
          </select>
        </div>
    </div>
  </div>
</div>
