<!-- Modal Import Data -->
<div id="modal-import-data" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-import-data"
	aria-hidden="true">
	<div class="vertical-alignment-helper">
		<div class="modal-dialog vertical-align-center">
			<div class="modal-content">
				<form class="form-horizontal" id="frmImportData" enctype="multipart/form-data">
					<div class="modal-header no-padding">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<div class="table-header">
							Import <?php echo $smodul; ?>
						</div>
					</div>

					<div class="modal-body" style="min-height:100px; margin:10px">
						<input type="file" name="userfile" id="fileInput">
						<br />
						<div class="progress">
							<div class="progress-bar"></div>
						</div>
					</div>
				</form>

				<div class="modal-footer no-margin-top">
					<button class="btn" style="background-color: #343851; color: white; border-radius: 4px !important;"
						id="submit-import-data" onclick="save()">
						<i class="fa fa-upload"></i>
						Import
					</button>
					<button class="btn" style="background-color: #A01818; color: white; border-radius: 4px !important;"
						data-dismiss="modal">
						<i class="fa fa-times"></i>
						Close
					</button>
				</div>
			</div>
		</div>
	</div>
</div>