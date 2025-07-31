<div class="row">
	
	<div class="col-md-12 col-sm-12">
		<div class="form-group">
			<label class="col-md-2 control-label no-padding-right">Request Number</label>
			<div class="col-md-10">
				<?=$txtreqnumber;?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-2 control-label no-padding-right">Subject</label>
			<div class="col-md-10">
				<?=$selsubject;?>
			</div>
		</div>
	</div>

	<div class="col-md-6 col-sm-12">
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Request Date </label>
			<div class="col-md-8">
				<?=$txtrequestdate;?>
			</div>
		</div>
	</div>
	<div class="col-md-6 col-sm-12">
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Required Date </label>
			<div class="col-md-8">
				<?=$txtrequireddate;?>
			</div>
		</div>
	</div>

	<div class="col-md-12 col-sm-12">
		<div class="form-group">
			<label class="col-md-2 control-label no-padding-right">Section</label>
			<div class="col-md-10">
				<?=$selsection;?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-2 control-label no-padding-right">Head Count</label>
			<div class="col-md-10">
				<?=$txtheadcount;?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-2 control-label no-padding-right">Job Level</label>
			<div class="col-md-10">
				<?=$seljoblevel;?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-2 control-label no-padding-right">Employee Status</label>
			<div class="col-md-10">
				<?=$selempstatus;?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-2 control-label no-padding-right">Justification</label>
			<div class="col-md-10">
				<?=$txtjustification;?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-2 control-label no-padding-right">Request By</label>
			<div class="col-md-10">
				<?=$selrequestby;?>
				<input type="hidden" id="status" name="status">
			</div>
		</div>
		<!-- <div class="form-group">
			<label class="col-md-2 control-label no-padding-right">Status</label>
			<div class="col-md-10">
				<?=$txtstatus;?>
			</div>
		</div> -->
	</div>
	
</div>


<button href="#tabrequirement" data-toggle="tab" class="accordion" id="accordion_requirement">Requirement Details</button>

<div class="panel" id="tabrequirement">
	<div class="row requirement">
	    <div class="col-md-12">
			<div class="portlet box">
				<div class="portlet-title">
					<div class="caption"><!-- Job List --> </div>
					<div class="tools">
						<input type="button" class="btn btn-default blue btn-outline btn-circle btn-sm active" id="addrequirement" value="Add Row" />
					</div>
				</div>
				<div class="portlet-body">
					<div class="table-scrollable tablesaw-cont"> 
						<table class="table table-striped table-bordered table-hover requirement-list tablesaw tablesaw-stack" data-tablesaw-mode="stack" id="tblDetailRequirement">
							<thead>
								<tr>
									<th scope="col">No</th>
									<th scope="col">Type</th>
									<th scope="col">Description</th>
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
</div>


<button href="#tabjob" data-toggle="tab" class="accordion" id="accordion_job">Job Description Details</button>

<div class="panel" id="tabjob">
	<div class="row job">
	    <div class="col-md-12">
			<div class="portlet box">
				<div class="portlet-title">
					<div class="caption"><!-- Job List --> </div>
					<div class="tools">
						<input type="button" class="btn btn-default blue btn-outline btn-circle btn-sm active" id="addjob" value="Add Row" />
					</div>
				</div>
				<div class="portlet-body">
					<div class="table-scrollable tablesaw-cont"> 
						<table class="table table-striped table-bordered table-hover job-list tablesaw tablesaw-stack" data-tablesaw-mode="stack" id="tblDetailJob">
							<thead>
								<tr>
									<th scope="col">No</th>
									<th scope="col">Priority Level</th>
									<th scope="col">Description</th>
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
</div>



								