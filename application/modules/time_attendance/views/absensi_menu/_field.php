<div class="row">
	
	<div class="col-md-6 col-sm-12">
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Date Attendance</label>
			<div class="col-md-8">
				<?=$txtdateattendance;?>
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
		<!-- <div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Late</label>
			<div class="col-md-8">
				<?=$txtlatedesc;?>
			</div>
		</div> -->
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
		<!-- <div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Leaving Office Early</label>
			<div class="col-md-8">
				<?=$txtleavingearlydesc;?>
			</div>
		</div> -->
		
	</div>
</div>



								