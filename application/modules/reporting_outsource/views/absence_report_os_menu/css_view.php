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

    /* BUNGKUS FILTER BIAR ENAK */
.filter-wrap {
  margin-top: 40px;
  margin-bottom: 10px;
}

/* Rapihin spacing form */
.filter-wrap .form-group {
  margin-bottom: 10px;
}

/* Biar label sejajar tengah */
.filter-wrap label {
  padding-top: 6px;
  font-weight: 600;
}

/* Jangan pakai margin negatif */
.filter-wrap [style*="margin-left"] {
  margin-left: 0 !important;
}

/* Button area */
.filter-actions {
  margin-top: 5px;
}

/* Responsif: mobile label jadi di atas input */
@media (max-width: 768px) {
  .filter-wrap .form-group.row {
    display: block;
  }
  .filter-wrap label {
    width: 100%;
    margin-bottom: 6px;
    padding-top: 0;
  }
  .filter-wrap .filter-actions {
    text-align: left !important;
  }
}

/* select2/chosen width full */
.filter-wrap select,
.filter-wrap .select2-container,
.filter-wrap .chosen-container {
  width: 100% !important;
}

    
</style>