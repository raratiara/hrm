<!-- Font Awesome CDN -->
<style>
	.year-input-wrapper {
		position: relative;
		background-color: white;
		border-radius: 9999px !important;
		/* pill shape */
		display: flex;
		align-items: center;
		padding: 0 16px;
		border: 1px solid #b0b0b0ff;
		width: 150px !important;
	}

	.year-input-wrapper .calendar-icon,
	.year-input-wrapper .chevron-icon {
		color: #2d3667;
		font-size: 13px;
	}

	.year-input-wrapper .calendar-icon {
		margin-right: 10px;
	}

	.year-input-wrapper .chevron-icon {
		margin-left: auto;
	}

	.year-input-wrapper .year-input {
		border: none;
		outline: none;
		flex: 1;
		font-size: 13px;
		color: #2d3667;
		background-color: transparent;
		padding: 12px 0;
	}

	.year-input-wrapper .year-input::placeholder {
		color: #2d3667;
	}

	#tblDetailHardskill input,
	#tblDetailHardskill textarea,
	#tabsoftskill input,
	#tabsoftskill textarea {
		width: 100%;
		/* isi penuh kolom */
		height: 38px;
		/* tinggi seragam */
		box-sizing: border-box;
		padding: 5px 8px;
		border: 1px solid #ccc;
		border-radius: 4px;
	}

	/* Matikan resize textarea */
	#tblDetailHardskill textarea,
	#tabsoftskill textarea {
		resize: none;
	}



	
</style>

<div class="row">

	<div>

		<div>
			<!-- <label class="col-md-4 control-label no-padding-right"> Employee</label> -->
			<div class="col-md-8">
				<?= $selemployee; ?>
			</div>
		</div>

	</div>
	<div>
		<div>
			<!-- <label class="col-md-4 control-label no-padding-right">Year</label> -->
			<div>
				<!-- <?= $txtyear; ?> -->
				<!-- <input class="form-control " name="year" id="year" type="text" value="" placeholder="Select Year"> -->
				<div class="year-input-wrapper">
					<i class="fa-solid fa-calendar-days calendar-icon"></i>
					<input class="form-control year-input" name="year" id="year" type="text" value=""
						placeholder="Select Year">
				</div>
			</div>
		</div>

	</div>

	<!-- <div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Year</label>
			<div class="col-md-8">
				<?= $txtyear; ?>
			</div>
		</div> -->
</div>


<div class="row-flex" style="margin-bottom: 20px;">

	<div class="col-md-6 col-sm-12 col-xs-12">

		<div class="row">
			<label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Employee ID</label>
			<div class="col-md-8 col-sm-8 col-xs-8">
				: <span class="empid"></span>
			</div>
		</div>
		<div class="row">
			<label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Job Title</label>
			<div class="col-md-8 col-sm-8 col-xs-8">
				: <span class="jobtitle"></span>
			</div>
		</div>
		<div class="row">
			<label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Employee Type</label>
			<div class="col-md-8 col-sm-8 col-xs-8">
				: <span class="emptype"></span>
			</div>
		</div>
		<div class="row">
			<label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Employee Status</label>
			<div class="col-md-8 col-sm-8 col-xs-8">
				: <span class="empstatus"></span>
			</div>
		</div>
	</div>


	<div class="col-md-6 col-sm-12 col-xs-12">
		<div class="row">
			<label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Division</label>
			<div class="col-md-8 col-sm-8 col-xs-8">
				: <span class="division"></span>
			</div>
		</div>
		<div class="row">
			<label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Department</label>
			<div class="col-md-8 col-sm-8 col-xs-8">
				: <span class="department"></span>
			</div>
		</div>
		<div class="row">
			<label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Direct</label>
			<div class="col-md-8 col-sm-8 col-xs-8">
				: <span class="direct"></span>
			</div>
		</div>
		<div class="row">
			<label class="col-md-4 col-sm-4 col-xs-4 control-label no-padding-right">Date of Hired</label>
			<div class="col-md-8 col-sm-8 col-xs-8">
				: <span class="dateofhired"></span>
			</div>
		</div>
	</div>
</div>


<!-- <button class="accordion">Click Me</button>
<div class="panel">
  <p>This is the hidden panel content. You can add more content here, like text or images.</p>
</div> -->




<!-- <button href="#tabhardskill" data-toggle="tab" class="accordion">Job Details</button> -->

<div class="panel" id="tabhardskill">
	<div class="row ca">
		<div class="col-md-12">
			<div class="portlet box">
				<div class="portlet-title">
					<div class="caption">Job Details</div>
					<div class="tools">
						<input type="button" class="btn btn-default btn-outline btn-circle btn-sm active"
							id="addhardskill" value="Add Row" />
					</div>
				</div>
				<div class="portlet-body">
					<div class="table-scrollable tablesaw-cont">
						<table class="table table-striped table-bordered table-hover ca-list tablesaw tablesaw-stack"
							data-tablesaw-mode="stack" id="tblDetailHardskill">
							<thead>
								<tr>
									<th scope="col">No</th>
									<th scope="col">Job</th>
									<th scope="col">Type</th>
									<th scope="col">Notes</th>
									<th scope="col" style="width:7%">Weight</th>
									<th scope="col" style="width:7%">Score by Employee (%)</th>
									<th scope="col" style="width:7%">Score by Direct (%)</th>
									<th scope="col" style="width:7%">Final Score</th>
									<th scope="col"></th>
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
								<tr>

								</tr>
							</thead>
							<tbody>
								<tr>
									<td style="width:796px; text-align: right;"><b>Total Final Score</b></td>
									<td><b><span id="ttl_final_score"></span></b>
										<input type="hidden" name="hdnttl_final_score" id="hdnttl_final_score">
									</td>

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
</div>


<!-- <button href="#tabsoftskill" data-toggle="tab" class="accordion" id="accordion_softskill">Soft Skills
	Assessment</button> -->

<div class="panel" id="tabsoftskill">
	<span id="tblsoftskill"></span>
</div>

<div class="summary">
	<div>
		Total Kehadiran : <b><span id="ttl_kehadiran"></span></b>
	</div>
	<div>
		Total Ijin : <b><span id="ttl_ijin"></span></b>
	</div>
	<div>
		Total Telat : <b><span id="ttl_telat"></span></b>
	</div>

</div>