<div class="row" style="margin-bottom:20px">
	
	<div class="col-md-6 col-sm-12">
		
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right"> Employee</label>
			<div class="col-md-8">
				<?=$selemployee;?>
			</div>
		</div>
		
	</div>
	<div class="col-md-6 col-sm-12">
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




<button href="#tabhardskill" data-toggle="tab" class="accordion">Job Details</button>

<div class="panel" id="tabhardskill">
	<div class="row ca">
	    <div class="col-md-12">
			<div class="portlet box">
				<div class="portlet-title">
					<div class="caption"><!-- Job List --> </div>
					<div class="tools">
						<input type="button" class="btn btn-default blue btn-outline btn-circle btn-sm active" id="addhardskill" value="Add Row" />
					</div>
				</div>
				<div class="portlet-body">
					<div class="table-scrollable tablesaw-cont"> 
					<table class="table table-striped table-bordered table-hover ca-list tablesaw tablesaw-stack" data-tablesaw-mode="stack" id="tblDetailHardskill">
						<thead>
							<tr>
								<th scope="col">No</th>
								<th scope="col">Job</th>
								<th scope="col">Notes</th>
								<th scope="col" style="width:7%">Weight</th>
								<th scope="col" style="width:7%">Score by Employee (%)</th>
								<th scope="col" style="width:7%">Score by Direct (%)</th>
								<th scope="col" style="width:7%">Final Score</th>
								<th scope="col"></th>
							</tr>
						</thead>
						<tbody>
							
						</tbody>

						<tfoot>
						</tfoot>
					</table>

					<table class="table table-striped table-bordered table-hover tablesaw tablesaw-stack" data-tablesaw-mode="stack" >
						<thead>
							<tr>
							
							</tr>
						</thead>
						<tbody>
								<tr>
									<td style="width:796px; text-align: right;">Total Final Score</td>
									<td><span id="ttl_final_score"></span>
										<input type="hidden" name="hdnttl_final_score" id="hdnttl_final_score">
									</td>
									
								</tr>
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


<button href="#tabsoftskill" data-toggle="tab" class="accordion" id="accordion_softskill">Soft Skills Assessment</button>

<div class="panel" id="tabsoftskill">
	<span id="tblsoftskill"></span>
</div>









