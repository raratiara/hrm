<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Modal Reject Data -->
<div id="modal-reject-data" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-reject-data" aria-hidden="true">
	<div class="vertical-alignment-helper">
	<div class="modal-dialog vertical-align-center">
		<div class="modal-content" style="text-align:center;">
			<form class="form-horizontal" id="frmRejectData">
			<div class="modal-header bg-blue bg-font-blue no-padding">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<div class="table-header">
					Reject Training
				</div>
			</div>

			<div class="modal-body" style="min-height:100px; margin:10px">
				<p class="text-center">Are you sure to reject this data?</p>
				<div class="form-group">
					<label class="col-md-4 control-label no-padding-right">Reason</label>
					<div class="col-md-8">
						<?=$reject_reason;?>
						<input type="hidden" name="approval_id_reject" id="approval_id_reject" value="">
						<input type="hidden" name="approval_level_reject" id="approval_level_reject" value="">
					</div>
				</div>
			</div>
			 </form>

			<div class="modal-footer no-margin-top">
				<center>
				<button class="btn blue" id="submit-reject-data" onclick="save_reject()">
					<i class="fa fa-check"></i>
					Ok
				</button>
				<button class="btn blue" data-dismiss="modal">
					<i class="fa fa-times"></i>
					Cancel
				</button>
				</center>
			</div>
		</div>
	</div>
	</div>
</div>

<!-- Modal Approve Data -->
<div id="modal-approve-data" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-approve-data" aria-hidden="true">
	<div class="vertical-alignment-helper">
	<div class="modal-dialog vertical-align-center">
		<div class="modal-content">
			<form class="form-horizontal" id="frmApproveData">
			<div class="modal-header bg-blue bg-font-blue no-padding">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<div class="table-header">
					Approve Training
				</div>
			</div>

			<div class="modal-body" style="min-height:100px; margin:10px">
				<p class="text-center">Are you sure to approve this data?</p>
				<input type="hidden" name="approval_id_approve" id="approval_id_approve" value="">
				<input type="hidden" name="approval_level_approve" id="approval_level_approve" value="">
			</div>
			 </form>

			<div class="modal-footer no-margin-top">
				<center>
				<button class="btn blue" id="submit-approve-data" onclick="save_approve()">
					<i class="fa fa-check"></i>
					Ok
				</button>
				<button class="btn blue" data-dismiss="modal">
					<i class="fa fa-times"></i>
					Cancel
				</button>
				</center>
			</div>
		</div>
	</div>
	</div>
</div>

<!-- Modal RFU Data -->
<div id="modal-rfu-data" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-rfu-data" aria-hidden="true">
	<div class="vertical-alignment-helper">
	<div class="modal-dialog vertical-align-center">
		<div class="modal-content" style="text-align:center;">
			<form class="form-horizontal" id="frmRFUData">
			<div class="modal-header bg-blue bg-font-blue no-padding">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<div class="table-header">
					Request For Update
				</div>
			</div>

			<div class="modal-body" style="min-height:100px; margin:10px">
				<p class="text-center">Are you sure to request update for this data?</p>
				<div class="form-group">
					<label class="col-md-4 control-label no-padding-right">Reason</label>
					<div class="col-md-8">
						<?=$rfu_reason;?>
						<input type="hidden" name="approval_id_rfu" id="approval_id_rfu" value="">
						<input type="hidden" name="approval_level_rfu" id="approval_level_rfu" value="">
					</div>
				</div>
			</div>
			 </form>

			<div class="modal-footer no-margin-top">
				<center>
				<button class="btn blue" id="submit-rfu-data" onclick="save_rfu()">
					<i class="fa fa-check"></i>
					Ok
				</button>
				<button class="btn blue" data-dismiss="modal">
					<i class="fa fa-times"></i>
					Cancel
				</button>
				</center>
			</div>
		</div>
	</div>
	</div>
</div>

