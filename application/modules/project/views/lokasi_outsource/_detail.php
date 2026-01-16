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
			<label class="col-md-4 control-label no-padding-right">Lokasi</label>
			<div class="col-md-8">
				: <span class="lokasi"></span>
			</div>
		</div>
		<div class="row-flex">
			<label class="col-md-4 control-label no-padding-right">Zona Waktu </label>
			<div class="col-md-8">
				: <span class="zona_waktu"></span>
			</div>
		</div>
		<div class="row-flex">
			<label class="col-md-4 control-label no-padding-right">Latitude</label>
			<div class="col-md-8">
				: <span class="latitude"></span>
			</div>
		</div>
	

	</div>


	<div class="col-md-6 col-sm-12 col-xs-12">
		<div class="row-flex">
			<label class="col-md-4 control-label no-padding-right">Customer</label>
			<div class="col-md-8">
				: <span class="customer"></span>
			</div>
		</div>

		<div class="row-flex">
			<label class="col-md-4 control-label no-padding-right"> Selisih Waktu</label>
			<div class="col-md-8">
				: <span class="selisih_waktu"></span>
			</div>
		</div>
		<div class="row-flex">
			<label class="col-md-4 control-label no-padding-right"> Longitude</label>
			<div class="col-md-8">
				: <span class="longitude"></span>
			</div>
		</div>
		

	</div>
</div>