<div class="row-flex" style="margin-bottom:20px">

	<div class="col-md-6 col-sm-12 col-xs-12">

		<div class="row">
			<label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Employee</label>
			<div class="col-md-8 col-sm-8 col-xs-8">
				: <span class="employee"></span>
			</div>
		</div>
	</div>


	<div class="col-md-6 col-sm-12 col-xs-12">
		<div class="row">
			<label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Year</label>
			<div class="col-md-8 col-sm-8 col-xs-8">
				: <span class="year"></span>
			</div>
		</div>

	</div>
</div>



<div class="row ca">
	<div class="col-md-12">
		<div class="portlet box">
			<div class="portlet-title">
				<div class="caption">Detail Job </div>
				<div class="tools">

				</div>
			</div>
			<div class="portlet-body">
				<div class="table-scrollable tablesaw-cont">
					<table class="table table-striped table-bordered table-hover ca-list tablesaw tablesaw-stack"
						data-tablesaw-mode="stack" id="tblHardskill">
						<thead>
							<tr>
								<th scope="col">No</th>
								<th scope="col">Job</th>
								<th scope="col">Notes</th>
								<th scope="col">Weight</th>
								<th scope="col">Score by Employee (%)</th>
								<th scope="col">Score by Direct (%)</th>
								<th scope="col">Final Score</th>
							</tr>
						</thead>
						<tbody>

						</tbody>
						<tfoot>
						</tfoot>
					</table>

					<table class="table table-striped table-bordered table-hover tablesaw tablesaw-stack"
						data-tablesaw-mode="stack">
						<thead>

						</thead>
						<tbody>
							<tr>
								<td style="width:906px; text-align: right;"><b>Total Final Score</b></td>
								<td><b><span id="ttl_final_score"></span></b></td>
							</tr>
						</tbody>
						<tfoot>
						</tfoot>
					</table>

				</div>
			</div>
		</div>
	</div>
</div>


<div class="table-scrollable-mobile">
	<span id="tblsoftskill_detail"></span>
</div>




Total Kehadiran : <b><span id="ttl_kehadiran_dtl"></span></b> </br>
Total Ijin : <b><span id="ttl_ijin_dtl"></span></b> </br>
Total Telat : <b><span id="ttl_telat_dtl"></span></b>