<script type="text/javascript">
var module_path = "<?php echo base_url($folder_name);?>"; //for save method string
var myTable;
var validator;
var save_method; //for save method string
var idx; //for save index string
var ldx; //for save list index string
var TRAINING_UI = {
	page: 1,
	pageSize: 9,
	search: '',
	status: '',
	isLoading: false
};




$(document).ready(function() {
   	$(function() {
   		
        $( "#training_date" ).datetimepicker();
		
	});
});

function escapeHtml(text) {
	return $('<div>').text(text == null ? '' : text).html();
}

function getTrainingStatusBadge(status) {
	var label = status || '-';
	var style = 'background:#eef2f7;color:#475569;';

	if (label === 'Waiting Approval') {
		style = 'background:#f3e8ff;color:#7c3aed;';
	} else if (label === 'Approved') {
		style = 'background:#dcfce7;color:#166534;';
	} else if (label === 'Rejected') {
		style = 'background:#fee2e2;color:#b91c1c;';
	} else if (label === 'Request for Update') {
		style = 'background:#ffedd5;color:#c2410c;';
	}

	return '<span class="badge-status" style="' + style + '">' + escapeHtml(label) + '</span>';
}

function buildTrainingCard(row) {
	var checkboxHtml = row[0] || '';
	var actionHtml = row[1] || '';
	var id = row[2] || '';
	var trainingName = row[3] || '-';
	var trainingDate = row[4] || '-';
	var location = row[5] || '-';
	var trainer = row[6] || '-';
	var notes = row[7] || '-';
	var status = row[8] || '-';
	var participants = row[9] || '-';
	var createdBy = row[10] || '-';

	return '' +
		'<div class="col-md-4 col-sm-6 col-xs-12" style="margin-bottom:16px;">' +
			'<div class="lms-card">' +
				'<div class="cover">' +
					getTrainingStatusBadge(status) +
					'<div style="position:absolute;left:14px;bottom:12px;font-size:22px;color:#112D80;">' +
						'<i class="fa fa-graduation-cap"></i>' +
					'</div>' +
				'</div>' +
				'<div class="body">' +
					'<div class="title">' + escapeHtml(trainingName) + '</div>' +
					'<div class="meta">' +
						'<div class="row-meta"><i class="fa fa-calendar"></i><span>' + escapeHtml(trainingDate) + '</span></div>' +
						'<div class="row-meta"><i class="fa fa-map-marker"></i><span>' + escapeHtml(location) + '</span></div>' +
						'<div class="row-meta"><i class="fa fa-user"></i><span>' + escapeHtml(trainer) + '</span></div>' +
						'<div class="row-meta"><i class="fa fa-users"></i><span>' + escapeHtml(participants) + '</span></div>' +
						'<div class="row-meta"><i class="fa fa-pencil-square-o"></i><span>' + escapeHtml(createdBy) + '</span></div>' +
						'<div class="row-meta"><i class="fa fa-sticky-note-o"></i><span>' + escapeHtml(notes) + '</span></div>' +
					'</div>' +
				'</div>' +
				'<div class="footer">' +
					'<div class="select-box">' + checkboxHtml + ' <span>#' + escapeHtml(id) + '</span></div>' +
					'<div class="actions">' + actionHtml + '</div>' +
				'</div>' +
			'</div>' +
		'</div>';
}

