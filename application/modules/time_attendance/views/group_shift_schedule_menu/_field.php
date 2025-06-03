<div class="row">
	
	<div class="col-md-4 col-sm-12">
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Group</label>
			<div class="col-md-8">
				<?=$selgroup;?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Period</label>
			<div class="col-md-8">
				<input type="month" id="monthPicker" name="period" class="form-control">
			</div>
		</div>
		
	</div>
	
	<!-- <div class="col-md-4 col-sm-12" style="margin-top: 51px;margin-left:-20px">
		<button type="button" class="btn btn-primary" id="btnGen" onclick="generate()">Generate</button>
	</div> -->
	
	
</div>



<div class="calendar-header">
  <h2 id="monthYear"></h2>
</div>

<div class="calendar-grid" id="calendar">
  
</div> 


<button href="#tabemplist" data-toggle="tab" id="btnAccordion" class="accordion">Employee List</button>

<div class="panel" id="tabemplist">
	<div class="row emplist">
	    <div class="col-md-12">
			<div class="portlet box">
				<div class="portlet-title">
					<div class="caption"><!-- Job List --> </div>
					<div class="tools">
						<input type="button" class="btn btn-default blue btn-outline btn-circle btn-sm active" id="addemplist" value="Add Row" />
					</div>
				</div>
				<div class="portlet-body">
					<div class="table-scrollable tablesaw-cont"> 
					<table class="table table-striped table-bordered table-hover emp-list tablesaw tablesaw-stack" data-tablesaw-mode="stack" id="tblDetailEmpList">
						<thead>
							<tr>
								<th scope="col">No</th>
								<th scope="col">Employee</th>
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




								