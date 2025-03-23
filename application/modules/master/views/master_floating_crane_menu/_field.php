<div>

	<div class="form-group">
		<label class="col-md-3 control-label no-padding-right">Code <span class="required">*</span></label>
		<div class="col-md-9">
			<?=$txtcode;?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-md-3 control-label no-padding-right">Name <span class="required">*</span></label>
		<div class="col-md-9">
			<?=$txtname;?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-md-3 control-label no-padding-right">Latitude <span class="required">*</span></label>
		<div class="col-md-9">
			<?=$txtlatitude;?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-md-3 control-label no-padding-right">Longitude <span class="required">*</span></label>
		<div class="col-md-9">
			<?=$txtlongitude;?>
		</div>
	</div>
</div>		


<div class="row ca">
    <div class="col-md-12">
		<div class="portlet box green">
			<div class="portlet-title">
				<div class="caption">List CCTV </div>
				<div class="tools">
					<input type="button" class="btn btn-default blue btn-outline btn-circle btn-sm active" id="addcarow" value="Add Row" />
				</div>
			</div>
			<div class="portlet-body">
				<div class="table-scrollable tablesaw-cont">
				<table class="table table-striped table-bordered table-hover ca-list tablesaw tablesaw-stack" data-tablesaw-mode="stack" id="tblCctv">
					<thead>
						<tr>
							<th scope="col">No</th>
							<th scope="col">Kode</th>
							<th scope="col">Nama</th>
							<th scope="col">Posisi</th>
							<th scope="col">RTSP</th>
							<th scope="col">Embed</th>
							<th scope="col"></th>
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