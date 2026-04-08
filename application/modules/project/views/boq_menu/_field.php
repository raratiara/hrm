<div class="row">
	
	<div class="col-md-6 col-sm-12">
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right"> Customer</label>
			<div class="col-md-8">
				<?=$selcustomer;?>
			</div>
		</div>

		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Periode</label>
			<div class="col-md-8">
				<?=$txtperiode;?>
			</div>
		</div>
		
	</div>

	<div class="col-md-6 col-sm-12">
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Project</label>
			<div class="col-md-8">
				<?=$selproject;?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Template</label>
			<div class="col-md-8">
				<?=$seltemplate;?>
			</div>
		</div>
		
		
	</div>
</div>




<div class="row absenos" id="divBoq" style="display:none;">
    <div class="col-md-12">
		<div class="portlet box">
			<div class="portlet-title">
				<div class="caption">Boq Details </div>
				<div class="tools">
					<!-- <input type="button" class="btn btn-default blue btn-outline btn-circle btn-sm active" id="addabsenosrow" value="Add Row" /> -->
				</div>
			</div>
			<div class="portlet-body">
				<div class="table-scrollable tablesaw-cont">
				<table class="table table-striped table-bordered table-hover boq-list tablesaw tablesaw-stack" data-tablesaw-mode="stack" id="tblDetailBoq">
					<thead>
						<tr>
							<th scope="col">No</th>
							<th scope="col">Jenis Pekerjaan</th>
							<th scope="col">Jumlah</th>
							<th scope="col">Harga Satuan</th>
							<th scope="col">Jumlah Harga</th>
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



								