<div class="row">
	
	<div class="col-md-6 col-sm-12">

		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right"> Bulan Penggajian</label>
			<div class="col-md-8">
				<?=$sel_penggajian_bulan;?>
			</div>
		</div>
		<div class="form-group" id="inp_is_all_project_fcast">
			<label class="col-md-4 control-label no-padding-right">Semua Project?</label>
			<div class="col-md-8">
				<?=$is_all_project;?>
			</div>
		</div>

		<div class="form-group" id="inputProject_fcast" style="display: none;">
			<label class="col-md-4 control-label no-padding-right">Project</label>
			<div class="col-md-8">
				<?=$selprojectids;?>
			</div>
		</div>

		
	</div>

	<div class="col-md-6 col-sm-12">
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right"> Tahun Penggajian</label>
			<div class="col-md-8">
				<?=$txt_penggajian_tahun;?>
			</div>
		</div>
		
		
		
	</div>
</div>



<div class="row listfcast" id="listFcast" style="display:none;">
    <div class="col-md-12">
        <div class="portlet box">
            <div class="portlet-title">
                <div class="caption">Details </div>
                <div class="tools">
                   
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-scrollable tablesaw-cont">
                <table class="table table-striped table-bordered table-hover listfcast-list tablesaw tablesaw-stack" data-tablesaw-mode="stack" id="tblDetailListFcast">
                    <thead>
                        <tr>
                        	<th scope="col">No</th>
                            <th scope="col">NIK</th>
                            <th scope="col">Karyawan</th>
                            <th scope="col">Project</th>
                            <th scope="col">Total Masuk</th>
                            <th scope="col">Total Masuk (Nominal)</th>
                            <th scope="col">Total Lembur</th>
                            <th scope="col">Total Lembur (Nominal)</th>
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

								
