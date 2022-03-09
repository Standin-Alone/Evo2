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
    /* .panel-inverse>.panel-heading {
      background: #008a8a;
    }
     */
    .notification {
        /* background-color: #555; */
        /* color: white; */
        /* text-decoration: none; */
        /* padding: 15px 26px; */
        position: relative;
        display: inline-block;
        /* border-radius: 2px; */
    }

    /* .notification:hover {
        background: red;
    } */

    .notification .badge {
        position: absolute;
        top: -12px;
        right: -10px;
        padding: 5px 10px;
        border-radius: 50%;
        background-color: rgb(236, 81, 81);
        color: white;
    }
    .form-control[readonly]{
      background: rgb(255 255 255);
      opacity: 1;
    }
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
    }
    
    table thead th{
        color: white !important;;
    }

    .dt-button{
        background-color: #00c3ff !important;
        color: #fff !important;
        font-size: 13px !important;
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
    table.dataTable thead .sorting_asc:after {
        content: none !important;
        /* cursor: default !important; */

    }
    table.dataTable thead .sorting_asc{
        cursor: default !important;
    }

    @media (max-width: 480px){
        .note .note-icon, .note .note-icon i {
            position: relative !important;
            display: block !important;
            left: -85px !important;
            top: -8 !important;
            margin: 0 !important;
        }
    }


    @media (max-width: 480px){
        .note .note-icon+.note-content {
            margin-left: 0 !important;
            display: block !important;
            top: -65 !important;
            position: relative !important;
            /* font-size:15px !important; */
        }
        .note .note-icon+.note-content, h4 {
            font-size: 15px;
        }
    }

    @media (max-width: 480px){
        .note.note-primary{
            border-left-width: 60px !important;
            border-top-width: 20px !important;
            margin-bottom: 0 !important;
            /* width: 300px !important; */
            height: 85px !important;
        }
    }
    @media (max-width: 480px){
        .note.note-primary .note-icon {
            background: none !important;
        }
    }

    .select2-search__field{
        font-size: 14px !important;
        text-align: center !important;
    }

    .select2{
        border: 1px solid rgb(12, 134, 204) !important;
        /* border-radius:20px !important; */
    }

    @media (max-width: 480px){
        .select2 {
            width:100%!important;
            border: 1px solid rgb(12, 134, 204) !important;
        }
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

/* ==================================================================== */
.btn-toggle {
  margin: 0 4rem;
  padding: 0;
  position: relative;
  border: none;
  height: 1.5rem;
  width: 3rem;
  border-radius: 1.5rem;
  color: #6b7381;
  background: #c53d3d;
}
.btn-toggle:focus,
.btn-toggle.focus,
.btn-toggle:focus.active,
.btn-toggle.focus.active {
  outline: none;
}
.btn-toggle:before,
.btn-toggle:after {
  /* line-height: 1.5rem;
  width: 4rem;
  text-align: center;
  font-weight: 600;
  font-size: 0.8rem;
  text-transform: uppercase;
  letter-spacing: 2px;
  position: absolute;
  bottom: 0;
  transition: opacity 0.25s; */
}
/* .btn-toggle:before {
  content: 'INACTIVE';
  left: -4rem;
}
.btn-toggle:after {
  content: 'ACTIVE';
  right: -4rem;
  opacity: 0.5;
} */
/* .btn-toggle > .handle {
  position: absolute;
  top: 0.1875rem;
  left: 0.1875rem;
  width: 1.125rem;
  height: 1.125rem;
  border-radius: 1.125rem;
  background: #fff;
  transition: left 0.25s;
} */

.btn-toggle.active {
  transition: background-color 0.25s;
}

.btn-toggle.active > .handle {
  left: 1.6875rem;
  transition: left 0.25s;
}

.btn-toggle.active:before {
  opacity: 0.5;
}

.btn-toggle.active:after {
  opacity: 1;
}

.btn-toggle:before,
.btn-toggle:after {
  color: #6b7381;
}
.btn-toggle.active {
  background-color: #29b5a8;
}

#account_active_and_inactive.btn-sm:before,
#account_active_and_inactive.btn-sm:after {
  line-height: -0.5rem;
  color: #fff;
  letter-spacing: 0.75px;
  left: 0.4125rem;
  width: 2.325rem;
}
#account_active_and_inactive.btn-sm:before {
  text-align: right;
}
#account_active_and_inactive.btn-sm:after {
  text-align: left;
  opacity: 0;
}
#account_active_and_inactive.btn-sm.active:before {
  opacity: 0;
}
#account_active_and_inactive.btn-sm.active:after {
  opacity: 1;
}

