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
			<label class="col-md-4 control-label no-padding-right">Project</label>
			<div class="col-md-8">
				<input type="text" class="form-control" id="project_name" name="project_name" readonly>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Job Title</label>
			<div class="col-md-8">
				<input type="text" class="form-control" id="job_title_name" name="job_title_name" readonly>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="portlet box">
			<div class="portlet-title">
				<div class="caption"><i class="fa fa-plus-circle" style="color:#27ae60"></i> Earning Components</div>
			</div>
			<div class="portlet-body">
				<div class="table-scroll-x benefit-deduction-scroll">
					<div class="table-scrollable tablesaw-cont">
						<table class="table table-striped table-bordered table-hover benefit-deduction-list tablesaw tablesaw-stack" data-tablesaw-mode="stack" id="tblEarning">
							<thead>
								<tr id="earningHeader">
									<th scope="col">Component</th>
								</tr>
							</thead>
							<tbody>
								<tr id="earningAmountRow">
									<td>No earning data</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="portlet box">
			<div class="portlet-title">
				<div class="caption"><i class="fa fa-minus-circle" style="color:#e74c3c"></i> Deduction Components</div>
			</div>
			<div class="portlet-body">
				<div class="table-scroll-x benefit-deduction-scroll">
					<div class="table-scrollable tablesaw-cont">
						<table class="table table-striped table-bordered table-hover benefit-deduction-list tablesaw tablesaw-stack" data-tablesaw-mode="stack" id="tblDeduction">
							<thead>
								<tr id="deductionHeader">
									<th scope="col">Component</th>
								</tr>
							</thead>
							<tbody>
								<tr id="deductionAmountRow">
									<td>No deduction data</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
