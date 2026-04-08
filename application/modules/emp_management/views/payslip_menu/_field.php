<div class="row">
	
	<div class="col-md-6 col-sm-12">
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right"> Employee</label>
			<div class="col-md-8">
				<?=$selemployee;?>
			</div>
		</div>

		
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">File</label>
			<div class="col-md-8">
				<?=$txtfile;?>
				<input type="hidden" id="hdnfile" name="hdnfile"/>
				<span class="file-link" id="file-link"></span>
			</div>
		</div>
		
	</div>

	<div class="col-md-6 col-sm-12" id="divStatus">
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Status</label>
			<div class="col-md-8">
				<?=$selstatus;?>
			</div>
		</div>
		
	</div>
</div>



								