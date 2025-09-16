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

    #file-link {
      display: inline-block; 
      vertical-align: middle; 
      margin-left: 6px; /* kasih jarak dari teks CV */
      margin-top: 10px;
    }
    #file-link a i {
      font-size: 18px; /* biar pas ukurannya */
    }

    .card {
      border-radius: 12px;
    }
    .card h5 {
      margin-bottom: 8px;
    }


    .kanban-header {
        font-weight: bold;
        margin-bottom: 10px;
        padding: 8px 12px;
        border-radius: 6px;
        text-align: center;
    }

    /* mapping warna sesuai status */
    .kanban-header.not-started {
        background-color: #34495e; /* abu gelap */
        color: #fff;
    }
    .kanban-header.in-process {
        background-color: #f39c12; /* oranye */
        color: #000;
    }
    .kanban-header.hired {
        background-color: #27ae60; /* hijau */
        color: #fff;
    }
    .kanban-header.not-passed {
        background-color: #9b59b6; /* ungu */
        color: #fff;
    }
    .kanban-header.rejected {
        
        background-color: #e74c3c; /* merah */
        color: #fff;
    }


    .kanban-card {
        border: 1px solid #ddd;
        border-radius: 6px;
        background-color: #fff;
        color: #2c3e50; /* warna teks default lebih gelap */
    }

    .kanban-card .card-title {
        font-size: 14px;
        font-weight: 600;
        color: #2c3e50; /* nama kandidat lebih tegas */
    }

    .kanban-card small {
        /*display: block;
        color: #2c3e50;  
        font-size: 12px;
        margin-bottom: 2px;*/

        background-color: #8bdcf4; /* abu gelap */
        color: #0a0a0a;
    }



</style>