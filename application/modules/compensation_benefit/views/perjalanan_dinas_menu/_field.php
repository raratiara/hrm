<div class="row">
	
	<div class="col-md-6 col-sm-12">
		
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right"> Employee</label>
			<div class="col-md-8">
				<?=$selemployee;?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Start Date</label>
			<div class="col-md-8">
				<?=$txtstartdate;?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Reason </label>
			<div class="col-md-8">
				<?=$txtreason;?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Different from current work location?  </label>
			<div class="col-md-8">
				<?=$different_work_location;?>
			</div>
		</div>
		<div class="form-group" id="inpLocation" style="display: none;">
			<label class="col-md-4 control-label no-padding-right"> Location</label>
			<div class="col-md-8">
				<?=$selbustriploc;?>
			</div>
		</div>
		
	</div>
	<div class="col-md-6 col-sm-12">
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Destination</label>
			<div class="col-md-8">
				<?=$txtdestination;?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">End Date</label>
			<div class="col-md-8">
				<?=$txtenddate;?>
			</div>
		</div>
		<div class="form-group" id="rfuReasonEdit" style="display: none;">
			<label class="col-md-4 control-label no-padding-right">RFU Reason </label>
			<div class="col-md-8">
				<span class="rfu_reason_edit"></span>
			</div>
		</div>
		
	</div>
</div>



<div class="row ca">
    <div class="col-md-12">
		<div class="portlet box">
			<div class="portlet-title">
				<div class="caption">Detail Business Trip </div>
				<div class="tools">
					<input type="button" class="btn btn-default blue btn-outline btn-circle btn-sm active" id="addbustriprow" value="Add Row" />
				</div>
			</div>
			<div class="portlet-body">
				<div class="table-scrollable tablesaw-cont">
				<table class="table table-striped table-bordered table-hover bustrip-list tablesaw tablesaw-stack" data-tablesaw-mode="stack" id="tblDetailBustrip">
					<thead>
						<tr>
							<th scope="col">No</th>
							<th scope="col">Type</th>
							<th scope="col" style="width:15%">Amount</th>
							<th scope="col">File</th>
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


<div class="row">
	<div class="col-md-6 col-sm-12">
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right"> Total Amount</label>
			<div class="col-md-8">
				<?=$txttotalamount;?>
			</div>
		</div>
	</div>
</div>








								