#account_active_and_inactive.btn-sm {
  margin: 0 0.5rem;
  padding: 0;
  position: relative;
  border: none;
  height: 1.5rem;
  width: 4.5rem;
  border-radius: 1.5rem;
}

#account_active_and_inactive.btn-sm:focus,
#account_active_and_inactive.btn-sm.focus,
#account_active_and_inactive.btn-sm:focus.active,
#account_active_and_inactive.btn-sm.focus.active {
  outline: none;
}

#account_active_and_inactive.btn-sm:before,
#account_active_and_inactive.btn-sm:after {
  line-height: 1.5rem;
  width: 0.5rem;
  text-align: center;
  font-weight: 600;
  font-size: 0.55rem;
  text-transform: uppercase;
  letter-spacing: 2px;
  position: absolute;
  bottom: 0;
  transition: opacity 0.25s;
}

#account_active_and_inactive.btn-sm:before {
  content: 'INACTIVE';
  left: -0.5rem;
}

#account_active_and_inactive.btn-sm:after {
  content: 'ACTIVE';
  right: -0.5rem;
  opacity: 0.5;
}

#account_active_and_inactive.btn-sm > .handle {
  position: absolute;
  top: 0.1875rem;
  left: 0.1875rem;
  width: 1.125rem;
  height: 1.125rem;
  border-radius: 1.125rem;
  background: #fff;
  transition: left 0.25s;
}

#account_active_and_inactive.btn-sm.active {
  transition: background-color 0.25s;
}

#account_active_and_inactive.btn-sm.active > .handle {
  left: 3.2rem;
  transition: left 0.25s;
}

#account_active_and_inactive.btn-sm.active:before {
  opacity: 0.5;
}

#account_active_and_inactive.btn-sm.active:after {
  opacity: 1;
}

#account_active_and_inactive.btn-sm.btn-sm:before{
    line-height: -0.5rem;
    color: #fff;
    letter-spacing: 0.75px;
    left: 1.4125rem;
    width: 2.325rem;
}

#account_active_and_inactive.btn-sm.btn-sm:after {
  line-height: -0.5rem;
  color: #fff;
  letter-spacing: 0.75px;
  left: 0.4125rem;
  width: 2.325rem;
}

#account_active_and_inactive.btn-sm.btn-sm:before {
  text-align: right;
}

#account_active_and_inactive.btn-sm.btn-sm:after {
  text-align: left;
  opacity: 0;
}

#account_active_and_inactive.btn-sm.btn-sm.active:before {
  opacity: 0;
}

#account_active_and_inactive.btn-sm.btn-sm.active:after {
  opacity: 1;
}

/* ==================================================================== */
/* SWITCH TOGGLE - BLOCK and UNBLOCK */
#account_active_block_and_unblock.btn-sm {
  margin: 0 0.5rem;
  padding: 0;
  position: relative;
  border: none;
  height: 1.5rem;
  width: 4.5rem;
  border-radius: 1.5rem;
}

#account_active_block_and_unblock.btn-sm:focus,
#account_active_block_and_unblock.btn-sm.focus,
#account_active_block_and_unblock.btn-sm:focus.active,
#account_active_block_and_unblock.btn-sm.focus.active {
  outline: none;
}

#account_active_block_and_unblock.btn-sm:before,
#account_active_block_and_unblock.btn-sm:after {
  line-height: 1.5rem;
  width: 0.5rem;
  text-align: center;
  font-weight: 600;
  font-size: 0.55rem;
  text-transform: uppercase;
  letter-spacing: 2px;
  position: absolute;
  bottom: 0;
  transition: opacity 0.25s;
}

#account_active_block_and_unblock.btn-sm:before {
  content: 'UNBLOCK';
  left: -0.5rem;
}

#account_active_block_and_unblock.btn-sm:after {
  content: 'BLOCK';
  right: -0.5rem;
  opacity: 0.5;
}

