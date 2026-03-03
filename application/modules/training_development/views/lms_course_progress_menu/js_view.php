<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript">
var module_path = "<?php echo base_url($folder_name);?>";
var validator;
var save_method;
var idx;
var ldx;

var LMS = {
  page: 1,
  pageSize: 9,
  search: "",
  statusFilter: "", // "", "completed", "progress"
  rowsAll: [],
  isLoading: false
};

$(document).ready(function () {
  initProgressCardView();
});

function initProgressCardView() {
  // search debounce
  var t;
  $("#lmsSearch").on("keyup", function () {
    clearTimeout(t);
    t = setTimeout(function () {
      LMS.search = ($("#lmsSearch").val() || "").trim().toLowerCase();
      LMS.page = 1;
      renderGrid();
    }, 250);
  });

  // filter click
  $(document).on("click", ".lms-filters .btn", function (e) {
    e.preventDefault();
    $(".lms-filters .btn").removeClass("active");
    $(this).addClass("active");

    LMS.statusFilter = $(this).data("status") || "";
    LMS.page = 1;
    renderGrid();
  });

  // check all
  $("#check-all").on("click", function () {
    $(".data-check").prop("checked", $(this).prop("checked"));
  });

  // first load
  fetchProgressData();
}

function fetchProgressData() {
  if (LMS.isLoading) return;
  LMS.isLoading = true;

  // panggil endpoint DataTables (server-side) tapi kita ambil aaData
  $.ajax({
    url: module_path + "/get_data",
    type: "GET",
    dataType: "json",
    data: {
      sEcho: 1,
      iDisplayStart: 0,
      iDisplayLength: 1000, // ambil banyak (kalau datanya besar, nanti kita buat endpoint custom)
      sSearch: ""
    },
    beforeSend: function () {
      showLoading();
    },
    success: function (res) {
      var rows = (res && res.aaData) ? res.aaData : [];
      LMS.rowsAll = rows;

      updateStats(rows);
      renderGrid();
    },
    error: function (jqXHR) {
      console.log("fetchProgressData error:", jqXHR.responseText);
      Swal.fire("Error", "Gagal mengambil data progress.", "error");
    },
    complete: function () {
      LMS.isLoading = false;
      hideLoading();
    }
  });
}

function updateStats(rows) {
  var total = rows.length;
  var completed = 0;
  var inprogress = 0;

  rows.forEach(function (r) {
    // mapping dari model: [0 checkbox, 1 actions, 2 id, 3 course_name, 4 full_name, 5 progress, 6 completed_at, 7 file_sertifikat]
    var prog = parseFloat(r[5] || 0);
    if (prog >= 100) completed++;
    else inprogress++;
  });

  $("#stat_total").text(total);
  $("#stat_completed").text(completed);
  $("#stat_inprogress").text(inprogress);
}

function applyFilters(rows) {
  var filtered = rows.slice();

  // filter status
  if (LMS.statusFilter) {
    filtered = filtered.filter(function (r) {
      var prog = parseFloat(r[5] || 0);
      if (LMS.statusFilter === "completed") return prog >= 100;
      if (LMS.statusFilter === "progress") return prog < 100;
      return true;
    });
  }

  // search (course + employee)
  if (LMS.search) {
    filtered = filtered.filter(function (r) {
      var course = String(r[3] || "").toLowerCase();
      var emp = String(r[4] || "").toLowerCase();
      return (course.indexOf(LMS.search) !== -1) || (emp.indexOf(LMS.search) !== -1);
    });
  }

  return filtered;
}

