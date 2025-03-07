
<input type="hidden" id="id_fc" name="id_fc">

<div class="row ca">
    <div class="col-md-12 col-sm-12 col-xs-12">
		<div class="portlet box green">
			<div class="portlet-title">
				<div class="caption"><span class="title_maps">Activity Monitor</span></div>
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



<span class="tblCctv">
		
</span>



<!-- &nbsp; &nbsp;
<div class="row">
	<div class="col-md-6 col-sm-6">
		<div class="form-group">
			<label class="col-md-2 control-label no-padding-right">Start Date</label>
		</div>
		<div class="col-md-4">
			<input type="text" name="">
		</div>
	</div>
	<div class="col-md-6 col-sm-6">
		<div class="form-group">
			<label class="col-md-2 control-label no-padding-right">End Date</label>
		</div>
		<div class="col-md-4">
			<input type="text" name="">
		</div>
	</div>
</div> &nbsp; &nbsp;
<div></div>  -->



<!-- <div class="row ca">
    <div class="col-md-12">
		<div class="portlet box green col-md-6" style="width:800px">
			<div class="portlet-title">
				<div class="caption">Job Bar</div>
				<div class="tools">
			
				</div>
			</div>
			<div class="portlet-body">
				<div class="table-scrollable tablesaw-cont">
				


				</div>
			</div>
		</div>
		<div class="col-md-2"></div>
		<div class="portlet box green col-md-6" style="width:800px">
			<div class="portlet-title">
				<div class="caption">Activity Bar</div>
				<div class="tools">
			
				</div>
			</div>
			<div class="portlet-body">
				<div class="table-scrollable tablesaw-cont">
				


				</div>
			</div>
		</div>
	</div>
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
		<div style="width:100%;height:100%;text-align:center">
		    <h2 class="page-header" >Activity Reports</h2>
		    <div><span id="title_activity"></span> </div>
		    <canvas  id="chartjs_bar_activity" style="height: 250px; width: 500px;"></canvas>
		</div>
	</div>
</div>



	


	

	



		
	


	 




