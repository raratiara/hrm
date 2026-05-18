<input type="hidden" name="action_type" value="">


<div class="row">
	
	<div class="col-md-6 col-sm-12">
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Periode Bulan</label>
			<div class="col-md-8">
				<?=$selmonth;?>
			</div>
		</div>
		
		

		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Tahun</label>
			<div class="col-md-8">
				<?=$txtyear;?>
			</div>
		</div>

	</div>
	<div class="col-md-6 col-sm-12">
		
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Catatan</label>
			<div class="col-md-8">
				<?=$txtnotes;?>
			</div>
		</div>
		
	</div>
</div>

<div class="row bonus-thr-summary" style="margin:10px 0;">
	<div class="col-md-3 col-sm-6">
		<strong>Total <?=$nominal_label;?>:</strong> <span id="total_nominal_text">0</span>
	</div>
</div>

<div class="row bonusthr" id="inpBonusThr">
	<div class="col-md-12">
		<div class="portlet box">
			<div class="portlet-title">
				<div class="caption">Details</div>
			</div>
			<div class="portlet-body">
				<div class="row" style="margin-bottom:10px;">
					<div class="col-md-4">
						<input type="text" id="filterEmployeeEdit_bonusthr" class="form-control input-sm" placeholder="Cari nama karyawan...">
					</div>
				</div>
				<div class="table-scroll-x">
					<div class="table-scrollable tablesaw-cont bonus-thr-table-wrap">
						<table class="table table-striped table-bordered table-hover bonusthr-list tablesaw tablesaw-stack" data-tablesaw-mode="stack" id="tblDetailBonusThr">
							<thead>
								<tr>
									<th scope="col">NIK</th>
									<th scope="col">Karyawan</th>
									<th scope="col"><?=$nominal_label;?></th>
									<th scope="col">Catatan Detail</th>
								</tr>
							</thead>
							<tbody>
								<tr><td colspan="4" class="center">Data karyawan akan dimuat saat form dibuka</td></tr>
							</tbody>
							<tfoot></tfoot>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
