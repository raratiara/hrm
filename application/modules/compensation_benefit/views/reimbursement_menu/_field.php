<div class="row">
	
	<div class="col-md-6 col-sm-12">
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right"> Date</label>
			<div class="col-md-8">
				<?=$txtdate;?>
			</div>
		</div>

		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right"> Employee</label>
			<div class="col-md-8">
				<?=$selemployee;?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Reimburs For</label>
			<div class="col-md-8">
				<?=$selreimbursfor;?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Nominal Billing </label>
			<div class="col-md-8">
				<?=$txtnominalbilling;?>
			</div>
		</div>
		
	</div>
	<div class="col-md-6 col-sm-12">
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Reimburs Type</label>
			<div class="col-md-8">
				<?=$seltype;?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Atas Nama</label>
			<div class="col-md-8">
				<?=$txtatasnama;?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Diagnosa </label>
			<div class="col-md-8">
				<?=$txtdiagnosa;?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Nominal Reimburs</label>
			<div class="col-md-8">
				<?=$txtnominalreimburs;?>
			</div>
		</div>
		
	</div>
</div>




<div class="row ca">
    <div class="col-md-12">
		<div class="portlet box green">
			<div class="portlet-title">
				<div class="caption">Detail Reimbursement </div>
				<div class="tools">
					<input type="button" class="btn btn-default blue btn-outline btn-circle btn-sm active" id="addcarow" value="Add Row" />
				</div>
			</div>
			<div class="portlet-body">
				<div class="table-scrollable tablesaw-cont">
				<table class="table table-striped table-bordered table-hover ca-list tablesaw tablesaw-stack" data-tablesaw-mode="stack" id="tblDetailReimburs">
					<thead>
						<tr>
							<th scope="col">No</th>
							<th scope="col" style="width:15%">Sub Type</th>
							<th scope="col">File</th>
							<th scope="col">Notes</th>
							<th scope="col">Biaya</th>
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



								