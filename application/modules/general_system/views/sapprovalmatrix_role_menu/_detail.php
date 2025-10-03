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
	<label class="col-md-3 control-label no-padding-right">Role Name</label>
	<div class="col-md-9">
		: <span class="role_name"></span>
	</div>
</div>
<div class="row-flex">
	<label class="col-md-3 control-label no-padding-right">Location</label>
	<div class="col-md-9">
		: <span class="location"></span>
	</div>
</div>
<div class="row-flex">
	<label class="col-md-3 control-label no-padding-right">Description</label>
	<div class="col-md-9">
		: <span class="description"></span>
	</div>
</div>


<br>
<div>
    <div class="col-md-12">
		<div class="portlet box grey">
			<div class="portlet-title">
				<div class="caption"> List PIC </div>
				<div class="tools">
					
				</div>
			</div>
			<div class="portlet-body">
				<div class="table-scrollable tablesaw-cont">
				<table class="table table-striped table-bordered table-hover rolepic-list-view tablesaw tablesaw-stack" data-tablesaw-mode="stack" id="tblPicRole">
					<thead>
						<tr>
							<th scope="col">No</th>
							<th scope="col">PIC</th>

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