<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

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

  .select2-container {
    width: 200px !important;

  }

  .select2-dropdown {
    width: 200px !important;
  }




  /* Select2 container menyesuaikan wrapper */


  /* Override placeholder text */
  .select2-selection__placeholder {
    visibility: hidden;
    /* sembunyikan teks default */
    position: relative;
    border: none !important;
    background: #0000 !important;
  }

  .select2-selection__placeholder::after {
    content: "Select Employee";
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



  /*#fldashemp {
    width: 120px;
    height: 20px;
    font-size: 12px;
  }*/




  .dashboard-container {
    font-family: 'Poppins', sans-serif;
    width: 100% !important;
    margin: auto;
    display: flex;
    flex-direction: column;
    gap: 20px;
  }

  h2 {
    text-align: left;
    font-size: 16px;
    margin-bottom: 20px;
    color: #333;
  }


  #canvas_steps {
    width: 350px;
    height: 250px;
  }

  #canvas_heart_rate {
    width: 350px;
    height: 250px;
  }

  #canvas_vitalsigns {
    width: 300px;
    height: 200px;
  }

  

  .dashboard {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    /*gap: 10px;*/
    column-gap: 10px;
    /* jarak kanan kiri antar box */
    row-gap: 10px;
    /* jarak atas bawah antar box */
    padding: 10px;
  }

  .info-box {
    background-color: #ffffff;
    border-radius: 16px;
    padding: 10px 14px;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.05);
    transition: transform 0.2s;
    /*width: 100px;*/
  }

  .info-box:hover {
    transform: translateY(-4px);
  }

  .info-title {
    font-size: 12px;
    color: #888;
    margin-bottom: 8px;
  }

  .info-value {
    font-size: 14px;
    font-weight: bold;
    color: #333;
  }

  .info-icon {
    float: right;
    font-size: 25px;
    color: #4e73df;
  }

  .info-footer {
    font-size: 10px;
    color: #999;
    margin-top: 10px;
  }



  .dashboard-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    grid-template-rows: repeat(7, 1fr);
    grid-auto-rows: auto;
    gap: 10px;
    padding: 20px;
    background-color: #f4f6f9;
  }

  .box {
    background-color: #ffffff;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    display: flex;
    justify-content: center;
    align-items: center;
    font-weight: bold;
    color: #333;
    text-align: center;
    padding: 16px;
    position: relative;
    /* diperlukan jika pakai absolute di dalam */

  }

  .box-title {
    /*font-weight: bold;*/
    font-size: 10px;
    color: #888;
    position: absolute;
    top: 6px;
    left: 8px;
    /*background: white;*/
    padding: 2px 8px;
    border-radius: 4px;
    margin-bottom: 8px;
    /*font-size: 12px;
  color: #888;
  margin-bottom: 8px;*/
  }

  .box-value {
    margin-top: 12px;
    font-size: 14px;
    font-weight: bold;
    color: #333;
  }



  /* Ukuran tinggi dan lebar khusus */

  .box-1 {
    border-radius: 12px !important;
    padding: 10px;
    justify-content: space-between;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    flex: 1;
    width: 100% !important;
    background-color: white;
  }

  .title {
    font-size: 14px;
    font-weight: 600;
  }




  .box-2 {
    height: 70px;
  }

  .box-3 {
    height: 70px;
  }

  .box-4 {
    height: 70px;
  }

  .box-5 {
    /*height: 70px;*/
    grid-row: span 2;
  }

  .box-6 {
    /*height: 70px;*/
    /*height: 160px;*/
    grid-column: span 2;
    grid-row: span 3;
  }

  .box-7 {
    height: 70px;
  }

  .box-8 {
    height: 70px;
  }

  .box-9 {
    height: 70px;
  }

  .box-10 {
    height: 70px;
  }

  .box-11 {
    /*height: 70px; */
    grid-row: span 2;
  }

  .box-12 {
    height: 70px;

  }

  .box-13 {
    height: 70px;
  }

  .box-14 {
    /* height: 70px; */
    grid-column: span 2;
    grid-row: span 3;
  }

  .box-15 {
    /*height: 70px; */
    grid-column: span 2;
    grid-row: span 3;
  }

  .box-16 {
    /*height: 70px; */
    grid-row: span 3;
  }

  .box.chart-box {
    flex: 1;
    min-width: 280px;
    max-width: 315px;
  }

  .box.table-box {
    flex: 2;
    min-width: 500px;
  }

  .top-bar {
    display: flex;
    justify-content: flex-start;
    gap: 10px;
    color: #38406F;
    margin-top: 10px;
  }

  @media (max-width: 768px) {
    .top-bar {
      display: flex;
      justify-content: flex-start;
      gap: 5px;
      color: #38406F;
      margin-top: 10px;
    }

    .date-picker-wrapper {
      width: 70% !important;
      height: auto !important;
      background-color: #fff;
      margin-top: 10px;
    }

    .date-icon {
      position: absolute;
      left: 10px;
      color: #38406F;
      font-size: 10px !important;
    }

    .employee-select-wrapper {
      position: relative;
      width: 40% !important;
      padding-left: 18px !important;
      ym
    }

    .employee-icon {
      position: absolute;
      left: 10px;
      color: #666;
      font-size: 12px !important;
      pointer-events: none;
    }

    .select2-selection__placeholder {
      font-size: 11px !important;
    }

    .select2-selection {
      width: 100% !important;
      height: 28px !important;
    }


    .summary-container {
      gap: 10px !important;
      flex-direction: column !important;
    }

    .summary-card {
      min-width: none !important;
      max-width: none !important;
    }

    .chart-container {
      display: flex;
      flex-direction: column;
      gap: 20px;
    }

    .chart-row {
      margin-top: 0px;
      flex-direction: column;
    }

    .box-title {
      font-size: 10px !important;
      color: #977575ff;
      font-weight: 600;
    }

    .employee-container {
      display: flex;
      flex-direction: column;
      flex-wrap: wrap;
    }

    .box.chart-box {
      flex: 1;
      min-width: none !important;
      max-width: none !important;
    }




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
    width: 230px;
    height: 36px;
    border: 1px solid #ccc;
    border-radius: 20px !important;
    background-color: #fff;
    display: flex;
    align-items: center;
    padding-left: 34px;
    margin-top: 10px;
  }

  .employee-icon {
    position: absolute;
    left: 10px;
    color: #666;
    font-size: 16px;
    pointer-events: none;
  }





  .date-picker-wrapper {
    color: #38406F;
    position: relative;
    display: flex;
    align-items: center;
    border: 1px solid #ccc;
    border-radius: 20px !important;
    width: 250px;
    height: 36px;
    background-color: #fff;
    padding-left: 34px;
    margin-top: 10px;
  }

  .date-icon {
    position: absolute;
    left: 10px;
    color: #38406F;
    font-size: 16px;
  }

  .date-input {
    border: none;
    outline: none;
    font-size: 14px;
    width: 100%;
    height: 100%;
    background: transparent;
    padding-right: 10px;
    color: #333;
  }


  .summary-container {
    display: flex;
    gap: 20px;
    
  }

  .chart-container {
    display: flex;
    gap: 20px;
  }


  .right-container .box {
    margin-bottom: 16px;
    /* atau 20px sesuai kebutuhan */
    padding: 16px;
    border-radius: 8px;
  }

  .summary-card {
    display: flex;
    align-items: center;
    border-radius: 12px !important;
    padding: 20px;
    justify-content: space-between;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    width: 100% ! important;
  }

  .card-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
  }

  /* Container layout */
  .chart-row {
    display: flex;
    gap: 20px;
    margin-top: 20px;
  }

  /* Kiri: chart box */
  .chart-boxes {
    display: flex;
    gap: 20px;
    flex: 2;
  }

  /* Kanan: box kecil */
  .right-container {
    display: flex;
    flex-direction: column;
    gap: 10px;
  }


  .box {
    display: flex;
    border-radius: 12px !important;
    padding: 10px;
    justify-content: space-between;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    flex: 1;

  }



  .box-title {
    font-size: 14px;
    color: #303030;
    font-weight: 600;

  }

  .box-value {
    font-size: 15px;
    color: #2b2b2b;
    width: 100%;

  }



  .summary-card .title {
    font-size: 13px;
    color: #38406F;
    margin-bottom: 4px;
    font-weight: 500;
  }

  .summary-card .value {
    font-size: 20px;
    font-weight: bold;
    color: #2b2b2b;
  }

  .icon {
    font-size: 30px;
    color: #38406F;
  }

  .icon-2 {
    font-size: 40px;
    color: #38406F;
  }

  .grey {
    background-color: white;
    color: #38406F;
    min-width: 200px !important;
  }

  .grey .title {
    color: #959595;
    font-weight: 600;
    font-size: 14px;
  }

  .grey .value {
    color: #38406F;
  }

  /* Color variants */
  .navy {
    background-color: #38406F;
    color: white;
  }

  .navy .title,
  .navy .value,
  .navy .icon {
    color: white;
  }

  .yellow {
    background-color: #FED24B;
  }

  .beige {
    background-color: #F8F1E1;
  }

  .white {
    background-color: #ffffff;
  }

  .white .title {
    font-size: 12px;
  }

  .employee-container {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
  }


  table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
    margin-top: 10px;
  }

  thead th {
    text-align: left;
    background: #f3f4f6;
    padding: 10px;
    font-weight: 500;
    position: sticky;
    top: 0;
    z-index: 1;
  }

  tbody td {
    padding: 10px;
    vertical-align: middle;
  }

  .user {
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .user img {
    border-radius: 50% !important;
    width: 40px;
    height: 40px;
  }

  .user div {
    line-height: 1.2;
  }

  .table-container {
    overflow-y: auto;
    max-height: 220px;
  }


  /*.earlylogin-line {
    margin-top: 8px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 12px;
    gap: 10px;
  }

  .earlylogin-line span {
    display: flex;
    gap: 4px;
    align-items: center;
  }*/

  .earlylogin-line {
    display: flex;
    flex-direction: column;
    gap: 4px; /* jarak antar baris */
  }

  .status-row {
    display: flex;
    align-items: center;
  }

  .status-row strong {
    display: inline-block;
    width: 20px;   /* lebar tetap supaya angka rata */
    text-align: right;
    margin-right: 6px;

  }

  .txtt {
    font-size: 12px;   /* teks lebih kecil */
    color: #333;  
  }

  #sleep_desc_hrs {
    font-size: 10px;   /* teks lebih kecil */
  }

  #sleep_desc_mins {
    font-size: 10px;   /* teks lebih kecil */
  }
  


</style>