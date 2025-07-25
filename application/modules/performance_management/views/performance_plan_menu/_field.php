<!-- Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
	#tblDetailHardskill_plan.table-hover tbody tr:hover {
		background-color: #d3dcecff !important;
	}

	.year-input-wrapper {
		position: relative;
		background-color: white;
		border-radius: 9999px !important;
		/* pill shape */
		display: flex;
		align-items: center;
		padding: 0 16px;
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
</style>

<div class="row" style="font-family: 'Poppins', sans-serif;">

	<div>

		<div class="form-group">
			<!-- <label class="col-md-4 control-label no-padding-right"> Employee</label> -->
			<div class="col-md-8">
				<?= $selemployee; ?>
			</div>
		</div>

	</div>
	<div>
		<div class="form-group">
			<!-- <label class="col-md-4 control-label no-padding-right">Year</label> -->
			<div class="col-md-8">
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
</div>


<!-- <button class="accordion">Click Me</button>
<div class="panel">
  <p>This is the hidden panel content. You can add more content here, like text or images.</p>
</div> -->




<button href="#tabhardskill_plan" data-toggle="tab" class="accordion" style="font-family: 'Poppins', sans-serif;">Job
	Details</button>

<div class="panel show active" style="font-family: 'Poppins', sans-serif;" id="tabhardskill_plan">
	<div class="row ca">
		<div class="col-md-12">
			<div class="portlet">
				<!-- <div class="portlet-title"> -->
				<!-- <div class="caption">Job List </div> -->
				<div class="tools">
					<input type="button" class="btn btn-addrow btn-sm active" id="addhardskill_plan" value="Add Row" />
				</div>
				<!-- </div> -->
				<div>
					<div class="table-scrollable tablesaw-cont">
						<table
							class="table table-striped table-bordered table-hover performance-plan-list tablesaw tablesaw-stack "
							data-tablesaw-mode="stack" id="tblDetailHardskill_plan">
							<thead class="thead-style">
								<tr>
									<th scope="col">No</th>
									<th scope="col">Job</th>
									<th scope="col">Notes</th>
									<th scope="col">Weight (%)</th>
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
</div>