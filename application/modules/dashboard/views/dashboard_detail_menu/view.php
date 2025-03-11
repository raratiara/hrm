<?php 
if  (_USER_ACCESS_LEVEL_VIEW == "1" && _USER_ACCESS_LEVEL_EKSPORT == "1") { 
	$this->load->view(_TEMPLATE_PATH . "modal/eksport"); // standard
}
?>



<input type="hidden" id="id_fc" name="id_fc">

<div class="row ca">
	<div class="col-md-6 col-sm-6 col-xs-6">
		<span class="tblCctv"></span>
	</div>


    <div class="col-md-6 col-sm-6 col-xs-6">
		<div class="portlet box green">
			<div class="portlet-title">
				<div class="caption"><span class="title_maps">Activity Monitor</span></div>
				<div class="actions">
					<a class="btn btn-default btn-sm btn-circle" id="btnEksportData">
						<i class="fa fa-download"></i>
						Eksport
					</a>
				</div>
				<div class="tools">
				</div>
			</div>
			<div class="portlet-body">
				<!-- <div class="table-scrollable"> -->
				<table class="table table-striped table-bordered table-hover tblActMonitor" id="dynamic-table">
					<thead>
						<tr>
							<th scope="col">Date</th>
							<th scope="col">Order No</th>
							<th scope="col">Order Name</th>
							<th scope="col">Floating Crane</th>
							<th scope="col">Mother Vessel</th>
							<th scope="col">Activity</th>
							<th scope="col">Start Time</th>
							<th scope="col">End Time</th>
							<th scope="col">Total Time</th>
							<th scope="col">Degree</th>
							<th scope="col">Degree 2</th>
							<th scope="col">PIC</th>
							<th scope="col">Status</th>
						
						</tr>
					</thead>
					<tbody>
						
					</tbody>
					<tfoot>
					</tfoot>
				</table>
				<!-- </div> -->
			</div>
		</div>
	</div>
</div>


<!-- <div>
	<span class="tblCctv">
		
</span>
</div> -->






<div class="row">
	<div class="col-md-4 col-sm-12">
		<div class="form-group">
			<label class="col-md-3 control-label no-padding-right">Start Date</label>
			<div class="col-md-6">
				<?=$txtstartdate;?>
			</div>
		</div>
	</div>
	<div class="col-md-4 col-sm-12">
		<div class="form-group">
			<label class="col-md-3 control-label no-padding-right">End Date </label>
			<div class="col-md-6">
				<?=$txtenddate;?>
			</div>
		</div>
	</div>
	<div class="col-md-4 col-sm-12">
		<div class="form-group">
			<button type="button" class="btn btn-primary btnApply" onclick="getDateRange()">Apply Date Range</button>
		</div>
	</div>

	<div class="col-md-6 col-sm-6">
		<div style="width:100%;height:100%;text-align:center">
		    <h2 class="page-header" >Job Order Reports </h2>
		    <div><span id="title_job"></span> </div>
		    <canvas  id="chartjs_bar" style="height: 250px; width: 500px;"></canvas>
		</div>
	</div>
	<div class="col-md-6 col-sm-6">
		<div style="width:100%;height:100%;text-align:center; display:none" id="tblActRpt">
		    <h2 class="page-header" >Activity Reports</h2>
		    <div><span id="title_activity"></span> </div>
		    <canvas  id="chartjs_bar_activity" style="height: 250px; width: 500px;"></canvas>
		</div>
	</div>
</div>




<div class="row" id="tblDtlWaktu" style="display:none">
	<div class="col-md-6 col-sm-6">
		<div style="width:100%;height:100%;text-align:center">
	        <h2 class="page-header" >Detail Waktu Activity </h2>
	        <div>Cycle Time </div>
	        <canvas  id="chartjs_line"></canvas>
	    </div>   
	</div>
	<div class="col-md-6 col-sm-6" style="width:50%;height:100%;text-align:center; margin-top: 40px">
		<div class="portlet box green">
			<div class="portlet-title">
				<div class="caption"><span class="title_maps">Detail Waktu Activity Report</span></div>
				<div class="tools">
				</div>
			</div>
			<div class="portlet-body">
				<!-- <div class="table-scrollable"> -->
				<table class="table table-striped table-bordered table-hover" id="tbldetailWaktuAct">
					<thead>
						<tr>
							<th scope="col">Datetime Start</th>
							<th scope="col">Datetime End</th>
							<th scope="col">Cycle Time</th>
							<th scope="col">Degree</th>
							<th scope="col">Degree 2</th>
						</tr>
					</thead>
					<tbody>
						
					</tbody>
					<tfoot>
					</tfoot>
				</table>
				<!-- </div> -->
			</div>
		</div>
	</div>
</div>



	


	

	



		
	


	 




