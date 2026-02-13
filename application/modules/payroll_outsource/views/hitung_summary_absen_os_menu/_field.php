<div class="row">

	<div class="col-md-12 col-sm-12" id="projectView" style="display:none">
		<div class="form-group">
			<label class="col-md-2 control-label no-padding-right">Project</label>
			<div class="col-md-4">
				<?=$txtprojectview;?>
			</div>
		</div>
	</div>
	
	
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

		
		<div class="form-group" id="inp_is_all_project">
			<label class="col-md-4 control-label no-padding-right">Hitung Semua Project?</label>
			<div class="col-md-8">
				<?=$is_all_project;?>
			</div>
		</div>

		<div class="form-group" id="inputProject" style="display: none;">
			<label class="col-md-4 control-label no-padding-right">Project</label>
			<div class="col-md-8">
				<?=$selprojectids;?>
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




<div class="row absenos" id="inpAbsenOS" style="display:none">
    <div class="col-md-12">
		<div class="portlet box">
			<div class="portlet-title">
				<div class="caption">Details </div>
				<div class="tools">
					
				</div>
			</div>
			<div class="portlet-body">
				<div class="table-scrollable tablesaw-cont">
				<table class="table table-striped table-bordered table-hover absenos-list tablesaw tablesaw-stack" data-tablesaw-mode="stack" id="tblDetailAbsenOS">
					<thead>
						<tr>
							<th scope="col">NIK</th>
							<th scope="col">Karyawan</th>
							<th scope="col">Total Hari Kerja</th>
							<th scope="col">Total Masuk</th>
							<th scope="col">Total Ijin</th>
							<th scope="col">Total Cuti</th>
							<th scope="col">Total Alfa</th>
							<th scope="col">Total Jam Kerja</th>
							<th scope="col">Total Jam Lembur</th>
							<th scope="col">Total Nominal Lembur</th>
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


