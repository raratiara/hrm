<div class="row">
	
	<div>
		
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right"> Employee</label>
			<div class="col-md-8">
				<?=$selemployee;?>
			</div>
		</div>
		
	</div>
	<div>
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Year</label>
			<div class="col-md-8">
				<?=$txtyear;?>
			</div>
		</div>
		
	</div>
</div>


<!-- <button class="accordion">Click Me</button>
<div class="panel">
  <p>This is the hidden panel content. You can add more content here, like text or images.</p>
</div> -->




<button href="#tabhardskill_plan" data-toggle="tab" class="accordion">Job Details</button>

<div class="panel show active" id="tabhardskill_plan">
	<div class="row ca">
	    <div class="col-md-12">
			<div class="portlet box">
				<div class="portlet-title">
					<div class="caption"><!-- Job List --> </div>
					<div class="tools">
						<input type="button" class="btn btn-default blue btn-outline btn-circle btn-sm active" id="addhardskill_plan" value="Add Row" />
					</div>
				</div>
				<div class="portlet-body">
					<div class="table-scrollable tablesaw-cont"> 
					<table class="table table-striped table-bordered table-hover performance-plan-list tablesaw tablesaw-stack" data-tablesaw-mode="stack" id="tblDetailHardskill_plan">
						<thead>
							<tr>
								<th scope="col">No</th>
								<th scope="col">Job</th>
								<th scope="col">Notes</th>
								<th scope="col">Weight (%)</th>
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