function renderGrid() {
  var $grid = $("#lmsGrid");
  $grid.empty();

  var rows = applyFilters(LMS.rowsAll);

  // empty state
  if (rows.length === 0) {
    $grid.append(
      '<div class="col-md-12"><div class="alert alert-info" style="border-radius:12px;">' +
      '<b>No data found.</b> Coba ubah keyword search / filter.' +
      '</div></div>'
    );
    if ($("#lmsPagination").length) $("#lmsPagination").empty();
    return;
  }

  // pagination
  var totalPages = Math.ceil(rows.length / LMS.pageSize);
  if (LMS.page > totalPages) LMS.page = totalPages;

  var start = (LMS.page - 1) * LMS.pageSize;
  var end = start + LMS.pageSize;
  var pageRows = rows.slice(start, end);

  // render cards
  pageRows.forEach(function (r) {
    var id = r[2];
    var course = r[3];
    var emp = r[4];
    var prog = r[5];
    var completedAt = r[6] || "-";
    var sertifikat = r[7];

    var badge = (parseFloat(prog || 0) >= 100)
      ? '<span class="badge" style="background:#dcfce7;color:#16a34a;">Completed</span>'
      : '<span class="badge" style="background:#fff7ed;color:#f97316;">In Progress</span>';

    var sertiHtml = "-";
    if (sertifikat && String(sertifikat).trim() !== "") {
      sertiHtml = '<a href="' + sertifikat + '" target="_blank">Download</a>';
    }

    var checkboxHtml = "";
    <?php if (_USER_ACCESS_LEVEL_DELETE == "1") { ?>
      checkboxHtml = '<label class="select-box"><input type="checkbox" class="data-check" name="ids[]" value="' + id + '"> Select</label>';
    <?php } ?>

    var actionsHtml = '';
    <?php if (_USER_ACCESS_LEVEL_DETAIL == "1") { ?>
      actionsHtml += '<a class="btn btn-xs btn-success" style="background-color:#112D80;border-color:#112D80;" href="javascript:void(0);" onclick="detail(\'' + id + '\')"><i class="fa fa-search-plus"></i></a> ';
    <?php } ?>
    <?php if (_USER_ACCESS_LEVEL_UPDATE == "1") { ?>
      actionsHtml += '<a class="btn btn-xs btn-primary" style="background-color:#FFA500;border-color:#FFA500;" href="javascript:void(0);" onclick="edit(\'' + id + '\')"><i class="fa fa-pencil"></i></a> ';
    <?php } ?>
    <?php if (_USER_ACCESS_LEVEL_DELETE == "1") { ?>
      actionsHtml += '<a class="btn btn-xs btn-danger" style="background-color:#A01818;border-color:#A01818;" href="javascript:void(0);" onclick="deleting(\'' + id + '\')"><i class="fa fa-trash"></i></a>';
    <?php } ?>

    var card =
      '<div class="col-md-4 col-sm-6" style="margin-bottom:16px;">' +
        '<div class="lms-card">' +
          '<div class="cover">' +
            badge +
          '</div>' +
          '<div class="body">' +
            '<div class="title">' + escapeHtml(course) + '</div>' +
            '<div class="meta">' +
              '<div class="row-meta"><i class="fa fa-user"></i><div><b>Employee</b><br/>' + escapeHtml(emp) + '</div></div>' +
              '<div class="row-meta"><i class="fa fa-percent"></i><div><b>Progress</b><br/>' + escapeHtml(String(prog)) + '%</div></div>' +
              '<div class="row-meta"><i class="fa fa-calendar"></i><div><b>Completed At</b><br/>' + escapeHtml(String(completedAt)) + '</div></div>' +
              '<div class="row-meta"><i class="fa fa-certificate"></i><div><b>Sertifikat</b><br/>' + sertiHtml + '</div></div>' +
            '</div>' +
          '</div>' +
          '<div class="footer">' +
            '<div class="actions">' + actionsHtml + '</div>' +
            checkboxHtml +
          '</div>' +
        '</div>' +
      '</div>';

    $grid.append(card);
  });

  renderPagination(totalPages);
}

function renderPagination(totalPages) {
  var $p = $("#lmsPagination");
  if (!$p.length) return;
  $p.empty();

  var prevDisabled = (LMS.page <= 1) ? "disabled" : "";
  var nextDisabled = (LMS.page >= totalPages) ? "disabled" : "";

  $p.append('<li class="' + prevDisabled + '"><a href="#" data-page="' + (LMS.page - 1) + '">&laquo;</a></li>');

  for (var i = 1; i <= totalPages; i++) {
    var active = (i === LMS.page) ? "active" : "";
    $p.append('<li class="' + active + '"><a href="#" data-page="' + i + '">' + i + '</a></li>');
  }

  $p.append('<li class="' + nextDisabled + '"><a href="#" data-page="' + (LMS.page + 1) + '">&raquo;</a></li>');

  // click
  $(document).off("click", "#lmsPagination a");
  $(document).on("click", "#lmsPagination a", function (e) {
    e.preventDefault();
    var p = parseInt($(this).data("page"), 10);
    if (isNaN(p) || p < 1 || p > totalPages) return;
    LMS.page = p;
    renderGrid();
  });
}

function escapeHtml(s) {
  return String(s || "").replace(/[&<>"']/g, function (m) {
    return ({ "&":"&amp;", "<":"&lt;", ">":"&gt;", '"':"&quot;", "'":"&#039;" })[m];
  });
}

function showLoading() {
  if ($("#loadingOverlay").length) $("#loadingOverlay").show();
}
function hideLoading() {
  if ($("#loadingOverlay").length) $("#loadingOverlay").hide();
}
</script>