#account_active_block_and_unblock.btn-sm > .handle {
  position: absolute;
  top: 0.1875rem;
  left: 0.1875rem;
  width: 1.125rem;
  height: 1.125rem;
  border-radius: 1.125rem;
  background: #fff;
  transition: left 0.25s;
}

#account_active_block_and_unblock.btn-sm.active {
  transition: background-color 0.25s;
}

#account_active_block_and_unblock.btn-sm.active > .handle {
  left: 3.2rem;
  transition: left 0.25s;
}

#account_active_block_and_unblock.btn-sm.active:before {
  opacity: 0.5;
}

#account_active_block_and_unblock.btn-sm.active:after {
  opacity: 1;
}

#account_active_block_and_unblock.btn-sm.btn-sm:before{
    line-height: -0.5rem;
    color: #fff;
    letter-spacing: 0.75px;
    left: 1.4125rem;
    width: 2.325rem;
}

#account_active_block_and_unblock.btn-sm.btn-sm:after {
  line-height: -0.5rem;
  color: #fff;
  letter-spacing: 0.75px;
  left: 0.4125rem;
  width: 2.325rem;
}

#account_active_block_and_unblock.btn-sm.btn-sm:before {
  text-align: right;
}

#account_active_block_and_unblock.btn-sm.btn-sm:after {
  text-align: left;
  opacity: 0;
}

#account_active_block_and_unblock.btn-sm.btn-sm.active:before {
  opacity: 0;
}

#account_active_block_and_unblock.btn-sm.btn-sm.active:after {
  opacity: 1;
}

/* Toggle Button */
.account_active_and_inactive {
	-webkit-appearance: none;
	-webkit-tap-highlight-color: transparent;
	position: relative;
	border: 0;
	outline: 0;
	cursor: pointer;
	margin: 10px;
}


/* To create surface of toggle button */
.account_active_and_inactive:after {
	content: '-- INACTIVE';
	width: 5.5rem;
	height: 1.5rem;
	display: inline-block;
	background: rgba(235, 11, 11, 0.55);
	border-radius: 18px;
	/* clear: both; */
    line-height: 1.5rem;
    text-align: center;
    font-weight: 700;
    font-size: 0.55rem;
    text-transform: uppercase;
    letter-spacing: 2px;
    color: white;

    transition: opacity 0.25s;
}


/* Contents before checkbox to create toggle handle */
.account_active_and_inactive:before {
	content: '';
	width: 1.125rem;
	height: 1.125rem;
	/* display: block; */
	position: absolute;
	left: 0.1875rem;
	top: 0.1875rem;
	border-radius: 50%;
	background: rgb(255, 255, 255);
	box-shadow: 1px 1px 3px rgba(0, 0, 0, 0.6);
}


/* Shift the handle to left on check event */
.account_active_and_inactive:checked:before {
	left: 68px;
	box-shadow: -1px 1px 3px rgba(0, 0, 0, 0.6);
}
/* Background color when toggle button will be active */
.account_active_and_inactive:checked:after {
    content: "ACTIVE";
	background: #16a085;
    line-height: 1.5rem;
    /* width: 0.5rem; */
    text-align: center;
    font-weight: 700;
    font-size: 0.55rem;
    text-transform: uppercase;
    letter-spacing: 2px;
    color: white;
    /* bottom: 0; */
    transition: opacity 0.25s;
}

/* #account_active_and_inactive.btn-sm:before,
#account_active_and_inactive.btn-sm:after {
  line-height: 1.5rem;
  width: 0.5rem;
  text-align: center;
  font-weight: 600;
  font-size: 0.55rem;
  text-transform: uppercase;
  letter-spacing: 2px;
  position: absolute;
  bottom: 0;
  transition: opacity 0.25s;
} */

/* Transition for smoothness */
.account_active_and_inactive,
.account_active_and_inactive:before,
.account_active_and_inactive:after,
.account_active_and_inactive:checked:before,
.account_active_and_inactive:checked:after {
	transition: ease .3s;
	-webkit-transition: ease .3s;
	-moz-transition: ease .3s;
	-o-transition: ease .3s;
}


