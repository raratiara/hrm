<div class="row">
	
	<div class="col-md-6 col-sm-12 col-xs-12">
		<div class="row-flex">
			<label class="col-md-4 control-label no-padding-right">Nama Karyawan</label>
			<div class="col-md-8">
				: <span class="id_employee"></span>
			</div>
		</div>
		<div class="row-flex">
			<label class="col-md-4 control-label no-padding-right">Nominal Pinjaman</label>
			<div class="col-md-8">
				: <span class="nominal_pinjaman"></span>
			</div>
		</div>
		<div class="row-flex">
			<label class="col-md-4 control-label no-padding-right">Tenor (dlm bulan)</label>
			<div class="col-md-8">
				: <span class="tenor"></span>
			</div>
		</div> 
		
		<div class="row-flex">
			<label class="col-md-4 control-label no-padding-right">Bunga per Bulan (%)</label>
			<div class="col-md-8">
				: <span class="bunga_per_bulan"></span>
			</div>
		</div>
		<div class="row-flex">
			<label class="col-md-4 control-label no-padding-right">Cicilan per bulan </label>
			<div class="col-md-8">
				: <span class="nominal_cicilan_per_bulan"></span>
			</div>
		</div> 
		<div class="row-flex">
			<label class="col-md-4 control-label no-padding-right">Status</label>
			<div class="col-md-8">
				: <span class="status"></span>
			</div>
		</div>  
		<div class="row-flex" id="rejectReason" style="display: none;">
			<label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Reject Reason</label>
			<div class="col-md-8 col-sm-8 col-xs-8">
				: <span class="reject_reason"></span>
			</div>
		</div>
	</div>


	<div class="col-md-6 col-sm-12 col-xs-12">
		
		<div class="row-flex">
			<label class="col-md-4 control-label no-padding-right"> Tgl Pengajuan</label>
			<div class="col-md-8">
				: <span class="date_pengajuan"></span>
			</div>
		</div>
		<div class="row-flex">
			<label class="col-md-4 control-label no-padding-right"> Tgl Persetujuan</label>
			<div class="col-md-8">
				: <span class="date_persetujuan"></span>
			</div>
		</div>
		<div class="row-flex">
			<label class="col-md-4 control-label no-padding-right">Tgl  Pencairan</label>
			<div class="col-md-8">
				: <span class="date_pencairan"></span>
			</div>
		</div> 
		<div class="row-flex">
			<label class="col-md-4 control-label no-padding-right">Tgl Start Angsuran</label>
			<div class="col-md-8">
				: <span class="date_start_cicilan"></span>
			</div>
		</div>  
		<div class="row-flex">
			<label class="col-md-4 control-label no-padding-right">Sisa Tenor</label>
			<div class="col-md-8">
				: <span class="sisa_tenor"></span>
			</div>
		</div> 
		
		
	</div>
</div>



<div class="row loan" style="margin-top: 10px; display:none;" id="listPembayaranView">
    <div class="col-md-12">
		<div class="portlet box">
			<div class="portlet-title">
				<div class="caption">Detail Pembayaran </div>
				<div class="tools">
					
				</div>
			</div>
			<div class="portlet-body">
				<div class="table-scrollable tablesaw-cont">
				<table class="table table-striped table-bordered table-hover loan-list tablesaw tablesaw-stack" data-tablesaw-mode="stack" id="tblLoan">
					<thead>
						<tr>
							<th scope="col">Cicilan Ke</th>
							<th scope="col">Jatuh Tempo</th>
							<th scope="col">Status</th>
							<th scope="col">Tanggal Bayar</th>
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
