

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">



<div class="dashboard-container">


	<div class="top-bar">

		<div class="employee-select-wrapper">
			<span class="employee-icon"><i class="fa-solid fa-user"></i></span>
			
			<?=$selCust?>
		</div>

		<!-- <div class="row">
			<div class="col-md-12 col-sm-12 filter-wrapper">
				<div class="form-group custom-form" id="inp_is_all_project_gaji">
				  <label>Lihat Semua Project?</label>
				  <div class="radio-group">
				    <?=$is_all_project;?>
				  </div>
				</div>

			</div>
			<div class="col-md-12 col-sm-12 filter-wrapper">
				<div class="form-group custom-form" id="inputProject_gaji" style="display: none;">
				  <label>Project</label>
				  <div class="select-group">
				    <?=$selprojectids;?>
				  </div>
				</div>
			</div>
		</div> -->

	</div>

	<div class="row">
			<div class="col-md-12 col-sm-12 filter-wrapper">
				<div class="form-group custom-form" id="inp_is_all_project_gaji">
				  <label>Lihat Semua Project?</label>
				  <div class="radio-group">
				    <?=$is_all_project;?>
				  </div>
				</div>

			</div>
			<div class="col-md-12 col-sm-12 filter-wrapper">
				<div class="form-group custom-form" id="inputProject_gaji" style="display: none;">
				  <label>Project</label>
				  <div class="select-group">
				    <?=$selprojectids;?>
				  </div>
				</div>
			</div>
		</div>

	<!-- <button type="button" id="btnGenerateProfit" style="width: 100px; text-align: center;" class="btn-sm btn-circle btn-generateProfit" onclick="generateProfit()">
        <i class="fa fa-history"></i> Generate
    </button> -->

	<div class="summary-container" id="summary-container-id" >
		<div class="summary-card navy">
			<div class="card-content">
				<div>
					<div class="title">Total Employee</div>
					<div class="value"><span id="ttl_emp"></span></div> 
				
				</div>
				<i class="icon fas fa-users"></i>
			</div>
		</div>



		<div class="summary-card purple">
			<div class="card-content">
				<div>
					<div class="title">Total Biaya</div>
					<div class="value"><span id="ttl_biaya"></span></div> 
					<!-- <div class="earlylogin-line">
	                    <div><strong><span class="ttl_managerial"></span></strong> Managerial </div>
	                    <div><strong><span class="ttl_nonmanagerial"></span></strong> Non-Managerial</div>
	                </div> -->
				</div>
				<i class="icon fas fa-calendar-alt"></i>
				
			</div>
		</div>

		<div class="summary-card beige">
			<div class="card-content">
				<div>
					<div class="title">Total Tagihan</div>
					
	                <div class="value"><span id="ttl_tagihan"></span></div> 
				</div>
				<i class="icon fas fa-chart-line"></i>
			</div>
		</div>

	</div>

	

	<!-- <div class="chart-container">
		<div class="box monthlySumm">
			<div class="box-title">Monthly Cash Advance Summary</div>
			<div class="box-value">
				<canvas id="monthly_ca_summ" style="margin-top: 15px;"></canvas>
			</div>
		</div>
		<div class="box byDiv">
			<div class="box-title">Cash Advance by Division</div>
			<div class="box-value">
				<canvas id="cabyDiv" style="margin-top: 15px;"></canvas>
			</div>
		</div>
	</div> -->


	<!-- <div class="employee-container">

		<div class="box typeSubtype">
			<div class="box-title">Outstanding Settlement</div>
			<div class="box-value">
				<canvas id="outstandingSett" style="margin-top: 15px;"></canvas>
			</div>
		</div>


		<div class="box reimbursfor">
			<div class="box-title">FPP Type</div>
			<div class="box-value">
				<canvas id="fppType" style="margin-top: 15px;"></canvas>
			</div>
		</div>

	</div> -->

	<div class="employee-container" id="employee-container-id" >

		<div class="box typeSubtype">
			<div class="box-title">Monthly Profit Amount</div>
			<div class="box-value">
				<canvas id="monthly_profit_amount" style="margin-top: 15px;"></canvas>
			</div>
		</div>


	</div>
	


</div>


</div>