/* To create surface of toggle button */
.account_active_and_inactive:after {
	content: '-- INACTIVE';
	width: 5.5rem;
	height: 1.5rem;
	display: inline-block;
	background: rgba(235, 11, 11, 0.55);
	border-radius: 18px;
	/* clear: both; */
    line-height: 1.5rem;
    text-align: center;
    font-weight: 700;
    font-size: 0.55rem;
    text-transform: uppercase;
    letter-spacing: 2px;
    color: white;

    transition: opacity 0.25s;
}


/* Contents before checkbox to create toggle handle */
.account_active_and_inactive:before {
	content: '';
	width: 1.125rem;
	height: 1.125rem;
	/* display: block; */
	position: absolute;
	left: 0.1875rem;
	top: 0.1875rem;
	border-radius: 50%;
	background: rgb(255, 255, 255);
	box-shadow: 1px 1px 3px rgba(0, 0, 0, 0.6);
}


/* Shift the handle to left on check event */
.account_active_and_inactive:checked:before {
	left: 68px;
	box-shadow: -1px 1px 3px rgba(0, 0, 0, 0.6);
}
/* Background color when toggle button will be active */
.account_active_and_inactive:checked:after {
    content: "ACTIVE";
	background: #16a085;
    line-height: 1.5rem;
    /* width: 0.5rem; */
    text-align: center;
    font-weight: 700;
    font-size: 0.55rem;
    text-transform: uppercase;
    letter-spacing: 2px;
    color: white;
    /* bottom: 0; */
    transition: opacity 0.25s;
}

/* #account_active_and_inactive.btn-sm:before,
#account_active_and_inactive.btn-sm:after {
  line-height: 1.5rem;
  width: 0.5rem;
  text-align: center;
  font-weight: 600;
  font-size: 0.55rem;
  text-transform: uppercase;
  letter-spacing: 2px;
  position: absolute;
  bottom: 0;
  transition: opacity 0.25s;
} */

/* Transition for smoothness */
.account_active_and_inactive,
.account_active_and_inactive:before,
.account_active_and_inactive:after,
.account_active_and_inactive:checked:before,
.account_active_and_inactive:checked:after {
	transition: ease .3s;
	-webkit-transition: ease .3s;
	-moz-transition: ease .3s;
	-o-transition: ease .3s;
}


/* ==================================================================== */

/* Toggle Button */
.account_active_block_and_unblock {
	-webkit-appearance: none;
	-webkit-tap-highlight-color: transparent;
	position: relative;
	border: 0;
	outline: 0;
	cursor: pointer;
	margin: 10px;
}


/* To create surface of toggle button */
.account_active_block_and_unblock:after {
	content: '-- INACTIVE';
	width: 5.5rem;
	height: 1.5rem;
	display: inline-block;
	background: rgba(235, 11, 11, 0.55);
	border-radius: 18px;
	/* clear: both; */
    line-height: 1.5rem;
    text-align: center;
    font-weight: 700;
    font-size: 0.55rem;
    text-transform: uppercase;
    letter-spacing: 2px;
    color: white;

    transition: opacity 0.25s;
}


/* Contents before checkbox to create toggle handle */
.account_active_block_and_unblock:before {
	content: '';
	width: 1.125rem;
	height: 1.125rem;
	/* display: block; */
	position: absolute;
	left: 0.1875rem;
	top: 0.1875rem;
	border-radius: 50%;
	background: rgb(255, 255, 255);
	box-shadow: 1px 1px 3px rgba(0, 0, 0, 0.6);
}


/* Shift the handle to left on check event */
.account_active_block_and_unblock:checked:before {
	left: 68px;
	box-shadow: -1px 1px 3px rgba(0, 0, 0, 0.6);
}
/* Background color when toggle button will be active */
.account_active_block_and_unblock:checked:after {
    content: "ACTIVE";
	background: #16a085;
    line-height: 1.5rem;
    /* width: 0.5rem; */
    text-align: center;
    font-weight: 700;
    font-size: 0.55rem;
    text-transform: uppercase;
    letter-spacing: 2px;
    color: white;
    /* bottom: 0; */
    transition: opacity 0.25s;
}

/* #account_active_and_inactive.btn-sm:before,
#account_active_and_inactive.btn-sm:after {
  line-height: 1.5rem;
  width: 0.5rem;
  text-align: center;
  font-weight: 600;
  font-size: 0.55rem;
  text-transform: uppercase;
  letter-spacing: 2px;
  position: absolute;
  bottom: 0;
  transition: opacity 0.25s;
} */