function renderTrainingPagination(totalRecords) {
	var $p = $('#lmsPagination');
	if (!$p.length) return;

	$p.empty();

	var totalPages = Math.ceil((totalRecords || 0) / TRAINING_UI.pageSize);
	if (totalPages <= 1) return;

	function addItem(page, label, active, disabled) {
		$p.append(
			'<li class="' + (active ? 'active' : '') + ' ' + (disabled ? 'disabled' : '') + '">' +
				'<a href="javascript:void(0);" data-page="' + page + '">' + label + '</a>' +
			'</li>'
		);
	}

	addItem(TRAINING_UI.page - 1, '&laquo;', false, TRAINING_UI.page <= 1);

	var start = Math.max(1, TRAINING_UI.page - 2);
	var end = Math.min(totalPages, TRAINING_UI.page + 2);

	if (start > 1) {
		addItem(1, '1', TRAINING_UI.page === 1, false);
		if (start > 2) $p.append('<li class="disabled"><span>...</span></li>');
	}

	for (var i = start; i <= end; i++) {
		addItem(i, i, TRAINING_UI.page === i, false);
	}

	if (end < totalPages) {
		if (end < totalPages - 1) $p.append('<li class="disabled"><span>...</span></li>');
		addItem(totalPages, totalPages, TRAINING_UI.page === totalPages, false);
	}

	addItem(TRAINING_UI.page + 1, '&raquo;', false, TRAINING_UI.page >= totalPages);
}

function loadCards() {
	if (TRAINING_UI.isLoading) return;
	TRAINING_UI.isLoading = true;

	var $grid = $('#lmsGrid');
	if (!$grid.length) {
		TRAINING_UI.isLoading = false;
		return;
	}

  rows.forEach(function(r){
    var badge = buildBadge(r.status_name);

    // checkbox: ambil value id & class data-check dari server (bulk delete tetap nyambung)
    // kalau checkbox html kosong (misal akses delete off), kita handle
    var checkboxBlock = '';
    if(r.checkboxHtml && r.checkboxHtml.trim() !== ''){
      checkboxBlock =
        '<div class="select-box">'+
          r.checkboxHtml+
          '<span>Select</span>'+
        '</div>';
    } else {
      checkboxBlock = '<div></div>';
    }

    // actionsHtml dari server sudah ada onclick detail/edit/reject/approve/rfu
    var actionsBlock = '<div class="actions">'+ (r.actionsHtml || '') +'</div>';

    var html =
      '<div class="col-md-4 col-sm-6" style="margin-bottom:16px;">'+
        '<div class="lms-card">'+
          '<div class="cover">'+ badge +'</div>'+
          '<div class="body">'+
            '<div class="title">'+ escapeHtml(r.training_name) +'</div>'+
            '<div class="meta">'+
              '<div class="row-meta"><i class="fa fa-calendar"></i><div>'+ escapeHtml(r.training_date) +'</div></div>'+
              '<div class="row-meta"><i class="fa fa-map-marker"></i><div>'+ escapeHtml(r.location) +'</div></div>'+
              '<div class="row-meta"><i class="fa fa-user"></i><div>'+ escapeHtml(r.trainer) +'</div></div>'+
              '<div class="row-meta"><i class="fa fa-users"></i><div>'+ escapeHtml(r.participant_names) +'</div></div>'+
              '<div class="row-meta"><i class="fa fa-id-badge"></i><div>'+ escapeHtml(r.created_by_name) +'</div></div>'+
            '</div>'+
          '</div>'+
          '<div class="footer">'+
            checkboxBlock+
            actionsBlock+
          '</div>'+
        '</div>'+
      '</div>';

    $grid.append(html);
  });
}

function renderPagination(){
  var total = LMS.totalDisplay || 0;
  var perPage = LMS.length;
  var pages = Math.ceil(total / perPage);
  var current = LMS.page; // 0-based

  var $p = $("#lmsPagination");
  $p.empty();

  if(pages <= 1){
    return;
  }

  function pageItem(label, pageIndex, disabled, active){
    var cls = 'page-item';
    if(disabled) cls += ' disabled';
    if(active) cls += ' active';

    var href = 'javascript:void(0);';
    var li =
      '<li class="'+cls+'">'+
        '<a class="page-link" href="'+href+'" data-page="'+pageIndex+'">'+label+'</a>'+
      '</li>';
    return li;
  }

  // prev
  $p.append(pageItem('&laquo;', current-1, current===0, false));

  // window pages
  var start = Math.max(0, current - 2);
  var end = Math.min(pages - 1, current + 2);

  if(start > 0){
    $p.append(pageItem('1', 0, false, current===0));
    if(start > 1){
      $p.append('<li class="page-item disabled"><a class="page-link" href="javascript:void(0);">…</a></li>');
    }
  }

  for(var i=start; i<=end; i++){
    $p.append(pageItem(String(i+1), i, false, i===current));
  }

  if(end < pages - 1){
    if(end < pages - 2){
      $p.append('<li class="page-item disabled"><a class="page-link" href="javascript:void(0);">…</a></li>');
    }
    $p.append(pageItem(String(pages), pages-1, false, current===pages-1));
  }

  // next
  $p.append(pageItem('&raquo;', current+1, current >= pages-1, false));
}

