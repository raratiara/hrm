<div class="row">
	<div class="col-md-6 col-sm-12">
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Name <span class="text-danger">*</span></label>
			<div class="col-md-8">
				<?=$txtname;?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Type</label>
			<div class="col-md-8">
				<?=$seltype;?>
			</div>
		</div>
        <div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Fixed</label>
			<div class="col-md-8">
				<?=$selisfixed;?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Default Amount</label>
			<div class="col-md-8">
				<?=$txtdefaultamount;?>
			</div>
		</div>

	</div>

	<div class="col-md-6 col-sm-12">
        <div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Status</label>
			<div class="col-md-8">
				<?=$selisactive;?>
			</div>
		</div>
		
		
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Sort Order</label>
			<div class="col-md-8">
				<?=$txtsortorder;?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Calculate %</label>
			<div class="col-md-8">
				<input type="text" class="form-control" id="calculate_percentage" name="calculate_percentage" placeholder="e.g. 0.04">
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Calculate From</label>
			<div class="col-md-8">
				<?=$selcalculatefrom;?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Description</label>
			<div class="col-md-8">
				<?=$txtdescription;?>
			</div>
		</div>
	</div>
</div>
