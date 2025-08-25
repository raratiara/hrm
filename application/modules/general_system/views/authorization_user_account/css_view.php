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
        width: 80% !important;
    }

    .row-flex {
        display: flex;
        margin: 0;
    }

    @media screen and (max-width: 768px) {
        .row-flex {
            display: flex;
            font-size: 12px !important;
            margin-bottom: 8px ! important;
            align-items: flex-start;
            /* biar kalau isi banyak baris tetap sejajar di atas */
            margin: 0;

        }



        .row-flex label {
            width: 100px;
            margin: 0;
        }

        .row-flex .col-md-9 span {
            word-break: break-all;
            /* kalau ada kata super panjang, tetap pecah */

        }
    }
    
</style>