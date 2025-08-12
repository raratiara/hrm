<style>
	.row-flex {
		display: flex;
		align-items: center;
		margin: 0;
	}

	@media screen and (max-width: 768px) {
		.row-flex {
			display: flex !important;
			align-items: center ! important;
			font-size: 10px ! important;
			margin-bottom: 10px ! important;
		}


		.row-flex .col-md-9 {
			flex: 1 ! important;
		}

		.row-flex label {
			width: 150px ! important;
			margin: 0;
		}

		#tblRequirement th,
		#tblRequirement td {
			font-size: 11px !important;
		}

		#tblRequirement .tablesaw-cell-label {
			display: inline-block;
			min-width: 90px;
			/* kasih lebar minimum label */
			margin-right: 8px;
			/* jarak antara label dan isi */
			text-align: left;
			/* rata kanan */
		}

		#tblRequirement .tablesaw-cell-content {
			display: inline-block;
			white-space: normal;
			/* biar teks bisa turun kalau panjang */
		}

		#tblJob th,
		#tblJob td {
			font-size: 11px !important;
		}

		#tblJob .tablesaw-cell-label {
			display: inline-block;
			min-width: 90px;
			/* kasih lebar minimum label */
			margin-right: 8px;
			/* jarak antara label dan isi */
			text-align: left;
			/* rata kanan */
		}

		#tblJob .tablesaw-cell-content {
			display: inline-block;
			white-space: normal;
			/* biar teks bisa turun kalau panjang */
		}
	}
</style>
<div class="row">

	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="row-flex">
			<label class="col-md-2 col-sm-4 col-xs-4 control-label no-padding-right">Request Number</label>
			<div class="col-md-10 col-sm-8 col-xs-8">
				: <span class="request_number"></span>
			</div>
		</div>
		<div class="row-flex">
			<label class="col-md-2 col-sm-4 col-xs-4 control-label no-padding-right">Subject</label>
			<div class="col-md-10 col-sm-8 col-xs-8">
				: <span class="subject"></span>
			</div>
		</div>
	</div>
	<div class="col-md-6 col-sm-12 col-xs-12">
		<div class="row-flex">
			<label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Request Date</label>
			<div class="col-md-8 col-sm-8 col-xs-8">
				: <span class="request_date"></span>
			</div>
		</div>
	</div>
	<div class="col-md-6 col-sm-12 col-xs-12">
		<div class="row-flex">
			<label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Required Date</label>
			<div class="col-md-8 col-sm-8 col-xs-8">
				: <span class="required_date"></span>
			</div>
		</div>
	</div>
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="row-flex">
			<label class="col-md-2 col-sm-4 col-xs-4 control-label no-padding-right">Section</label>
			<div class="col-md-10 col-sm-8 col-xs-8">
				: <span class="section"></span>
			</div>
		</div>
		<div class="row-flex">
			<label class="col-md-2 col-sm-4 col-xs-4 control-label no-padding-right">Head Count</label>
			<div class="col-md-10 col-sm-8 col-xs-8">
				: <span class="headcount"></span>
			</div>
		</div>
		<div class="row-flex">
			<label class="col-md-2 col-sm-4 col-xs-4 control-label no-padding-right">Job Level</label>
			<div class="col-md-10 col-sm-8 col-xs-8">
				: <span class="job_level"></span>
			</div>
		</div>
		<div class="row-flex">
			<label class="col-md-2 col-sm-4 col-xs-4 control-label no-padding-right">Employee Status</label>
			<div class="col-md-10 col-sm-8 col-xs-8">
				: <span class="emp_status"></span>
			</div>
		</div>
		<div class="row-flex">
			<label class="col-md-2 col-sm-4 col-xs-4 control-label no-padding-right">Justification</label>
			<div class="col-md-10 col-sm-8 col-xs-8">
				: <span class="justification"></span>
			</div>
		</div>
		<div class="row-flex">
			<label class="col-md-2 col-sm-4 col-xs-4 control-label no-padding-right">Request By</label>
			<div class="col-md-10 col-sm-8 col-xs-8">
				: <span class="request_by"></span>
			</div>
		</div>
	</div>

</div>



<div class="row requirement">
	<div class="col-md-12">
		<div class="portlet box">
			<div class="portlet-title">
				<div class="caption">Detail Requirement </div>
				<div class="tools">

				</div>
			</div>
			<div class="portlet-body">
				<div class="table-scrollable tablesaw-cont">
					<table
						class="table table-striped table-bordered table-hover requirement-list tablesaw tablesaw-stack"
						data-tablesaw-mode="stack" id="tblRequirement">
						<thead>
							<tr>
								<th scope="col">No</th>
								<th scope="col">Type</th>
								<th scope="col">Description</th>
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


<div class="row job">
	<div class="col-md-12">
		<div class="portlet box">
			<div class="portlet-title">
				<div class="caption">Detail Job </div>
				<div class="tools">

				</div>
			</div>
			<div class="portlet-body">
				<div class="table-scrollable tablesaw-cont">
					<table class="table table-striped table-bordered table-hover job-list tablesaw tablesaw-stack"
						data-tablesaw-mode="stack" id="tblJob">
						<thead>
							<tr>
								<th scope="col">No</th>
								<th scope="col">Priority Level</th>
								<th scope="col">Description</th>
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