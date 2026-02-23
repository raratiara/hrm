<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript">
var module_path = "<?php echo base_url($folder_name);?>";
var save_method;
var idx;
var ldx;

// ===== CARD LIST STATE =====
var LMS = {
  page: 0,           // 0-based
  length: 9,         // cards per page
  search: "",
  status: "",        // filter status text
  totalRecords: 0,
  totalDisplay: 0,
  lastEcho: 1
};

// ===== Helpers =====
function escapeHtml(str){
  if(str === null || str === undefined) return '';
  return String(str)
    .replace(/&/g,"&amp;")
    .replace(/</g,"&lt;")
    .replace(/>/g,"&gt;")
    .replace(/"/g,"&quot;")
    .replace(/'/g,"&#039;");
}

function showEmptyState(){
  var $grid = $("#lmsGrid");
  $grid.empty();
  $grid.append(
    '<div class="col-md-12">'+
      '<div class="alert alert-info" style="border-radius:12px;">'+
        '<b>No data found.</b> Coba ubah keyword search / filter status.'+
      '</div>'+
    '</div>'
  );
  $("#lmsPagination").empty();
}

function buildBadge(status){
  // kamu bisa rapihin warna sesuai selera
  var bg = "#f1f5f9";
  var color = "#111";

  if(status === "Approved"){ bg="#dcfce7"; color="#166534"; }
  else if(status === "Rejected"){ bg="#fee2e2"; color="#991b1b"; }
  else if(status === "Waiting Approval"){ bg="#e0e7ff"; color="#3730a3"; }
  else if(status === "Request for Update"){ bg="#ffedd5"; color="#9a3412"; }

  return '<span class="badge-status" style="background:'+bg+';color:'+color+';">'+escapeHtml(status)+'</span>';
}

function parseRow(aaRow){
  // mapping dari model Training_menu_model:
  // 0 checkbox html
  // 1 actions html
  // 2 id
  // 3 training_name
  // 4 training_date
  // 5 location
  // 6 trainer
  // 7 notes
  // 8 status_name
  // 9 participant_names
  // 10 created_by_name
  return {
    checkboxHtml: aaRow[0] || '',
    actionsHtml: aaRow[1] || '',
    id: aaRow[2] || '',
    training_name: aaRow[3] || '',
    training_date: aaRow[4] || '',
    location: aaRow[5] || '',
    trainer: aaRow[6] || '',
    notes: aaRow[7] || '',
    status_name: aaRow[8] || '',
    participant_names: aaRow[9] || '',
    created_by_name: aaRow[10] || ''
  };
}

// ===== Render =====
function renderCards(rows){
  var $grid = $("#lmsGrid");
  $grid.empty();

  if(!rows || rows.length === 0){
    showEmptyState();
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
});
</script>
