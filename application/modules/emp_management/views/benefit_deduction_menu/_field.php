<div class="row">
	<div class="col-md-6 col-sm-12">
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Emp Code</label>
			<div class="col-md-8">
				<input type="text" class="form-control" id="emp_code" name="emp_code" readonly>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Employee Name</label>
			<div class="col-md-8">
				<input type="text" class="form-control" id="full_name" name="full_name" readonly>
			</div>
		</div>
	</div>
	<div class="col-md-6 col-sm-12">
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Job Title</label>
			<div class="col-md-8">
				<input type="text" class="form-control" id="job_title_name" name="job_title_name" readonly>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Department</label>
			<div class="col-md-8">
				<input type="text" class="form-control" id="department_name" name="department_name" readonly>
			</div>
		</div>
	</div>
</div>

<!-- EARNING COMPONENTS -->
<div class="row">
	<div class="col-md-12">
		<div class="portlet box">
			<div class="portlet-title">
				<div class="caption"><i class="fa fa-plus-circle" style="color:#27ae60"></i> Earning Components</div>
				<div class="actions" style="float:right;margin-top:-5px;">
					<button type="button" class="btn btn-sm btn-success" id="btnAddEarning"><i class="fa fa-plus"></i> Add</button>
				</div>
			</div>
			<div class="portlet-body">
				<table class="table table-striped table-bordered table-hover" id="tblEarning">
					<thead>
						<tr>
							<th style="width:40%">Component</th>
							<th style="width:45%">Amount</th>
							<th style="width:15%;text-align:center;">Action</th>
						</tr>
					</thead>
					<tbody id="earningBody">
						<tr id="earningEmptyRow"><td colspan="3" class="text-center text-muted">No earning data. Click "Add" to add component.</td></tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<!-- DEDUCTION COMPONENTS -->
<div class="row">
	<div class="col-md-12">
		<div class="portlet box">
			<div class="portlet-title">
				<div class="caption"><i class="fa fa-minus-circle" style="color:#e74c3c"></i> Deduction Components</div>
				<div class="actions" style="float:right;margin-top:-5px;">
					<button type="button" class="btn btn-sm btn-danger" id="btnAddDeduction"><i class="fa fa-plus"></i> Add</button>
				</div>
			</div>
			<div class="portlet-body">
				<table class="table table-striped table-bordered table-hover" id="tblDeduction">
					<thead>
						<tr>
							<th style="width:40%">Component</th>
							<th style="width:45%">Amount</th>
							<th style="width:15%;text-align:center;">Action</th>
						</tr>
					</thead>
					<tbody id="deductionBody">
						<tr id="deductionEmptyRow"><td colspan="3" class="text-center text-muted">No deduction data. Click "Add" to add component.</td></tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
