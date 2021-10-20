{{-- datatable row group--}}
<link rel="stylesheet" href="https://cdn.datatables.net/rowgroup/1.1.3/css/rowGroup.dataTables.min.css">

{{-- datatable buttons --}}
{{-- <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css"> --}}
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.0/css/buttons.dataTables.min.css">

{{-- <link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/3.3.3/css/fixedColumns.dataTables.min.css"> --}}

{{-- datatable responsive --}}
<link rel="stylesheet" href="//cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.css">
<link rel="stylesheet" href="//cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">

<style>
    table.dataTable td {
        font-size: 14px !important;
    }
    table.dataTable th {
        font-size: 14px !important;
    }

    .table-header{
        background-color: #008a8a;
    }
    
    table thead th{
        color: white !important;;
    }

    .dt-button{
        background-color: #00c3ff !important;
        color: #fff !important;
        font-size: 14px !important;
        border-radius: 5px !important;
        padding-top: 5px !important;
        padding-bottom: 5px !important;
        padding-left: 20px !important;
        padding-right: 20px !important;
        width: 107px;
        height: 32px;
    }

    .buttons-print{
        background-color: #12abda !important;
        color: #fff !important;
    }
    .buttons-excel{
        background-color: #0cb458 !important;
        color: #fff !important;
    }
    .buttons-csv{
        background-color: #0cb458 !important;
        color: #fff !important;
    }
    .buttons-pdf{
        background-color: #e42535 !important;
        color: #fff !important;
    }

    #co-program-focal-datatable-by-region_wrapper{
        margin-top: 30px !important;
    }

    #co-program-focal-datatable_wrapper{
        margin-top: 30px !important;
    }

    #co-program-focal-datatable-by-region-province-municipality-and-barangay_wrapper{
        margin-top: 30px !important;
    }

    #co-program-focal-report_approval_wrapper{
        margin-top: 30px !important;
    }

    /* MODIFY DATATABLE WRAPPER/MOBILE VIEW NAVAGATE ROW ICON */
    .dataTables_wrapper table.dataTable.dtr-inline.collapsed > tbody > tr > td:first-child::before{
        /* background: #008a8a !important; */
        background: #008a8a !important;
        border-radius: 10px !important;
        border: none !important;
        top: 18px !important;
        left: 5px !important;
        line-height: 16px !important;
        box-shadow: none !important;
        color: #fff !important;
        font-weight: 700 !important;
        height: 16px !important;
        width: 16px !important;
        text-align: center !important;
        text-indent: 0 !important;
        font-size: 14px !important;
    }
    
    .dataTables_wrapper table.dataTable.dtr-inline.collapsed>tbody>tr.parent>td:first-child:before, 
    .dataTables_wrapper table.dataTable.dtr-inline.collapsed>tbody>tr.parent>th:first-child:before{
        /* background: #008a8a !important; */
        background: #b31515 !important;
        border-radius: 10px !important;
        border: none !important;
        top: 18px !important;
        left: 5px !important;
        line-height: 16px !important;
        box-shadow: none !important;
        color: #fff !important;
        font-weight: 700 !important;
        height: 16px !important;
        width: 16px !important;
        text-align: center !important;
        text-indent: 0 !important;
        font-size: 14px !important;
    }
</style>