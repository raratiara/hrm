<?php 
if  (_USER_ACCESS_LEVEL_VIEW == "1" && _USER_ACCESS_LEVEL_EKSPORT == "1") { 
	$this->load->view(_TEMPLATE_PATH . "modal/eksport"); // standard
}
?>



<input type="hidden" id="id_fc" name="id_fc">
&nbsp; &nbsp;

<div class="row">
	<div class="col-md-12 col-sm-12">
		<div class="col-md-6 col-sm-12">
			<div class="form-group">
				<label class="col-md-4 control-label no-padding-right">Floating Crane</label>
				<div class="col-md-6">
					<?=$selfloatcrane;?>
				</div>
			</div>
		</div>
		<div class="col-md-6 col-sm-12">
			<div class="form-group">
				<label class="col-md-4 control-label no-padding-right">Datetime Start </label>
				<div class="col-md-6">
					<?=$txtdatetimestart;?>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-12 col-sm-12">
		<div class="col-md-6 col-sm-12">
			<div class="form-group">
				<label class="col-md-4 control-label no-padding-right">Order Name</label>
				<div class="col-md-6">
					<?=$selordername;?>
				</div>
			</div>
		</div>
		<div class="col-md-6 col-sm-12">
			<div class="form-group">
				<label class="col-md-4 control-label no-padding-right">Current Datetime </label>
				<div class="col-md-6">
					<?=$txtcurrdatetime;?>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-12 col-sm-12">
		<div class="col-md-6 col-sm-12">
			<div class="form-group">
				<label class="col-md-4 control-label no-padding-right">Mother Vessel</label>
				<div class="col-md-6">
					<?=$txtmothervessel;?>
				</div>
			</div>
		</div>
		<div class="col-md-6 col-sm-12">
			<div class="form-group">
				<label class="col-md-4 control-label no-padding-right">Process Time </label>
				<div class="col-md-6">
					<?=$txtprocesstime;?>
				</div>
			</div>
		</div>
	</div>
</div>

&nbsp; &nbsp;

<div class="row ca">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<span class="tblCctv"></span>
	</div>
</div>


<div class="row">
	<div class="col-md-8 col-sm-6 col-xs-6">
		<!-- <div class="portlet box green">
			<div class="portlet-title">
				<div class="caption"><span class="title_maps">Realtime Analytics Detection</span></div>
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
				<table class="table table-striped table-bordered table-hover tblActMonitor" id="dynamic-table">
					<thead>
						<tr>
							<th scope="col">Activity</th>
							<th scope="col">Start Time</th>
							<th scope="col">End Time</th>
							<th scope="col">Total Time</th>
							<th scope="col">Degree</th>
							<th scope="col">Degree 2</th>
						</tr>
					</thead>
					<tbody>
						
					</tbody>
					<tfoot>
					</tfoot>
				</table>
			</div>
		</div> -->

		<span class="tblDataRealtime"></span>
	</div> 
	<div class="col-md-4 col-sm-6">
		<div class="row ca">
            <div class="col-md-12">
				<div class="portlet box green">
					<div class="portlet-title">
						<div class="caption">SLA Cycle Time (%)</div>
						<div class="tools"></div>
					</div>
					<div class="portlet-body">
						<div class="table-scrollable tablesaw-cont">
							<canvas  id="chartjs_pie" style="height: 180px; width: 360px;"></canvas>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row ca">
            <div class="col-md-12">
				<div class="portlet box green">
					<div class="portlet-title">
						<div class="caption">SLA Cycle Time (Jml)</div>
						<div class="tools"></div>
					</div>
					<div class="portlet-body">
						<div class="table-scrollable tablesaw-cont">
							<canvas  id="chartjs_cycle_bar" style="height: 250px; width: 500px;"></canvas>
						</div>
					</div>
				</div>
			</div>
		</div>
		
	</div>

</div>






<div class="row">
	<div class="col-md-12 col-sm-12">
		<div class="col-md-4 col-sm-12">
			<div class="form-group">
				<label class="col-md-3 control-label no-padding-right">Periode</label>
				<div>
					<?=$txtstartdate;?>
				</div>
			</div>
		</div>
		<div class="col-md-4 col-sm-12">
			<div class="form-group">
				<label class="col-md-3 control-label no-padding-right">s/d. </label>
				<div>
					<?=$txtenddate;?>
				</div>
			</div>
		</div>
		<div class="col-md-4 col-sm-12">
			<div class="form-group">
				<button type="button" class="btn btn-primary btnApply" onclick="getDateRange()">Apply Date Range</button>
			</div>
		</div>
	</div>
	
</div>


<div class="row">
	<div class="col-md-6 col-sm-6">
		<div class="row ca">
            <div class="col-md-12">
				<div class="portlet box green">
					<div class="portlet-title">
						<div class="caption">Summary Waktu Pekerjaan</div>
						<div class="tools">
							
						</div>
					</div>
					<div class="portlet-body">
						<div style="width:100%;height:100%;text-align:center">
							<div style="text-align:right;">
								<a class="btn btn-default btn-sm btn-circle" id="downloadCSV_pekerjaan">
									<i class="fa fa-download"></i>
									CSV
								</a>
								<a class="btn btn-default btn-sm btn-circle" id="downloadImage_pekerjaan">
									<i class="fa fa-download"></i>
									Image
								</a>
							</div>
							<div><span id="title_job"></span> </div>
							<canvas  id="chartjs_bar" style="height: 250px; width: 500px;"></canvas>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-6 col-sm-6">
		<div class="row ca">
            <div class="col-md-12">
				<div class="portlet box green">
					<div class="portlet-title">
						<div class="caption">Summary Waktu Per Activity</div>
						<div class="tools">
							
						</div>
					</div>
					<div class="portlet-body">
						<div style="width:100%;height:100%;text-align:center">
							<div style="text-align:right;">
								<a class="btn btn-default btn-sm btn-circle" id="downloadCSV_activity">
									<i class="fa fa-download"></i>
									CSV
								</a>
								<a class="btn btn-default btn-sm btn-circle" id="downloadImage_activity">
									<i class="fa fa-download"></i>
									Image
								</a>
							</div>
							<div><span id="title_activity"></span> </div> 
							<canvas  id="chartjs_bar_activity" style="height: 250px; width: 500px;"></canvas>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>





<div class="row" id="tblDtlWaktu" >
	<div class="col-md-12 col-sm-12">
		<div class="row ca">
            <div class="col-md-12">
				<div class="portlet box green">
					<div class="portlet-title">
						<div class="caption">Detail Activity</div>
						<div class="tools">
							
						</div>
					</div>
					<div class="portlet-body">
						<div style="width:100%;height:100%;text-align:center">
							<div style="text-align:right;">
								<a class="btn btn-default btn-sm btn-circle" id="downloadCSV">
									<i class="fa fa-download"></i>
									CSV
								</a>
								<a class="btn btn-default btn-sm btn-circle" id="downloadImage">
									<i class="fa fa-download"></i>
									Image
								</a>
							</div>
					        <div>Cycle Time</div>
					        <canvas  id="chartjs_line"></canvas>
					    </div>  
					</div>
				</div>
			</div>
		</div>
		
	</div>
	<!-- <div class="col-md-6 col-sm-6" style="width:50%;height:100%;text-align:center; margin-top: 40px">
		<div class="portlet box green">
			<div class="portlet-title">
				<div class="caption"><span class="title_maps">Detail Waktu Activity Report</span></div>
				<div class="tools">
				</div>
			</div>
			<div class="portlet-body">
				
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
				
			</div>
		</div>
	</div> -->
</div>



	


	

	



		
	


	 




