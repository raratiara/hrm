

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">



<div class="dashboard-container">


	<!-- <div class="top-bar">

		<div class="date-picker-wrapper">
			<span class="date-icon"><i class="fas fa-calendar-alt"></i></span>
			
			<input type="text" class="form-control date-input" id="fldateperiod" name="fldateperiod">
		</div>


		<div class="employee-select-wrapper">
			<span class="employee-icon"><i class="fas fa-user"></i></span>
			
			<?=$selemp?>
		</div>
	</div> -->

	<br>

	<div class="summary-container">
		<div class="summary-card navy">
			<div class="card-content">
				<div>
					<div class="title">BPM</div>
					<div class="value"><span id="bpm"></span></div>
					<span id="bpm_desc" style="font-size:10px; font-style: italic;"></span>
				</div>
				
				<i class="icon fas fa-heart-pulse"></i>
			</div>
		</div>



		<div class="summary-card yellow">
			<div class="card-content">
				<div>
					<div class="title">Spo2</div>
					<div class="value"><span style="font-size:16px" id="spo2"></span></div>
					<span id="spo2_desc" style="font-size:10px; font-style: italic;"></span>
				</div>
				
				<i class="icon fas fa-droplet"></i>
			</div>
		</div>

		
		<div class="summary-card beige">
		  <div class="card-content">
		    <div>
		      <div class="title">Sleep</div>
		      
		      <div class="value"><span id="sleep"></span><span id="sleep_desc_hrs"> hrs</span> <span id="sleep_mins"></span><span id="sleep_desc_mins"> mins</span></div>
		      <span id="sleep_desc" style="font-size:10px; font-style: italic;"></span>
		    </div>
		    <i class="icon fas fa-moon"></i>
		  </div>
		</div>


		<div class="summary-card white">
			<div class="card-content">
				<div>
					<div class="title">Fatigue</div>
					<div class="value"><span id="fatigue"></span></div>
					<span id="fatigue_category" style="font-size:10px; font-style: italic;"></span>
				</div>
				<i class="icon fas fa-notes-medical"></i>
			</div>
		</div>
	</div>

	<div class="chart-container">
		<div class="box">
			<div class="box-title">Sleep Analysis</div>
			<div class="box-value">
				<canvas id="canvas_sleeps" style="margin-top: 15px;"></canvas>
			</div>
		</div>
		<div class="box">
			<div class="box-title">Steps</div>
			<div class="box-value">
				<canvas id="canvas_steps" style="margin-top: 15px;"></canvas>
			</div>
		</div>
	</div>


	<div class="chart-row">
		<div class="chart-boxes">
			<div class="box">
				<div class="box-title">Vital Signs Trend</div>
				<div class="box-value">
					<canvas id="canvas_vitalsigns" style="margin-top: 15px;"></canvas>
				</div>
			</div>
			
		</div>

		<div class="right-container">
			<div class="summary-card grey">
				<div class="card-content">
					<div>
						<div class="title">Avg BPM</div>
						<div class="value"><span id="avg_bpm"></span></div>
					</div>
					<i class="icon fas fa-heart-pulse"></i>
				</div>
			</div>
			<div class="summary-card grey">
				<div class="card-content">
					<div>
						<div class="title">Avg SpO2</div>
						<div class="value"><span id="avg_spo2"></span></div>
					</div>
					<i class="icon fas fa-droplet"></i>
				</div>
			</div>
		</div>
	</div>
	


</div>