/* Transition for smoothness */
.account_active_block_and_unblock,
.account_active_block_and_unblock:before,
.account_active_block_and_unblock:after,
.account_active_block_and_unblock:checked:before,
.account_active_block_and_unblock:checked:after {
	transition: ease .3s;
	-webkit-transition: ease .3s;
	-moz-transition: ease .3s;
	-o-transition: ease .3s;
}


/* To create surface of toggle button */
.account_active_block_and_unblock:after {
	content: '-- UNBLOCK';
	width: 5.5rem;
	height: 1.5rem;
	display: inline-block;
	background: rgba(235, 11, 11, 0.55);
	border-radius: 18px;
	/* clear: both; */
    line-height: 1.5rem;
    text-align: center;
    font-weight: 700;
    font-size: 0.55rem;
    text-transform: uppercase;
    letter-spacing: 2px;
    color: white;

    transition: opacity 0.25s;
}


/* Contents before checkbox to create toggle handle */
.account_active_block_and_unblock:before {
	content: '';
	width: 1.125rem;
	height: 1.125rem;
	/* display: block; */
	position: absolute;
	left: 0.1875rem;
	top: 0.1875rem;
	border-radius: 50%;
	background: rgb(255, 255, 255);
	box-shadow: 1px 1px 3px rgba(0, 0, 0, 0.6);
}


/* Shift the handle to left on check event */
.account_active_block_and_unblock:checked:before {
	left: 68px;
	box-shadow: -1px 1px 3px rgba(0, 0, 0, 0.6);
}
/* Background color when toggle button will be active */
.account_active_block_and_unblock:checked:after {
    content: "BLOCK";
	background: #16a085;
    line-height: 1.5rem;
    /* width: 0.5rem; */
    text-align: center;
    font-weight: 700;
    font-size: 0.55rem;
    text-transform: uppercase;
    letter-spacing: 2px;
    color: white;
    /* bottom: 0; */
    transition: opacity 0.25s;
}

/* #account_active_and_inactive.btn-sm:before,
#account_active_and_inactive.btn-sm:after {
  line-height: 1.5rem;
  width: 0.5rem;
  text-align: center;
  font-weight: 600;
  font-size: 0.55rem;
  text-transform: uppercase;
  letter-spacing: 2px;
  position: absolute;
  bottom: 0;
  transition: opacity 0.25s;
} */

/* Transition for smoothness */
.account_active_block_and_unblock,
.account_active_block_and_unblock:before,
.account_active_block_and_unblock:after,
.account_active_block_and_unblock:checked:before,
.account_active_block_and_unblock:checked:after {
	transition: ease .3s;
	-webkit-transition: ease .3s;
	-moz-transition: ease .3s;
	-o-transition: ease .3s;
}

/* ==================================================================== */

/* Toggle Button */
.main_branch_approve_and_disapprove {
	-webkit-appearance: none;
	-webkit-tap-highlight-color: transparent;
	position: relative;
	border: 0;
	outline: 0;
	cursor: pointer;
	margin: 10px;
}


/* To create surface of toggle button */
.main_branch_approve_and_disapprove:after {
	content: '-- INACTIVE';
	width: 5.5rem;
	height: 1.5rem;
	display: inline-block;
	background: rgba(235, 11, 11, 0.55);
	border-radius: 18px;
	/* clear: both; */
    line-height: 1.5rem;
    text-align: center;
    font-weight: 700;
    font-size: 0.55rem;
    text-transform: uppercase;
    letter-spacing: 2px;
    color: white;

    transition: opacity 0.25s;
}


/* Contents before checkbox to create toggle handle */
.main_branch_approve_and_disapprove:before {
	content: '';
	width: 1.125rem;
	height: 1.125rem;
	/* display: block; */
	position: absolute;
	left: 0.1875rem;
	top: 0.1875rem;
	border-radius: 50%;
	background: rgb(255, 255, 255);
	box-shadow: 1px 1px 3px rgba(0, 0, 0, 0.6);
}


