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
			width: 120px;
			/* lebar label lebih kecil di HP */
			margin: 0;
		}


	}
</style>
<div class="row">

	<div class="col-md-6 col-sm-12 col-xs-12">
		<div class="row-flex">
			<label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Year</label>
			<div class="col-md-8 col-sm-8 col-xs-8">
				: <span class="year"></span>
			</div>
		</div>
		<div class="row-flex">
			<label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Section</label>
			<div class="col-md-8 col-sm-8 col-xs-8">
				: <span class="section"></span>
			</div>
		</div>
		<div class="row-flex">
			<label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Level</label>
			<div class="col-md-8 col-sm-8 col-xs-8">
				: <span class="level"></span>
			</div>
		</div>
		<div class="row-flex">
			<label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Headcount</label>
			<div class="col-md-8 col-sm-8 col-xs-8">
				: <span class="headcount"></span>
			</div>
		</div>

	</div>
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="row-flex">
			<label class="col-md-2 col-sm-4 col-xs-4 control-label no-padding-right">Description</label>
			<div class="col-md-10 col-sm-8 col-xs-8">
				: <span class="notes"></span>
			</div>
		</div>

	</div>
</div>