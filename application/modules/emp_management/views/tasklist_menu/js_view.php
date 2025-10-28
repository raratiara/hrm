
<!-- <link rel="stylesheet" href="https://unpkg.com/frappe-gantt/dist/frappe-gantt.css"> -->
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<!-- <script src="https://unpkg.com/frappe-gantt/dist/frappe-gantt.min.js"></script> -->


<!-- Frappe Gantt CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/frappe-gantt@0.6.1/dist/frappe-gantt.css">

<!-- Frappe Gantt JS -->
<script src="https://cdn.jsdelivr.net/npm/frappe-gantt@0.6.1/dist/frappe-gantt.min.js"></script>





<style>
#gantt {
  background: #fff;
  border: 1px solid #ddd;
  border-radius: 5px;
  overflow-x: auto;
  zoom: 0.9; /* sedikit perkecil tampilan */
 /* max-height: 200px;*/ /* biar gak terlalu panjang */


  /*overflow-y: clip;*/   /* 🔹 HILANGKAN space kosong vertikal tapi tetap render penuh */
  /*overflow-y: hidden;*/ /* 🔹 hilangkan scrollbar & space vertikal */
  max-height: 500px; /* 🔹 atur tinggi sesuai kebutuhan */
  margin-bottom: 0; /* 🔹 hilangkan jarak bawah */
}

.gantt .grid-background {
  height: auto !important; /* 🔹 biar tinggi grid ngikut isi, bukan fix */
}

.gantt .bar-progress {
  fill: #007bff;
}


.gantt .bar-label {
  overflow: visible !important;
  white-space: nowrap !important;
  text-overflow: unset !important;
  font-size: 12px !important;
  fill: #fff !important; /* warna teks putih agar kontras */
  text-anchor: middle !important;

   transform: translateX(-10px);
}

.gantt .bar {
  min-width: 60px; /* biar bar ga terlalu kecil */
}

table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}
th, td {
  border: 1px solid #ddd;
  padding: 8px;
  text-align: center;
}
th {
  background: #f0f0f0;
}
td[contenteditable="true"] {
  background: #e9f9e9;
}
button {
  margin: 5px;
  padding: 5px 10px;
  cursor: pointer;
}

.details-container {
  background: #f9f9f9;
  border-radius: 5px;
}


.modal-content {
    max-height: 80vh;
    overflow-y: auto;
    width: 100% !important;
}

.modal-dialog {
    width: 90% !important;
    margin-left: auto !important;
}

.vertical-alignment-helper {
    display: flex;
    align-items: center;
    justify-content: center;
}

.vertical-align-center {
    width: 100%;
}


#taskTable thead th {
  font-size: 10px !important;
}


#taskTable tbody tr {
  height: 28px !important;        /* tinggi baris */
}

#taskTable tbody td {
  padding: 3px 6px !important;    /* jarak dalam sel */
  line-height: 1.2 !important;    /* rapatkan teks */
  vertical-align: middle !important; /* biar teks tetap di tengah vertikal */
}

#taskTable select,
#taskTable input,
#taskTable textarea {
  height: 24px !important;        /* kecilkan tinggi input/select */
  font-size: 10px !important;     /* kecilkan teks agar proporsional */
  padding: 0 4px !important;
}



.table-footer {
  display: flex !important;
  justify-content: space-between !important;
  align-items: center !important;
  margin-top: 10px !important;
}

.table-footer .dataTables_paginate {
  margin: 0 !important;
}

.table-footer .paginate_button {
  border: 1px solid #ddd !important;
  padding: 3px 8px !important;
  border-radius: 5px !important;
  background: #fff !important;
  margin-left: 3px !important;
  cursor: pointer !important;
}

.table-footer .paginate_button.current {
  background: #007bff !important;
  color: white !important;
}


#addRowBtn {
  font-size: 10px !important;        /* kecilkan teks */
  padding: 2px 8px !important;       /* kecilkan tinggi & lebar tombol */
  line-height: 1.2 !important;
  height: 26px !important;           /* biar seimbang dengan row tabel */
  border-radius: 6px !important;     /* agak lembut sudutnya */
}


#taskTable th:last-child,
#taskTable td:last-child {
  width: 50px !important;
  min-width: 50px !important;
  max-width: 50px !important;
  text-align: center !important;
  padding: 2px !important;
}
#taskTable .btn-danger {
  padding: 2px 4px !important;
  font-size: 11px !important;
  width: 24px !important;
  height: 24px !important;
  line-height: 1 !important;
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
  border-radius: 50% !important; /* opsional kalau mau bulat */
  margin-left: 10px;
}


/* Pagination container */
#paginationContainer {
  display: flex;
  align-items: center;
  justify-content: flex-end;
}



#paginationContainer .paginate_button {
  padding: 2px 6px !important;
  margin: 0 2px !important;
  min-width: 26px !important;
  line-height: 1 !important;
  height: 30px !important;
  border: none !important;
  background: transparent !important;
  color: #007bff !important;
  font-size: 11px !important;
  border-radius: 4px !important;
}

/* Saat tombol aktif */
#paginationContainer .paginate_button.current {
  background: #007bff !important;
  color: #fff !important;
  border-color: #007bff !important;
}

/* Hover effect */
/*#paginationContainer .paginate_button:hover {
  background: #007bff !important;
  color: #fff !important;
}*/


.bar-label-with-avatar {
  display: flex;
  align-items: center;
  gap: 6px;
}

