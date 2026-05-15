<input type="hidden" name="action_type" value="">
<input type="hidden" name="payroll_action" value="draft">

<div class="row">
<div id="payroll-period-warning" class="alert alert-warning" style="display:none; margin:10px 15px;">
	<i class="fa fa-exclamation-triangle"></i> <span id="payroll-period-warning-text"></span>
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
		
		<div class="form-group" id="inp_is_all_employee_gaji">
			<label class="col-md-4 control-label no-padding-right">Hitung Semua Karyawan?</label>
			<div class="col-md-8">
				<?=$is_all_employee;?>
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




<div class="row absen_gaji" id="inpAbsenInt_gaji" style="display:none;">
    <div class="col-md-12">
        <div class="portlet box">
            <div class="portlet-title">
                <div class="caption">Details </div>
                <div class="tools">
                   
                </div>
            </div>
            <div class="portlet-body">
                <div class="row" style="margin-bottom:10px;">
                    <div class="col-md-4">
                        <input type="text" id="filterEmployeeEdit" class="form-control input-sm" placeholder="Cari nama karyawan...">
                    </div>
                </div>
                <div class="table-scroll-x">
                    <div class="table-scrollable tablesaw-cont" style="overflow: auto; max-height: 300px;">
                        <table class="table table-striped table-bordered table-hover absen_gaji-list tablesaw tablesaw-stack" data-tablesaw-mode="stack" id="tblDetailAbsenGaji">
                            <thead>
                                <tr>
                                    <th scope="col">NIK</th>
                                    <th scope="col">Karyawan</th>
                                    <th scope="col">Jumlah Jam Kerja</th>
                                    <th scope="col">Jumlah Hadir</th>
                                    <th scope="col">Jumlah Tdk Hadir</th>
                                    <th scope="col">Gaji Bulanan</th>
                                    <th scope="col">Gaji Harian</th>
                                    <th scope="col">Gaji</th>
                                    <th scope="col">Tunj. Jabatan</th>
                                    <th scope="col">Tunj. Transport</th>
                                    <th scope="col">Tunj. Konsumsi</th>
                                    <th scope="col">Tunj. Komunikasi</th>
                                    <th scope="col">Lembur per jam</th>
                                    <th scope="col">Total Jam Lembur</th>
                                    <th scope="col">Total Nominal Lembur</th>
                                    <th scope="col" class="gaji-bonus-col">Bonus</th>
                                    <th scope="col" class="gaji-thr-col">THR</th>
                                    <th scope="col">Total Pendapatan</th>
                                    <th scope="col">BPJS Kesehatan</th>
                                    <th scope="col">BPJS TK</th>
                                    <th scope="col">JHT</th>
                                    <th scope="col">JP</th>
                                    <th scope="col">JKK</th>
                                    <th scope="col">JKM</th>

                                    <!-- <th scope="col">Absen</th> -->
                                    <th scope="col">Seragam</th>
                                    <th scope="col">Pelatihan</th>
                                    <th scope="col">Lain-Lain</th>
                                    <th scope="col">Hutang</th>
                                    <th scope="col">Sosial</th>
                                    <th scope="col">Payroll</th>
                                    <th scope="col">PPh 21</th>
                                    <th scope="col">Sub Total</th>
                                    <th scope="col">Gaji Bersih</th>
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
</div>


				
