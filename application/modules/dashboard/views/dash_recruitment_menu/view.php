

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">



<div class="dashboard-container">


  <div class="top-bar">

    <div class="employee-select-wrapper">
      <span class="employee-icon"><i class="fa-solid fa-user"></i></span>
      
      <?=$seldiv?>
    </div>
  </div>

  
  
  <div class="dashboard-layout">
    <!-- Baris atas -->
    <div class="left-column">
      <div class="box bydiv">
        <div class="box-title">Open Position by Division</div>
        <div class="box-value">
          <canvas id="open_bydiv" style="margin-top: 15px;"></canvas>
        </div>
      </div>

      <div class="box bylevel">
        <div class="box-title">Job Level</div>
        <div class="box-value">
          <canvas id="byJobLevel" style="margin-top: 15px;"></canvas>
        </div>
      </div>
    </div>

    <div class="right-column">
      <div class="summary-card navy">
        <div class="card-content">
          <div>
            <div class="title">Total Request</div>
            <div class="value"><span id="ttlrequest"></span></div>
          </div>
          <i class="icon fas fa-tasks"></i>
        </div>
      </div>

      <div class="box chart-box">
        <div class="box-title">Status Pengajuan</div>
        <div class="box-value">
          <canvas id="byStatusPengajuan" style="margin-top: 15px;"></canvas>
        </div>
      </div>

      <div class="box chart-box">
        <div class="box-title">Status Employee</div>
        <div class="box-value">
          <canvas id="byStatusEmployee" style="margin-top: 15px;"></canvas>
        </div>
      </div>
    </div>
  </div>



  
  


</div>