// ===== Fetch =====
function fetchTrainingCards(){
  // DataTables server-side params
  var start = LMS.page * LMS.length;
  var length = LMS.length;

  // untuk filter status: paling aman kita “gabung” ke search global (karena endpoint get_data pakai sSearch)
  // jadi kalau status aktif, kita append ke search string
  var searchTerm = (LMS.search || '').trim();
  var finalSearch = searchTerm;

  if(LMS.status && LMS.status !== ''){
    // supaya pencarian tetep bisa
    // catatan: ini tergantung kolom status_name ada di aColumns (ada), jadi aman
    finalSearch = (finalSearch ? (finalSearch + ' ') : '') + LMS.status;
  }

  // sEcho harus naik supaya consistent
  LMS.lastEcho = (LMS.lastEcho || 1) + 1;

  $.ajax({
    url: module_path + "/get_data",
    type: "GET",
    dataType: "json",
    cache: false,
    data: {
      sEcho: LMS.lastEcho,
      iDisplayStart: start,
      iDisplayLength: length,
      sSearch: finalSearch
    },
    beforeSend: function(){
      if(typeof showLoading === "function") showLoading();
    },
    success: function(res){
      // res: { sEcho, iTotalRecords, iTotalDisplayRecords, aaData: [...] }
      LMS.totalRecords = parseInt(res.iTotalRecords || 0, 10);
      LMS.totalDisplay = parseInt(res.iTotalDisplayRecords || 0, 10);

      // STAT total (pakai total record dari server)
      $("#stat_total").text(LMS.totalRecords);

      // STAT waiting/approved: ini dari PAGE yang tampil saja (biar tidak nambah endpoint)
      // kalau kamu mau akurat 100% seluruh data, harus bikin endpoint stats khusus (baru nanti)
      var waiting = 0, approved = 0;
      var rows = (res.aaData || []).map(parseRow);
      rows.forEach(function(r){
        if(r.status_name === "Waiting Approval") waiting++;
        if(r.status_name === "Approved") approved++;
      });
      $("#stat_waiting").text(waiting);
      $("#stat_approved").text(approved);

      renderCards(rows);
      renderPagination();

      // sync check-all state
      if($("#check-all").length){
        $("#check-all").prop("checked", false);
      }
    },
    error: function(jqXHR){
      var dialog = bootbox.dialog({
        title: 'Error ' + jqXHR.status + ' - ' + jqXHR.statusText,
        message: jqXHR.responseText,
        buttons: { confirm: { label: 'Ok', className: 'btn blue' } }
      });
      showEmptyState();
    },
    complete: function(){
      if(typeof hideLoading === "function") hideLoading();
    }
  });
}

// supaya reload_table() di common_module_js bisa memanggil card refresh
window.loadCards = fetchTrainingCards;

// ===== Events =====
var searchTimer = null;

