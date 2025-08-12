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
			font-size: 12px !important;
		}


		.row-flex .col-md-9 {
			flex: 1;
		}

		.row-flex label {
			width: 100px;
			margin: 0;
		}
	}
</style>

<div class="row-flex">
	<label class="col-md-3 control-label no-padding-right">Name</label>
	<div class="col-md-9">
		: <span class="name"></span>
	</div>
</div>
<div class="row-flex">
	<label class="col-md-3 control-label no-padding-right">Username</label>
	<div class="col-md-9">
		: <span class="username"></span>
	</div>
</div>
<div class="row-flex">
	<label class="col-md-3 control-label no-padding-right">Link Data</label>
	<div class="col-md-9">
		: <span class="link"></span>
	</div>
</div>
<div class="row-flex">
	<label class="col-md-3 control-label no-padding-right">Email</label>
	<div class="col-md-9">
		: <span class="email"></span>
	</div>
</div>
<div class="row-flex">
	<label class="col-md-3 control-label no-padding-right">Role</label>
	<div class="col-md-9">
		: <span class="id_groups"></span>
	</div>
</div>
<div class="row-flex">
	<label class="col-md-3 control-label no-padding-right">Base Menu</label>
	<div class="col-md-9">
		: <span class="bmenu"></span>
	</div>
</div>
<div class="row-flex">
	<label class="col-md-3 control-label no-padding-right">Active</label>
	<div class="col-md-9">
		: <span class="isaktif"></span>
	</div>
</div>