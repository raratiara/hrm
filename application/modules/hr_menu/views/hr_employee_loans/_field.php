<div class="row">
	
	<div class="col-md-6 col-sm-12">
		
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Nama Karyawan</label>
			<div class="col-md-8">
				<?=$seloPic;?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Nominal Pinjaman</label>
			<div class="col-md-8">
				<?=$txt_nominal_pinjaman;?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Tenor (dlm bulan)</label>
			<div class="col-md-8">
				<?=$txt_tenor;?>
			</div>
		</div> 
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Sisa Tenor</label>
			<div class="col-md-8">
				<?=$txt_sisa_tenor;?>
			</div>
		</div> 
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Bunga per Bulan</label>
			<div class="col-md-8">
				<?=$txt_bunga_per_bulan;?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Cicilan per bulan </label>
			<div class="col-md-8">
				<?=$txt_nominal_cicilan_per_bulan;?>
				<input type="hidden" id="nominal_cicilan_per_bulan" name="nominal_cicilan_per_bulan">
			</div>
		</div> 
	</div>
	<div class="col-md-6 col-sm-12">
		
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right"> Tgl Pengajuan</label>
			<div class="col-md-8">
				<?=$txt_date_pengajuan;?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right"> Tgl Persetujuan</label>
			<div class="col-md-8">
				<?=$txt_date_persetujuan;?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Tgl  Pencairan</label>
			<div class="col-md-8">
				<?=$txt_date_pencairan;?>
			</div>
		</div> 
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Tgl Start Angsuran</label>
			<div class="col-md-8">
				<?=$txt_date_start_cicilan;?>
			</div>
		</div>  
		<div class="form-group" id="inpStatus" style="display:none;">
			<label class="col-md-4 control-label no-padding-right">Status</label>
			<div class="col-md-8">
				<?=$selStatus;?>
			</div>
		</div>  
	</div> 
</div>


<div class="row loan" id="listPembayaran" style="display: none;">
    <div class="col-md-12">
		<div class="portlet box">
			<div class="portlet-title">
				<div class="caption">Detail Pembayaran </div>
				<div class="tools">
					
				</div>
			</div>
			<div class="portlet-body">
				<div class="table-scrollable tablesaw-cont">
				<table class="table table-striped table-bordered table-hover loan-list tablesaw tablesaw-stack" data-tablesaw-mode="stack" id="tblDetailLoan">
					<thead>
						<tr>
							<th scope="col" style="width:5%; text-align: center;">Cicilan ke</th>
							<th scope="col" style="text-align: center">Jatuh Tempo</th>
							<th scope="col" style="text-align: center">Status</th>
							<th scope="col" style="text-align: center">Tanggal Bayar</th>
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





								