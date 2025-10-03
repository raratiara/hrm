	
	<div class="form-group">
		<label class="col-md-3 control-label no-padding-right">Role Name <span class="required">*</span></label>
		<div class="col-md-9">
			<?=$txtrolename;?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-md-3 control-label no-padding-right">Location <span class="required">*</span></label>
		<div class="col-md-9">
			<?=$selloc;?>
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
						<input type="button" class="btn btn-default blue btn-outline btn-circle btn-sm active" id="addrolepicrow" value="Add Row" />
					</div>
				</div>
				<div class="portlet-body">
					<div class="table-scrollable tablesaw-cont">
					<table class="table table-striped table-bordered table-hover rolepic-list tablesaw tablesaw-stack" data-tablesaw-mode="stack" id="tblDetailRolePic">
						<thead>
							<tr>
								<th scope="col" style="width:10px">No</th>
								<th scope="col" style="text-align:center">PIC</th>
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
		
