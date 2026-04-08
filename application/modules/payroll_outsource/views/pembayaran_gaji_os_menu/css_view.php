<style type="text/css">
    [class*="col-"] .chosen-container {
        width: 98% !important;
    }

    [class*="col-"] .chosen-container .chosen-search input[type="text"] {
        padding: 2px 4% !important;
        width: 90% !important;
        margin: 5px 2%;
    }

    [class*="col-"] .chosen-container .chosen-drop {
        width: 100% !important;
    }

    .modal-dialog {
        width: 90% !important;
       
    }

    #modal-view-data .modal-dialog {
        width: 90% !important;
        max-width: 900px !important; /* atur ukuran modal */
        margin: auto;
    }


    .row-flex {
        display: flex;
        margin: 0;
    }

    @media screen and (max-width: 768px) {

        .row-flex {
            font-size: 12px !important;
            display: flex;
            margin-bottom: 8px ! important;
            align-items: flex-start;
        }

        .row-flex label {
            width: 150px ! important;
            /* lebar label lebih kecil di HP */
            margin: 0;
        }
    }



    /* ============================= */
    /* SET WIDTH KOLOM (WAJIB) */
    /* ============================= */
    #tblDetailAbsenOSGaji th:nth-child(1),
    #tblDetailAbsenOSGaji td:nth-child(1) {
        width: 150px;
        min-width: 150px;
    }

    #tblDetailAbsenOSGaji th:nth-child(2),
    #tblDetailAbsenOSGaji td:nth-child(2) {
        width: 200px;
        min-width: 200px;
    }

    /* ============================= */
    /* FREEZE BODY */
    /* ============================= */
    #tblDetailAbsenOSGaji td:nth-child(1) {
        position: sticky !important;
        left: 0 !important;
        background: #fff;
        z-index: 20;
    }

    #tblDetailAbsenOSGaji td:nth-child(2) {
        position: sticky !important;
        left: 150px !important;
        background: #fff;
        z-index: 19;
    }

    /* ============================= */
    /* FREEZE TITLE (HEADER) */
    /* ============================= */
    #tblDetailAbsenOSGaji thead th:nth-child(1) {
        position: sticky !important;
        top: 0 !important;
        left: 0 !important;
        background: #f0f0f0;
        z-index: 50 !important;
    }

    #tblDetailAbsenOSGaji thead th:nth-child(2) {
        position: sticky !important;
        top: 0 !important;
        left: 150px !important;
        background: #f0f0f0;
        z-index: 49 !important;
    }


    #tblDetailAbsenOSGaji {
        table-layout: fixed !important;
        width: 100% !important;
    }


    #tblDetailAbsenOSGaji th,
    #tblDetailAbsenOSGaji td {
        width: 150px !important;
        min-width: 150px !important;
        max-width: 150px !important;

        height: 30px !important;
        line-height: 30px !important;
        padding: 0 6px !important;

        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
    }

    /* JARAK ANTAR ROW (TR) */
    #tblDetailAbsenOSGaji tbody tr {
        height: 42px !important;          /* jarak vertikal antar row */
    }


    #tblDetailAbsenOSGaji td {
        padding-top: 6px !important;
        padding-bottom: 6px !important;
        line-height: 1.4 !important;      /* jangan terlalu rapet */
    }


    .table-scroll-x {
        padding-bottom: 25px !important;  /* jarak ke scrollbar */
    }


    .table-scrollable {
        margin-bottom: 15px !important;
    }

    
</style>