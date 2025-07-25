					<!-- Modal Form Data -->
					<div id="modal-form-data" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-form-data" aria-hidden="true">
						<div class="vertical-alignment-helper">
						<div class="modal-dialog vertical-align-center">
							<div class="modal-content">
								<form class="form-horizontal" id="frmInputData" enctype="multipart/form-data">
								<div class="modal-header bg-blue bg-font-blue no-padding">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
									<div class="table-header">
										<span id="mfdata"></span> <!-- <?php echo $smodul; ?>  -->
										<?php 
										if($this->module_name != 'absensi_menu'){
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
                                    	if($this->module_name == 'request_recruitment_menu'){
                                    		?>
                                    		<a class="btn btn-warning" id="btnDraft" onclick="save('draft')">
												Save as Draft
											</a> 

											<button class="btn btn-info" id="submit-data" onclick="save('waiting_approval')">
												<i class="fa fa-check"></i>
												Submit
											</button>
                                    		<?php
                                    	}else{
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
								            <button class="btn btn-info" style="background-color: #343851; color: white; border-radius: 4px !important; margin-right: 5px;" id="submit-data" onclick="save()">
								                <i class="fa fa-check"></i> Save
								            </button>
								        <?php endif; ?>
								    </span>

								    <!-- Container untuk tombol tetap di kanan -->
								    <span class="d-flex gap-2 ms-auto">
								        <button class="btn" style="background-color: #343851; color: white; border-radius: 4px !important;" onclick="reset()" id="btnReset">
								            <i class="fa fa-undo"></i> Reset
								        </button>
								        <button class="btn" style="background-color: #343851; color: white; border-radius: 4px !important;" data-dismiss="modal">
								            <i class="fa fa-times"></i> Close
								        </button>
								    </span>
								</div>



							</div>
						</div>
						</div>
					</div>