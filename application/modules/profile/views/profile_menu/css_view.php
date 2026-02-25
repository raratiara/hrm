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




  .top-bar {
    display: flex;
    gap: 10px;
  }

  /*.select2-selection__placeholder {*/
  #flstatus+.select2 .select2-selection__placeholder {
    visibility: hidden;
    /* sembunyikan teks default */
    position: relative;
    border: none !important;
    background: #0000 !important;
  }

  /*.select2-selection__placeholder::after {*/
  #flstatus+.select2 .select2-selection__placeholder::after {
    content: "Select Status";
    /* teks baru */
    visibility: visible;
    position: absolute;
    left: 0;
    color: #999;
  }

  /*.select2-container--bootstrap .select2-selection {*/
  #flstatus+.select2.select2-container--bootstrap .select2-selection {
    border: none !important;
    box-shadow: none !important;
    background: transparent !important;
  }

  /*.select2-selection {*/
  #flstatus+.select2 .select2-selection {
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
    margin-top: 20px;

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



  .health-card {
    background-color: #ffffff;
    border-radius: 10px !important;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    font-size: 13px !important;
  }

  .profile-merged {
    display: flex;
    gap: 18px;
    width: 100%;
    padding: 18px 20px;
    border-radius: 12px !important;

    /* gradient kiri -> kanan */
    background: linear-gradient(170deg, rgb(207, 233, 253) 0%, #ffffff 100%);

    /* shadow sama kaya yang lain */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  }

  /* .profile-card,
.profile-info {
  background: #fff;
  border-radius: 10px !important;
  padding: 20px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  font-size: 13px !important;
} */

  .profile-card,
  .profile-info {
    background: transparent !important;
    box-shadow: none !important;
    padding: 0 !important;
    border-radius: 0 !important;
  }


  /* kiri */
  .profile-left {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    /* center horizontal */
    justify-content: center;
    /* center vertical */
    text-align: center;
    height: 100%;
  }

  /* divider vertikal */
  .profile-divider {
    width: 1px;
    background: rgba(0, 0, 0, 0.1);
    margin: 6px 0;
  }

  /* kanan */
  .profile-right {
    flex: 1;
    display: flex;
    align-items: center;
  }

  /* rapihin info grid supaya mirip card kanan di gambar */
  .profile-right .info-grid {
    width: 100%;
    display: grid;
    grid-template-columns: 1fr;
    /* <- 1 kolom */
    gap: 10px;
  }


  .profile-right .info-grid strong {
    color: #888888;
    font-size: 13px;
    font-weight: 500;
  }

  .profile-right .info-grid span {
    font-size: 13px;
    font-weight: 600;
    color: #1f2a44;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  /* tiap baris label-value jadi grid: kolom label fix, value ngikut */
  .profile-right .info-grid .column>div {
    display: grid;
    grid-template-columns: 140px auto;
    /* atur 120-160px sesuai kebutuhan */
    column-gap: 14px;
    /* jarak label-value */
    align-items: center;
    margin-bottom: 10px;
    white-space: nowrap;
  }

  /* label */
  .profile-right .info-grid strong {
    color: #888888;
    font-size: 13px;
    font-weight: 500;
  }

  /* value: jangan didorong ke kanan */
  .profile-right .info-grid span {
    font-size: 13px;
    font-weight: 600;
    color: #1f2a44;
    text-align: left;
    /* penting */
    max-width: none;
    /* penting biar gak “ngunci” */
    overflow: visible;
  }


  /* mobile */
  @media (max-width: 768px) {
    .profile-merged {
      flex-direction: column;
      gap: 14px;
    }

    .profile-left {
      width: 100%;
    }

    .profile-divider {
      width: 100%;
      height: 1px;
    }

    .profile-right .info-grid {
      grid-template-columns: 1fr;
      /* jadi 1 kolom biar rapi */
    }

    .profile-right .info-grid span {
      max-width: 100%;
    }
  }

  .profile-card,
  .health-card {
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



  .info-grid span {
    font-size: 13px;
  }


  /* .column>div {
    min-height: 40px;
    display: flex;
    flex-direction: column;
    justify-content: center;
  } */

  /* .columnlevel>div {
    min-height: 40px;
    display: flex;
    flex-direction: column;
    justify-content: center;
  } */

  /* .info-grid .column div {
    margin-bottom: 15px;
    font-size: 13px;
    word-wrap: break-word !important;
    overflow-wrap: break-word !important;
    white-space: normal !important;
  } */

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
    gap: 15px;
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

  .summary-health {
    display: flex;
    gap: 20px;
    /* Jarak antar kotak */
    align-items: stretch;
    /* Biar tingginya otomatis sama */
  }

  .summary-section {
    display: flex;
    gap: 20px;
    /* Jarak antar kotak */
    align-items: stretch;
    /* Biar tingginya otomatis sama */
  }


  .birthday-box {
    background: linear-gradient(135deg, #f497e9 0%, #add6fa 100%) !important;
    padding: 15px;
    border-radius: 15px !important;

    /* shadow sama */
    box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25) !important;
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
    box-shadow: 0px 0px 4px rgba(0, 0, 0, 0.25) !important;
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
    color: #ffffff;
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
    color: #ffffff;
  }


  .birthday-arrow i {
    line-height: 1;
    font-size: 15px;

  }

  .birthday-job {
    font-size: 12px;
    color: #ffffff;
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
    gap: 30px;
    margin-bottom: 10px;
    border-radius: 20px !important;
    background-color: #fff;
    box-shadow: 0px 0px 4px rgba(0, 0, 0, 0.25) !important;
    padding: 20px 20px;
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
    background: linear-gradient(to bottom, #C2E3FF 0%, rgb(246, 248, 250) 100%);
    color: #38406F;

    display: flex !important;
    align-items: center !important;
    text-align: left !important;

    box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
    border-radius: 18px;
  }

  .summary-item.reimbursement {
    background: linear-gradient(to bottom, #f9e7ab 0%, rgb(246, 248, 250) 100%);
    color: #38406F;
    align-items: center !important;
    text-align: center !important;
    width: 100% !important;

    box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
    border-radius: 18px;
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
    background: linear-gradient(to bottom, #f6b4ee 0%, rgb(246, 248, 250) 100%);
    align-items: center !important;
    color: #38406F;

    box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
    border-radius: 18px;
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
    box-shadow: 0px 0px 4px rgba(0, 0, 0, 0.25) !important;
    flex: 2;
    font-size: 13px !important;

  }

  .chart-box2 {
    background-color: white !important;
    padding: 15px;
    border-radius: 20px !important;
    box-shadow: 0px 0px 4px rgba(0, 0, 0, 0.25) !important;
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
    box-shadow: 0px 0px 4px rgba(0, 0, 0, 0.25) !important;
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
    height: 500px;
  }

  .title_tasklist {
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 10px;
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
    background-color: #E2EEDA;
    color: #3D7B14;
    border: none;
    padding: 10px 12px;
    font-size: 13px;
    border-radius: 6px;
    cursor: pointer;
    transition: background 0.3s ease;
    margin-right: 8px;
    font-weight: bold;
    margin-top: 10px;
  }

  .btn-checkin:hover {
    background-color: #157347;
    color: white;
  }

  .btn-checkout {
    background-color: #F9D1E6;
    color: #7F0947;
    border: none;
    padding: 10px 12px;
    font-size: 13px;
    border-radius: 50px;
    cursor: pointer;
    transition: background 0.3s ease;
    font-weight: bold;
    margin-top: 10px;
  }

  .btn-checkout:hover {
    background-color: #7F0947;
    color: white;
  }

  .health-card {
    min-width: 330px;
    /*background: #000000;*/
    /*#D9FB60;*/
    /*#000000;*/
    /*#E9F3FF;*/
    /*#005479;*/
    /* dark mode */
    padding: 20px;
    border-radius: 12px;
  }

  .card-box {
    /*background: #dbdadaff;*/
    border-radius: 12px !important;
    padding: 4px;
    text-align: center;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
    margin-bottom: 15px;
    min-height: 150px;
  }



  .card-box p {
    margin: 0px;

  }

  .card-box small {
    font-size: 8px;
    /* kecil untuk teks tambahan */
    color: #ffffff;
    /*#888;*/
  }

  .card-box .icon {
    font-size: 24px;
    /* ikon lebih kecil */
    margin-bottom: 3px;
  }

  .card-box .badge {
    font-size: 8px;
    /* badge kecil */
    padding: 3px 6px;
    border-radius: 8px;
    /*color: #ffffff;
    background: #000000;*/
  }

  /* Cardbox 1 */
  .cardbox-1 {
    background-color: #F7EDD4;
    border: 1px solid #FFD05C;
  }

  .cardbox-1 h3,
  .cardbox-1 p {
    color: #e6b435ff;
  }

  .cardbox-1 .badge {
    background-color: #FFD05C;
    color: #fff;
  }

  /* Cardbox 2 */
  .cardbox-2 {
    background-color: #FFE2F4;
    border: 1px solid #FF76CB;
  }

  .cardbox-2 h3,
  .cardbox-2 p {
    color: #FF76CB;
  }

  .cardbox-2 .badge {
    background-color: #FF76CB;
    color: #fff;
  }

  /* Cardbox 3 */
  .cardbox-3 {
    background-color: #E2EEDA;
    border: 1px solid #95F753;
  }

  .cardbox-3 h3,
  .cardbox-3 p {
    color: #52ad14ff;
  }

  .cardbox-3 small {
    color: #414141ff;
  }

  .cardbox-3 .badge {
    background-color: #059669;
    color: #fff;
  }

  /* Cardbox 4 */
  .cardbox-4 {
    background-color: #D7EBFC;
    border: 1px solid #77b8f2;
  }

  .cardbox-4 h3,
  .cardbox-4 p {
    color: #77b8f2;
  }

  .cardbox-4 .badge {
    background-color: #77b8f2;
    color: #fff;
  }

  .health-card .row {
    margin-left: 0;
    margin-right: 0;
  }

  .health-card .col-md-6 {
    padding-left: 4px;
    padding-right: 4px;
  }


  .btn-health {
    font-size: 12px;
    /* kecilkan teks */
    padding: 4px 12px;
    /* kecilkan tinggi & lebar */
    border-radius: 20px;
    /* biar oval */
    background-color: #ecf5fd;
    /*#fac86bff;*/
    color: #074069 !important;
  }

  .btn-health:hover {
    background-color: #D7EBFC;
  }

  /*.action-button {
    margin-top: 20px;
  }*/


  .quick-links {

    padding: 15px;
    border-radius: 12px !important;


  }

  .quick-links_old {
    position: fixed;
    /* biar selalu melayang */
    bottom: 20px;
    /* jarak dari bawah */
    right: 20px;
    /* jarak dari kanan */
    background: #fff;
    padding: 15px;
    border-radius: 12px !important;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    width: 250px;
    /* lebar panel */
    z-index: 9999;
    /* pastikan di atas elemen lain */
  }

  /* judul jangan terlalu mepet bawah, karena ada button + */
  .quick-links h1 {
    font-size: 16px;
    font-weight: 700;
    color: #232323;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
  }

  /* grid horizontal scroll */
  .quick-links-grid {
    display: flex;
    flex-wrap: nowrap;
    gap: 18px;
    overflow-x: auto;
    padding: 5px 2px 8px;
    scrollbar-width: thin;
    scrollbar-color: #ccc transparent;
    margin-top: 10px;
  }

  .quick-links-grid::-webkit-scrollbar {
    height: 6px;
  }

  .quick-links-grid::-webkit-scrollbar-thumb {
    background: #ccc;
    border-radius: 4px;
  }

  /* item */
  .quick-link-item {
    flex: 0 0 auto;
    width: 100px;
    height: 89px;

    background: #fffdfd;
    box-shadow: 0px 0px 4px rgba(0, 0, 0, 0.25) !important;
    /*#C9E6F0;*/
    /*#BED2E1;*/
    /*#fafafa;*/
    border: 1px solid #eee;
    border-radius: 12px !important;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    /* agak kecil biar muat */
    text-align: center;
    cursor: pointer;
    transition: all 0.2s ease-in-out;
    user-select: none;

    /* biar gak ke-block teks pas di-swipe */
  }


  .quick-link-item icon {
    width: 100px;
    height: 30px;
    background: #3062A4;

  }

  /* icon */
  .quick-link-item i {
    font-size: 20px;
    margin-bottom: 6px;
    color: #666;
  }

  /* teks biar rapi */
  .quick-link-item span {
    max-width: 100px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    width: 80px;
    font-size: 10px;
  }

  /* hover effect */
  .quick-link-item:hover {
    background: #DBEDFC;
    border-color: #90C9FF;
    /*#f5f5f5;*/
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
  }

  /*#quickLinksContainer {
    width: 50px;
    height: 50px;
  }*/




  .select2-container {
    width: 100% !important;
  }

  .select2-dropdown {
    position: absolute !important;
    top: auto !important;
    left: 0 !important;
  }

  .card-information-health {
    margin-top: 15px !important;


  }


  /* Container list */
  .tasklist-container {
    display: flex;
    flex-direction: column;
    gap: 12px;
    overflow-y: auto;
    flex: 1;
    padding-right: 6px;
    /* biar ga ketutup scrollbar */
    height: 480px;
  }

  /* Card item */
  .task-card {
    background: #fff;
    border: 1px solid #e6e9f2;
    border-radius: 14px !important;
    padding: 14px 16px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
  }

  /* Row label-value */
  .task-row {
    display: grid;
    grid-template-columns: 110px 1fr;
    /* kiri label, kanan value */
    gap: 10px;
    align-items: start;
    margin-bottom: 6px;
  }

  .task-row:last-child {
    margin-bottom: 0;
  }

  .task-label {
    font-size: 12px;
    color: #8A8FA3;
    font-weight: 500;
  }

  .task-value {
    font-size: 11px;
    color: #1f2a44;
    font-weight: 600;
    text-align: right;
    /* kaya gambar (value rata kanan) */
    word-break: break-word;
  }

  /* Badge status progress */
  .task-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 4px 10px;
    border-radius: 999px !important;
    font-size: 11px;
    font-weight: 600;
    border: 1px solid transparent;
    width: fit-content;
    margin-left: auto;
    /* dorong ke kanan */
  }

  /* contoh warna (silakan sesuaikan) */
  .badge-open {
    background: #F7F4D0;
    color: #38406F;
    border-color: #F1EAB0;
  }

  .badge-progress {
    background: #DBEDFC;
    color: #112D80;
    border-color: #90C9FF;
  }

  .badge-done {
    background: #E2EEDA;
    color: #3D7B14;
    border-color: #95F753;
  }

  /* warna due (overdue/near_due) */
  .due-overdue .task-value {
    color: #ee4399;
  }

  .due-near .task-value {
    color: #f08a00;
  }


  /* ===============================
   PROFILE - MOBILE 1 COLUMN (NO CUT)
   =============================== */
  @media (max-width: 768px) {

    .profile-merged {
      flex-direction: column !important;
      padding: 14px 14px !important;
      gap: 12px !important;
    }

    .profile-left {
      width: 100% !important;
      align-items: center !important;
      text-align: center !important;
    }

    .profile-divider {
      width: 100% !important;
      height: 1px !important;
      margin: 6px 0 !important;
    }

    .profile-right {
      width: 100% !important;
      align-items: flex-start !important;
    }

    /* 1 kolom: label atas, value bawah */
    .profile-right .info-grid .column>div {
      display: flex !important;
      flex-direction: column !important;
      align-items: flex-start !important;
      gap: 4px !important;
      margin-bottom: 10px !important;

      white-space: normal !important;
    }

    .profile-right .info-grid strong {
      font-size: 12px !important;
      line-height: 1.2;
    }

    .profile-right .info-grid span {
      font-size: 12px !important;
      line-height: 1.3;

      /* ini kunci biar gak kepotong */
      white-space: normal !important;
      word-break: break-word !important;
      /* email/alamat panjang */
      overflow-wrap: anywhere !important;
      /* paling ampuh */
      overflow: visible !important;
      text-overflow: initial !important;

      text-align: left !important;
      width: 100% !important;
      max-width: 100% !important;
    }

    /* tombol biar rapih di mobile */
    .profile-left .action-button {
      width: 100% !important;
      display: flex;
      justify-content: center;
      gap: 10px;
      flex-wrap: wrap;
    }
  }

  /* kecil banget */
  @media (max-width: 480px) {
    .profile-right .info-grid span {
      font-size: 11px !important;
    }
  }



  /* ===============================
   HEALTH CARD - MOBILE SPACING FIX
   =============================== */
  @media (max-width: 768px) {

    /* card utama */
    .health-card {
      padding: 22px 16px !important;
      border-radius: 14px !important;
    }

    /* paksa jadi 1 kolom */
    .health-card .row {
      display: flex !important;
      flex-direction: column !important;
      gap: 0px !important;
      /* jarak antar grup */
    }

    .health-card .col-md-6 {
      width: 100% !important;
      padding: 0 !important;
    }

    /* jarak antar cardbox */
    .health-card .card-box {
      margin-bottom: 15px !important;
      height: auto !important;
      min-height: 88px;
      /* biar napas */
    }

    /* teks lebih lega */
    .health-card .card-box h3 {
      margin-top: 6px !important;
      font-size: 15px !important;
    }

    .health-card .card-box p {
      margin-top: 6px !important;
      font-size: 11px !important;
    }

    .health-card .badge {
      margin-top: 6px !important;
    }

    /* tombol bawah */
    .health-card .btn-health {
      margin-top: 16px !important;
    }
  }

  /* HP kecil banget */
  @media (max-width: 480px) {
    .health-card .card-box {
      min-height: 92px;
    }
  }

  .health-card .card-information-health>[class*="col-"] {
    padding-left: 8px;
    padding-right: 8px;
  }


  .health-card .card-box {
    border-radius: 16px !important;
    padding: 18px 18px !important;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08) !important;
    border: 2px solid transparent !important;
    transition: all .3s ease !important;
    min-height: 150px;
    /* boleh kamu kecilin */
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }

  .health-card .card-box:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12) !important;
  }

  /* "header": label (p) + icon */
  .health-card .card-box {
    position: relative;
  }


  /* BPM */
  .health-card .cardbox-1 {
    border-color: #fef3c7 !important;
    background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%) !important;
  }

  .health-card .cardbox-1 h3 {
    color: #d97706 !important;
  }

  .health-card .cardbox-1 .icon {
    background: #fadd94;
    color: #fff;
  }

  /* Sleep */
  .health-card .cardbox-3 {
    border-color: #d1fae5 !important;
    background: linear-gradient(135deg, #f0fdf4 0%, #d1fae5 100%) !important;
  }

  .health-card .cardbox-3 h3 {
    color: #059669 !important;
  }

  .health-card .cardbox-3 .icon {
    background: #b7fbb4;
    color: #fff;
  }

  /* SPO2 */
  .health-card .cardbox-2 {
    border-color: #fedcf0 !important;
    background: linear-gradient(135deg, #fff1f2 0%, #fecdea 100%) !important;
  }

  .health-card .cardbox-2 h3 {
    color: #e11d8f !important;
  }

  .health-card .cardbox-2 .icon {
    background: #fb94d0;
    color: #fff;
  }

  /* Fatigue/Kritikus (biru) */
  .health-card .cardbox-4 {
    border-color: #dbeafe !important;
    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%) !important;
  }

  .health-card .cardbox-4 h3 {
    color: #2563eb !important;
  }

  .health-card .cardbox-4 .icon {
    background: #92b3f9;
    color: #fff;
  }

  /* label BPM/Sleep/SpO2/Fatigue */
  .health-card .card-box p {
    font-size: 13px !important;
    font-weight: 600 !important;
    color: #64748b !important;
    text-transform: uppercase;

  }

  .health-card .card-box h3 {
    margin: 8px 0 6px !important;
    /* kasih ruang dari header */
    font-size: 29px !important;
    font-weight: 700 !important;

  }

  .health-card .card-box .icon {
    /* position: absolute;
  top: 16px;
  right: 16px; */
    width: 28px;
    height: 28px;
    border-radius: 8px !important;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px !important;
    margin: 0 !important;
  }

  .title-health {
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  /* ===== Quick Links = link-card style ===== */

  /* container: tetap horizontal scroll seperti sebelumnya */
  .quick-links-grid {
    display: flex !important;
    flex-wrap: nowrap !important;
    gap: 20px !important;
    overflow-x: auto !important;
    padding: 6px 2px 10px !important;
    margin-top: 10px !important;
  }

  /* card */
  .quick-link-item {
    flex: 0 0 auto !important;
    width: 140px !important;
    min-height: 130px !important;

    background: #fff !important;
    border-radius: 16px !important;
    text-align: center !important;
    cursor: pointer !important;

    transition: all 0.3s ease !important;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08) !important;
    border: 2px solid transparent !important;

    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    justify-content: center !important;
  }

  .quick-link-item:hover {
    transform: translateY(-6px) !important;
    border-color: #c5d4f9 !important;
  }

  /* icon box gradient */
  .quick-link-icon {
    width: 50px !important;
    height: 50px !important;
    background: linear-gradient(135deg, #f6b4ee 0%, #add6fa 100%) !important;
    border-radius: 14px !important;

    display: flex !important;
    align-items: center !important;
    justify-content: center !important;

    margin: 0 auto 16px !important;
    box-shadow: 0 4px 16px rgba(102, 126, 234, 0.3) !important;
  }

  .quick-link-icon i {
    font-size: 20px !important;
    color: #fff !important;

  }

  /* label */
  .quick-link-label {
    font-size: 12px !important;
    font-weight: 600 !important;
    color: #1e293b !important;

    max-width: 140px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  /* responsive */
  @media (max-width: 768px) {
    .quick-link-item {
      width: 130px !important;
      min-height: 130px !important;
      padding: 20px 16px !important;
    }

    .quick-link-icon {
      width: 54px !important;
      height: 54px !important;
      margin-bottom: 12px !important;
    }

    .quick-link-icon i {
      font-size: 24px !important;
    }
  }


  /* responsive: di mobile jangan ellipsis (biar tetap kebaca) */
  @media (max-width: 768px) {
    .profile-right .info-grid span {
      max-width: 100% !important;
      white-space: normal !important;
      overflow: visible !important;
      text-overflow: initial !important;
    }
  }

  /* Avatar Inisial */
  .initial-avatar {
    width: 80px;
    /* samakan dengan .profile-image */
    height: 80px;
    border-radius: 100% !important;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 26px;
    letter-spacing: 1px;
    color: #fff;
    user-select: none;
    text-transform: uppercase;
    margin-bottom: 8px;
    background: linear-gradient(135deg, #f6b4ee 0%, #add6fa 100%);
    box-shadow: 0 6px 16px rgba(0, 0, 0, .12);
  }

  /* kalau kamu pakai gambar juga, biar konsisten */
  .profile-image {
    width: 72px;
    height: 72px;
    object-fit: cover;
  }



  /* ===== PROFILE INFO: jangan ngelebar + ellipsis ===== */
  .profile-merged,
  .profile-right,
  .profile-right .info-grid,
  .profile-right .info-grid .column,
  .profile-right .info-grid .column>div {
    min-width: 0 !important;
    /* wajib supaya ellipsis jalan di layout flex/grid */
  }

  .profile-right .info-grid .column>div {
    grid-template-columns: 140px minmax(0, 1fr) !important;
    /* kolom value boleh mengecil */
  }

  /* value (alamat/email/dll) jadi ... */
  .profile-right .info-grid span {
    display: block !important;
    max-width: 260px;
    /* atur sesuai kebutuhan */
    overflow: hidden !important;
    text-overflow: ellipsis !important;
    white-space: nowrap !important;
    text-align: left !important;
  }

  /* biar di desktop tetap rapi */
  @media (min-width: 992px) {
    .profile-right .info-grid span {
      max-width: 320px;
      /* versi desktop */
    }
  }

  /* mobile biasanya mending wrap (bukan ellipsis), tapi kalau kamu tetap mau ellipsis juga, hapus block ini */
  @media (max-width: 768px) {
    .profile-right .info-grid span {
      max-width: 100% !important;
      white-space: normal !important;
      /* mobile: turun baris */
      overflow: visible !important;
      text-overflow: initial !important;
    }
  }


  .birthday-avatar-wrap {
    width: 48px;
    /* samain dgn ukuran avatar kamu */
    height: 48px;
    border-radius: 50%;
    overflow: hidden;
    flex: 0 0 48px;
  }

  .birthday-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
    display: block;
  }

  .birthday-initial {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 14px;
    letter-spacing: .5px;
    text-transform: uppercase;
    color: #38406F;
    background: linear-gradient(180deg, #C2E3FF 0%, #EFF5F9 100%);
    border: 1px solid rgba(56, 64, 111, .15);
  }

  /* =========================
   NEW LAYOUT SECTIONS
   ========================= */

  /* 3 cards row */
  .info-3cards-section {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 20px;
    align-items: stretch;
  }

  /* 2 charts row */
  .charts-2cards-section {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 20px;
    align-items: stretch;
  }

  /* biar semua card di grid bisa mengecil dengan benar */
  .info-3cards-section>div,
  .charts-2cards-section>div {
    min-width: 0;
  }

  /* tasklist card biar tinggi nyambung sama design */
  .info-3cards-section .box-1.table-box {
    background: #fff;
    border-radius: 15px !important;
    box-shadow: 0px 0px 4px rgba(0, 0, 0, 0.25) !important;
    padding: 15px;
  }

  /* atur tinggi list task di card agar konsisten */
  .info-3cards-section #tasklistList {
    height:   110px;
    /* sesuaikan kalau mau lebih tinggi */
    overflow-y: auto;
  }

  /* events scroll biar konsisten */
  .info-3cards-section .scroll-area {
    max-height: 110px;
    overflow-y: auto;
  }

  /* =========================
   RESPONSIVE
   ========================= */
  @media (max-width: 992px) {

    /* tablet: 2 kolom */
    .info-3cards-section {
      grid-template-columns: repeat(2, minmax(0, 1fr));
    }
  }

  @media (max-width: 768px) {

    /* mobile: 1 kolom */
    .info-3cards-section {
      grid-template-columns: 1fr;
    }

    .charts-2cards-section {
      grid-template-columns: 1fr;
    }

    /* canvas biar tidak kepotong di mobile */
    #daily_tasklist {
      width: 100% !important;
      height: 240px !important;
    }
  }
</style>