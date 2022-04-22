
<table>        
    <tr style=''><td style='height:0px; width:580px; padding:0px;  padding-bottom:0px;' >
    <img src="{{url('assets/img/images/voucher-nrp.jpg')}}" height='220px' width='600px' style='position:absolute; z-index: 1; border: .5px solid black;' />
    <div style='position:relative; top:93px; text-align:center; left:-267px; z-index: 10; font-family: Arial;font-weight:700;font-size: 22pt; -ms-transform: rotate(90deg); transform: rotate(90deg); color:#ffffff;'><b>{{$amount}}<b></div><br> 
    <span style='position:relative; z-index: 999; font-family: Arial; font-size:8pt; top:5px; left:250px;'><b>{{$name_l}} {{$name_ext}} {{$name_f}} {{$name_m}}<b></span><br> 
    <span style='position:relative; z-index: 999; font-family: Arial;font-size:8pt; top:15px; left:250px;'><b>NRP FERTILIZER VOUCHER<b></span><br> 
    <span style='position:relative; z-index: 999; font-family: Arial;font-size:8pt; top:25px; left:250px;'><b>Date<b></span><br>
    <span style='position:relative; z-index: 999; font-family: Arial;font-size:8pt; top:36px; left:250px;'><b>RFO12<b></span><br>
    <span><img style='position:relative; z-index: 999; top:-85px; left:464;'  src='https://chart.googleapis.com/chart?chs=177x177&cht=qr&chl={{$ref_no}}' title='Employee Voucher QR' width='120px;'/></span><br>
    <span style='position:relative; z-index: 999; font-family: Arial; font-size:8pt; top:-95px; left:485;'><b>{{$ref_no}}<b>
    </tr>
</table>


<script type="text/javascript">
      window.onload = function() { window.print(); }
 </script>