$(document).ready(function(){
  // initial load
  fetchTrainingCards();

  // search
  $("#lmsSearch").on("input", function(){
    clearTimeout(searchTimer);
    var val = $(this).val();
    searchTimer = setTimeout(function(){
      LMS.search = val;
      LMS.page = 0;
      fetchTrainingCards();
    }, 350);
  });

  // filter status
  $(".lms-filters").on("click", "button[data-status]", function(e){
    e.preventDefault();
    $(".lms-filters button[data-status]").removeClass("active");
    $(this).addClass("active");

    LMS.status = $(this).data("status") || "";
    LMS.page = 0;
    fetchTrainingCards();
  });

  // pagination click
  $("#lmsPagination").on("click", "a.page-link", function(e){
    e.preventDefault();
    var p = parseInt($(this).data("page"), 10);
    if(isNaN(p)) return;

    var pages = Math.ceil((LMS.totalDisplay || 0) / LMS.length);
    if(p < 0 || p >= pages) return;

    LMS.page = p;
    fetchTrainingCards();
  });

  // check all (bulk delete)
  $("#check-all").on("click", function(){
    $(".data-check").prop('checked', $(this).prop('checked'));
  });

  <?php if  (_USER_ACCESS_LEVEL_ADD == "1" || _USER_ACCESS_LEVEL_UPDATE == "1") { ?>
  validator = $("#frmInputData").validate({
    errorElement: 'span',
    errorClass: 'help-block help-block-error',
    focusInvalid: false,
    ignore: "",
    rules: {
      training_name: { required: true },
      training_date: { required: true }
    },
    messages: {},
    errorPlacement: function (error, element) {
      if (element.parent(".input-group").size() > 0) {
        error.insertAfter(element.parent(".input-group"));
      } else if (element.attr("data-error-container")) {
        error.appendTo(element.attr("data-error-container"));
      } else if (element.parents('.radio-list').size() > 0) {
        error.appendTo(element.parents('.radio-list').attr("data-error-container"));
      } else if (element.parents('.radio-inline').size() > 0) {
        error.appendTo(element.parents('.radio-inline').attr("data-error-container"));
      } else if (element.parents('.checkbox-list').size() > 0) {
        error.appendTo(element.parents('.checkbox-list').attr("data-error-container"));
      } else if (element.parents('.checkbox-inline').size() > 0) {
        error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
      } else {
        error.insertAfter(element);
      }
    },
    highlight: function (element) {
      $(element).closest('.form-group').addClass('has-error');
    },
    unhighlight: function (element) {
      $(element).closest('.form-group').removeClass('has-error');
    },
    success: function (label) {
      label.closest('.form-group').removeClass('has-error');
    }
  });
  <?php } ?>

});

<?php $this->load->view(_TEMPLATE_PATH . "common_module_js"); ?>

