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
  myTable = {
    ajax: {
      reload: function () {
        fetchProgressData();
      }
    }
  };
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

  // action buttons
  $(document).on("click", "#btnImportData", function (e) {
    e.preventDefault();
    save_method = "import";
    if ($("#frmImportData").length) {
      $("#frmImportData")[0].reset();
    }
    $(".progress-bar").width("0%");
    $(".progress-bar").html("0%");
    $("#modal-import-data").modal("show");
  });

  $(document).on("click", "#btnEksportData", function (e) {
    e.preventDefault();
    save_method = "export";
    $("#modal-eksport-data").modal("show");
  });

  $(document).on("click", "#btnBulkData", function (e) {
    e.preventDefault();
    save_method = "bulk";
    ldx = [];
    $(".data-check:checked").each(function () {
      ldx.push(this.value);
    });
    if (ldx.length < 1) {
      Swal.fire("Info", "Pilih minimal 1 data untuk dihapus.", "info");
      return;
    }
    $("span#ids").html(ldx.join(", "));
    $("#modal-delete-bulk-data").modal("show");
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

function edit(id) {
  save_method = "update";
  idx = id;
  load_data();
}

function detail(id) {
  save_method = "detail";
  idx = id;
  load_data();
}

function deleting(id) {
  save_method = "delete";
  idx = id;
  $("span#ids").html(id);
  $("#frmDeleteData [name=\"id\"]").val(id);
  $("#modal-delete-data").modal("show");
}

function load_data() {
  $.ajax({
    type: "POST",
    url: module_path + "/get_detail_data",
    data: { id: idx },
    cache: false,
    dataType: "JSON",
    success: function (data) {
      if (!data) {
        Swal.fire("Error", "Gagal peroleh data.", "error");
        return;
      }

      if (save_method === "update") {
        $("[name=\"id\"]").val(data.id || "");
        $("[name=\"course_name\"]").val(data.course_name || "");
        $("[name=\"employee\"]").val(data.full_name || "");
        $("[name=\"progress\"]").val(data.progress_percentage || "");
        $("#mfdata").text("Update");
        $("#modal-form-data").modal("show");
      } else if (save_method === "detail") {
        $("span.course_name").html(data.course_name || "-");
        $("span.employee").html(data.full_name || "-");
        $("span.progress").html((data.progress_percentage || "0") + "%");
        $("span.completed_at").html(data.completed_at || "-");
        $("#modal-view-data").modal("show");
      }
    },
    error: function (jqXHR) {
      Swal.fire("Error", jqXHR.responseText || "Terjadi kesalahan server", "error");
    }
  });
}

function save() {
  var sendUrl = "";
  var formData = null;

  if (save_method === "update") {
    sendUrl = module_path + "/edit";
    formData = new FormData($("#frmInputData")[0]);
  } else if (save_method === "delete") {
    sendUrl = module_path + "/delete";
    formData = new FormData($("#frmDeleteData")[0]);
  } else if (save_method === "bulk") {
    sendUrl = module_path + "/bulk";
    formData = new FormData($("#frmListData")[0]);
  } else if (save_method === "import") {
    sendUrl = module_path + "/import";
    formData = new FormData($("#frmImportData")[0]);
  } else if (save_method === "export") {
    sendUrl = module_path + "/eksport";
    formData = $("#frmEksportData").serialize();
    window.location = sendUrl + "?" + formData;
    $("#modal-eksport-data").modal("hide");
    return;
  } else {
    return;
  }

  $.ajax({
    xhr: function () {
      var xhr = new window.XMLHttpRequest();
      if (save_method === "import") {
        xhr.upload.addEventListener("progress", function (evt) {
          if (evt.lengthComputable) {
            var percentComplete = ((evt.loaded / evt.total) * 100);
            $(".progress-bar").width(percentComplete + "%");
            $(".progress-bar").html(percentComplete.toFixed(0) + "%");
          }
        }, false);
      }
      return xhr;
    },
    type: "POST",
    url: sendUrl,
    data: formData,
    contentType: false,
    processData: false,
    cache: false,
    dataType: "JSON",
    beforeSend: function () {
      showLoading();
      if (save_method === "import") {
        $(".progress-bar").width("0%");
        $(".progress-bar").html("0%");
      }
    },
    success: function (response) {
      if (response && response.status) {
        if (save_method === "update") $("#modal-form-data").modal("hide");
        if (save_method === "delete") $("#modal-delete-data").modal("hide");
        if (save_method === "bulk") $("#modal-delete-bulk-data").modal("hide");
        if (save_method === "import") $("#modal-import-data").modal("hide");
        Swal.fire("Success", response.msg || "Berhasil", "success");
        fetchProgressData();
      } else {
        Swal.fire("Error", (response && response.msg) ? response.msg : "Gagal memproses data", "error");
      }
    },
    error: function (jqXHR) {
      Swal.fire("Error", jqXHR.responseText || "Terjadi kesalahan server", "error");
    },
    complete: function () {
      hideLoading();
    }
  });
}
</script>
