@php 

    header("Content-Type: application/xls");    
    header("Content-Disposition: attachment; filename=IMP-For Correction.xls");  
    header("Pragma: no-cache"); 
    header("Expires: 0");


@endphp

    <table>        
        <thead>
          <tr>
            <th>RSBSA_REFERENCE_NUMBER</th>
            <th>BENEFICIARY_NAME_1 (LAST NAME)</th>
            <th>BENEFICIARY_NAME_2 (FIRST NAME)</th>
            <th>BENEFICIARY_NAME_3 (MIDDLE INITIAL)</th>
            <th>BENEFICIARY_NAME_4 (SUFFIX)</th>
            <th>ADDRESS_1 BARANGAY</th>
            <th>ADDRESS_2 MUNICIPALITY</th>
            <th>ADDRESS_3 PROVINCE</th>
            <th>BIRTHDAY MM/DD/YYYY</th>
            <th>SEX MALE/FEMALE</th>
            <th>CONTACT_NUMBER  (9191234567)</th>
            <th>4Ps Beneficiary (YES/NO)</th>
            <th>Indigenous People (YES/NO)</th>
            <th>PWD (YES/NO)</th>
            <th>Farm Area (ha)</th>
            <th>SEED CLASS (Hybrid/Inbred)</th>
            <th>VOUCHER VALUE</th>
            <th>FUND SOURCE RFO _ - (GAA/SUPPLEMENTAL)</th>
            <th>SEASON DS/WS</th>
            <th>VOUCHER REMARKS</th>
          <tr>
        </thead>

        <tbody>

          @foreach($ref_search as $ref_search)
          <tr>
            <td>{{ $ref_search->rsbsa_no }}</td>
            <td>{{ $ref_search->last_name }}</td>
            <td>{{ $ref_search->first_name }}</td>
            <td>{{ $ref_search->middle_name }}</td>
            <td>{{ $ref_search->ext_name }}</td>
            <td>{{ $ref_search->brgy_desc }}</td>
            <td>{{ $ref_search->mun_desc }}</td>
            <td>{{ $ref_search->prv_desc }}</td>
            <td>{{ $ref_search->birthday }}</td>
            <td>{{ $ref_search->sex }}</td>
            <td>{{ $ref_search->contact_no }}</td>
            @if($ref_search->if_4ps =='1') 
              <td>YES</td>        
            @else
              <td>NO</td>   
            @endif
            @if($ref_search->if_ip =='1') 
              <td>YES</td>        
            @else
              <td>NO</td>   
            @endif
            @if($ref_search->if_pwd =='1') 
              <td>YES</td>        
            @else
              <td>NO</td>   
            @endif
            <td>{{ $ref_search->farm_area }}</td>

            @if($ref_search->seed_class =='1') 
              <td>HYBRID</td>        
            @else
              <td>INBRED</td>   
            @endif
            <td>{{ $ref_search->amount }}</td>
            <td>{{ $ref_search->fund_desc }}</td>
            <td>{{ $ref_search->voucher_season }}</td>
            <td>{{ $ref_search->voucher_remarks }}</td>
          
          @endforeach
        </tbody>
    </table>