<?php if  (_USER_ACCESS_LEVEL_VIEW == "1" && (_USER_ACCESS_LEVEL_UPDATE == "1" || _USER_ACCESS_LEVEL_DETAIL == "1")) { ?>
function load_data()
{
    $.ajax({
      type: "POST",
      url : module_path+'/get_detail_data',
      data: { id: idx },
      cache: false,
      dataType: "JSON",
      success: function(data)
      {
        if(data != false){
          if(save_method == 'update'){
            $('[name="id"]').val(data.id);
            $('[name="training_name"]').val(data.training_name);
            $('[name="trainer"]').val(data.trainer);
            $('[name="training_date"]').val(data.training_date);
            $('[name="location"]').val(data.location);
            $('[name="notes"]').val(data.notes);

            if(data.lms_course_id){
              $('select#lms_course').val(data.lms_course_id).trigger('change');
            }

            if(data.participants){
              var empIds = data.participants.split(',');
              $('select#employee').val(empIds).trigger('change');
            }

            if(data.rfu_reason && data.rfu_reason != ''){
              $('#rfuReasonEdit').show();
              $('span.rfu_reason_edit').html(data.rfu_reason);
            } else {
              $('#rfuReasonEdit').hide();
            }

            $.uniform.update();
            $('#mfdata').text('Update');
            $('#modal-form-data').modal('show');
          }
          if(save_method == 'detail'){
            $('span.training_name').html(data.training_name);
            $('span.trainer').html(data.trainer);
            $('span.training_date').html(data.training_date);
            $('span.location').html(data.location);
            $('span.notes').html(data.notes);
            $('span.lms_course').html(data.course_name || '-');
            $('span.participant').html(data.participant_names || '-');
            $('span.created_by_name').html(data.created_by_name || '-');

            if(data.rfu_reason && data.rfu_reason != ''){
              $('#rfuReason').show();
              $('span.rfu_reason').html(data.rfu_reason);
            } else {
              $('#rfuReason').hide();
            }

            if(data.reject_reason && data.reject_reason != ''){
              $('#rejectReason').show();
              $('span.reject_reason').html(data.reject_reason);
            } else {
              $('#rejectReason').hide();
            }

            $('#modal-view-data').modal('show');
          }
        } else {
          title = '<div class="text-center" style="padding-top:20px;padding-bottom:10px;"><i class="fa fa-exclamation-circle fa-5x" style="color:red"></i></div>';
          btn = '<br/><button class="btn blue" data-dismiss="modal">OK</button>';
          msg = '<p>Gagal peroleh data.</p>';
          var dialog = bootbox.dialog({
            message: title+'<center>'+msg+btn+'</center>'
          });
        }
      },
      error: function (jqXHR, textStatus, errorThrown)
      {
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

function reject(id, approval_level){
  expire();
  Swal.fire({
    title: 'Reject Training',
    input: 'textarea',
    inputLabel: 'Reject Reason',
    inputPlaceholder: 'Type your reason here...',
    showCancelButton: true,
    confirmButtonText: 'Reject',
    confirmButtonColor: '#A01818',
    preConfirm: (reason) => {
      if (!reason) {
        Swal.showValidationMessage('Please enter a reason');
      }
      return reason;
    }
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        type: 'POST',
        url: module_path + '/reject',
        data: { id: id, approval_level: approval_level, reject_reason: result.value },
        dataType: 'JSON',
        success: function(response) {
          if(response){
            Swal.fire('Rejected!', 'Training has been rejected.', 'success');
            reload_table();
          } else {
            Swal.fire('Error', 'Failed to reject.', 'error');
          }
        },
        error: function() {
          Swal.fire('Error', 'Server error.', 'error');
        }
      });
    }
  });
}

function approve(id, approval_level){
  expire();
  Swal.fire({
    title: 'Approve Training?',
    text: 'Are you sure you want to approve this training?',
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: 'Approve',
    confirmButtonColor: '#2c9e1f'
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        type: 'POST',
        url: module_path + '/approve',
        data: { id: id, approval_level: approval_level },
        dataType: 'JSON',
        success: function(response) {
          if(response){
            Swal.fire('Approved!', 'Training has been approved.', 'success');
            reload_table();
          } else {
            Swal.fire('Error', 'Failed to approve.', 'error');
          }
        },
        error: function() {
          Swal.fire('Error', 'Server error.', 'error');
        }
      });
    }
  });
}

function rfu(id, approval_level){
  expire();
  Swal.fire({
    title: 'Request for Update',
    input: 'textarea',
    inputLabel: 'RFU Reason',
    inputPlaceholder: 'Type your reason here...',
    showCancelButton: true,
    confirmButtonText: 'Submit',
    confirmButtonColor: '#fd9b00',
    preConfirm: (reason) => {
      if (!reason) {
        Swal.showValidationMessage('Please enter a reason');
      }
      return reason;
    }
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        type: 'POST',
        url: module_path + '/rfu',
        data: { id: id, approval_level: approval_level, reason: result.value },
        dataType: 'JSON',
        success: function(response) {
          if(response){
            Swal.fire('Submitted!', 'Request for Update has been sent.', 'success');
            reload_table();
          } else {
            Swal.fire('Error', 'Failed to submit.', 'error');
          }
        },
        error: function() {
          Swal.fire('Error', 'Server error.', 'error');
        }
      });
    }
  });
}

</script>
