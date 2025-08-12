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

	@media screen and (max-width: 480px) {
		.row-flex {
			display: flex;
			align-items: center;
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
<div class="row-flex">
	<label class="col-md-3 control-label no-padding-right">Role</label>
	<div class="col-md-3">
		: <span class="name"></span>
	</div>
</div>
<div class="row-flex">
	<label class="col-md-3 control-label no-padding-right">Description</label>
	<div class="col-md-9">
		: <span class="description"></span>
	</div>
</div>