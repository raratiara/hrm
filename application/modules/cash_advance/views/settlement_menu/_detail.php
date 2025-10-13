<div class="row">
	
	<div class="col-md-6 col-sm-12 col-xs-12">
		<div class="row">
			<label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Settlement Number</label>
			<div class="col-md-8 col-sm-8 col-xs-8">
				: <span class="settlement_number"></span>
			</div>
		</div>
		<div class="row">
			<label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Prepared By</label>
			<div class="col-md-8 col-sm-8 col-xs-8">
				: <span class="prepared_by"></span>
			</div>
		</div>
		<div class="row">
			<label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">FPU/FPP Number</label>
			<div class="col-md-8 col-sm-8 col-xs-8">
				: <span class="ca_number"></span>
			</div>
		</div>
		<div class="row" id="rfuReason" style="display: none;">
			<label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">RFU Reason</label>
			<div class="col-md-8 col-sm-8 col-xs-8">
				: <span class="rfu_reason"></span>
			</div>
		</div>
		<div class="row" id="rejectReason" style="display: none;">
			<label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Reject Reason</label>
			<div class="col-md-8 col-sm-8 col-xs-8">
				: <span class="reject_reason"></span>
			</div>
		</div>
	</div>


	<div class="col-md-6 col-sm-12 col-xs-12">
		<div class="row">
			<label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Settlement Date</label>
			<div class="col-md-8 col-sm-8 col-xs-8">
				: <span class="settlement_date"></span>
			</div>
		</div>
		<div class="row">
			<label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Requested By</label>
			<div class="col-md-8 col-sm-8 col-xs-8">
				: <span class="requested_by"></span>
			</div>
		</div>
		<div class="row">
			<label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">FPU/FPP Cost</label>
			<div class="col-md-8 col-sm-8 col-xs-8">
				: <span class="ca_cost"></span>
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
					
				</div>
			</div>
			<div class="portlet-body">
				<div class="table-scrollable tablesaw-cont">
				<table class="table table-striped table-bordered table-hover sett-list-view tablesaw tablesaw-stack" data-tablesaw-mode="stack" id="tblSett">
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
	
	<div class="col-md-6 col-sm-12 col-xs-12">
		<div class="row">
			<label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Total Cost</label>
			<div class="col-md-8 col-sm-8 col-xs-8">
				: <span class="total_cost_sett"></span>
			</div>
		</div>
		<div class="row">
			<label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Terbilang</label>
			<div class="col-md-8 col-sm-8 col-xs-8">
				: <span class="total_cost_terbilang_sett"></span>
			</div>
		</div>
		<div class="row">
			<label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Settlement Amount</label>
			<div class="col-md-8 col-sm-8 col-xs-8">
				: <span class="settlement_amount"></span>
			</div>
		</div>
		<div class="row" id="div_bukti_transfer_view" style="display:none">
			<label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Bukti Transfer</label>
			<div class="col-md-8 col-sm-8 col-xs-8">
				: <span class="bukti_transfer" id="file-link-buktitrf"></span>
			</div>
		</div>
		<div class="row" id="div_no_rekening_view" style="display:none">
			<label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">No Rekening</label>
			<div class="col-md-8 col-sm-8 col-xs-8">
				: <span class="no_rekening"></span>
			</div>
		</div>
		<div class="row" id="div_bank_view" style="display:none">
			<label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Bank</label>
			<div class="col-md-8 col-sm-8 col-xs-8">
				: <span class="bank"></span>
			</div>
		</div>
		<div class="row" id="div_nama_rekening_view" style="display:none">
			<label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Nama Rekening</label>
			<div class="col-md-8 col-sm-8 col-xs-8">
				: <span class="nama_rekening"></span>
			</div>
		</div>
	</div>


	<div class="col-md-6 col-sm-12 col-xs-12">
		<div class="row">
			<label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Document Settlement</label>
			<div class="col-md-8 col-sm-8 col-xs-8">
				: <!-- <span class="document"></span> --> <span class="file" id="file-link-sett"></span>
			</div>
		</div>
		
		
	</div>
</div>