/* Shift the handle to left on check event */
.main_branch_approve_and_disapprove:checked:before {
	left: 68px;
	box-shadow: -1px 1px 3px rgba(0, 0, 0, 0.6);
}
/* Background color when toggle button will be active */
.main_branch_approve_and_disapprove:checked:after {
    content: "APPROVE";
	background: #16a085;
    line-height: 1.5rem;
    /* width: 0.5rem; */
    text-align: center;
    font-weight: 700;
    font-size: 0.55rem;
    text-transform: uppercase;
    letter-spacing: 2px;
    color: white;
    /* bottom: 0; */
    transition: opacity 0.25s;
}

/* #account_active_and_inactive.btn-sm:before,
#account_active_and_inactive.btn-sm:after {
  line-height: 1.5rem;
  width: 0.5rem;
  text-align: center;
  font-weight: 600;
  font-size: 0.55rem;
  text-transform: uppercase;
  letter-spacing: 2px;
  position: absolute;
  bottom: 0;
  transition: opacity 0.25s;
} */

/* Transition for smoothness */
.main_branch_approve_and_disapprove,
.main_branch_approve_and_disapprove:before,
.main_branch_approve_and_disapprove:after,
.main_branch_approve_and_disapprove:checked:before,
.main_branch_approve_and_disapprove:checked:after {
	transition: ease .3s;
	-webkit-transition: ease .3s;
	-moz-transition: ease .3s;
	-o-transition: ease .3s;
}


/* To create surface of toggle button */
.main_branch_approve_and_disapprove:after {
	content: '--INACTIVE';
	width: 5.5rem;
	height: 1.5rem;
	display: inline-block;
	background: rgba(235, 11, 11, 0.55);
	border-radius: 18px;
	/* clear: both; */
    line-height: 1.5rem;
    text-align: center;
    font-weight: 700;
    font-size: 0.55rem;
    text-transform: uppercase;
    letter-spacing: 2px;
    color: white;

    transition: opacity 0.25s;
}


/* Contents before checkbox to create toggle handle */
.main_branch_approve_and_disapprove:before {
	content: '';
	width: 1.125rem;
	height: 1.125rem;
	/* display: block; */
	position: absolute;
	left: 0.1875rem;
	top: 0.1875rem;
	border-radius: 50%;
	background: rgb(255, 255, 255);
	box-shadow: 1px 1px 3px rgba(0, 0, 0, 0.6);
}


/* Shift the handle to left on check event */
.main_branch_approve_and_disapprove:checked:before {
	left: 68px;
	box-shadow: -1px 1px 3px rgba(0, 0, 0, 0.6);
}
/* Background color when toggle button will be active */
.main_branch_approve_and_disapprove:checked:after {
    content: "ACTIVE";
	background: #16a085;
    line-height: 1.5rem;
    /* width: 0.5rem; */
    text-align: center;
    font-weight: 700;
    font-size: 0.55rem;
    text-transform: uppercase;
    letter-spacing: 2px;
    color: white;
    /* bottom: 0; */
    transition: opacity 0.25s;
}

/* #account_active_and_inactive.btn-sm:before,
#account_active_and_inactive.btn-sm:after {
  line-height: 1.5rem;
  width: 0.5rem;
  text-align: center;
  font-weight: 600;
  font-size: 0.55rem;
  text-transform: uppercase;
  letter-spacing: 2px;
  position: absolute;
  bottom: 0;
  transition: opacity 0.25s;
} */

/* Transition for smoothness */
.main_branch_approve_and_disapprove,
.main_branch_approve_and_disapprove:before,
.main_branch_approve_and_disapprove:after,
.main_branch_approve_and_disapprove:checked:before,
.main_branch_approve_and_disapprove:checked:after {
	transition: ease .3s;
	-webkit-transition: ease .3s;
	-moz-transition: ease .3s;
	-o-transition: ease .3s;
}


/* ==================================================================== */

/* Toggle Button */
.main_branch_onhold_and_unhold {
	-webkit-appearance: none;
	-webkit-tap-highlight-color: transparent;
	position: relative;
	border: 0;
	outline: 0;
	cursor: pointer;
	margin: 10px;
}


/* To create surface of toggle button */
.main_branch_onhold_and_unhold:after {
	content: '-- INACTIVE';
	width: 5.5rem;
	height: 1.5rem;
	display: inline-block;
	background: rgba(235, 11, 11, 0.55);
	border-radius: 18px;
	/* clear: both; */
    line-height: 1.5rem;
    text-align: center;
    font-weight: 700;
    font-size: 0.55rem;
    text-transform: uppercase;
    letter-spacing: 2px;
    color: white;

    transition: opacity 0.25s;
}


