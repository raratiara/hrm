<div class="row">
	
	<div class="col-md-6 col-sm-12">
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Date Attendance</label>
			<div class="col-md-8">
				<?=$txtdateattendance;?>
				<input type="hidden" id="latitude" name="latitude" />
				<input type="hidden" id="longitude" name="longitude" />
			</div>
		</div>

		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Employee Name</label>
			<div class="col-md-8">
				<?=$selemployee;?>
				<input type="hidden" id="hdnempid" name="hdnempid" value="<?=$empid?>" />
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Time In</label>
			<div class="col-md-8">
				<?=$txtimein;?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Attendance IN</label>
			<div class="col-md-8">
				<?=$txtattendancein;?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Description</label>
			<div class="col-md-8">
				<?=$txtdesc;?>
			</div>
		</div>
		
	</div>
	<div class="col-md-6 col-sm-12">
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Location</label>
			<div class="col-md-8">
				<?=$selloc;?>
			</div>
		</div>

		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Absence Type</label>
			<div class="col-md-8">
				<?=$txtemptype;?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Time Out </label>
			<div class="col-md-8">
				<?=$txtimeout;?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Attendance OUT </label>
			<div class="col-md-8">
				<?=$txtattendanceout;?>
			</div>
		</div>
		
		
	</div>
</div>



<div class="row tasklist">
    <div class="col-md-12">
		<div class="portlet box">
			<div class="portlet-title">
				<div class="caption">Task List </div>
				
			</div>
			<div class="portlet-body">
				<div class="table-scrollable tablesaw-cont">
				<table class="table table-striped table-bordered table-hover task-list tablesaw tablesaw-stack" data-tablesaw-mode="stack" id="tblTasklist">
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



								