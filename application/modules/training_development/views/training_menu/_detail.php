<style>
	.training-detail-row {
		display: flex;
		gap: 10px;
		margin-bottom: 10px;
		line-height: 1.45;
	}
	.training-detail-label {
		flex: 0 0 145px;
		font-weight: 600;
		color: #475569;
	}
	.training-detail-value {
		flex: 1;
		color: #1f2937;
		word-break: break-word;
	}
	.training-detail-value:before {
		content: ":";
		display: inline-block;
		margin-right: 8px;
		color: #64748b;
	}
	@media (max-width: 767px) {
		.training-detail-row {
			display: block;
		}
		.training-detail-label {
			margin-bottom: 2px;
		}
	}
</style>

<div class="row">
	<div class="col-md-6 col-sm-12 col-xs-12">
		<div class="training-detail-row">
			<div class="training-detail-label">Training Name</div>
			<div class="training-detail-value"><span class="training_name"></span></div>
		</div>
		<div class="training-detail-row">
			<div class="training-detail-label">Trainer</div>
			<div class="training-detail-value"><span class="trainer"></span></div>
		</div>
		<div class="training-detail-row">
			<div class="training-detail-label">LMS Course</div>
			<div class="training-detail-value"><span class="lms_course"></span></div>
		</div>
		<div class="training-detail-row">
			<div class="training-detail-label">Participant</div>
			<div class="training-detail-value"><span class="participant"></span></div>
		</div>
		<div class="training-detail-row">
			<div class="training-detail-label">Created By</div>
			<div class="training-detail-value"><span class="created_by_name"></span></div>
		</div>
	</div>

	<div class="col-md-6 col-sm-12 col-xs-12">
		<div class="training-detail-row">
			<div class="training-detail-label">Training Date</div>
			<div class="training-detail-value"><span class="training_date"></span></div>
		</div>
		<div class="training-detail-row">
			<div class="training-detail-label">Location</div>
			<div class="training-detail-value"><span class="location"></span></div>
		</div>
		<div class="training-detail-row">
			<div class="training-detail-label">Notes</div>
			<div class="training-detail-value"><span class="notes"></span></div>
		</div>
		<div class="training-detail-row" id="rfuReason" style="display: none;">
			<div class="training-detail-label">RFU Reason</div>
			<div class="training-detail-value"><span class="rfu_reason"></span></div>
		</div>
		<div class="training-detail-row" id="rejectReason" style="display: none;">
			<div class="training-detail-label">Reject Reason</div>
			<div class="training-detail-value"><span class="reject_reason"></span></div>
		</div>
	</div>
</div>