/* Contents before checkbox to create toggle handle */
.main_branch_onhold_and_unhold:before {
	content: '';
	width: 2.125rem;
	height: 1.125rem;
	/* display: block; */
	position: absolute;
	left: 0.1875rem;
	top: 0.1875rem;
	border-radius: 50%;
	background: rgb(255, 255, 255);
	box-shadow: 1px 1px 3px rgba(0, 0, 0, 0.6);
}


/* Shift the handle to left on check event */
.main_branch_onhold_and_unhold:checked:before {
	left: 68px;
	box-shadow: -1px 1px 3px rgba(0, 0, 0, 0.6);
}
/* Background color when toggle button will be active */
.main_branch_onhold_and_unhold:checked:after {
    content: "APPROVE";
	background: #16a085;
    line-height: 1.5rem;
    /* width: 0.5rem; */
    text-align: center;
    font-weight: 700;
    font-size: 0.55rem;
    text-transform: uppercase;
    letter-spacing: 2px;
    color: white;
    /* bottom: 0; */
    transition: opacity 0.25s;
}

/* #account_active_and_inactive.btn-sm:before,
#account_active_and_inactive.btn-sm:after {
  line-height: 1.5rem;
  width: 0.5rem;
  text-align: center;
  font-weight: 600;
  font-size: 0.55rem;
  text-transform: uppercase;
  letter-spacing: 2px;
  position: absolute;
  bottom: 0;
  transition: opacity 0.25s;
} */

/* Transition for smoothness */
.main_branch_onhold_and_unhold,
.main_branch_onhold_and_unhold:before,
.main_branch_onhold_and_unhold:after,
.main_branch_onhold_and_unhold:checked:before,
.main_branch_onhold_and_unhold:checked:after {
	transition: ease .3s;
	-webkit-transition: ease .3s;
	-moz-transition: ease .3s;
	-o-transition: ease .3s;
}


/* To create surface of toggle button */
.main_branch_onhold_and_unhold:after {
	content: '-- UNBLOCK';
	width: 5.5rem;
	height: 1.5rem;
	display: inline-block;
	background: rgba(235, 11, 11, 0.55);
	border-radius: 18px;
	/* clear: both; */
    line-height: 1.5rem;
    text-align: center;
    font-weight: 700;
    font-size: 0.55rem;
    text-transform: uppercase;
    letter-spacing: 2px;
    color: white;

    transition: opacity 0.25s;
}


/* Contents before checkbox to create toggle handle */
.main_branch_onhold_and_unhold:before {
	content: '';
	width: 1.125rem;
	height: 1.125rem;
	/* display: block; */
	position: absolute;
	left: 0.1875rem;
	top: 0.1875rem;
	border-radius: 50%;
	background: rgb(255, 255, 255);
	box-shadow: 1px 1px 3px rgba(0, 0, 0, 0.6);
}


/* Shift the handle to left on check event */
.main_branch_onhold_and_unhold:checked:before {
	left: 68px;
	box-shadow: -1px 1px 3px rgba(0, 0, 0, 0.6);
}
/* Background color when toggle button will be active */
.main_branch_onhold_and_unhold:checked:after {
    content: "BLOCK";
	background: #16a085;
    line-height: 1.5rem;
    /* width: 0.5rem; */
    text-align: center;
    font-weight: 700;
    font-size: 0.55rem;
    text-transform: uppercase;
    letter-spacing: 2px;
    color: white;
    /* bottom: 0; */
    transition: opacity 0.25s;
}

/* #account_active_and_inactive.btn-sm:before,
#account_active_and_inactive.btn-sm:after {
  line-height: 1.5rem;
  width: 0.5rem;
  text-align: center;
  font-weight: 600;
  font-size: 0.55rem;
  text-transform: uppercase;
  letter-spacing: 2px;
  position: absolute;
  bottom: 0;
  transition: opacity 0.25s;
} */

/* Transition for smoothness */
.main_branch_onhold_and_unhold,
.main_branch_onhold_and_unhold:before,
.main_branch_onhold_and_unhold:after,
.main_branch_onhold_and_unhold:checked:before,
.main_branch_onhold_and_unhold:checked:after {
	transition: ease .3s;
	-webkit-transition: ease .3s;
	-moz-transition: ease .3s;
	-o-transition: ease .3s;
}


