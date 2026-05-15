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

    #tblDetailSptOS input,
    #tblDetailSptOS select {
        width: 100% !important;
        min-width: 120px; /* biar gak kekecilan */
        box-sizing: border-box;
    }

    .sptint-list,
    .sptint-list-view,
    .sptos-list,
    .sptos-list-view {
        table-layout: fixed !important;
        min-width: 1680px !important;
        width: max-content !important;
    }

    .sptint-list th,
    .sptint-list td,
    .sptint-list-view th,
    .sptint-list-view td,
    .sptos-list th,
    .sptos-list td,
    .sptos-list-view th,
    .sptos-list-view td {
        width: 150px !important;
        min-width: 150px !important;
        max-width: 150px !important;
        white-space: nowrap !important;
        vertical-align: middle !important;
    }

    .sptint-list th:nth-child(2),
    .sptint-list td:nth-child(2),
    .sptint-list-view th:nth-child(3),
    .sptint-list-view td:nth-child(3),
    .sptos-list th:nth-child(2),
    .sptos-list td:nth-child(2),
    .sptos-list-view th:nth-child(3),
    .sptos-list-view td:nth-child(3) {
        width: 320px !important;
        min-width: 320px !important;
        max-width: 320px !important;
    }

    .sptint-list-view th:nth-child(1),
    .sptint-list-view td:nth-child(1),
    .sptos-list-view th:nth-child(1),
    .sptos-list-view td:nth-child(1) {
        width: 120px !important;
        min-width: 120px !important;
        max-width: 120px !important;
    }

    .sptint .tablesaw-cont,
    .sptintview .tablesaw-cont,
    .sptos .tablesaw-cont,
    .sptosview .tablesaw-cont {
        max-height: 48vh !important;
        overflow: auto !important;
        position: relative !important;
        padding-bottom: 16px !important;
    }

    .sptint-list thead th,
    .sptint-list-view thead th,
    .sptos-list thead th,
    .sptos-list-view thead th {
        position: sticky !important;
        top: 0 !important;
        background: #fff !important;
        z-index: 20 !important;
    }

    .sptint-list th:nth-child(1),
    .sptint-list td:nth-child(1),
    .sptos-list th:nth-child(1),
    .sptos-list td:nth-child(1) {
        position: sticky !important;
        left: 0 !important;
        background: #fff !important;
        z-index: 30 !important;
    }

    .sptint-list th:nth-child(2),
    .sptint-list td:nth-child(2),
    .sptos-list th:nth-child(2),
    .sptos-list td:nth-child(2) {
        position: sticky !important;
        left: 150px !important;
        background: #fff !important;
        z-index: 29 !important;
    }

    .sptint-list-view th:nth-child(1),
    .sptint-list-view td:nth-child(1),
    .sptos-list-view th:nth-child(1),
    .sptos-list-view td:nth-child(1) {
        position: sticky !important;
        left: 0 !important;
        background: #fff !important;
        z-index: 31 !important;
    }

    .sptint-list-view th:nth-child(2),
    .sptint-list-view td:nth-child(2),
    .sptos-list-view th:nth-child(2),
    .sptos-list-view td:nth-child(2) {
        position: sticky !important;
        left: 120px !important;
        background: #fff !important;
        z-index: 30 !important;
    }

    .sptint-list-view th:nth-child(3),
    .sptint-list-view td:nth-child(3),
    .sptos-list-view th:nth-child(3),
    .sptos-list-view td:nth-child(3) {
        position: sticky !important;
        left: 270px !important;
        background: #fff !important;
        z-index: 29 !important;
    }

    .sptint-list thead th:nth-child(-n+2),
    .sptos-list thead th:nth-child(-n+2),
    .sptint-list-view thead th:nth-child(-n+3),
    .sptos-list-view thead th:nth-child(-n+3) {
        z-index: 45 !important;
    }
    
</style>
