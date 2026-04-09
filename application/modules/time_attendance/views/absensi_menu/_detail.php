<div class="row">
	
	<div class="col-md-6 col-sm-12 col-xs-12">
		<div class="row">
			<label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Date Attendance</label>
			<div class="col-md-8 col-sm-8 col-xs-8">
				: <span class="date_attendance"></span>
			</div>
		</div>
		<div class="row">
			<label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Employee Name</label>
			<div class="col-md-8 col-sm-8 col-xs-8">
				: <span class="employee"></span>
			</div>
		</div>
		<div class="row">
			<label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Time In</label>
			<div class="col-md-8 col-sm-8 col-xs-8">
				: <span class="time_in"></span>
			</div>
		</div>
		<div class="row">
			<label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Attendance IN</label>
			<div class="col-md-8 col-sm-8 col-xs-8">
				: <span class="attendance_in"></span>
			</div>
		</div>
		<div id="mapContainer_checkin" style="margin-top:10px;"></div>

		<div class="row">
			<label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Description</label>
			<div class="col-md-8 col-sm-8 col-xs-8">
				: <span class="description"></span>
			</div>
		</div>
		<div class="row">
			<label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Photo</label>
			<div class="col-md-8 col-sm-8 col-xs-8">
				: <span class="photo"></span>
			</div>
		</div>
	</div>



	<div class="col-md-6 col-sm-12 col-xs-12">
		<div class="row">
			<label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Location</label>
			<div class="col-md-8 col-sm-8 col-xs-8">
				: <span class="work_loc"></span>
			</div>
		</div>
		<div class="row">
			<label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Absence Type</label>
			<div class="col-md-8 col-sm-8 col-xs-8">
				: <span class="emp_type"></span>
			</div>
		</div>
		<div class="row">
			<label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Time Out</label>
			<div class="col-md-8 col-sm-8 col-xs-8">
				: <span class="time_out"></span>
			</div>
		</div>
		<div class="row">
			<label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Attendance OUT</label>
			<div class="col-md-8 col-sm-8 col-xs-8">
				: <span class="attendance_out"></span>
			</div>
		</div>
		<!-- <div class="row">
			<label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Latitude</label>
			<div class="col-md-8 col-sm-8 col-xs-8">
				: <span class="latitude"></span>
			</div>
		</div>
		<div class="row">
			<label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Longitude</label>
			<div class="col-md-8 col-sm-8 col-xs-8">
				: <span class="longitude"></span>
			</div>
		</div> -->
		<div id="mapContainer" style="margin-top:10px;"></div>
		
	</div>
</div>




<div class="row tasklist">
    <div class="col-md-12">
		<div class="portlet box">
			<div class="portlet-title">
				<div class="caption">Task List </div>
				<div class="tools">
					
				</div>
			</div>
			<div class="portlet-body">
				<div class="table-scrollable tablesaw-cont">
				<table class="table table-striped table-bordered table-hover task-list-view tablesaw tablesaw-stack" data-tablesaw-mode="stack" id="tblTasklist">
					<thead>
						<tr>
							<th scope="col">No</th>
							<th scope="col">Task</th>
							<th scope="col">Project</th>
							<th scope="col">Due Date</th>
							<th scope="col">Progress</th>
							<th scope="col">Status</th>
						</tr>
					</thead>
					<tbody>
						
					</tbody>
					<tfoot>
					</tfoot>
				</table>
				</div>
			</div>
		</div>
	</div>
</div>