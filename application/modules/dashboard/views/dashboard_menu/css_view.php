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



  #fldashemp {
    width: 120px;
    height: 20px;
  }


  .dashboard-container {
    font-family: 'Poppins', sans-serif;
    max-width: 1000px;
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

  #empby_dept_gender {
    width: 330px;
    height: 250px;
  }


  .dashboard {
    width: 1000px;
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


  #empby_gen {
    width: 200px;
    height: 180px;
  }

  #workLocation {
    width: 200px;
    height: 180px;
  }


  #att_percentage {
    width: 100px;
    height: 100px;
  }


  #workhrs_percentage {
    width: 100px;
    height: 100px;
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
    justify-content: flex-end;
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
    width: 180px;
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


  .summary-container {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
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
    min-width: 220px;
    justify-content: space-between;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    flex: 1;
    max-width: 280px;
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




  /*.dashboard-flex {
  display: flex;
  flex-direction: row;
  flex-wrap: wrap;
  gap: 16px;
  height: 500px; 
  width: 1000px;
  flex: 0 0 calc((100% - 64px) / 5); 
}*/

  /*.column {
  flex: 1;
  display: flex;
  flex-direction: column;
}*/

  /*.box {
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  margin-bottom: 10px;
  margin-top: 10px;
  padding: 16px;
  text-align: center;
  font-weight: bold;
}


.box-1 {
  height: 70px;
  width: 350px;
}
.box-2 {
  height: 70px;
  
}
.box-3 {
  height: 70px;
  width: 200px;
}
.box-4 {
  height: 70px;
  width: 200px;
}
.box-5 {
  height: 120px;
  width: 200px;
}

.box-6 {
  
  height: 170px;

  flex: 0 0 190.4px;
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
  height: 120px;
  
}
.box-11 {
  
  height: 170px;
 
}
.box-12 {
  height: 70px;
  
}
.box-13 {
  height: 70px;
 
}
.box-14 {
  height: 70px;
  
}
.box-15 {
  flex-grow: 1;

}
.box-16 {
  height: 170px;

  flex: 0 0 390.4px;
}*/
</style>