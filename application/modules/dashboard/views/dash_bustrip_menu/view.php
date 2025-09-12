

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">



<div class="dashboard-container">


	<div class="top-bar">

		<!-- <div class="date-picker-wrapper">
			<span class="date-icon"><i class="fas fa-calendar-alt"></i></span>
			<input type="month" id="fldashdateperiod" name="fldashdateperiod" placeholder="Select Date"
				class="date-input" />
		</div> -->


		<div class="employee-select-wrapper">
			<span class="employee-icon"><i class="fas fa-user"></i></span>
			<!-- <select id="fldashemp" name="fldashemp" class="dropdown-select" required>
				<option value="">Select Employee</option>
				<?php foreach ($master_emp as $row): ?>
					<option value="<?= $row->id ?>"><?= $row->full_name ?></option>
				<?php endforeach; ?>
			</select> -->
			<?=$seldiv?>
		</div>
	</div>


	<div class="summary-container">
		<div class="summary-card navy">
			<div class="card-content">
				<div>
					<div class="title">Total Trip</div>
					<div class="value"><span id="ttl_trip"></span></div>
				</div>
				<i class="icon fas fa-plane"></i>
			</div>
		</div>



		<div class="summary-card yellow">
			<div class="card-content">
				<div>
					<div class="title">Total Budget</div>
					<div class="value"><span style="font-size:16px" id="ttl_budget"></span></div>
				</div>
				<i class="icon fas fa-calculator"></i>
				
			</div>
		</div>

		<!-- <div class="summary-card beige">
			<div class="card-content">
				<div>
					<div class="title">Status</div>
				 	<div class="earlylogin-line">
	                    <div><strong><span class="total_waitingapproval"></span></strong> Waiting Approval</div>
	                    <div><strong><span class="total_approved"></span></strong> Approved</div>
	                    <div><strong><span class="total_rejected"></span></strong> Rejected</div>
	                </div>
				</div>
				<i class="icon fas fa-chart-line"></i>
			</div>
		</div> -->
		<div class="summary-card beige">
		  <div class="card-content">
		    <div>
		      <div class="title">Status</div>
		      <div class="earlylogin-line">
		        <div class="status-row">
		          <strong><span class="total_waitingapproval">1</span></strong>
		          <span class="txtt">Waiting Approval</span>
		        </div>
		        <div class="status-row">
		          <strong><span class="total_approved">1</span></strong>
		          <span class="txtt">Approved</span>
		        </div>
		        <div class="status-row">
		          <strong><span class="total_rejected">0</span></strong>
		          <span class="txtt">Rejected</span>
		        </div>
		      </div>
		    </div>
		    <i class="icon fas fa-chart-line"></i>
		  </div>
		</div>


		<div class="summary-card white">
			<div class="card-content">
				<div>
					<div class="title">Avg Trip Duration</div>
					<div class="value"><span class="avg_days"></span><span class="txtdays"></span></div>
				</div>
				<div class="icon-2">
					<i class="fas fa-calendar-check-o"></i>
				</div>
			</div>
		</div>
	</div>

	<div class="chart-container">
		<div class="box">
			<div class="box-title">Business Trip by Division</div>
			<div class="box-value">
				<canvas id="bustripbyDiv"></canvas>
			</div>
		</div>
		<div class="box">
			<div class="box-title">Cost by Type</div>
			<div class="box-value">
				<canvas id="costbyType" style="margin-top: 15px;"></canvas>
			</div>
		</div>
	</div>


	<div class="chart-container">
		<div class="box">
			<div class="box-title">Monthly Trip Summary</div>
			<div class="box-value">
				<canvas id="monthlyTripSummary"></canvas>
			</div>
		</div>
		
	</div>
	


</div>







</div>


