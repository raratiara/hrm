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
            width: 200px ! important;
            /* lebar label lebih kecil di HP */
            margin: 0;
        }
    }


    #tblDetailAbsen {
        border-collapse: separate;
        border-spacing: 0;
        min-width: 1200px; /* biar bisa scroll horizontal */
    }

    /* =========================
       FREEZE HEADER
    ========================= */
    #tblDetailAbsen thead th {
        position: sticky;
        top: 0;
        /*background: #1f3c88;*/ /* sesuaikan warna header kamu */
        /*color: #fff;*/
        z-index: 5;
    }

    /* =========================
       FREEZE KOLOM 1 (NIK)
    ========================= */
    #tblDetailAbsen th:nth-child(1),
    #tblDetailAbsen td:nth-child(1) {
        position: sticky;
        left: 0;
        background: #fff;
        z-index: 4;
        width: 150px;
        min-width: 150px;
        max-width: 150px;
    }

    /* =========================
       FREEZE KOLOM 2 (Karyawan)
    ========================= */
    #tblDetailAbsen th:nth-child(2),
    #tblDetailAbsen td:nth-child(2) {
        position: sticky;
        left: 150px;   /* tetap 150 karena ini jarak dari kiri */
        background: #fff;
        z-index: 4;
        width: 200px;
        min-width: 200px;
        max-width: 200px;
    }

    /* Biar header kolom 1 & 2 tetap paling atas */
    #tblDetailAbsen thead th:nth-child(1),
    #tblDetailAbsen thead th:nth-child(2) {
        z-index: 6;
    }
    
</style>