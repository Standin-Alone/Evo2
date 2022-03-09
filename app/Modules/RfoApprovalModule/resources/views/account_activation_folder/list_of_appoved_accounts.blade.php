<div class="panel panel-inverse">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i
                    class="fa fa-expand mt-1"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i
                    class="fa fa-redo mt-1"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i
                    class="fa fa-minus mt-1"></i></a>
        </div>
        <img class="iconDetails" src="{{ url('/assets/img/images/number03.png') }}" alt=""> <h4 class="panel-title" style="margin-top: 2px;">LIST OF APPROVED ACCOUNTS</h4>
    </div>
    <div class="panel-body">
        <br>
        <br>
        <table id="list_of_approved_accounts_datatable" class="table table-bordered text-center" style="width:100%">
        {{-- <table id="" class="table table-bordered text-center" style="width:100%"> --}}
            <thead class="table-header">
                <tr>
                    <th>COMPANY NAME</th>
                    <th>COMPANY ADDRESS</th>
                    <th>PROGRAM</th>
                    <th>EMAIL</th>
                    <th>FULLNAME</th>
                    <th>CONTACT NO.</th>
                    <th>REGION</th>
                    <th>APPROVED BY FULLNAME</th>
                    <th>APPROVAL STATUS</th>
                </tr>
            </thead>
            <tbody>
                {{-- <tr>
                    <td style="font-size:12px;">GOLDEN STAR RICE</td>
                    <td style="font-size:12px;">#112 Bgiasang Bayan Quezon city</td>
                    <td style="font-size:12px;">Rice Resiliency Project 2</td>
                    <td style="font-size:12px;">luismanzano@gmail.com</td>
                    <td style="font-size:12px;">LUIS BORJA MANZANO</td>
                    <td style="font-size:12px;">09169382460</td>
                    <td style="font-size:12px;">REGION I ILOCOS REGION</td>
                    <td style="font-size:12px;">JOJO BARDON</td>
                    <td><span class="badge" style="background-color: rgba(81, 248, 137, 0.17); color: #1af55b!important; font-size: 12px;"> <b>ACTIVE</b> </span></td>
                </tr>
                <tr>
                    <td style="font-size:12px;">ITLOGAN BAYAN</td>
                    <td style="font-size:12px;">#119 itlogan Bayan Quezon city</td>
                    <td style="font-size:12px;">Rice Resiliency Project 2</td>
                    <td style="font-size:12px;">jamesrider@gmail.com</td>
                    <td style="font-size:12px;">JAMES RIDER</td>
                    <td style="font-size:12px;">09169382460</td>
                    <td style="font-size:12px;">REGION I ILOCOS REGION</td>
                    <td style="font-size:12px;">JOJO BARDON</td>
                    <td><span class="badge" style="background-color: rgba(81, 248, 137, 0.17); color: #1af55b!important; font-size: 12px;"> <b>ACTIVE</b> </span></td>
                </tr>
                <tr>
                    <td style="font-size:12px;">COUNTRY CHICKEN</td>
                    <td style="font-size:12px;">#118 country chicken valenzuela city</td>
                    <td style="font-size:12px;">Rice Resiliency Project 2</td>
                    <td style="font-size:12px;">kentleyong@gmail.com</td>
                    <td style="font-size:12px;">KENTLEY ONG</td>
                    <td style="font-size:12px;">09169382460</td>
                    <td style="font-size:12px;">REGION I ILOCOS REGION</td>
                    <td style="font-size:12px;">JOJO BARDON</td>
                    <td><span class="badge" style="background-color: rgba(81, 248, 137, 0.17); color: #1af55b!important; font-size: 12px;"> <b>ACTIVE</b> </span></td>
                </tr>
                <tr>
                    <td style="font-size:12px;">STAR MAGIC RICE</td>
                    <td style="font-size:12px;">#115 star magic rice makati city</td>
                    <td style="font-size:12px;">Rice Resiliency Project 2</td>
                    <td style="font-size:12px;">henrypoblacion@gmail.com</td>
                    <td style="font-size:12px;">HENRY POBLACION</td>
                    <td style="font-size:12px;">09169382460</td>
                    <td style="font-size:12px;">REGION I ILOCOS REGION</td>
                    <td style="font-size:12px;">JOJO BARDON</td>
                    <td><span class="badge" style="background-color: rgba(81, 248, 137, 0.17); color: #1af55b!important; font-size: 12px;"> <b>ACTIVE</b> </span></td>
                </tr> --}}
            </tbody>
            <tfoot>
            </tfoot>
        </table>
    </div>
</div>
