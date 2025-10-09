	
	<div class="form-group">
		<label class="col-md-3 control-label no-padding-right">Approval Path Name <span class="required">*</span></label>
		<div class="col-md-9">
			<?=$txtapprovalname;?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-md-3 control-label no-padding-right">Location <span class="required">*</span></label>
		<div class="col-md-9">
			<?=$selloc;?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-md-3 control-label no-padding-right">Approval Type <span class="required">*</span> </label>
		<div class="col-md-9">
			<?=$selapprovaltype;?>
		</div>
	</div>
	<div class="form-group" id="divAbsenceType" style="display: none;">
		<label class="col-md-3 control-label no-padding-right">Absence Type </label>
		<div class="col-md-9">
			<?=$selabsencetype;?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-md-3 control-label no-padding-right">Min </label>
		<div class="col-md-9">
			<?=$txtmin;?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-md-3 control-label no-padding-right">Max </label>
		<div class="col-md-9">
			<?=$txtmax;?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-md-3 control-label no-padding-right">Description </label>
		<div class="col-md-9">
			<?=$txtdescription;?>
		</div>
	</div>



	<div class="row ca">
	    <div class="col-md-12">
			<div class="portlet box">
				<div class="portlet-title">
					<div class="caption">List PIC</div>
					<div class="tools">
						<input type="button" class="btn btn-default blue btn-outline btn-circle btn-sm active" id="addapprovalpicrow" value="Add Row" />
					</div>
				</div>
				<div class="portlet-body">
					<div class="table-scrollable tablesaw-cont">
					<table class="table table-striped table-bordered table-hover approvalpic-list tablesaw tablesaw-stack" data-tablesaw-mode="stack" id="tblDetailApprovalPic">
						<thead>
							<tr>
								<th scope="col" style="width:10px">No</th>
								<th scope="col" style="width:70px; text-align:center">Level</th>
								<th scope="col" style="text-align:center">Role</th>
								<th scope="col" style="width:50px">Duration<br><span style="font-size: 10px;">(days)</span></th>
								<th scope="col" style="width:50px"></th>
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
		
