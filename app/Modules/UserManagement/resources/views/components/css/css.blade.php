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
        color: white !important;
    }

    #add-btn{
        display: none;
    }
    #submit-btn{
        font-size: 14px;
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