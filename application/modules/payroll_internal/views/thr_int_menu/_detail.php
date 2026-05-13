<div class="row">
	<div class="col-md-6 col-sm-12 col-xs-12">
		<div class="row-flex">
			<label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Periode</label>
			<div class="col-md-8 col-sm-8 col-xs-8">: <span class="periode"></span></div>
		</div>
	</div>
	<div class="col-md-6 col-sm-12 col-xs-12">
		<div class="row-flex">
			<label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Catatan</label>
			<div class="col-md-8 col-sm-8 col-xs-8">: <span class="notes"></span></div>
		</div>
	</div>
</div>

<div class="row bonus-thr-summary" style="margin:10px 0;">
	<div class="col-md-3 col-sm-6">
		<strong>Total <?=$nominal_label;?>:</strong> <span id="total_nominal_view_text">0</span>
	</div>
</div>

<div class="row bonusthrview" id="inpBonusThrView">
	<div class="col-md-12">
		<div class="portlet box">
			<div class="portlet-title">
				<div class="caption">Details</div>
			</div>
			<div class="portlet-body">
				<div class="row" style="margin-bottom:10px;">
					<div class="col-md-4">
						<input type="text" id="filterEmployeeView_bonusthr" class="form-control input-sm" placeholder="Cari nama karyawan...">
					</div>
				</div>
				<div class="table-scroll-x">
					<div class="table-scrollable tablesaw-cont bonus-thr-table-wrap">
						<table class="table table-striped table-bordered table-hover bonusthr-view-list tablesaw tablesaw-stack" data-tablesaw-mode="stack" id="tblDetailBonusThrView">
							<thead>
								<tr>
									<th scope="col">NIK</th>
									<th scope="col">Karyawan</th>
									<th scope="col"><?=$nominal_label;?></th>
									<th scope="col">Catatan Detail</th>
								</tr>
							</thead>
							<tbody></tbody>
							<tfoot></tfoot>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
