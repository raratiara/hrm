<div class="row">
	
	<div class="col-md-6 col-sm-12">
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right"> Settlement Number</label>
			<div class="col-md-8">
				<?=$txtsettlementnum;?>
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
			<label class="col-md-4 control-label no-padding-right"> FPU/FPP Number</label>
			<div class="col-md-8">
				<?=$selcanumber;?>
			</div>
		</div>
		<div class="form-group" id="rfuReasonEdit" style="display: none;">
			<label class="col-md-4 control-label no-padding-right">RFU Reason</label>
			<div class="col-md-8">
				<span class="rfu_reason_edit"></span>
			</div>
		</div>
	</div>
	<div class="col-md-6 col-sm-12">
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Settlement Date</label>
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
			<label class="col-md-4 control-label no-padding-right">FPU/FPP Cost</label>
			<div class="col-md-8">
				<?=$txtcacost;?>
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
					<input type="button" class="btn btn-default blue btn-outline btn-circle btn-sm active" id="addsettrow" value="Add Row" />
				</div>
			</div>
			<div class="portlet-body">
				<div class="table-scrollable tablesaw-cont">
				<table class="table table-striped table-bordered table-hover sett-list tablesaw tablesaw-stack" data-tablesaw-mode="stack" id="tblDetailSett">
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
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right"> Settlement Amount</label>
			<div class="col-md-8">
				<?=$txtsettamount;?>
			</div>
		</div>
		<div class="form-group" id="div_bukti_transfer" style="display:none">
			<label class="col-md-4 control-label no-padding-right"> Bukti Transfer</label>
			<div class="col-md-4">
				<?=$txtdoc_buktitransfer;?>
				<input type="hidden" id="hdndoc_buktitransfer" name="hdndoc_buktitransfer"/>
			</div>
			<div class="col-md-4">
				<span class="file_doc_buktitransfer" id="file_doc_buktitransfer_id"></span>
			</div>
		</div>
		<div class="form-group" id="div_no_rekening" style="display:none">
			<label class="col-md-4 control-label no-padding-right"> No Rekening</label>
			<div class="col-md-8">
				<?=$txtnorekening;?>
			</div>
		</div>
		<div class="form-group" id="div_bank" style="display:none">
			<label class="col-md-4 control-label no-padding-right"> Bank</label>
			<div class="col-md-8">
				<?=$txtbank;?>
			</div>
		</div>
		<div class="form-group" id="div_nama_rekening" style="display:none">
			<label class="col-md-4 control-label no-padding-right"> Nama Rekening</label>
			<div class="col-md-8">
				<?=$txtnamarekening;?>
			</div>
		</div>
	</div>

	<div class="col-md-6 col-sm-12">
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right"> Document Settlement</label>
			<div class="col-md-4">
				<?=$txtdoc;?>
				<input type="hidden" id="hdndoc" name="hdndoc"/>
			</div>
			<div class="col-md-4">
				<span class="file_doc" id="file-doc-sett"></span>
			</div>
		</div>
	</div>

</div>

