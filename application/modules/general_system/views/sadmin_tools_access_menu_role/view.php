<style>
	@media screen and (max-width: 768px) {

		.pagination>li>a,
		.pagination>li>span {
			font-size: 10px !important;
			padding: 3px 6px !important;
		}

		.pagination {
			margin: 5px 0 !important;
		}

		.dataTables_info {
			font-size: 11px !important;
		}

		#dynamic-table th,
		#dynamic-table td {
			display: table-cell !important;
			font-size: 12px !important;
			padding: 6px !important;
			white-space: nowrap !important;
			vertical-align: middle !important;
		}
	}


	.box {
		background-color: #343851 !important;
		border: 1px solid #6B6B6B !important;
	}


	.portlet.box .portlet-title .caption i {
		color: #ffffff !important;
	}

	.portlet.box .portlet-title .caption i {
		color: #ffffff !important;
	}

	.btn.btn-default {
		border: none !important;
		background-color: #dde0f3ff;
	}

	.btn.btn-default:hover {
		background-color: #979797ff !important;
		/* warna hover lebih gelap sedikit */
		border-color: #979797ff !important;
		color: #000 !important;
	}

	.modal-header {
		background-color: #343851 !important;
		color: #ffffff !important;
	}

	.pagination>li.active>a {
		background-color: #343851 !important;
		border-color: #343851 !important;
		color: #fff !important;
	}

	.pagination>li>a,
	.pagination>li>span {
		color: #343851 !important;
	}
</style>

<h3 class="page-title"></h3>
<div class="row">
	<div class="col-md-12">
		<!-- BEGIN TABLE PORTLET-->
		<div class="portlet box">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-database"></i><?php if (isset($title) && $title <> "")
						echo $title; ?>
				</div>
				<div class="actions">
					<?php if (_USER_ACCESS_LEVEL_EKSPORT == "1") { ?>
						<a class="btn btn-default btn-sm btn-circle" id="btnEksportData">
							<i class="fa fa-upload"></i>
							Eksport
						</a>
					<?php } ?>
					<?php if (_USER_ACCESS_LEVEL_IMPORT == "1") { ?>
						<a class="btn btn-default btn-sm btn-circle" id="btnImportData">
							<i class="fa fa-download"></i>
							Import
						</a>
					<?php } ?>
					<?php if (_USER_ACCESS_LEVEL_ADD == "1") { ?>

						<a class="btn btn-default btn-sm btn-circle" href="<?php echo base_url($sfolder . '/add'); ?>">
							<i class="fa fa-floppy-o"></i>
							Tambah Data
						</a>
					<?php } ?>
				</div>
			</div>
			<div class="portlet-body">
				<form action="<?php echo base_url($sfolder . '/truncate'); ?>" method="post" name="frmData"
					id="frmData">
					<table id="dynamic-table" class="table table-striped table-bordered table-hover table-header-fixed"
						style="width:100%">
						<thead>
							<tr>
								<th width="120px"></th>
								<th>Role</th>
								<th>Description</th>
							</tr>
						</thead>

						<tbody>
						</tbody>
					</table>
				</form>
			</div>
		</div>
		<!-- END TABLE PORTLET-->
	</div>
</div>


<div id="modal-table" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header no-padding">
				<div class="table-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
						<span class="white">&times;</span>
					</button>
					KONFIRMASI
				</div>
			</div>

			<div class="modal-body" style="height:100px;">
				Apakah anda yakin akan menghapus data ini?
			</div>

			<div class="modal-footer no-margin-top">
				<button class="btn btn-sm btn-danger pull-left" style="background-color: #A01818; color: white; border-radius: 2px !important" data-dismiss="modal">
					<i class="fa fa-times"></i>
					Close
				</button>
				<button class="btn btn-sm btn-danger pull-right">
					<i class="fa fa-times"></i>
					<a class="btn btn-danger">Confirm</a>
				</button>
			</div>
		</div>
	</div>
</div>
<div id="modal-import-data" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<form class="form-horizontal" method="POST" id="frmImportData" enctype="multipart/form-data"
				action="<?php echo base_url($sfolder . '/import_action'); ?>">
				<div class="modal-header no-padding">
					<div class="table-header " id="title_materi_paket_sesi">
						Import <?php echo $smodul; ?>
					</div>
				</div>

				<div class="modal-body" style="min-height:100px; margin:10px">
					<div class="progress"></div>
					<?php $this->mylib->inputfile("userfile"); ?>
				</div>

				<div class="modal-footer no-margin-top">
					<button class="btn btn-sm pull-left"
						style="background-color:red; color:white; border-radius:2px !important" data-dismiss="modal">
						<i class="fa fa-times"></i>
						Tutup
					</button>
					<button class="btn btn-sm pull-right"
						style="background-color:#343851; color:white; border-radius:2px !important"
						id="submit-import-data">
						<i class="fa fa-times"></i>
						Import
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div id="modal-eksport-data" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<form class="form-horizontal" method="POST" id="frmEksportData" enctype="multipart/form-data"
				action="<?php echo base_url($sfolder . '/eksport_action'); ?>">
				<div class="modal-header no-padding">
					<div class="table-header " id="title_materi_paket_sesi">
						Eksport <?php echo $smodul; ?>
					</div>
				</div>

				<div class="modal-footer no-margin-top">
					<button class="btn btn-sm pull-left"
						style="background-color:red; color:white; border-radius:2px !important" data-dismiss="modal">
						<i class="fa fa-times"></i>
						Tutup
					</button>
					<button class="btn btn-sm pull-right"
						style="background-color:#343851; color:white; border-radius:2px !important"
						id="submit-eksport-data">
						<i class="fa fa-times"></i>
						Eksport
					</button>
				</div>
			</form>
		</div>
	</div>
</div>