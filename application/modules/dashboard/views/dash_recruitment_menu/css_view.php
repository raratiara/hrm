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
    content: "Select Division";
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

  /*.summary-card.beige {
    width: 100px;
    
  }*/

  /*.summary-item.yellow {
    background-color: #D9F103; 
    align-items: center !important;
    color: #38406F;
    
  }*/

  .tasklist-line {
    /*display: flex;
    justify-content: space-around;*/

    display: flex;
    gap: 20px;
    /* spasi antar grade */
    margin-top: 10px;
    flex-wrap: wrap;
    /* jika sempit, bisa turun ke bawah */
  }

  .task-item {
    text-align: center;
    min-width: 60px;
    /* opsional, untuk menjaga lebar */
  }

  .task-item p:first-child {
    font-size: 15px;
    font-weight: bold;
    margin-bottom: 2px;
  }

  .task-item p:last-child {
    font-size: 12px;
    margin: 0;
  }




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


  #monthly_att_summ {
    width: 350px;
    height: 250px;
  }

  #att_statistic {
    width: 350px;
    height: 250px;
  }

  #empby_div_gender {
    width: 330px;
    height: 250px;
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


  #open_bydiv {
    width: 300px;
    height: 280px;
  }

  #byStatusPengajuan {
    width: 300px;
    height: 280px;
  }


  #byStatusEmployee {
    width: 300px;
    height: 280px;
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

  /*.box {
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
   
  }*/

  /*.box-title {
    font-size: 10px;
    color: #888;
    position: absolute;
    top: 6px;
    left: 8px;
   
    padding: 2px 8px;
    border-radius: 4px;
    margin-bottom: 8px;
   
  }*/

  /* .box-value {
    margin-top: 12px;
    font-size: 14px;
    font-weight: bold;
    color: #333;
  }*/



  /* Ukuran tinggi dan lebar khusus */

  .title {
    font-size: 14px;
    font-weight: 600;
  }


  .top-bar {
    display: flex;
    justify-content: flex-start;
    gap: 10px;
    color: #38406F;
    margin-top: 10px;
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
    width: 200px;
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
    width: 180px;
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

  /* ADD NEW */

  /* Struktur grid utama */
  .dashboard-layout {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 20px;
    align-items: start;
    max-width: 100%;
    flex-wrap: wrap;
  }

  /* Kolom kiri dan kanan */
  .left-column,
  .right-column {
    display: flex;
    flex-direction: column;
    gap: 20px;
  }

  /* Box umum */
  .box {
    background: #fff;
    border-radius: 10px !important;
    padding: 20px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
    width: 100%;
    box-sizing: border-box;
  }

  /* Tinggi canvas agar stabil dan tidak kependekan */
  .bydiv canvas {
    width: 100% !important;
    height: 250px !important;
  }

  .chart-box canvas {
    width: 100% !important;
    height: 223px !important;
  }

  .bylevel canvas {
    width: 100% !important;
    height: 300px !important;
    /* Lebih tinggi untuk Job Level */
  }

  /* Summary card */
  .summary-card.navy {
    padding: 20px;
    border-radius: 10px !important;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
  }

  .card-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .card-content .title {
    font-weight: bold;
    font-size: 1rem;
  }

  .card-content .value {
    font-size: 2rem;
  }

  .card-content .icon {
    font-size: 2rem;
  }

  /* Optional: responsive layout */
  @media (max-width: 768px) {
    .dashboard-layout {
      grid-template-columns: 1fr;
    }

    .left-column,
    .right-column {
      width: 100%;
    }
  }


  /*.bylevel {
  height: 320px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}*/



  /* END ADD*/


  .summary-container {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
  }

  /*.summary-card.beige {
    flex: 1 1 100%; 
    max-width: 400px; 
  }*/

  .chart-container {
    display: flex;
    gap: 20px;
  }


  /*.summary-card.navy {
    height: 140px;
  }*/



  /*.right-container .box {
    margin-bottom: 16px;
    
    padding: 16px;
    border-radius: 8px;
  }*/

  /*.summary-card {
    display: flex;
    align-items: center;
    border-radius: 12px !important;
    padding: 20px;
    min-width: 220px;
    justify-content: space-between;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    flex: 1;
    max-width: 280px;
  }*/

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


  /*.box {
    display: flex;
    border-radius: 12px !important;
    padding: 10px;
    justify-content: space-between;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    flex: 1;

  }*/



  /*.box-title {
    font-size: 14px;
    color: #303030;
    font-weight: 600;

  }*/

  .box-value {
    font-size: 15px;
    color: #2b2b2b;
    width: 100%;

  }

  .box-value-ttlrequest {
    margin-top: 12px;
    font-size: 14px;
    font-weight: bold;
    color: #333;


    font-size: 20px;
    font-weight: bold;
    color: #2b2b2b;
  }

  /*

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
  }*/

  .icon {
    font-size: 30px;
    color: #38406F;
  }

  .icon-2 {
    font-size: 40px;
    color: #38406F;
  }

  .grey {
    backgroundColor: white;
    color: #38406F;
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
    background: linear-gradient(to bottom,
        #C2E3FF 0%,
        #EFF5F9 100%);
  }

  .navy .title,
  .navy .value,
  .navy .icon {
    color: #1A3891;
  }

  .yellow {
    background-color: #EEF660;
    /*#E3FC87;*/
    /*#FED24B;*/
  }

  .beige {
    background-color: #FF77AD;
    /*#C0E0FF;*/
    /*#F8F1E1;*/
  }

  .white {
    background-color: #ffffff;
  }

  .white .title {
    font-size: 12px;
  }

  /* .employee-container {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
  }*/


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


  .earlylogin-line {
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
  }
</style>