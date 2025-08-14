<div class="row">
	
	<div class="col-md-6 col-sm-12">
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right"> FPP Number</label>
			<div class="col-md-8">
				<?=$txtfppnum;?>
				<input type="hidden" id="action_type" name="action_type" />
			</div>
		</div>

		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right"> Prepared By</label>
			<div class="col-md-8">
				<?=$txtpreparedby;?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right"> Type</label>
			<div class="col-md-8">
				<?=$txttype;?>
			</div>
		</div>
		
	</div>
	<div class="col-md-6 col-sm-12">
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Request Date</label>
			<div class="col-md-8">
				<?=$txtreqdate;?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Requested By</label>
			<div class="col-md-8">
				<?=$txtrequestedby;?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">No Rekening</label>
			<div class="col-md-8">
				<?=$txtnorekening;?>
			</div>
		</div>
		
	</div>
</div>


<div id="inputVendor" style="display: none;">
	<div class="col-md-6 col-sm-12">
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right"> Vendor Name</label>
			<div class="col-md-8">
				<?=$txtvendorname;?>
				<input type="hidden" id="action_type" name="action_type" />
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Invoice Number</label>
			<div class="col-md-8">
				<?=$txtinvoicenum;?>
			</div>
		</div>
	</div>
	<div class="col-md-6 col-sm-12">
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Invoice Date</label>
			<div class="col-md-8">
				<?=$txtinvoicedate;?>
			</div>
		</div>
	</div>
</div>




<div class="row ca">
    <div class="col-md-12">
		<div class="portlet box">
			<div class="portlet-title">
				<div class="caption">Advance Request Details </div>
				<div class="tools">
					<input type="button" class="btn btn-default blue btn-outline btn-circle btn-sm active" id="addfpprow" value="Add Row" />
				</div>
			</div>
			<div class="portlet-body">
				<div class="table-scrollable tablesaw-cont">
				<table class="table table-striped table-bordered table-hover fpp-list tablesaw tablesaw-stack" data-tablesaw-mode="stack" id="tblDetailFpp">
					<thead>
						<tr>
							<th scope="col">No</th>
							<th scope="col">Post Budget</th>
							<th scope="col">Amount</th>
							<th scope="col">PPN/PPH (%)</th>
							<th scope="col">Total</th>
							<th scope="col">Notes</th>
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
			<label class="col-md-4 control-label no-padding-right"> Total Cost</label>
			<div class="col-md-8">
				<?=$txttotalbiaya;?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right"> Terbilang</label>
			<div class="col-md-8">
				<?=$txtterbilang;?>
			</div>
		</div>
	</div>

	<div class="col-md-6 col-sm-12">
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right"> Document</label>
			<div class="col-md-4">
				<?=$txtdoc;?>
				<input type="hidden" id="hdndoc" name="hdndoc"/>
			</div>
			<div class="col-md-4">
				<span class="file_doc" id="file-doc-fpp"></span>
			</div>
		</div>
	</div>


	
</div>



										
