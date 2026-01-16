<style>
	.row-flex {
		display: flex;
		align-items: center;
		margin: 0;
	}

	@media screen and (max-width: 768px) {
		.row-flex {
			display: flex;
			align-items: center;
			font-size: 12px;
			margin-bottom: 10px ! important;
		}


		.row-flex .col-md-9 {
			flex: 1;
		}

		.row-flex label {
			width: 100px;
			/* lebar label lebih kecil di HP */
			margin: 0;
		}


	}
</style>
<div class="row">

	<div class="col-md-6 col-sm-12 col-xs-12">
		<div class="row-flex">
			<label class="col-md-4 control-label no-padding-right">Kode Project</label>
			<div class="col-md-8">
				: <span class="kode_project"></span>
			</div>
		</div>
		<div class="row-flex">
			<label class="col-md-4 control-label no-padding-right">Nama Customer </label>
			<div class="col-md-8">
				: <span class="customer"></span>
			</div>
		</div>
		<div class="row-flex">
			<label class="col-md-4 control-label no-padding-right">Management Fee</label>
			<div class="col-md-8">
				: <span class="management_fee"></span>
			</div>
		</div>
		<div class="row-flex">
			<label class="col-md-4 control-label no-padding-right">Periode Start</label>
			<div class="col-md-8">
				: <span class="periode_start"></span>
			</div>
		</div>
		<div class="row-flex">
			<label class="col-md-4 control-label no-padding-right">Periode End</label>
			<div class="col-md-8">
				: <span class="periode_end"></span>
			</div>
		</div>


	</div>


	<div class="col-md-6 col-sm-12 col-xs-12">
		<div class="row-flex">
			<label class="col-md-4 control-label no-padding-right">Nama Project</label>
			<div class="col-md-8">
				: <span class="nama_project"></span>
			</div>
		</div>

		<div class="row-flex">
			<label class="col-md-4 control-label no-padding-right"> Lokasi</label>
			<div class="col-md-8">
				: <span class="lokasi"></span>
			</div>
		</div>
		<div class="row-flex">
			<label class="col-md-4 control-label no-padding-right"> Jenis Pekerjaan</label>
			<div class="col-md-8">
				: <span class="jenis_pekerjaan"></span>
			</div>
		</div>
		
		<div class="row-flex">
			<label class="col-md-4 control-label no-padding-right"> Jenis Layanan</label>
			<div class="col-md-8">
				: <span class="jenis_layanan"></span>
			</div>
		</div>
		


	</div>
</div>