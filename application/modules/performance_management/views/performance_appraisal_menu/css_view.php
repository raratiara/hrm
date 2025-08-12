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

  .row {
    display: flex;
    margin-bottom: 20px;

  }

  .select2-container {
    width: 200px !important;
  }

  .select2-container--bootstrap .select2-selection {
    border: 1px solid #b0b0b0ff !important;
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




  /* .accordion {

    background: linear-gradient(135deg, #07171f, #309dcd);
    color: white;
    cursor: pointer;
    padding: 16px 24px;
    width: 100%;
    border: none;
    outline: none;
    transition: background 0.3s ease, transform 0.2s ease;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    font-size: 18px;
    text-align: left;
    display: flex;
    justify-content: space-between;
    align-items: center;
  } */

  /* 
  .accordion:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
  }

  .accordion:after {
    content: '\25BC';
    font-size: 16px;
    transition: transform 0.3s ease;
  }

  .accordion.active:after {
    transform: rotate(180deg);
  } */



  .panel {
    max-height: none !important;
    overflow: visible !important;
    transition: none !important;
    background: white;
    padding: 0px 0px !important;
    display: block !important;
    margin-bottom: 0px !important;

  }

  .panel p {
    margin: 16px 0;
    color: #333;
  }

  .panel.show {
    max-height: 800px;
    padding: 16px 24px;
  }

  .summary {
    display: flex;
    gap: 20px;
    margin-top: -20px !important;
  }

  .summary span {
    color: #38406F;
  }

  .modal-dialog {
    width: 95% !important;
  }
</style>