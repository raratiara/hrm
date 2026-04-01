<div class="row">

	<div class="col-md-6 col-sm-12">
		<div class="form-group" id="projectView" style="display:none">
			<label class="col-md-4 control-label no-padding-right">Project</label>
			<div class="col-md-8">
				<?=$txtprojectview;?>
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Tahun</label>
			<div class="col-md-8">
				<?=$txtyear;?>
			</div>
		</div>

		<div class="form-group" id="inp_is_all_project">
			<label class="col-md-4 control-label no-padding-right">Generate Semua Project?</label>
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
		<div class="form-group" id="statusView" style="display:none">
			<label class="col-md-4 control-label no-padding-right">Status</label>
			<div class="col-md-8">
				<?=$selstatus;?>
			</div>
		</div>
		
	</div>



	<!-- <div class="col-md-12 col-sm-12" id="projectView" style="display:none">
		<div class="form-group">
			<label class="col-md-2 control-label no-padding-right">Project</label>
			<div class="col-md-4">
				<?=$txtprojectview;?>
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
		

		<div class="form-group" id="inpEmp" style="display: none;">
		    <label class="col-md-4 control-label no-padding-right">
		        Karyawan
		    </label>
		    <div class="col-md-8">
		        : <span class="employee_name form-control-static"></span>
		    </div>
		</div>

		
		<div class="form-group" id="inp_is_all_project">
			<label class="col-md-4 control-label no-padding-right">Generate Semua Project?</label>
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
		
	</div> -->
	
</div>




<div class="row sptos" id="inpSptOS" style="display:none">
    <div class="col-md-12">
		<div class="portlet box">
			<div class="portlet-title">
				<div class="caption">Details </div>
				<div class="tools">
					
				</div>
			</div>
			<div class="portlet-body">
				<div class="table-scrollable tablesaw-cont">
				<table class="table table-striped table-bordered table-hover sptos-list tablesaw tablesaw-stack" data-tablesaw-mode="stack" id="tblDetailSptOS">
					<thead>
						<tr>
							<th scope="col">NIK</th>
							<th scope="col">Karyawan</th>
							<th scope="col">Start Period</th>
							<th scope="col">End Period</th>
							<th scope="col">Bruto Tahunan</th>
							<th scope="col">Biaya Jabatan</th>
							<th scope="col">Iuran</th>
							<th scope="col">Neto Tahunan</th>
							<th scope="col">PTKP</th>
							<th scope="col">PKP</th>
							<th scope="col">PPH 21 Tahunan</th>
							<th scope="col">PPH 21 Ter Total</th>
							<!-- <th scope="col">Kurang Lebih Bayar</th> -->
							
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


