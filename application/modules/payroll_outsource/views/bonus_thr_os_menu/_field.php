<div class="row">
	<div class="col-md-6 col-sm-12">
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Project</label>
			<div class="col-md-8">
				<?=$selproject;?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Bulan</label>
			<div class="col-md-8">
				<?=$selmonth;?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Jenis</label>
			<div class="col-md-8">
				<?=$selcomponenttype;?>
			</div>
		</div>
	</div>

	<div class="col-md-6 col-sm-12">
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Tahun</label>
			<div class="col-md-8">
				<?=$txtyear;?>
			</div>
		</div>
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
		<strong>Total Bonus:</strong> <span id="total_bonus_text">0</span>
	</div>
	<div class="col-md-3 col-sm-6">
		<strong>Total THR:</strong> <span id="total_thr_text">0</span>
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
					<div class="table-scrollable tablesaw-cont">
						<table class="table table-striped table-bordered table-hover bonusthr-list tablesaw tablesaw-stack" data-tablesaw-mode="stack" id="tblDetailBonusThr">
							<thead>
								<tr>
									<th scope="col">NIK</th>
									<th scope="col">Karyawan</th>
									<th scope="col">Bonus</th>
									<th scope="col">THR</th>
									<th scope="col">Catatan Detail</th>
								</tr>
							</thead>
							<tbody>
								<tr><td colspan="5" class="center">Pilih project terlebih dahulu</td></tr>
							</tbody>
							<tfoot></tfoot>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
