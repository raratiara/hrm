<div class="row">
	
	<div class="col-md-6 col-sm-12">
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Penggajian Bulan</label>
			<div class="col-md-8">
				<?=$selmonth;?>
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Periode Absen Start</label>
			<div class="col-md-8">
				<?=$txtperiodstart;?>
			</div>
		</div>

		<div class="form-group" id="inpEmp" style="display: none;">
		    <label class="col-md-4 control-label no-padding-right">
		        Karyawan
		    </label>
		    <div class="col-md-8">
		        : <span class="employee_name form-control-static"></span>
		    </div>
		</div>

		<div class="form-group" id="inp_is_all_employee">
			<label class="col-md-4 control-label no-padding-right">Hitung Semua Karyawan?</label>
			<div class="col-md-8">
				<?=$is_all_employee;?>
			</div>
		</div>

		<div class="form-group" id="inputEmployee" style="display: none;">
			<label class="col-md-4 control-label no-padding-right">Karyawan</label>
			<div class="col-md-8">
				<?=$selemployeeids;?>
			</div>
		</div>
		
	</div>
	<div class="col-md-6 col-sm-12">
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Tahun</label>
			<div class="col-md-8">
				<?=$txtyear;?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">End </label>
			<div class="col-md-8">
				<?=$txtperiodend;?>
			</div>
		</div>
		
		
	</div>
</div>



								