.avatar-circle {
  width: 18px;
  height: 18px;
  border-radius: 50%;
  background-color: #6c63ff;
  color: white;
  font-size: 9px;
  font-weight: bold;
  display: flex;
  justify-content: center;
  align-items: center;
  flex-shrink: 0;
}

#gantt svg {
  height: auto !important;       /* biar tinggi SVG ngikut isi bar */
  min-height: 100px !important;  /* kasih batas minimal biar nggak 0 */
}
.gantt .grid-background {
  height: auto !important;       /* biar grid background juga pas */
}





</style>






<script type="text/javascript">
var module_path = "<?php echo base_url($folder_name);?>"; //for save method string
var myTable;
var validator;
var save_method; //for save method string
var idx; //for save index string
var ldx; //for save list index string


let currentViewMode = 'Week'; // default

$(document).ready(function() {
   	$(function() {
   			load_gantt();

   			// $('#taskTable').DataTable({
			  //   pageLength: 5,        // tampilkan 5 baris per halaman
			  //   lengthChange: false,  // sembunyikan dropdown "show entries"
			  //   searching: false,     // nonaktifkan pencarian
			  //   info: false,          // sembunyikan info "showing 1 to 5 of ..."
			  //   ordering: true,       // aktifkan sorting
			  //   scrollX: true,        // scroll horizontal kalau kolom banyak
			  //   paging: true          // pastikan pagination aktif
			  // });


        //$( ".due_date" ).datepicker();
		
   	});
});


<?php if  (_USER_ACCESS_LEVEL_VIEW == "1") { ?>
jQuery(function($) {
	/* load table list */
	myTable =
	$('#dynamic-table')
	.DataTable( {
		fixedHeader: {
			headerOffset: $('.page-header').outerHeight()
		},
		responsive: true,
		bAutoWidth: false,
		"aoColumnDefs": [
		  { "bSortable": false, "aTargets": [ 0,1 ] },
		  { "sClass": "text-center", "aTargets": [ 0,1 ] }
		],
		"aaSorting": [
		  	[2,'asc'] 
		],
		"sAjaxSource": module_path+"/get_data",
		"bProcessing": true,
        "bServerSide": true,
		"pagingType": "bootstrap_full_number",
		"colReorder": true
    } );

	<?php if  (_USER_ACCESS_LEVEL_ADD == "1" || _USER_ACCESS_LEVEL_UPDATE == "1") { ?>
	validator = $("#frmInputData").validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block help-block-error', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		ignore: "", // validate all fields including form hidden input
		rules: {
			title: {
				required: true
			},
			module_name: {
				required: true
			},
			url: {
				required: true
			}
		},
		messages: { // custom messages for radio buttons and checkboxes
		},
		errorPlacement: function (error, element) { // render error placement for each input type
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
				error.insertAfter(element); // for other inputs, just perform default behavior
			}
		},
		highlight: function (element) { // hightlight error inputs
			$(element)
				.closest('.form-group').addClass('has-error'); // set error class to the control group
		},
		unhighlight: function (element) { // revert the change done by hightlight
			$(element)
				.closest('.form-group').removeClass('has-error'); // set error class to the control group
		},
		success: function (label) {
			label
				.closest('.form-group').removeClass('has-error'); // set success class to the control group
		}
	});
	<?php } ?>

	<?php if  (_USER_ACCESS_LEVEL_DELETE == "1") { ?>
    //check all
    $("#check-all").click(function () {
        $(".data-check").prop('checked', $(this).prop('checked'));
    });
	<?php } ?>
})

