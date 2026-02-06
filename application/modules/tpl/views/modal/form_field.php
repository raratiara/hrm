<style>
	.modal-header {
		background-color: #112D80 !important;
		color: #fff !important;
	}

</style>


<!-- Modal Form Data -->
<div id="modal-form-data" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-form-data"
	aria-hidden="true">
	<div class="vertical-alignment-helper">
		<div class="modal-dialog model-dialog-centered vertical-align-center custom-modal">
			<div class="modal-content">
				<form class="form-horizontal" id="frmInputData" enctype="multipart/form-data">
					<div class="modal-header bg-blue bg-font-blue no-padding">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<div class="table-header">
							<span id="mfdata"></span> <!-- <?php echo $smodul; ?>  -->
							<?php
							if ($this->module_name != 'absensi_menu' && $this->module_name != 'absensi_os_menu') {
								echo $smodul;
							}
							?>
						</div>
					</div>

					<div class="modal-body" style="min-height:100px; margin:10px">
						<input type="hidden" name="id" value="">
						<?php $this->load->view("_field"); ?>
					</div>
				</form>

				<!-- <div class="modal-footer no-margin-top" id="mdlFooter">
									<span class="act-container-btn">
										<?php
										if ($this->module_name == 'request_recruitment_menu') {
											?>
											<a class="btn btn-warning" id="btnDraft" onclick="save('draft')">
												Save as Draft
											</a> 

											<button class="btn btn-info" id="submit-data" onclick="save('waiting_approval')">
												<i class="fa fa-check"></i>
												Submit
											</button>
											<?php
										} else {
											?>
											<button class="btn btn-info" id="submit-data" onclick="save()">
												<i class="fa fa-check"></i>
												Save
											</button>
											<?php
										}
										?> 
										
										<button class="btn" onclick="reset()" id="btnReset">
											<i class="fa fa-undo"></i>
											Reset
										</button>
									</span>
									<button class="btn" data-dismiss="modal">
										<i class="fa fa-times"></i>
										Close
									</button>
								</div> -->

				<div class="modal-footer d-flex justify-content-between flex-wrap no-margin-top" id="mdlFooter">
					<span class="act-container-btn d-flex flex-wrap gap-2">
						<?php if ($this->module_name == 'request_recruitment_menu'): ?>
							<a class="btn btn-warning" id="btnDraft" onclick="save('draft')">Save as Draft</a>
							<button class="btn btn-info" id="submit-data" onclick="save('waiting_approval')">
								<i class="fa fa-check"></i> Submit
							</button>
						<?php else: ?>
							<button class="btn btn-info"
								style="background-color: #112D80; color: white; border-radius: 4px !important; margin-right: 5px;"
								id="submit-data" onclick="save()">
								<i class="fa fa-check"></i> Save
							</button>
						<?php endif; ?>
					</span>

					<!-- Container untuk tombol tetap di kanan -->
					<span class="d-flex gap-2 ms-auto">
						<button class="btn"
							style="background-color: #FED24B; color: #112D80; border-radius: 4px !important;"
							onclick="reset()" id="btnReset">
							<i class="fa fa-undo"></i> Reset
						</button>
						<button class="btn"
							style="background-color: #A01818; color: white; border-radius: 4px !important;"
							data-dismiss="modal">
							<i class="fa fa-times"></i> Close
						</button>
					</span>
				</div>



			</div>
		</div>
	</div>
</div>




<!-- Loading Overlay -->
<div id="loadingOverlay" 
     style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
     background:rgba(255,255,255,0.8); z-index:99999; text-align:center; padding-top:20%;">
    <i class="fa fa-spinner fa-spin fa-4x"></i>
    <div style="margin-top:20px; font-size:18px; font-weight:bold; color:#112D80;">
        Processing, please wait...
    </div>
</div>