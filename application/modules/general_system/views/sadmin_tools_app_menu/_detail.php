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
		}


		.row-flex .col-md-9 {
			flex: 1;
		}

		.row-flex label {
			width: 120px;
			/* lebar label lebih kecil di HP */
			font-size: 14px;
			margin: 0;
		}

		.row-flex .col-md-9 {
			font-size: 14px;
		}

	}
</style>
<div class="row-flex">
	<label class="col-md-3 control-label no-padding-right">Title Menu</label>
	<div class="col-md-9">
		: <span class="menu_title"></span>
	</div>
</div>
<div class="row-flex">
	<label class="col-md-3 control-label no-padding-right">Link Type</label>
	<div class="col-md-9">
		: <span class="link_type"></span>
	</div>
</div>
<div class="row-flex">
	<label class="col-md-3 control-label no-padding-right">Module Name</label>
	<div class="col-md-9">
		: <span class="module_name"></span>
	</div>
</div>
<div class="row-flex">
	<label class="col-md-3 control-label no-padding-right">URL</label>
	<div class="col-md-9">
		: <span class="url"></span>
	</div>
</div>
<div class="row-flex">
	<label class="col-md-3 control-label no-padding-right">Parent Menu</label>
	<div class="col-md-9">
		: <span class="parent_title"></span>
	</div>
</div>
<div class="row-flex">
	<label class="col-md-3 control-label no-padding-right">Is Parent</label>
	<div class="col-md-9">
		: <span class="is_parent"></span>
	</div>
</div>
<div class="row-flex">
	<label class="col-md-3 control-label no-padding-right">Show Menu</label>
	<div class="col-md-9">
		: <span class="show_menu"></span>
	</div>
</div>
<div class="row-flex">
	<label class="col-md-3 control-label no-padding-right">Icon Class</label>
	<div class="col-md-9">
		: <span class="um_class"></span>
	</div>
</div>
<div class="row-flex">
	<label class="col-md-3 control-label no-padding-right">Order</label>
	<div class="col-md-9">
		: <span class="um_order"></span>
	</div>
</div>