<?php $this->load->view(_TEMPLATE_PATH . "common_module_js"); ?>
<?php } ?>

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


					

					// $('[name="id"]').val(data.id);
					
					// $('select#employee').val(data.employee_id).trigger('change.select2');
					// $('select#status').val(data.status_id).trigger('change.select2');
					// $('[name="task"]').val(data.task);
					// $('select#task_parent').val(data.parent_id).trigger('change.select2');
					// $('[name="progress"]').val(data.progress_percentage);
					// //$('[name="due_date"]').val(data.due_date);
					// //$('[name="solve_date"]').val(data.solve_date);
					// $('select#project').val(data.project_id).trigger('change.select2');
					// $('[name="description"]').val(data.description);

					// var due_date = dateFormat(data.due_date);
					// $('[name="due_date"]').datepicker('setDate', due_date);
					// var solve_date = dateFormat(data.solve_date);
					// $('[name="solve_date"]').datepicker('setDate', solve_date);


					

					
					// $.uniform.update();
					// $('#mfdata').text('Update');
					// $('#modal-form-data').modal('show');
				}
				if(save_method == 'detail'){ 
					// $('span.employee').html(data.employee_name);
					// $('span.task').html(data.task);
					// $('span.progress').html(data.progress_percentage);
					// $('span.status').html(data.status_name);
					// $('span.task_parent').html(data.parent_name);

					// var due_date = "-";
					// if(data.due_date != '0000-00-00'){
					// 	due_date = data.due_date;
					// }
					// var solve_date = "-";
					// if(data.solve_date != '0000-00-00'){
					// 	solve_date = data.solve_date;
					// }

					// $('span.due_date').html(due_date);
					// $('span.solve_date').html(solve_date);
					// $('span.project').html(data.project_name);
					// $('span.description').html(data.description);
					
					
					
					// $('#modal-view-data').modal('show');
				}
			} else {
				title = '<div class="text-center" style="padding-top:20px;padding-bottom:10px;"><i class="fa fa-exclamation-circle fa-5x" style="color:red"></i></div>';
				btn = '<br/><button class="btn blue" data-dismiss="modal">OK</button>';
				msg = '<p>Gagal peroleh data.</p>';
				var dialog = bootbox.dialog({
					message: title+'<center>'+msg+btn+'</center>'
				});
				if(response.status){
					setTimeout(function(){
						dialog.modal('hide');
					}, 1500);
				}
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


function dateFormat(tanggal) {
    if (!tanggal || tanggal === "0000-00-00") {
        return ""; // kosongkan saja, jangan ditampilkan
    }

    let parts = tanggal.split("-");
    if (parts.length !== 3) {
        return tanggal; // fallback kalau format aneh
    }

    return `${parts[1]}/${parts[2]}/${parts[0]}`;
}


//Event untuk ganti view mode
$(document).on('change', '#viewModeSelect', function () {
  currentViewMode = $(this).val();
  load_gantt(); 
});


function load_gantt(employee_id = '',project_id = '') {
  $.ajax({
    url: module_path + '/get_tasklist_gantt',
    type: "POST",
    dataType: "JSON",
    data: { employee_id: employee_id, project_id: project_id },
    cache: false,
    success: function (data) {
      console.log(data);

      if (data && data.length > 0) {
        // Mapping hasil dari controller menjadi format Gantt
        const tasks = data.map(item => ({
          id: item.id,
          name: item.task,
          /*start: item.progress_date && item.progress_date !== '0000-00-00' ? item.progress_date : item.due_date,*/
          start: item.progress_date && item.progress_date !== '0000-00-00' ? item.progress_date : item.date_create,
          end: item.solve_date && item.solve_date !== '0000-00-00' ? item.solve_date : item.due_date,
          progress: item.progress_percentage || 0,
          dependencies: item.parent_id && item.parent_id != 0 ? item.parent_id.toString() : '',
          status: item.status_name,
          status_id: item.status_id,
          project: item.project_name,
          project_id: item.project_id,
          employee: item.employee_name,
          employee_id: item.employee_id,
          parent: item.parent_name,
          parent_id: item.parent_id,
          description: item.description || '-',
          due_date: item.due_date,
          solve_date: item.solve_date,
          progress_date: item.progress_date,
          request_date: item.request_date
        }));


        // Inisialisasi ulang DataTables setelah semua value selesai di-set
        if ($.fn.DataTable.isDataTable('#taskTable')) {
          $('#taskTable').DataTable().clear().destroy();
        }

        // Bersihkan sebelum render ulang
        $("#gantt").empty();
        $("#taskTable tbody").empty();

        // Render ulang Gantt
        renderGantt(tasks, currentViewMode);

        // Tambah baris ke tabel
        tasks.forEach(task => {
          $("#taskTable tbody").append(`
            <tr>
              <td><span style="font-size:10px">${task.id}</span></td>
              <td contenteditable="true"><?=$selemployee;?></td>
              <td contenteditable="true"><?=$txttask;?></td>
              <td contenteditable="true"><?= $selstatus; ?></td>
              <td contenteditable="true"><?=$txtprogress;?></td>
              <td contenteditable="true"><?=$txtduedate;?></td>
              <td contenteditable="true"><?=$selproject;?></td>
              <td contenteditable="true"><?=$seltaskparent;?></td>
              <td contenteditable="true"><?=$txtdesc;?></td>
              <td>
                <button class="btn btn-sm btn-danger btn-trash" onclick="deleteRow(this)">
                  <i class="fa fa-trash"></i>
                </button>
              </td>
            </tr>
          `);
        });

        // Setelah append semua baris, isi value select/input sesuai data
        $('#taskTable tbody tr').each(function (i) {
          $(this).find('select#status').val(tasks[i].status_id).trigger('change.select2');
          $(this).find('#progress').val(tasks[i].progress);
          $(this).find('#task').val(tasks[i].name);
          $(this).find('select#employee').val(tasks[i].employee_id).trigger('change.select2');
          $(this).find('#description').val(tasks[i].description);
          $(this).find('select#task_parent').val(tasks[i].parent_id).trigger('change.select2');
          $(this).find('select#project').val(tasks[i].project_id).trigger('change.select2');
          $(this).find('#due_date').val(tasks[i].due_date);
        });

        /// Inisialisasi ulang Select2 agar dropdown muncul di luar area Gantt
        /*$('select').select2({
				  dropdownParent: $('body')
				});*/


        

        $('#taskTable').DataTable({
          pageLength: 5,
          lengthChange: false,
          searching: false,
          ordering: false,
          info: false,
          pagingType: "simple",
          language: {
            paginate: { previous: "&lt;", next: "&gt;" }
          }
        });

        // Pindahkan pagination ke bawah tombol
        setTimeout(() => {
          const pagination = $('#taskTable_wrapper .dataTables_paginate');
          $('#paginationContainer').empty().append(pagination);
        }, 100);

        // Tambahkan tombol Add Row + pagination container (sekali saja)
        if (!$("#addRowBtn").length) {
          $("#taskTable").after(`
            <div class="table-footer d-flex justify-content-between align-items-center mt-2 mb-2">
              <button id="addRowBtn" type="button" class="btn btn-sm btn-primary" onclick="addRow()">
                <i class="fa fa-plus"></i> add Task
              </button>
              <div id="paginationContainer"></div> 
            </div>
          `);
        }

        let typingTimer;
		    document.querySelector("#taskTable tbody").addEventListener("input", function () {
		      clearTimeout(typingTimer);
		      typingTimer = setTimeout(() => dataChanges(), 1000);
		    });

		    let typingTimer2;
		    document.querySelector("#taskTable tbody").addEventListener("change", function () {
		      clearTimeout(typingTimer2);
		      typingTimer2 = setTimeout(() => dataChanges(), 1000);
		    });

        // Auto update Gantt jika tabel berubah
        // document.querySelector("#taskTable tbody").addEventListener("input", function () {
        //   //updateGanttFromTable();
        //   dataChanges();
        // });

      } else {
        /*$("#gantt").html("<p class='text-center'>Tidak ada data tasklist.</p>");*/

        // Kosongkan semua area kalau data kosong
        $("#gantt").html("<p class='text-center text-muted mt-3'>Tidak ada data tasklist.</p>");
        $("#taskTable tbody").empty();

        // Hapus DataTable kalau ada
        if ($.fn.DataTable.isDataTable('#taskTable')) {
          $('#taskTable').DataTable().clear().destroy();
        }

        // Hapus tombol Add Row dan pagination
        $(".table-footer").remove();

      }
    },
    error: function (xhr, status, error) {
      console.error(error);
      $("#gantt").html("<p class='text-center text-danger'>Gagal mengambil data.</p>");
    }
  });
}


function renderGantt_hm(tasks, viewMode='Week') {
  $("#gantt").empty();

  const gantt = new Gantt("#gantt", tasks, {
    view_mode: viewMode,
    date_format: 'YYYY-MM-DD',
    bar_height: 22,
    padding: 20,
    language: 'en',

    // Event: update jika tanggal bar diubah
    on_date_change: function (task, start, end) {
      console.log(`Tanggal task ${task.id} diubah: ${start} - ${end}`);
      updateTaskDates(task, start, end);
    },

    // Event: update jika progress bar diubah
    on_progress_change: function (task, progress) {
      console.log(`Progress task ${task.id} diubah jadi ${progress}%`);
      updateTaskProgress(task, progress);
    },

    custom_popup_html: function (task) {
      let project = task.project || '-';
      let parent = task.parent || '-';
      let description = task.description || '-';
      let request_date = (task.request_date && task.request_date !== '0000-00-00') ? task.request_date : '-';
      let progress_date = (task.progress_date && task.progress_date !== '0000-00-00') ? task.progress_date : '-';
      let solve_date = (task.solve_date && task.solve_date !== '0000-00-00') ? task.solve_date : '-';
      let due_date = (task.due_date && task.due_date !== '0000-00-00') ? task.due_date : '-';

      return `
        <div class="p-2">
          <h6>${task.name}</h6>
          <p><b>Employee:</b> ${task.employee}</p>
          <p><b>Status:</b> ${task.status}</p>
          <p><b>Progress:</b> ${task.progress}%</p>
          <p><b>Request Date:</b> ${request_date}</p>
          <p><b>Progress Date:</b> ${progress_date}</p>
          <p><b>Close Date:</b> ${solve_date}</p>
          <p><b>Due Date:</b> ${due_date}</p>
          <p><b>Project:</b> ${project}</p>
          <p><b>Task Parent:</b> ${parent}</p>
          <p><b>Description:</b> ${description}</p>
        </div>
      `;
    }
  });


  document.querySelectorAll(".bar-wrapper").forEach(bar => {
  bar.style.pointerEvents = "auto";
  bar.addEventListener("mousedown", e => {
    e.stopPropagation(); // biar klik langsung ke bar
  });
});
  
  const observer = new MutationObserver(() => {
  const popups = document.querySelectorAll(".popup-wrapper");

  popups.forEach(popup => {
    // pindahkan popup ke body biar posisinya bebas
    if (!popup.classList.contains("fixed-popup")) {
      popup.classList.add("fixed-popup");
      document.body.appendChild(popup);
    }

    // reset semua style bawaan
    //popup.removeAttribute("style");
    popup.style.pointerEvents = "none"; // biar gak ganggu drag


    // styling popup
    popup.style.width = "260px";
    popup.style.position = "absolute";
    popup.style.zIndex = "9999";
    popup.style.borderRadius = "10px";
    popup.style.overflowY = "auto";
    popup.style.maxHeight = "420px";
    popup.style.background = "white";
    popup.style.boxShadow = "0 4px 20px rgba(0,0,0,0.2)";
    popup.style.border = "1px solid #ddd";
    popup.style.padding = "10px";
    popup.style.transform = "none";
    popup.style.transition = "none";

    // biarkan popup muncul dulu baru atur posisi
    setTimeout(() => {
      const activeBar = document.querySelector(".bar-wrapper.active");
      if (activeBar) {
        const rect = activeBar.getBoundingClientRect();
        const scrollY = window.scrollY || document.documentElement.scrollTop;
        const scrollX = window.scrollX || document.documentElement.scrollLeft;

        // posisi popup pas di atas bar
        const top = rect.top + scrollY - popup.offsetHeight - 8;
        const left = rect.left + scrollX + rect.width / 2 - popup.offsetWidth / 2;

        // biar gak keluar dari layar
        const safeLeft = Math.max(10, left);
        const safeTop = Math.max(10, top);

        popup.style.left = `${safeLeft}px`;
        popup.style.top = `${safeTop}px`;
      }
    }, 50); // tunggu 50ms setelah popup muncul
  });
});

// pantau perubahan di dalam #gantt
observer.observe(document.querySelector("#gantt"), {
  childList: true,
  subtree: true
});





  // overlay group (di atas semua bar)
  const svg = document.querySelector("#gantt svg");
  let overlayGroup = svg.querySelector(".employee-overlay");
  if (!overlayGroup) {
    overlayGroup = document.createElementNS("http://www.w3.org/2000/svg", "g");
    overlayGroup.classList.add("employee-overlay");
    svg.appendChild(overlayGroup);
  }

  // fungsi tunggu sampai bar siap
  function waitForBarsReady() {
    if (!gantt.bars || gantt.bars.length === 0) {
      requestAnimationFrame(waitForBarsReady);
      return;
    }

    const allReady = gantt.bars.every(b => b && b.$bar);
    if (!allReady) {
      requestAnimationFrame(waitForBarsReady);
      return;
    }

    overlayGroup.innerHTML = ""; // bersihkan dulu

    gantt.bars.forEach(bar => {
      if (!bar || !bar.$bar) return;

      const task = bar.task;
      const initials = (task.employee || "?")
        .split(" ")
        .map(w => w[0])
        .join("")
        .toUpperCase()
        .slice(0, 2);

      const color = colorFromName(task.employee || '');
      const barX = parseFloat(bar.$bar.getAttribute("x"));
      const barY = parseFloat(bar.$bar.getAttribute("y"));
      const barHeight = parseFloat(bar.$bar.getAttribute("height"));

      const x = barX + 8;
      const y = barY + barHeight / 2;

      // circle
      const circle = document.createElementNS("http://www.w3.org/2000/svg", "circle");
      circle.setAttribute("cx", x);
      circle.setAttribute("cy", y);
      circle.setAttribute("r", 8);
      circle.setAttribute("fill", color);
      circle.setAttribute("stroke", "#fff");
      circle.setAttribute("stroke-width", 1);
      overlayGroup.appendChild(circle);

      // text inisial
      const text = document.createElementNS("http://www.w3.org/2000/svg", "text");
      text.setAttribute("x", x);
      text.setAttribute("y", y + 3);
      text.setAttribute("text-anchor", "middle");
      text.setAttribute("font-size", "8px");
      text.setAttribute("fill", "#fff");
      text.setAttribute("font-weight", "bold");
      text.textContent = initials;
      overlayGroup.appendChild(text);
    });
  }

  requestAnimationFrame(waitForBarsReady);

  // Hapus space kosong bawah Gantt
  setTimeout(() => {
    const svg = document.querySelector("#gantt svg");
    if (!svg) return;

    const bars = svg.querySelectorAll(".bar");
    if (bars.length > 0) {
      const lastBar = bars[bars.length - 1];
      const bottom = parseFloat(lastBar.getAttribute("y")) + parseFloat(lastBar.getAttribute("height"));
      svg.setAttribute("height", bottom + 40); // +40px buffer kecil saja
    }
  }, 500);
}



function renderGantt(tasks, viewMode='Week') {
  $("#gantt").empty();

  const gantt = new Gantt("#gantt", tasks, {
    view_mode: viewMode,
    date_format: 'YYYY-MM-DD',
    bar_height: 22,
    padding: 20,
    language: 'en',


    // ⬅️ Tambahan event: update jika tanggal bar diubah
    /*on_date_change: function (task, start, end) {
      console.log(`Tanggal task ${task.id} diubah: ${start} - ${end}`);
      updateTaskDates(task, start, end); // optional: panggil function AJAX update
    },*/

    // ⬅️ Tambahan event: update jika progress bar diubah
    /*on_progress_change: function (task, progress) {
      console.log(`Progress task ${task.id} diubah jadi ${progress}%`);
      updateTaskProgress(task, progress); // optional: panggil function AJAX update
    },*/


    // ⬅️ Event: update jika tanggal bar diubah
    on_date_change: function (task, start, end) {
      console.log(`Tanggal task ${task.id} diubah: ${start} - ${end}`);
      updateTaskDates(task, start, end);
    },

    // ⬅️ Event: update jika progress bar diubah
    on_progress_change: function (task, progress) {
      console.log(`Progress task ${task.id} diubah jadi ${progress}%`);
      updateTaskProgress(task, progress);
    },


    custom_popup_html: function (task) {
      let project = task.project || '-';
      let parent = task.parent || '-';
      let description = task.description || '-';
      let request_date = (task.request_date && task.request_date !== '0000-00-00') ? task.request_date : '-';
      let progress_date = (task.progress_date && task.progress_date !== '0000-00-00') ? task.progress_date : '-';
      let solve_date = (task.solve_date && task.solve_date !== '0000-00-00') ? task.solve_date : '-';
      let due_date = (task.due_date && task.due_date !== '0000-00-00') ? task.due_date : '-';

      return `
        <div class="p-2">
          <p style="font-size:14px; font-weight:bold; text-decoration: underline;">${task.name}</p>
          <p><b>Employee:</b> ${task.employee}</p>
          <p><b>Status:</b> ${task.status}</p>
          <p><b>Progress:</b> ${task.progress}%</p>
          <p><b>Request Date:</b> ${request_date}</p>
          <p><b>Progress Date:</b> ${progress_date}</p>
          <p><b>Close Date:</b> ${solve_date}</p>
          <p><b>Due Date:</b> ${due_date}</p>
          <p><b>Project:</b> ${project}</p>
          <p><b>Task Parent:</b> ${parent}</p>
          <p><b>Description:</b> ${description}</p>
        </div>
      `;
    }
  });

  // 🔧 Force lebar popup bawaan Frappe Gantt agar seragam
	const observer = new MutationObserver(() => {
	  const popups = document.querySelectorAll(".popup-wrapper");
	  popups.forEach(popup => {
	    popup.style.width = "200px";
	    popup.style.maxWidth = "200px";
	    popup.style.minWidth = "200px";

	   /* popup.style.width = "260px";*/
	    popup.style.position = "absolute";
	    popup.style.zIndex = "9999";
	    popup.style.borderRadius = "10px";
	    popup.style.overflowY = "auto";
	    popup.style.maxHeight = "220px";
	    popup.style.background = "white";
	    popup.style.boxShadow = "0 4px 20px rgba(0,0,0,0.2)";
	    popup.style.border = "1px solid #ddd";
	    popup.style.padding = "10px";
	    popup.style.transform = "none";


	    // cari bar aktif
	    const activeBar = document.querySelector(".bar-wrapper.active");
	    if (activeBar) {
	      const rect = activeBar.getBoundingClientRect();
	      const scrollY = window.scrollY || document.documentElement.scrollTop;
	      const scrollX = window.scrollX || document.documentElement.scrollLeft;

	      // posisi popup di tengah-tengah bar
	      const top = rect.top + scrollY + (rect.height / 2) - (popup.offsetHeight / 2 + 30);
	      const left = rect.left + scrollX + (rect.width / 2) - (popup.offsetWidth / 2 - 300);

	      popup.style.top = `${top}px`;
	      popup.style.left = `${left}px`;
	    }

	    // hilangkan posisi default bawaan gantt
	    popup.classList.remove("left", "right");


	  });
	});

	// Pantau perubahan DOM di dalam #gantt
	observer.observe(document.querySelector("#gantt"), {
	  childList: true,
	  subtree: true
	});


  // overlay group (di atas semua bar)
  const svg = document.querySelector("#gantt svg");
  let overlayGroup = svg.querySelector(".employee-overlay");
  if (!overlayGroup) {
    overlayGroup = document.createElementNS("http://www.w3.org/2000/svg", "g");
    overlayGroup.classList.add("employee-overlay");
    svg.appendChild(overlayGroup);
  }

  // fungsi tunggu sampai bar siap
  function waitForBarsReady() {
    if (!gantt.bars || gantt.bars.length === 0) {
      requestAnimationFrame(waitForBarsReady);
      return;
    }

    const allReady = gantt.bars.every(b => b && b.$bar);
    if (!allReady) {
      requestAnimationFrame(waitForBarsReady);
      return;
    }

    overlayGroup.innerHTML = ""; // bersihkan dulu

    gantt.bars.forEach(bar => {
      if (!bar || !bar.$bar) return;

      const task = bar.task;
      const initials = (task.employee || "?")
        .split(" ")
        .map(w => w[0])
        .join("")
        .toUpperCase()
        .slice(0, 2);

      const color = colorFromName(task.employee || '');
      const barX = parseFloat(bar.$bar.getAttribute("x"));
      const barY = parseFloat(bar.$bar.getAttribute("y"));
      const barHeight = parseFloat(bar.$bar.getAttribute("height"));

      const x = barX + 8;
      const y = barY + barHeight / 2;

      // circle
      const circle = document.createElementNS("http://www.w3.org/2000/svg", "circle");
      circle.setAttribute("cx", x);
      circle.setAttribute("cy", y);
      circle.setAttribute("r", 8);
      circle.setAttribute("fill", color);
      circle.setAttribute("stroke", "#fff");
      circle.setAttribute("stroke-width", 1);
      overlayGroup.appendChild(circle);

      // text inisial
      const text = document.createElementNS("http://www.w3.org/2000/svg", "text");
      text.setAttribute("x", x);
      text.setAttribute("y", y + 3);
      text.setAttribute("text-anchor", "middle");
      text.setAttribute("font-size", "8px");
      text.setAttribute("fill", "#fff");
      text.setAttribute("font-weight", "bold");
      text.textContent = initials;
      overlayGroup.appendChild(text);
    });
  }

  requestAnimationFrame(waitForBarsReady);

  // Hapus space kosong bawah Gantt
	setTimeout(() => {
	  const svg = document.querySelector("#gantt svg");
	  if (!svg) return;

	  const bars = svg.querySelectorAll(".bar");
	  if (bars.length > 0) {
	    const lastBar = bars[bars.length - 1];
	    const bottom = parseFloat(lastBar.getAttribute("y")) + parseFloat(lastBar.getAttribute("height"));
	    svg.setAttribute("height", bottom + 40); // +40px buffer kecil saja
	  }
	}, 500);



}

function colorFromName(name) {
  let hash = 0;
  for (let i = 0; i < name.length; i++) {
    hash = name.charCodeAt(i) + ((hash << 5) - hash);
  }
  const hue = Math.abs(hash) % 360;
  return `hsl(${hue}, 70%, 50%)`;
}


/* Tambah baris baru ke tabel */
function addRow() { 
  const newRow = $(`
    <tr class="new-row">
      <td></td>
      <td contenteditable="true" class="editable"><?=$selemployee;?></td>
      <td contenteditable="true" class="editable"><?=$txttask;?></td>
  		<td contenteditable="true" class="editable"><?= $selstatus; ?></td>
  		<td contenteditable="true" class="editable"><?=$txtprogress;?></td>
      <td contenteditable="true" class="editable"><?=$txtduedate;?></td>
  		<td contenteditable="true" class="editable"><?=$selproject;?></td>
      <td contenteditable="true" class="editable"><?=$seltaskparent;?></td>
      <td contenteditable="true" class="editable"><?=$txtdesc;?></td>
  	  <td><button class="btn btn-sm btn-danger" onclick="deleteRow(this)"><icon class="fa fa-trash"></icon></button></td>
    </tr>
  `);


  /*<td contenteditable="true" class="editable"><?=$txtrequestdate;?></td>
	<td contenteditable="true" class="editable"><?=$txtprogressdate;?></td>
	<td contenteditable="true" class="editable"><?=$txtsolvedate;?></td>*/

  $("#taskTable tbody").append(newRow);
}


/* Deteksi perubahan & auto-save */
// $(document).on("blur", "#taskTable tbody td[contenteditable='true']", function() { 
//   const row = $(this).closest("tr");
//   const cells = row.find("td");

//   const data = {
//     id: cells.eq(0).text().trim() || '',
//     employee: row.find("select#employee").val() || '',  // ambil dari select employee
//     task: row.find("#task").val() || cells.eq(2).text().trim(),
//     status: row.find("select#status").val() || '',
//     progress: row.find("#progress").val() || cells.eq(4).text().trim(),
//     /*request_date: row.find("#request_date").val() || '',
//     progress_date: row.find("#progress_date").val() || '',
//     solve_date: row.find("#solve_date").val() || '',*/
//     due_date: row.find("#due_date").val() || cells.eq(5).text().trim(),
//     project: row.find("select#project").val() || '',
//     task_parent: row.find("select#task_parent").val() || '',
//     description: row.find("#description").val() || cells.eq(8).text().trim()
//   };

//   // Hanya simpan kalau ada data minimal di kolom task
//   if (data.task !== '') { 
//     $.ajax({
//       url: module_path + '/save_task',  
//       type: 'POST',
//       dataType: 'JSON',
//       data: data,
//       success: function(res) {
//         if (res.success) {
//           // update ID dari DB biar ga dobel insert
//           cells.eq(0).text(res.id);
//           row.removeClass('new-row');
//           console.log('Data saved:', res);
//           showToast(row, "Saved successfully");

//           //load_gantt();
//           updateGanttFromTable();

//         } else {
//           console.error('Gagal simpan:', res.message);
//         }
//       },
//       error: function(err) {
//         console.error('Error AJAX:', err);
//       }
//     });


    
//   }
// });


// // Auto-save juga saat select option berubah
// $(document).on("change", "#taskTable tbody select", function() { 
//   const row = $(this).closest("tr");
//   const cells = row.find("td");

//   const data = {
//     id: cells.eq(0).text().trim() || '',
//     employee: row.find("select#employee").val() || '',  // ambil dari select employee
//     task: row.find("#task").val() || cells.eq(2).text().trim(),
//     status: row.find("select#status").val() || '',
//     progress: row.find("#progress").val() || cells.eq(4).text().trim(),
//     /*request_date: row.find("#request_date").val() || '',
//     progress_date: row.find("#progress_date").val() || '',
//     solve_date: row.find("#solve_date").val() || '',*/
//     due_date: row.find("#due_date").val() || cells.eq(5).text().trim(),
//     project: row.find("select#project").val() || '',
//     task_parent: row.find("select#task_parent").val() || '',
//     description: row.find("#description").val() || cells.eq(8).text().trim()
//   };

//   // Hanya simpan kalau task ada
//   if (data.task !== '') {
//     $.ajax({
//       url: module_path + '/save_task',
//       type: 'POST',
//       dataType: 'JSON',
//       data: data,
//       success: function(res) {
//         if (res.success) {
//           cells.eq(0).text(res.id);
//           row.removeClass('new-row');
//           console.log('Data saved via select change:', res);
//           showToast(row, "Saved successfully");

//           //load_gantt();
//           updateGanttFromTable();

//         } else {
//           console.error('Gagal simpan (select):', res.message);
//         }
//       },
//       error: function(err) {
//         console.error('Error AJAX (select):', err);
//       }
//     });

    
//   }
// });



/* Hapus baris */
// function deleteRow(btn) { 
//   btn.closest("tr").remove();
//   //updateGanttFromTable();
//   deleteData();
// }


function updateGanttFromTable() {
  // Kalau tidak ada parameter, ambil employee dari baris pertama tabel
  /*if (employee_id === '') {
    const firstRow = $("#taskTable tbody tr:first");
    employee_id = firstRow.find("select#employee").val() || '';
  }*/

  // Tampilkan indikator loading agar user tahu Gantt sedang diperbarui
  //$("#gantt").html('<p class="text-center text-muted">Updating Gantt...</p>');

  // Ambil data terbaru dari database dan render ulang
  $.ajax({
    url: module_path + '/get_tasklist_gantt',
    type: "POST",
    dataType: "JSON",
    data: { },
    cache: false,
    success: function (data) {
      if (data && data.length > 0) {
        const tasks = data.map(item => ({
          id: item.id,
          name: item.task,
          start: item.progress_date && item.progress_date !== '0000-00-00' ? item.progress_date : item.due_date,
          end: item.solve_date && item.solve_date !== '0000-00-00' ? item.solve_date : item.due_date,
          progress: item.progress_percentage || 0,
          dependencies: item.parent_id && item.parent_id != 0 ? item.parent_id.toString() : '',
          status: item.status_name,
          status_id: item.status_id,
          project: item.project_name,
          project_id: item.project_id,
          employee: item.employee_name,
          employee_id: item.employee_id,
          parent: item.parent_name,
          parent_id: item.parent_id,
          description: item.description || '-',
          due_date: item.due_date,
          solve_date: item.solve_date,
          progress_date: item.progress_date,
          request_date: item.request_date
        }));


        renderGantt(tasks);



      } else {
        $("#gantt").html("<p class='text-center text-muted'>Tidak ada data tasklist.</p>");
      }
    },
    error: function (xhr, status, error) {
      console.error(error);
      $("#gantt").html("<p class='text-center text-danger'>Gagal mengambil data.</p>");
    }
  });
}



function showToast(row, message) {
  // hapus notif lama di baris itu
  row.find(".inline-toast").remove();

  // buat elemen toast
  const toast = $(`
    <div class="inline-toast" style="
      position: absolute;
      left: 50%;
      top: 50%;
      transform: translate(-50%, -50%);
      background: rgba(40, 167, 69, 0.95);
      color: white;
      padding: 6px 14px;
      border-radius: 6px;
      font-size: 14px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.3);
      opacity: 0;
      transition: opacity 0.3s ease;
      pointer-events: none;
      z-index: 10;
    ">
      ${message}
    </div>
  `);

  // bungkus baris agar bisa jadi posisi relatif
  row.css("position", "relative");
  row.append(toast);

  // animasi muncul
  setTimeout(() => toast.css("opacity", "1"), 50);

  // hilang otomatis setelah 2 detik
  setTimeout(() => {
    toast.css("opacity", "0");
    setTimeout(() => toast.remove(), 400);
  }, 2000);
}



function dataChanges() {
  // Dapatkan semua baris di tabel task
  $("#taskTable tbody tr").each(function () {
    const row = $(this);
    const cells = row.find("td");

    const data = {
      id: cells.eq(0).text().trim() || '',
      employee: row.find("select#employee").val() || '',
      task: row.find("#task").val() || cells.eq(2).text().trim(),
      status: row.find("select#status").val() || '',
      progress: row.find("#progress").val() || cells.eq(4).text().trim(),
      due_date: row.find("#due_date").val() || cells.eq(5).text().trim(),
      project: row.find("select#project").val() || '',
      task_parent: row.find("select#task_parent").val() || '',
      description: row.find("#description").val() || cells.eq(8).text().trim()
    };

    // Hanya simpan kalau task & status & duedate ada isinya
    if (data.task !== '' && data.status !== '' && data.due_date !== '') {
      $.ajax({
        url: module_path + '/save_task',
        type: 'POST',
        dataType: 'JSON',
        data: data,
        success: function (res) {
          if (res.success) {
            // Update ID baru (jika baru diinsert)
            cells.eq(0).text(res.id);
            row.removeClass('new-row');
            console.log('Auto-saved:', res);
            //showToast(row, "Saved successfully");

            // Setelah berhasil save, ambil ulang data Gantt dari DB
            updateGanttFromTable();
            //load_gantt();
          } else {
            console.error('Gagal simpan:', res.message);
          }
        },
        error: function (err) {
          console.error('Error saat auto-save:', err);
        }
      });
    }
  });
}


function deleteRow(btn) {
  const row = btn.closest("tr"); // ambil baris <tr> tombol itu
  const id = row.querySelector("td:first-child span")?.innerText.trim(); // ambil ID dari kolom pertama

  if (!id) {
    // Kalau ID kosong (belum tersimpan ke DB)
    row.remove();
    showToast(row, "Row deleted (not saved in DB)");
    return;
  }

  // Konfirmasi dulu sebelum hapus
  if (confirm("Yakin ingin menghapus task ini?")) {
    deleteData(id, row);
  }
}

function deleteData(id, row) {
  $.ajax({
    url: module_path + '/delete_task',
    type: 'POST',
    dataType: 'JSON',
    data: { id: id },
    success: function (res) {
      if (res.success) {
        // Hapus baris dari tabel
        row.remove();

        // Refresh data Gantt setelah delete
        //updateGanttFromTable();
        load_gantt();

        showToast(row, "Task deleted successfully");
      } else {
        console.error('Gagal hapus:', res.message);
      }
    },
    error: function (err) {
      console.error('Error saat hapus task:', err);
    }
  });
}





function updateTaskDates(task, start, end) {
  $.ajax({
    url: module_path + '/update_task_dates',
    type: 'POST',
    dataType: 'json',
    data: {
      id: task.id,
      /*start_date: start.format('YYYY-MM-DD'),
      end_date: end.format('YYYY-MM-DD')*/
      start_date: moment(start).format('YYYY-MM-DD'),
      end_date: moment(end).format('YYYY-MM-DD')
    },
   	success: function (res) {
      if (res.success) {
       
        console.log('Auto-saved:', res);
        //showToast(row, "Saved successfully");
        //updateGanttFromTable();
        load_gantt();
        //setTimeout(() => load_gantt(), 400);

      } else {
        console.error('Gagal simpan:', res.message);
      }
    },
    error: function (err) {
      console.error('Error saat auto-save:', err);
    }
  });
}

function updateTaskProgress(task, progress) {
  $.ajax({
    url: module_path + '/update_task_progress',
    type: 'POST',
    dataType: 'json',
    data: {
      id: task.id,
      progress: progress
    },
    success: function (res) {
      if (res.success) {
       
        console.log('Auto-saved:', res);
        //updateGanttFromTable();
        //showToast(row, "Saved successfully");
        load_gantt();
        //setTimeout(() => load_gantt(), 400);
      } else {
        console.error('Gagal simpan:', res.message);
      }
    },
    error: function (err) {
      console.error('Error saat auto-save:', err);
    }
  });
}


$('#flemployee').on('change', function () { 
  var empid = $("#flemployee option:selected").val();
  var projectid = $("#flproject option:selected").val();
  
  load_gantt(empid, projectid);

});

$('#flproject').on('change', function () { 
  var projectid = $("#flproject option:selected").val();
  var empid = $("#flemployee option:selected").val();  
  load_gantt(empid, projectid);

});





</script>