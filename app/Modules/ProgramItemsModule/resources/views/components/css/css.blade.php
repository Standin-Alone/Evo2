<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css" />

{{-- datatable responsive --}}
    <link rel="stylesheet" href="//cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.css">
    <link rel="stylesheet" href="//cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    <link href="{{url('assets/pgv/backend-style.css')}}" rel="stylesheet">

{{-- datatable buttons --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.0/css/buttons.dataTables.min.css">

{{-- Select2 plugin --}}
    <link href="{{url('assets/plugins/select2/dist/css/select2.min.css')}}" rel="stylesheet" />
{{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/solid.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/svg-with-js.min.css" rel="stylesheet" /> --}}

<style>
    .swal2-title {
        font-size: 20px !important;
    }
    .swal2-button {
        font-size: 20px !important;
    }
    table.dataTable td {
        font-size: 13px !important;
    }
    table.dataTable th {
        font-size: 13px !important;
    }

    .table-header{
        background-color: #008a8a;
        /* font-size: 12px !important; */
    }
    
    table thead th{
        color: white !important;
    }

    .dt-button{
        background-color: #00c3ff !important;
        color: #fff !important;
        font-size: 12px !important;
        border-radius: 5px !important;
        padding-top: 5px !important;
        padding-bottom: 5px !important;
        padding-left: 20px !important;
        padding-right: 20px !important;
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

    @media screen and (max-width: 767px){
        div.dataTables_wrapper div.dataTables_length{
            text-align:left !important;
        }
    }

    @media screen and (max-width: 767px){
        div.dataTables_wrapper div.dataTables_filter{
            text-align:left !important;
        }
    }


    @import url('https://fonts.googleapis.com/css2?family=Rubik:wght@300&display=swap');
    .card {
        font-family: 'Rubik', sans-serif;
    }

    /* .main {
        display: none !important;
    } */

    /* .active {
        display: block !important;
    } */

    /* .price {
        height: 60px;
        width: 50px;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: -30px;
        background-color: #77b633;
        position: absolute;
        color: #fff;
    } */

    /* .card h4 {
        font-weight: bold;
    } */

    /* .input_text {
        margin-top: -10px;
    } */

    /* .input_text p:nth-child(1) {
        font-size: 13px;
    } */

    /* .input_text p:nth-child(2) {
        font-weight: 700;
        font-size: 13px;
    } */

    /* .buy button:hover {
        background-color: #558b1a !important;
    } */

    .profilepic {
        position: relative;
        width: 300px;
        height: 250px;
        border-radius: 5%;
        overflow: hidden;
        background-color: #111;
    }

    .profilepic:hover .profilepic__content {
        opacity: 1;
        cursor: pointer;
    }

    .profilepic:hover .profilepic__image {
        opacity: .5;
        cursor: pointer;
    }

    .profilepic__image {
        /* object-fit: cover; */
        width: 300px; 
        height: 250px;
        opacity: 1;
        transition: opacity .2s ease-in-out;
    }

    .profilepic:hover .update_profilepic__image {
        opacity: .5;
        cursor: pointer;
    }

    .update_profilepic__image {
        width: 300px; 
        height: 250px;
        opacity: 1;
        transition: opacity .2s ease-in-out;
    }

    .profilepic__content {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        color: white;
        opacity: 0;
        transition: opacity .2s ease-in-out;
    }

    .profilepic__icon {
        color: white;
        padding-bottom: 8px;
    }

    .fas {
        font-size: 20px;
    }

    .profilepic__text {
        text-transform: uppercase;
        font-size: 12px;
        width: 50%;
        text-align: center;
    }

    .profile-pic {
        max-width: 222px;
        max-height: 222px;
        margin-left: auto;
        margin-right: auto;
        display: block;
    }

    /* .form-control[readonly]{
            background: #ffffff !important;
            opacity: 6 !important;
            font-size: 13px !important;
            text-transform: uppercase !important;
            cursor: default !important;
    } */

    
</style>