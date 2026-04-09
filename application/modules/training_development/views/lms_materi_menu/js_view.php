<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript">
	var module_path = "<?php echo base_url($folder_name); ?>"; //for save method string
	var myTable; // kita “palsukan” object ini supaya common_module_js tetap bisa reload
	var validator;
	var save_method; //for save method string
	var idx; //for save index string
	var ldx; //for save list index string

	// ====== CARD STATE ======
	var LMS = {
		page: 1,
		pageSize: 9,
		search: '',
		typeFilter: '',
		isLoading: false
	};

	// ====== LOADING OVERLAY (kalau kamu pakai) ======
	function showLoading() {
		if ($("#loadingOverlay").length) $("#loadingOverlay").show();
	}

	function hideLoading() {
		if ($("#loadingOverlay").length) $("#loadingOverlay").hide();
	}

	// ====== RENDER EMPTY STATE (lebih cakep) ======
	function renderEmptyState($grid) {
		$grid.append(
			'<div class="col-md-12">' +
			'<div class="alert alert-info" style="border-radius:12px;">' +
			'<b>No data found.</b> Coba ubah keyword search.' +
			'</div>' +
			'</div>'
		);
	}

	// ====== BUILD CARD HTML ======
	// row DataTables untuk materi: 
	// row[0]=checkbox html, row[1]=actions html, row[2]=id, row[3]=title, row[4]=type_name, row[5]=doc(html), row[6]=department_names, row[7]=course_name
	function buildMateriCard(row) {
		var checkboxHtml = row[0] || '';
		var actionHtml = row[1] || '';
		var id = row[2] || '';
		var title = row[3] || '-';
		var typeName = row[4] || '-';
		var docHtml = row[5] || '';
		var depts = row[6] || '-';
		var course = row[7] || '-';

		// Badge
		var badge = '<span class="badge-status">' + typeName + '</span>';

		// Cover icon based on type
		var coverIcon = '<i class="fa fa-file-pdf-o"></i>';
		if ((typeName + '').toLowerCase().indexOf('youtube') >= 0) coverIcon = '<i class="fa fa-youtube-play"></i>';

		return '' +
			'<div class="col-md-4 col-sm-6 col-xs-12" style="margin-bottom:16px;">' +
			'<div class="lms-card">' +
			'<div class="cover">' +
			badge +
			'<div style="position:absolute;left:14px;bottom:12px;font-size:22px;color:#112D80;">' + coverIcon + '</div>' +
			'</div>' +
			'<div class="body">' +
			'<div class="title">' + title + '</div>' +
			'<div class="meta">' +
			'<div><i class="fa fa-graduation-cap"></i> ' + course + '</div>' +
			'<div><i class="fa fa-building"></i> ' + depts + '</div>' +
			'<div><i class="fa fa-link"></i> ' + docHtml + '</div>' +
			'</div>' +
			'</div>' +
			'<div class="footer">' +
			'<div class="select-box">' + checkboxHtml + ' <span>#' + id + '</span></div>' +
			'<div class="actions">' + actionHtml + '</div>' +
			'</div>' +
			'</div>' +
			'</div>';
	}

	// ====== PAGINATION ======
	function renderPagination(totalRecords) {
		var $p = $('#lmsPagination');
		if (!$p.length) return;

		$p.empty();

		var totalPages = Math.ceil((totalRecords || 0) / LMS.pageSize);
		if (totalPages <= 1) return;

		function li(page, label, active, disabled) {
			return '' +
				'<li class="' + (active ? 'active' : '') + ' ' + (disabled ? 'disabled' : '') + '">' +
				'<a href="javascript:void(0);" data-page="' + page + '">' + label + '</a>' +
				'</li>';
		}

		// Prev
		$p.append(li(LMS.page - 1, '&laquo;', false, LMS.page <= 1));

		// Pages (simple)
		var start = Math.max(1, LMS.page - 2);
		var end = Math.min(totalPages, LMS.page + 2);

		if (start > 1) {
			$p.append(li(1, '1', LMS.page === 1, false));
			if (start > 2) $p.append('<li class="disabled"><span>...</span></li>');
		}

		for (var i = start; i <= end; i++) {
			$p.append(li(i, i, LMS.page === i, false));
		}

		if (end < totalPages) {
			if (end < totalPages - 1) $p.append('<li class="disabled"><span>...</span></li>');
			$p.append(li(totalPages, totalPages, LMS.page === totalPages, false));
		}

		// Next
		$p.append(li(LMS.page + 1, '&raquo;', false, LMS.page >= totalPages));

		// click
		$p.off('click', 'a[data-page]').on('click', 'a[data-page]', function() {
			var p = parseInt($(this).data('page'), 10);
			if (isNaN(p) || p < 1 || p > totalPages) return;
			LMS.page = p;
			loadMateriGrid();
		});
	}

	// ====== LOAD GRID (ambil dari get_data format DataTables) ======
	function loadMateriGrid() {
		if (LMS.isLoading) return;
		LMS.isLoading = true;

		var $grid = $('#lmsGrid');
		if (!$grid.length) {
			console.error('Element #lmsGrid tidak ditemukan.');
			LMS.isLoading = false;
			return;
		}

		showLoading();

		// Parameter DataTables server-side yang model kamu sudah ngerti
		var start = (LMS.page - 1) * LMS.pageSize;

		$.ajax({
			url: module_path + "/get_data",
			type: "GET",
			dataType: "json",
			data: {
				sEcho: 1,
				iDisplayStart: start,
				iDisplayLength: LMS.pageSize,
				sSearch: LMS.search
			},
			success: function(res) {
				$grid.empty();

				// DataTables format
				var rows = (res && res.aaData) ? res.aaData : [];
				// ===== FILTER BY TYPE (PDF / Youtube) =====
				if (LMS.typeFilter) {
					rows = rows.filter(function(r) {
						// r[4] = type_name dari backend (PDF / Youtube)
						return String(r[4] || '')
							.toLowerCase()
							.includes(String(LMS.typeFilter).toLowerCase());
					});
				}

				var totalRecords = (res && typeof res.iTotalDisplayRecords !== 'undefined') ? parseInt(res.iTotalDisplayRecords, 10) : 0;
				var totalAll = (res && typeof res.iTotalRecords !== 'undefined') ? parseInt(res.iTotalRecords, 10) : totalRecords;

				// Update stat total (opsional)
				if ($('#stat_total').length) $('#stat_total').text(totalAll);

				// Render
				if (!rows || rows.length === 0) {
					renderEmptyState($grid);
					if ($('#lmsPagination').length) $('#lmsPagination').empty();
					return;
				}

				for (var i = 0; i < rows.length; i++) {
					$grid.append(buildMateriCard(rows[i]));
				}

				renderPagination(totalRecords);

				// checkbox handler (buat bulk)
				<?php if (_USER_ACCESS_LEVEL_DELETE == "1") { ?>
					$("#check-all").off('click').on('click', function() {
						$(".data-check").prop('checked', $(this).prop('checked'));
					});
				<?php } ?>
			},
			error: function(xhr) {
				$grid.empty();
				$grid.append(
					'<div class="col-md-12">' +
					'<div class="alert alert-danger" style="border-radius:12px;">' +
					'<b>Error load data.</b><br>' + (xhr.responseText || 'Unknown error') +
					'</div>' +
					'</div>'
				);
				if ($('#lmsPagination').length) $('#lmsPagination').empty();
			},
			complete: function() {
				hideLoading();
				LMS.isLoading = false;
			}
		});
	}

	$(document).ready(function() {
		// search
		$('#lmsSearch').off('keyup').on('keyup', function() {
			LMS.search = $(this).val();
			LMS.page = 1;
			loadMateriGrid();
		});

		// INIT load
		loadMateriGrid();

		// Bikin “myTable” dummy supaya common_module_js bisa call reload_table()
		myTable = {
			ajax: {
				reload: function() {
					// saat save/delete/bulk sukses => common_module_js panggil reload_table() => masuk sini
					loadMateriGrid();
				}
			}
		};
	});

	// ================= FILTER CLICK =================
	$(document).on('click', '.lms-filters .btn', function(e) {
		e.preventDefault();

		// UI aktif
		$('.lms-filters .btn').removeClass('active');
		$(this).addClass('active');

		// ambil filter type
		LMS.typeFilter = $(this).data('type') || '';

		// reset halaman & reload
		LMS.page = 1;
		loadMateriGrid();
	});


	// =========================== BAGIAN DETAIL/EDIT DARI KODE ASLI KAMU ===========================
	<?php if (_USER_ACCESS_LEVEL_VIEW == "1" && (_USER_ACCESS_LEVEL_UPDATE == "1" || _USER_ACCESS_LEVEL_DETAIL == "1")) { ?>

		function load_data() {
			var getUrl = window.location;
			/*local=>*/
			var baseUrl = getUrl.protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];

			$.ajax({
				type: "POST",
				url: module_path + '/get_detail_data',
				data: {
					id: idx
				},
				cache: false,
				dataType: "JSON",
				success: function(data) {
					if (data != false) {
						if (save_method == 'update') {
							$('[name="id"]').val(data.id);

							$('select#course').val(data.lms_course_id).trigger('change.select2');
							$('[name="title_materi"]').val(data.title);
							$('select#type').val(data.type).trigger('change.select2');

							var deptIds = [];
							if (data.department_ids) {
								deptIds = data.department_ids.split(',');
							}

							var $deptSelect = $('#departments');

							deptIds.forEach(function(id) {
								if ($deptSelect.find('option[value="' + id + '"]').length === 0) {
									var newOption = new Option('Department ' + id, id, true, true);
									$deptSelect.append(newOption);
								}
							});

							$deptSelect.val(deptIds).trigger('change.select2');

							if (data.type == 1) { /// PDF
								document.getElementById('inpFile').style.display = 'block';
								document.getElementById('inpUrl').style.display = 'none';

								$('[name="hdnfile"]').val(data.file_pdf);
								const fileName = data.file_pdf;
								const fileUrl = baseUrl + "/uploads/lms_materi/" + fileName;

								document.getElementById("file-link").innerHTML = '';

								const link = document.createElement('a');
								link.href = fileUrl;
								link.textContent = "Current PDF";
								link.target = "_blank";

								document.getElementById("file-link").appendChild(link);

							} else { //Youtube
								document.getElementById('inpFile').style.display = 'none';
								document.getElementById('inpUrl').style.display = 'block';

								$('[name="youtube_url"]').val(data.url_youtube);
							}

							$.uniform.update();
							$('#mfdata').text('Update');
							$('#modal-form-data').modal('show');
						}

						if (save_method == 'detail') {
							$('span.departments').html(data.department_names);
							$('span.course').html(data.course_name);
							$('span.title_materi').html(data.title);
							$('span.type').html(data.type_name);

							if (data.type == 1) { /// PDF
								document.getElementById('inpFileView').style.display = 'block';
								document.getElementById('inpUrlView').style.display = 'none';

								const fileName = data.file_pdf;
								const fileUrl = baseUrl + "/uploads/lms_materi/" + fileName;

								document.getElementById("file-link-view").innerHTML = '';

								const link = document.createElement('a');
								link.href = fileUrl;
								link.textContent = "Current PDF";
								link.target = "_blank";

								document.getElementById("file-link-view").appendChild(link);

							} else { //Youtube
								document.getElementById('inpFileView').style.display = 'none';
								document.getElementById('inpUrlView').style.display = 'block';

								$('span.youtube_url').html(data.url_youtube);
							}

							$('#modal-view-data').modal('show');
						}
					} else {
						title = '<div class="text-center" style="padding-top:20px;padding-bottom:10px;"><i class="fa fa-exclamation-circle fa-5x" style="color:red"></i></div>';
						btn = '<br/><button class="btn blue" data-dismiss="modal">OK</button>';
						msg = '<p>Gagal peroleh data.</p>';
						var dialog = bootbox.dialog({
							message: title + '<center>' + msg + btn + '</center>'
						});
						setTimeout(function() {
							dialog.modal('hide');
						}, 1500);
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					var dialog = bootbox.dialog({
						title: 'Error ' + jqXHR.status + ' - ' + jqXHR.statusText,
						message: jqXHR.responseText,
						buttons: {
							confirm: {
								label: 'Ok',
								className: 'btn blue'
							}
						}
					});
				}
			});
		}
	<?php } ?>

	// toggle type PDF/Youtube
	$('select[name="type"]').off('change').on('change', function() {
		var type = $("#type").val();
		if (type == 1) {
			document.getElementById('inpFile').style.display = 'block';
			document.getElementById('inpUrl').style.display = 'none';
		} else {
			document.getElementById('inpFile').style.display = 'none';
			document.getElementById('inpUrl').style.display = 'block';
		}
	});

	function downloadFile(filename) {
		const link = document.createElement('a');
		link.href = module_path + '/downloadFile?file=' + encodeURIComponent(filename);
		link.setAttribute('download', filename);
		document.body.appendChild(link);
		link.click();
		document.body.removeChild(link);
	}
</script>