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

  .modal-content {
    background: #EAEDF5 !important;
    border: none !important;
  }


  #modal-rfu-data {
    padding-left: 600px
  }

  /*.accordion {
  background-color: #eee;
  color: #444;
  cursor: pointer;
  padding: 18px;
  width: 100%;
  border: none;
  text-align: left;
  outline: none;
  font-size: 15px;
  transition: 0.4s;
}

.active, .accordion:hover {
  background-color: #ccc; 
}

.panel {
  padding: 0 18px;
  display: none;
  background-color: white;
  overflow: hidden;
}*/


  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f4f4f4;
    padding: 50px;
  }

  .modal-body {
    background: #EAEDF5;
  }



  .modal-header {
    background: #343851 !important;
    color: #ffffff !important;
    font-family: 'Poppins', sans-serif;
  }

  .btn-addrow {
    background: #343851 !important;
    border-radius: 999px !important;
    color: #ffffff !important;
    padding: 8px 16px !important;

  }

  .tools {
    display: flex;
    justify-content: end;
    margin-bottom: 20px;
  }

  .thead-style {
    border-bottom: 1px solid #6B6B6B !important;
  }

  .select2-container {
    width: 200px !important;


  }

  .select2-container--bootstrap .select2-selection {
    border: none !important;
    box-shadow: none !important;
    background: white !important;
    border-radius: 999px !important;
  }

  .select2-selection__placeholder {
    visibility: hidden;
    /* sembunyikan teks default */
    position: relative;
    border: none !important;
    background: #0000 !important;
    font-size: 13px !important;
  }

  .select2-selection__placeholder::after {
    content: "Select Employee";
    /* teks baru */
    visibility: visible;
    position: absolute;
    left: 0;
    color: #38406F;
  }


  .accordion {
    background: #b6c5dfff;
    color: #404144;
    cursor: pointer;
    padding: 10px 20px;
    width: 100%;
    border: none;
    outline: none;
    transition: background 0.3s ease, transform 0.2s ease;
    border-radius: 999px !important;
    font-size: 14px;
    font-weight: 600;
    text-align: left;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
  }


  .accordion:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
  }

  .accordion:after {
    content: '\25BC';
    /* Down arrow */
    font-size: 16px;
    transition: transform 0.3s ease;
  }

  .accordion.active:after {
    transform: rotate(180deg);
  }


  .panel {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease;
    background: transparent !important;
    padding: 0 24px;
    border-radius: 0 0 10px 10px;
  }

  .panel p {
    margin: 16px 0;
    color: #333;
  }

  .panel.show {
    max-height: 800px;
    padding: 16px 0px;
  }

  .row {
    display: flex;
    padding: 0px 10px;
    margin-bottom: 8px;
    gap: 8px !important;
  }

  .modal-dialog {
    width: 95% !important;
  }

  @media (max-width: 768px) {
    label input[type="search"] {
      font-size: 12px;
      /* kecilkan font */
      padding: 3px 6px;
      /* kecilkan tinggi kotak */
      width: 80% !important;
    }

    label {
      font-size: 12px;
      /* kecilkan tulisan "Search" */
    }

    .dataTables_filter {
      margin-top: 0px !important;
      margin-bottom: 10px !important;
    }

    .modal-body {
      margin: 0 !important;
    }

    .row-flex {
      display: flex;
      flex-direction: column;
      margin-bottom: 30px ! important;
      margin-left: -15px ! important;
      gap: 15px !important;
    }



    .row-flex .col-md-9 {
      flex: 1;
    }

    .row-flex label {
      width: 120px;
      /* lebar label lebih kecil di HP */
      margin: 0;
    }

    .row {
      font-size: 12px !important;
    }

    .ca .col-md-12 {
      width: 100% !important;
    }


    #tblHardskill_plan th,
    #tblHardskill_plan td {
      display: table-cell !important;
      font-size: 12px !important;
      padding: 6px !important;
      white-space: nowrap !important;
      vertical-align: middle !important;
    }


  }

  .row-flex {
    display: flex;
    align-items: center;
    margin: 0;
  }
</style>