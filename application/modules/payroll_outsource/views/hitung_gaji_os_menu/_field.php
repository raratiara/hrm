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

		<div class="form-group" id="inpEmp_gaji" style="display: none;">
		    <label class="col-md-4 control-label no-padding-right">
		        Karyawan
		    </label>
		    <div class="col-md-8">
		        : <span class="employee_name form-control-static"></span>
		    </div>
		</div>

		
		<div class="form-group" id="inp_is_all_project_gaji">
			<label class="col-md-4 control-label no-padding-right">Hitung Semua Project?</label>
			<div class="col-md-8">
				<?=$is_all_project;?>
			</div>
		</div>

		<div class="form-group" id="inputProject_gaji" style="display: none;">
			<label class="col-md-4 control-label no-padding-right">Project</label>
			<div class="col-md-8">
				<?=$selprojectids;?>
			</div>
		</div>

		

		<div class="form-group" id="inputEmployee_gaji" style="display: none;">
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




<div class="row absenos" id="inpAbsenOS_gaji" style="display:none;">
    <div class="col-md-12">
		<div class="portlet box">
			<div class="portlet-title">
				<div class="caption">Details </div>
				<div class="tools">
					<!-- <input type="button" class="btn btn-default blue btn-outline btn-circle btn-sm active" id="addabsenosrow" value="Add Row" /> -->
				</div>
			</div>
			<div class="portlet-body">

				<div class="row">
					<div class="col-md-6 col-sm-12">
						<div class="form-group">
							<label class="col-md-4 control-label no-padding-right">Gaji Bulanan</label>
							<div class="col-md-8">
								<?=$txtgajibulanan;?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label no-padding-right">Gaji</label>
							<div class="col-md-8">
								<?=$txtgajiharian;?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label no-padding-right">Konsumsi</label>
							<div class="col-md-8">
								<?=$txtxx;?>
							</div>
						</div>
						
					</div>
					<div class="col-md-6 col-sm-12">
						<div class="form-group">
							<label class="col-md-4 control-label no-padding-right">Gaji Harian</label>
							<div class="col-md-8">
								<?=$txtgajiharian;?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label no-padding-right">T.Transport</label>
							<div class="col-md-8">
								<?=$txtxx;?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label no-padding-right">T.Komunikasi</label>
							<div class="col-md-8">
								<?=$txtxx;?>
							</div>
						</div>
					</div>
				</div>



				<div class="table-scrollable tablesaw-cont">
				<table class="table table-striped table-bordered table-hover absenos-list-gaji tablesaw tablesaw-stack" data-tablesaw-mode="stack" id="tblDetailAbsenOSGaji">
					<thead>
						<tr>
							<th scope="col">Jumlah Jam Kerja</th>
							<th scope="col">Jml Hadir</th>
							<th scope="col">Jml Tidak Hadir</th>
							<th scope="col">Lembur Per Jam</th>
							<th scope="col">OT</th>
							<th scope="col">Jam Lembur</th>
							
						</tr>
					</thead>
					<tbody>
						
					</tbody>
					<tfoot>
					</tfoot>
				</table>
				</div>


				<div class="row">
					<div class="col-md-12 col-sm-12">
						<div class="form-group">
							<label class="col-md-2 control-label no-padding-right">Total Pendapatan</label>
							<div class="col-md-4">
								<?=$txtgajiharian;?>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-sm-12">
						<div class="form-group">
							<label class="col-md-4 control-label no-padding-right">BPJS Kesehatan</label>
							<div class="col-md-8">
								<?=$txtgajiharian;?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label no-padding-right">Absen</label>
							<div class="col-md-8">
								<?=$txtgajiharian;?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label no-padding-right">Pelatihan</label>
							<div class="col-md-8">
								<?=$txtxx;?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label no-padding-right">Hutang</label>
							<div class="col-md-8">
								<?=$txtgajiharian;?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label no-padding-right">Payroll</label>
							<div class="col-md-8">
								<?=$txtxx;?>
							</div>
						</div>
						
					</div>
					<div class="col-md-6 col-sm-12">
						<div class="form-group">
							<label class="col-md-4 control-label no-padding-right">BPJS Ketenagakerjaan</label>
							<div class="col-md-8">
								<?=$txtgajiharian;?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label no-padding-right">Seragam</label>
							<div class="col-md-8">
								<?=$txtxx;?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label no-padding-right">Lain-Lain</label>
							<div class="col-md-8">
								<?=$txtxx;?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label no-padding-right">Sosial</label>
							<div class="col-md-8">
								<?=$txtgajiharian;?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label no-padding-right">PPH 120</label>
							<div class="col-md-8">
								<?=$txtxx;?>
							</div>
						</div>
					</div>
					<div class="col-md-12 col-sm-12">
						<div class="form-group">
							<label class="col-md-2 control-label no-padding-right">Sub Total</label>
							<div class="col-md-4">
								<?=$txtgajiharian;?>
							</div>
						</div>
					</div>
					<div class="col-md-12 col-sm-12">
						<div class="form-group">
							<label class="col-md-2 control-label no-padding-right">Gaji Bersih</label>
							<div class="col-md-4">
								<?=$txtgajiharian;?>
							</div>
						</div>
					</div>
				</div>


			</div>
		</div>
	</div>
</div>


								