/* checklist */
.inbox {
	max-width: 500px;
	margin: 50px auto;
	background: rgb(255, 252, 252);
	border-radius: 5px;
	/* box-shadow: 10px 10px 0 rgba(0, 0, 0, 0.1); */
  box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
}

.item {
	display: flex;
	align-items: center;
	border-bottom: 1px solid #838383;
}

.item:last-child {
	border-bottom: 0;
}

input:checked+p {
	background: #e2f7ec;
	text-decoration: line-through;
}

input[type="checkbox"] {
	margin: 20px;
}

p {
	margin: 0;
	padding: 20px;
	transition: background 0.2s;
	flex: 1;
	font-family: 'helvetica neue';
	font-size: 14px;
	font-weight: 200;
	border-left: 1px solid #D1E2FF;
}






img {
  max-width: 100%;
  display: block;
  vertical-align: middle;
}

.cards_link_custom, .cards_link_custom:hover, .cards_link_custom:focus, .cards_link_custom:active{
	text-decoration: none; /* remove underline */
	color: inherit; /* remove blue */
}

.rfo_approval_module_card {
  margin: 0 auto; /* Added */
  float: none; /* Added */
  margin-bottom: 10px; /* Added */

  box-shadow: 0 3px 10px 0 #aaa;
  cursor: pointer;
  height: 350px;
  position: relative;
  width: 320px;
}

.rfo_approval_module_card h2 {
  font-size: 20px;
  font-weight: bold;
}

/* .card.visited {
  box-shadow: 0 3px 10px 2px #444;
} */

@media (max-width: 1100px) {
  /* .cards {
    grid-template-columns: 1fr 1fr;
    width: calc(280px * 2);
  } */
  .rfo_approval_module_card {
    margin: 0 auto 2rem;
  }
}

@media (max-width: 768px) {
  /* .cards {
    display: block;
    width: 100vw;
  } */
  .rfo_approval_module_card {
    margin: 0 auto 2rem;
  }
}

/* .cards:visited {
    box-shadow: 0 3px 10px 2px #444;
    transform: scale(1.1)
} */

/* Hover effect on Cards */
.rfo_approval_module_card {
	/* margin: 0px 0px 35px 0px; */
	/* padding: 0 0 15px 0px; */
	border-radius: 5px;
	overflow: hidden;
	min-height: 390px;
	background: #fff;
	-moz-transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
	-o-transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
	transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
}

.rfo_approval_module_card:hover {
	-webkit-transform: translate(0, -8px);
	-moz-transform: translate(0, -8px);
	-ms-transform: translate(0, -8px);
	-o-transform: translate(0, -8px);
	transform: translate(0, -8px);
	box-shadow: 0 10px 30px rgb(113, 180, 235);
}

.also_view_card_row{
  margin-top: 20px;
  margin-left: 0.5px;
}

.also_view_card{
    /* margin: 0 auto; */
    float: none;
    margin-left: 30px;
    margin-bottom: 10px;
    box-shadow: 0 3px 10px 0 #aaa;
    cursor: pointer;
    height: 350px;
    position: relative;
    width: 320px;
}

/* Hover effect on Cards */
.also_view_card {
	/* margin: 0px 0px 35px 0px; */
	/* padding: 0 0 15px 0px; */
	border-radius: 5px;
	overflow: hidden;
	min-height: 390px;
	background: #fff;
	-moz-transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
	-o-transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
	transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
}

.also_view_card:hover {
	-webkit-transform: translate(0, -8px);
	-moz-transform: translate(0, -8px);
	-ms-transform: translate(0, -8px);
	-o-transform: translate(0, -8px);
	transform: translate(0, -8px);
	box-shadow: 0 10px 30px rgb(113, 180, 235);
}

@media (max-width: 1100px) {
  .also_view_card {
    /* margin: 0 auto 2rem; */
    margin-top: 10px;
  }
}

@media (max-width: 768px) {
  .also_view_card {
    margin: 0 auto 2rem;
  }
}


  .iconDetails {
    margin-right:1%;
    float:left; 
  } 
</style>