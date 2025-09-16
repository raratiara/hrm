<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">


<style>
  body {
    background-color: #f3f5fc;
    margin: 0;
    /*padding: 30px;*/

  }

  .dashboard-container {
    font-family: 'Poppins', sans-serif;
    /*max-width: 1000px;*/
    margin: auto;
    display: flex;
    flex-direction: column;
    gap: 20px;
  }

  .page-content {
    background-color: #EAEDF5 !important;
  }

  .page-bar {
    background-color: #EAEDF5 !important;
  }


  .top-bar {
    display: flex;
    gap: 10px;
  }

  /*.select2-selection__placeholder {*/
  #flstatus + .select2 .select2-selection__placeholder {
    visibility: hidden;
    /* sembunyikan teks default */
    position: relative;
    border: none !important;
    background: #0000 !important;
  }

  /*.select2-selection__placeholder::after {*/
  #flstatus + .select2 .select2-selection__placeholder::after {
    content: "Select Status";
    /* teks baru */
    visibility: visible;
    position: absolute;
    left: 0;
    color: #999;
  }

  /*.select2-container--bootstrap .select2-selection {*/
  #flstatus + .select2.select2-container--bootstrap .select2-selection {
    border: none !important;
    box-shadow: none !important;
    background: transparent !important;
  }

  /*.select2-selection {*/
  #flstatus + .select2 .select2-selection {
    width: 150px !important;
    font-size: 10px !important;
    margin-top: 8px !important;
  }


  @media (max-width: 768px) {
    .top-bar {
      display: flex;
      gap: 5px;
      color: #38406F;
      margin-top: 10px;
    }

    .date-picker-wrapper {
      height: auto !important;
      background-color: #fff;
      width: 120px !important;
      padding-left: 25px !important;
      width: 120px !important;
      padding-left: 25px !important;
    }

    .date-icon {
      position: absolute;
      left: 10px;
      color: #38406F;
      font-size: 10px !important;
    }

    .employee-select-wrapper {
      position: relative;
      padding-left: 18px !important;
      width: 160px !important;
      width: 160px !important;
    }

    .employee-icon {
      position: absolute;
      left: 10px;
      color: #666;
      font-size: 12px !important;
      pointer-events: none;
    }

    .select2-selection {
      width: 140px !important;
      width: 140px !important;
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

    .title-box-chart {
      display: flex !important;
      flex-direction: column !important;
    }

  }

  .title-box-chart {
    display: flex !important;
    justify-content: space-between !important;
    align-items: center !important;

    .title-box-chart {
      display: flex !important;
      flex-direction: column !important;
    }

  }

  .title-box-chart {
    display: flex !important;
    justify-content: space-between !important;
    align-items: center !important;
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


  .date-picker-wrapper {
    position: relative;
    display: flex;
    align-items: center;
    border: 1px solid #ccc;
    border-radius: 20px !important;
    background-color: #fff;
    padding-left: 34px;
    height: 28px !important;
    height: 28px !important;
  }

  .date-icon {
    position: absolute;
    left: 10px;
    color: grey !important;
    font-size: 10px;
    color: grey !important;
    font-size: 10px;
  }

  .date-input {
    border: none;
    outline: none;
    font-size: 10px;
    font-size: 10px;
    width: 100%;
    height: 100%;
    background: transparent;
    padding-right: 10px;
    color: #333;
  }

  /* .profile-box,
    .summary-box,
    .chart-box {
      background-color: white;
      border-radius: 20px !important;
      padding: 20px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05) ;
    }

    /*.profile-box {
      display: flex;
      gap: 20px;
      align-items: center;
    }*/

  /* .profile-box {
  display: flex;
  gap: 20px;
  align-items: flex-start;
  position: relative; 
  min-height: 160px;
} */




  .profile-section {
    display: flex;
    justify-content: space-between;
    gap: 20px;

  }


  @media (max-width: 768px) {
    .profile-section {
      flex-direction: column;
    }

    .right-section {
      width: 100% !important;

    }

    .info-grid span {
      font-size: 11px !important;
    }

    .info-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr) !important;
      justify-content: center !important;


    }

    .summary-box {
      flex-direction: column;
      width: 100% !important;

    }

    .columnlevel>div {
      margin-top: -10px !important;
    }

    .reimbursement-grid .item {
      flex-direction: column !important;
      min-width: 120px;
      text-align: center;

    }

    .reimbursement-grid {
      font-size: 10px !important;
      margin-top: 10px !important;
      gap: 10px !important;
      display: flex !important;
      justify-content: space-between !important;
      flex-wrap: wrap !important;
    }

    .chart-section {
      display: flex;
      flex-direction: column;
      gap: 20px;
      /* Jarak antar kotak */
      align-items: stretch;
      /* Biar tingginya otomatis sama */
    }

    .chart-section2 {
      display: flex;
      flex-direction: column;
      gap: 20px;
      /* Jarak antar kotak */
      align-items: stretch;
      /* Biar tingginya otomatis sama */
    }

    #daily_tasklist {
      height: 220px;
      /* lebih kecil */
    }

    .events-box {
      width: 100% !important;
    }




  }


  .profile-card,
  .profile-info {
    background-color: #ffffff;
    border-radius: 10px !important;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    font-size: 13px !important;
  }

  .profile-card {
    text-align: center;
  }


  .profile-image {
    width: 100px;
    height: 100px;
    border-radius: 50% !important;
  }

  .profile-name {
    margin-top: 5px;
  }

  .profile-card h3 {
    /*font-size: 20px;*/
    margin: 10px 0;
  }

  .profile-info {
    flex: 1;

  }

  .profile-details {
    color: #414141;

  }

  .info-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
  }

  .info-grid span {
    font-size: 13px;
  }


  .column>div {
    min-height: 40px;
    /* Paksa semua baris minimal tinggi yang sama */
    display: flex;
    flex-direction: column;
    justify-content: center;
  }

  .columnlevel>div {
    min-height: 40px;
    /* Paksa semua baris minimal tinggi yang sama */
    display: flex;
    flex-direction: column;
    justify-content: center;
  }

  .info-grid .column div {
    margin-bottom: 15px;
    font-size: 13px;
    word-wrap: break-word !important;
    overflow-wrap: break-word !important;
    white-space: normal !important;
  }

  .info-grid strong {
    color: #888888;
    font-size: 13px;
    font-weight: 500;
  }

  .info-grid div {
    font-size: 13px;

  }

  .app-download {
    position: absolute;
    right: 20px;
    bottom: 10px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 13px;
    flex-wrap: wrap;
  }

  .right-section {
    display: flex;
    flex-direction: column;
    gap: 10px;
    width: 300px;

  }

  .download-icon {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-weight: 500;
    text-decoration: none;
    font-size: 13px;
    color: #2e3267;
    padding: 4px 8px;
    border-radius: 6px;
    background-color: #f3f4f8;
  }

  .download-icon img {
    width: 16px;
    height: 16px;
  }


  /* .profile-img {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid #ddd;
    }

    .profile-details {
      display: grid;
      grid-template-columns: repeat(2, auto);
      gap: 5px 30px;
      font-size: 12px;
    } */

  .summary-section {
    display: flex;
    gap: 20px;
    /* Jarak antar kotak */
    align-items: stretch;
    /* Biar tingginya otomatis sama */
  }


  .birthday-box {
    /*background-color: white !important;*/
    background: linear-gradient(to bottom, #FFE69B, #FED24B) !important;
    padding: 15px;
    border-radius: 15px !important;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }


  .birthday-balon {
    width: 120px;
    height: 100px;
    object-fit: contain;
    transform: rotate(-18deg);
    margin-bottom: -17px !important;
    margin-right: -20px !important;
  }



  .scroll-area {
    max-height: 220px;
    overflow-y: auto;
    padding-right: 5px;
  }

  .events-box {
    background-color: white !important;
    padding: 15px;
    border-radius: 15px !important;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }

  .event-item {
    padding: 10px;
    border-bottom: 1px solid #ddd;
    font-size: 13px;
  }

  /*.event-item:last-child {
    border-bottom: none;
  }*/

  /*.event-item:last-child {
    border-bottom: none;
  }*/

  .event-item:last-child {
    font-weight: 600;
    color: #3d3d5c;
    margin-bottom: 10px;
    font-size: 15px;
  }



  .birthday-title {
    margin-top: 0px;
    font-weight: 600;
    color: #3d3d5c;
    margin-bottom: 10px;
    font-size: 15px;
  }

  .birthday-content {
    display: flex;
    align-items: center;
    /* margin-top: 15px;*/
    position: relative;

  }

  .birthday-image {
    width: 50px;
    height: 50px;
    border-radius: 50% !important;
    object-fit: cover;
    margin-right: 5px;
  }

  .birthday-info {
    flex: 1;
  }

  .birthday-name {
    font-weight: bold;
    font-size: 13px;
    color: #000;
  }


  .birthday-arrow i {
    line-height: 1;
    font-size: 15px;

  }

  .birthday-job {
    font-size: 12px;
    color: #555;
  }

  .birthday-arrow {
    margin-right: 10px;
    display: flex;
    flex-direction: column;
    color: #555;
    cursor: pointer;
  }

  .birthday-arrow i {
    line-height: 1;
    font-size: 15px;

  }

  .summary-box {
    display: flex;
    justify-content: space-between;
    text-align: center;
    gap: 10px;
    background-color: white !important;
    padding: 20px;
    border-radius: 20px !important;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    flex: 1;
    width: 100%;
  }

  .summary-item {
    flex: 1;
    padding: 10px 15px;
    margin: 0;
    background-color: #9E9E9E;
    border-radius: 15px !important;
    font-size: 13px;
    justify-content: space-between;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
  }

  .summary-item .icon {
    font-size: 50px;
  }

  .summary-item h4 {
    margin: 0;
    font-size: 13px;
    font-weight: 600;
  }

  .summary-item .total {
    font-size: 16px;
    font-weight: bold;
    margin-top: 5px;
  }



  .summary-item .number {
    font-size: 10px;
    font-weight: bold;
    margin-top: 5px;
  }

  .summary-item .title {
    font-size: 13px;
    font-weight: bold;
  }

  .summary-item.highlight {
    background-color: #38406F;
    color: white;
    display: flex !important;
    align-items: center !important;
    text-align: left !important;
  }

  .summary-item.reimbursement {
    background-color: #9cb4deff;
    color: white;
    align-items: center !important;
    text-align: center !important;
    width: 100% !important;
  }


  .reimbursement-grid {
    font-size: 12px;
    display: grid;
    grid-template-columns: repeat(2, auto);
    gap: 15px 15px;
    text-align: left;
    margin-top: 16px;
  }

  .item {
    display: flex;
    justify-content: space-between;
    min-width: 180px;
  }

  .amount {
    font-weight: bold;
  }

  .summary-item.yellow {
    background-color: #FED24B;
    align-items: center !important;
    color: #38406F;

  }


  .tasklist-line {
    display: flex;
    justify-content: space-around;
  }

  .task-item p:first-child {
    font-size: 13px;
    font-weight: bold;
    margin-bottom: 2px;
  }

  .task-item p:last-child {
    font-size: 12px;
    margin: 0;
  }


  .chart-section {
    display: flex;
    gap: 20px;
    /* Jarak antar kotak */
    align-items: stretch;
    /* Biar tingginya otomatis sama */
  }

  .chart-section2 {
    display: flex;
    gap: 20px;
    /* Jarak antar kotak */
    align-items: stretch;
    /* Biar tingginya otomatis sama */
  }


  .chart-box {
    background-color: white !important;
    padding: 15px;
    border-radius: 20px !important;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    flex: 2;
    font-size: 13px !important;

  }

  .chart-box2 {
    background-color: white !important;
    padding: 15px;
    border-radius: 20px !important;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    flex: 2;
    font-size: 13px !important;
  }


  .event {
    display: flex;
    align-items: start;
    gap: 1rem;
    margin-bottom: 1.5rem;
  }

  .date-box {
    padding: 0.5rem 1rem;
    border-radius: 0.5rem !important;
    color: black;
    text-align: center;
    font-size: 0.8rem;
    width: 110px;
    height: auto;
  }

  .today {
    background: #e6ecf7;
  }

  .yellow {
    background: #fcefb5;
  }

  .orange {
    background: #fde0bb;
  }

  .grey {
    background: #d2d2d2;
  }


  .time {
    font-size: 15px;
    font-weight: 600;
  }

  .info h3 {
    margin: 0;
    font-size: 1rem;
    font-weight: bold;
  }

  .info p {
    margin: 0.2rem 0 0 0;
    color: #555;
    font-size: 0.9rem;
  }

  #daily_tasklist {
    width: 330px;
    height: 250px;
  }


  .box-1 {
    border-radius: 12px !important;
    padding: 10px;
    justify-content: space-between;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    flex: 1;
    width: 100% !important;
    background-color: white;
  }

  .box.table-box {
    flex: 2;
    min-width: 500px;
  }

  .table-container {
    overflow-y: auto;
    max-height: 320px;
  }

  .title_tasklist {
    font-size: 13px;
    font-weight: 600;
  }

  table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
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


  .birthday-title .birthday-span {
    position: relative;
    top: 4px;
    margin-left: 4px;

  }

  .birthday-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .birthday-content svg {
    display: inline-block;
    margin-right: 18px;

  }


  .btn-checkin {
    background-color: #25c87c; /* hijau */
    color: white;
    border: none;
    padding: 10px;
    font-size: 13px;
    border-radius: 6px;
    cursor: pointer;
    transition: background 0.3s ease;
    margin-right: 8px;
    width:100px; 
    font-weight:bold;
  }
  .btn-checkin:hover {
    background-color: #157347;
  }

  .btn-checkout {
    background-color: #c82571; /* merah */
    color: white;
    border: none;
    padding: 10px;
    font-size: 13px;
    border-radius: 50px;
    cursor: pointer;
    transition: background 0.3s ease;
    width:100px; 
    font-weight:bold;
  }
  .btn-checkout:hover {
    background-color: #bb2d3b;
  }

  .action-button {
    margin-top: 20px;
  }



</style>