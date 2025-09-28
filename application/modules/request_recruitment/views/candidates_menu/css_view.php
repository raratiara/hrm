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

    .kanban-board {
        display: flex;
        gap: 16px;
        overflow-x: auto;
        overflow-y: hidden;   
        padding: 2px;
        width: 100%;
        box-sizing: border-box;
        margin-top: 10px;
    }
    
    .kanban-header {
        font-weight: 600;
        padding: 10px;
        /*padding: 8px 12px;*/
        border-bottom: 2px solid #eee;
        /*border-radius: 8px 8px 0 0;*/
        border-radius: 16px !important;
        font-size: 12px;
        margin-bottom: 8px;
        text-align: center;
        margin-top: 5px;
    }

    .kanban-items {
        flex: 1;
        overflow-y: auto;
        padding: 10px;
    }

    .kanban-items::-webkit-scrollbar {
        width: 6px;
    }
    .kanban-items::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 3px;
    }
    
    
    /*.kanban-header.not-started {
        background-color: #F0F3FA; 
        color: #000;
    }*/
    .kanban-header.hr-interview {
        background-color: #D5DEEF; 
        color: #34495e;
    }
    .kanban-header.user-interview {
        background-color: #B1C9EF; 
        color: #395886;
    }
    .kanban-header.technical-test {
        background-color: #8AAEE0; 
        color: #fff;
    }
    .kanban-header.psycho-test {
        background-color: #628ECB; 
        color: #fff;
    }
    .kanban-header.medical-check {
        background-color: #395886; 
        color: #fff;
    }
    .kanban-header.offering-letter {
        background-color: #34495e; 
        color: #fff;
    }
    .kanban-header.hired {
        background-color: #17A6A3; 
        color: #fff;
    }

    .kanban-header.done {
        background-color: #27ae60; 
        color: #fff;
    }

    .kanban-header.not-passed {
        background-color: #FBD160; 
        color: #fff;
    }
    .kanban-header.rejected {
        
        background-color: #ED6191; 
        color: #fff;
    }


    /*.kanban-card {
        border: 1px solid #ddd;
        border-radius: 6px;
        background-color: #fff;
        color: #2c3e50; 
        height: 150px;
    }*/

    .kanban-column {
        background: #fafafa;
        border-radius: 18px !important;
        min-width: 180px;
        flex: 0 0 180px;
        display: flex;
        flex-direction: column;
        max-height: 80vh;
    }

    .kanban-card {
        background: #fff;
        border-radius: 6px !important;
        padding: 8px 10px;
        margin-bottom: 10px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        font-size: 13px;
    }

    .kanban-card .card-body{
        margin-top: 5px;
        height: 140px !important;
    }


    .kanban-card .card-title {
        font-size: 14px;
        font-weight: bold;/*600;*/
        color: #2c3e50; 
        margin-top: 5px;
    }

    .kanban-card small {
       
        /*background-color: #8bdcf4; 
        color: #0a0a0a;*/
        font-size: 11px;
       
    }

    .truncate {
      display: inline-block;
      max-width: 110px;   /* atur sesuai kebutuhan */
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      vertical-align: middle;
    }


    .step-group {
        background: #f0f0f0;
        padding: 4px 6px;
        font-size: 12px;
        font-weight: bold;
        margin-top: 6px;
        border-radius: 4px;
    }


    .title-box-chart-hdr {
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
    }

    .top-bar {
        display: flex;
        gap: 5px;
        margin-top: 5px;
    }

    .select2-selection__placeholder {
        visibility: hidden;
        /* sembunyikan teks default */
        position: relative;
        border: none !important;
        background: #0000 !important;
    }

    #filter-division+.select2 .select2-selection__placeholder::after {
        content: "Select Division";
        /* teks baru */
        visibility: visible;
        position: absolute;
        left: 0;
        color: #999;
    }

    #filter-position+.select2 .select2-selection__placeholder::after {
        content: "Select Position";
        /* teks baru */
        visibility: visible;
        position: absolute;
        left: 0;
        color: #999;
    }


    .select2-container--bootstrap .select2-selection {
        border: none !important;
        box-shadow: none !important;
        background: transparent !important;
    }

    .select2-selection {
        width: 150px !important;
        font-size: 10px !important;
        margin-top: 8px !important;
    }

    .dropdown-select {
        border: none;
        outline: none;
        font-size: 14px;
        background: transparent;
        width: 100%;
        height: 100%;
        color: #333;
        z-index: 2;
        appearance: none;
        padding-right: 10px;
    }

      /* Optional: add custom arrow */
    .dropdown-select::after {
        content: "";
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
    }

    .employee-select-wrapper {
      position: relative;
      padding-left: 18px !important;
      width: 160px !important;
      width: 160px !important;
    }

    .employee-select-wrapper {
        position: relative;
        border: 1px solid #ccc;
        border-radius: 20px !important;
        background-color: #fff;
        display: flex;
        align-items: center;
        padding-left: 20px;
        height: 28px !important;

        padding-left: 20px;
        height: 28px !important;

    }

    .employee-icon {
        position: absolute;
        left: 10px;
        color: #666;
        font-size: 10px;
        font-size: 10px;
        pointer-events: none;
      }

    .view-toggle .btn {
        /*border-radius: 8px;*/
        margin-left: 5px;
        margin-top: 5px;
        border: 1px solid #ccc;
        border-radius: 20px !important;
    }

    .view-toggle .btn.active {
      background-color: #38406F;
      color: #fff;
    }


    .card-placeholder {
      border: 2px dashed #999;
      background: #f9f9f9;
      height: 60px;
      margin: 5px 0;
      border-radius: 5px;
    }



</style>