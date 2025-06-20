


<script type="text/javascript">
var module_path = "<?php echo base_url($folder_name);?>"; //for save method string
var myTable;
var validator;
var save_method; //for save method string
var idx; //for save index string
var ldx; //for save list index string
var modloc = '/time_attendance/group_shift_schedule_menu/';
var opsForm = 'form#frmInputData';
var locate = 'table.emp-list';
var wcount = 0; //for ca list row identify




$(document).ready(function() {

		/*document.getElementById("btnAccordion").style.display = "none";

   	$(function() {
        const picker = document.getElementById('monthPicker');

				picker.addEventListener('change', function () {
				  const value = this.value; 
				  const [year, month] = value.split('-');
				  

				  generate();
				});		

				const acc = document.querySelector('.accordion');
				const panel = document.querySelector('.panel');

		  	acc.addEventListener('click', function() {
			    acc.classList.toggle('active');
			    panel.classList.toggle('show');
		  	});
		
   	});*/

		/*let currentWeek = 0;
		renderSchedule(currentWeek);*/	

		

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

// deklarasi global
let currentWeek = 0;
let jadwalData = [];
let selectedShift = []; 

const hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];




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

					
					$('[name="id"]').val(idx);
					document.getElementById("hdnjadwalTersimpan").value = JSON.stringify(data);
					document.getElementById("selectedshift").value = JSON.stringify(data);

					/*let jadwalData = [];*/ // penampung sementara semua shift yang di-drag
				  // Buat baris per karyawan
				  data.forEach(emp => {
				  	var id = emp.employee_id;
				  	var empname = emp.employee_name;
				  	var tgl = emp.tanggal;
				  	var shift = emp.shift;
				    jadwalData.push({ id, empname, tgl, shift });
				    $('[name="selectedshift"]').val(JSON.stringify(jadwalData)); 
		 			});
					


					let periode = data[0].period;
					let myArray = periode.split("-");
					let tahun = myArray[0];
					let bulan = parseInt(myArray[1]-1);
					$('select#bulan').val(bulan).trigger('change.select2');
					$('select#tahun').val(tahun).trigger('change.select2');

					
					
					let tanggalList = generateTanggalMingguan(); // misalnya minggu ini

					/*let jadwalData = [];*/ // penampung untuk update saat save
					
					let currentWeek=0;
					/*renderFormJadwal(karyawanList, jadwalTersimpan,jadwalData,tanggalList);*/
					renderSchedule(currentWeek, data, jadwalData, tanggalList);

					


					if(save_method == 'update'){ 

							document.getElementById("btnpilihshift").style.display = "block";
							document.getElementById("submit-data").style.display = "block";
							document.getElementById("btnReset").style.display = "block";

							$.uniform.update();
							$('#mfdata').text('Update');
							$('#modal-form-data').modal('show');

					}else if(save_method == 'detail'){

							document.getElementById("btnpilihshift").style.display = "none";
							document.getElementById("submit-data").style.display = "none";
							document.getElementById("btnReset").style.display = "none";
							

							$.uniform.update();
							$('#mfdata').text('	view');
							$('#modal-form-data').modal('show');
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



function generateTanggalMingguan() {

	let month = document.getElementById('bulan').value;
	let year = document.getElementById('tahun').value;
  let currentWeek = 0;

	if(month == '' && year == ''){ 
		let month = new Date().getMonth(); 
		let year = new Date().getFullYear();
	}



	/*let startDate = new Date(year, month, 1 + currentWeek * 7);*/ //awal bulan
  const result = [];
  const today = new Date();
  /*const start = new Date(today.setDate(today.getDate() - today.getDay()));*/ // minggu ini
  const start = new Date(year, month, 1 + currentWeek * 7);

  for (let i = 0; i < 7; i++) {
    const d = new Date(start);
    d.setDate(start.getDate() + i);
    result.push(d.toISOString().slice(0, 10));
  }

  return result;
}



function load_data_old()
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
					$('[name="period"]').val(data.periode);
				
					$('select#group').val(data.master_group_shift_id).trigger('change.select2');
					

					var dt = data.periode.split("-");
					var year = dt[0];
					var month = dt[1]; 
					generateCalendar(year, month, data.data_shift);

					const selects = document.querySelectorAll('.clshift');
					selects.forEach(select => {
					  select.disabled = false;
					});


					$.ajax({type: 'post',url: module_path+'/genemplistrow',data: { id:data.id },success: function (response) {
							var obj = JSON.parse(response);
							$(locate+' tbody').html(obj[0]);
							
							wcount=obj[1];
						}
					}).done(function() {
						//$(".id_wbs").chosen({width: "100%",allow_single_deselect: true});
						tSawBclear(locate);
						///expenseviewadjust(lstatus);
					});

					
					$.uniform.update();
					$('#mfdata').text('Update');
					$('#modal-form-data').modal('show');
				}
				if(save_method == 'detail'){  
					$('span.period').html(data.periode);
					$('span.group').html(data.group_name);
				
					var dt = data.periode.split("-");
					var year = dt[0];
					var month = dt[1]; 
					generateCalendarView(year, month, data.data_shift);
					
					const selects = document.querySelectorAll('.clshift');
					selects.forEach(select => {
					  select.disabled = true;
					});


					$.ajax({type: 'post',url: module_path+'/genemplistrow',data: { id:data.id, view:true },success: function (response) { 
							var obj = JSON.parse(response);
							$(locate+' tbody').html(obj[0]);
							
							wcount=obj[1];
						}
					}).done(function() {
						//$(".id_wbs").chosen({width: "100%",allow_single_deselect: true});
						tSawBclear(locate);
						///expenseviewadjust(lstatus);
					});

					
					$('#modal-view-data').modal('show');
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



function generateCalendar(year, month, shiftData='') { 
	
	const calendar = document.getElementById('calendar');
	const monthYearLabel = document.getElementById('monthYear');

	calendar.innerHTML = '';

	const date = new Date(year, month - 1, 1);
	const monthName = date.toLocaleString('default', { month: 'long' });
	monthYearLabel.textContent = `${monthName} ${year}`;
    
    

    const daysInMonth = new Date(year, month, 0).getDate();
    const startDay = new Date(year, month - 1, 1).getDay(); // 0 (Sun) - 6 (Sat)

    const dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

    // Add weekday headers
    dayNames.forEach(day => {
      const dayName = document.createElement('div');
      dayName.className = 'day-name';
      dayName.textContent = day;
      calendar.appendChild(dayName);
    });

    // Fill in blank days before the start
    for (let i = 0; i < startDay; i++) {
      const emptyBox = document.createElement('div');
      emptyBox.className = 'day-box';
      calendar.appendChild(emptyBox);
    }

    // Fill in the actual days
    for (let day = 1; day <= daysInMonth; day++) {

    	const dateKey = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
    	
    	/*const selectedShift = shiftData[dateKey] || "";*/
    	const dayyy = String(day).padStart(2, '0');
    	const selectedShift = shiftData[`${dayyy}`] || "";

    	const cell = document.createElement("div");
	    cell.style.border = "1px solid #ccc";
	    cell.style.padding = "10px";

	    const label = document.createElement("div");
	    label.textContent = day;

	    const select = document.createElement("select");
    	/*select.name = `shift[${dateKey}]`;*/
    	select.name = "shift[]";
    	select.id = "shiftSelect";
		select.className = "form-control clshift";

    	const shiftOptions  = [
		  { label: "", value: "" },
		  { label: "Shift 1", value: "1" },
		  { label: "Shift 2", value: "2" },
		  { label: "Shift 3", value: "3" },
		  { label: "OFF", value: "0" }
		];

    	shiftOptions.forEach(shift => { 
	      const option = document.createElement("option");
	      option.value = shift.value;
	      option.text = shift.label || "";
	      if (shift.value === selectedShift) option.selected = true;
	      select.appendChild(option);
	      
	    });

	    cell.appendChild(label);
	    cell.appendChild(select);
	    calendar.appendChild(cell);


    }

    document.getElementById("btnAccordion").style.display = "";
}



function generateCalendarView(year, month, shiftData='') { 
	
	const calendar = document.getElementById('calendar_view');
	const monthYearLabel = document.getElementById('monthYear_view');

	calendar.innerHTML = '';

	const date = new Date(year, month - 1, 1);
	const monthName = date.toLocaleString('default', { month: 'long' });
	monthYearLabel.textContent = `${monthName} ${year}`;
    
    

    const daysInMonth = new Date(year, month, 0).getDate();
    const startDay = new Date(year, month - 1, 1).getDay(); // 0 (Sun) - 6 (Sat)

    const dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

    // Add weekday headers
    dayNames.forEach(day => {
      const dayName = document.createElement('div');
      dayName.className = 'day-name';
      dayName.textContent = day;
      calendar.appendChild(dayName);
    });

    // Fill in blank days before the start
    for (let i = 0; i < startDay; i++) {
      const emptyBox = document.createElement('div');
      emptyBox.className = 'day-box';
      calendar.appendChild(emptyBox);
    }

    // Fill in the actual days
    for (let day = 1; day <= daysInMonth; day++) {

    	const dateKey = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
    	
    	/*const selectedShift = shiftData[dateKey] || "";*/
    	const dayyy = String(day).padStart(2, '0');
    	const selectedShift = shiftData[`${dayyy}`] || "";

    	const cell = document.createElement("div");
	    cell.style.border = "1px solid #ccc";
	    cell.style.padding = "10px";

	    const label = document.createElement("div");
	    label.textContent = day;

	    const select = document.createElement("select");
    	/*select.name = `shift[${dateKey}]`;*/
    	select.name = "shift[]";
    	select.id = "shiftSelect";
		select.className = "form-control clshift";

    	const shiftOptions  = [
		  { label: "", value: "" },
		  { label: "Shift 1", value: "1" },
		  { label: "Shift 2", value: "2" },
		  { label: "Shift 3", value: "3" },
		  { label: "OFF", value: "0" }
		];

    	shiftOptions.forEach(shift => { 
	      const option = document.createElement("option");
	      option.value = shift.value;
	      option.text = shift.label || "";
	      if (shift.value === selectedShift) option.selected = true;
	      select.appendChild(option);
	      
	    });

	    cell.appendChild(label);
	    cell.appendChild(select);
	    calendar.appendChild(cell);


    }
}

 


function generate(){ 
	var period = $("#monthPicker").val();
	var group = $("#group").val();
	var dt = period.split("-");
	var year = dt[0];
	var month = dt[1]; 

	if(period != 'undefined' && period != '' && group != ''){
		generateCalendar(year, month);
	}else{
		alert("Please fill the Periode and Group");
	}
  	
  	
}


$("#addemplist").on("click", function () { 
	
		expire();
		var newRow = $("<tr>");
		$.ajax({type: 'post',url: module_path+'/genemplistrow',data: { count:wcount },success: function (response) {
				newRow.append(response);
				$(locate).append(newRow);
				wcount++;
				
			}
		}).done(function() {
			tSawBclear('table.order-list');
		});
	
});


function del(idx,hdnid){
	
	if(hdnid != ''){ //delete dr table

		$.ajax({type: 'post',url: module_path+'/delrowDetailEmpList',data: { id:hdnid },success: function (response) {
				
			}
		}).done(function() {
			tSawBclear('table.emp-list');
		});

	}

	//delete tampilan row
	var table = document.getElementById("tblDetailEmpList");
	table.deleteRow(idx);

}



function formatDateLocal(date) {
  const year = date.getFullYear();
  const month = String(date.getMonth() + 1).padStart(2, '0');
  const day = String(date.getDate()).padStart(2, '0');
  return `${year}-${month}-${day}`;
}


function renderSchedule(currentWeek, jadwalTersimpan='',jadwalData='',tanggalList=''){

	
	let hdnjadwalTersimpana = $("#hdnjadwalTersimpan").val(); console.log(hdnjadwalTersimpana);
	if(hdnjadwalTersimpana != ''){ 
		//let hdnjadwalTersimpan = JSON.parse(hdnjadwalTersimpana);
		jadwalTersimpan = JSON.parse(hdnjadwalTersimpana);
	}else{ 
		jadwalTersimpan=[];
	}
	

	let month = document.getElementById('bulan').value; 
	let year = document.getElementById('tahun').value; 
	if(month == '' && year == ''){ 
		let month = new Date().getMonth(); 
		let year = new Date().getFullYear(); 
	}



 	$.ajax({
		type: "POST",
    url : module_path+'/get_shift',
		data: { },
		cache: false,		
    dataType: "JSON",
    success: function(data)
    {
			if(data != false){ 

				let karyawanList = data;
			  let tbody = document.getElementById('jadwal-body');
			  tbody.innerHTML = "";

			  var perioddate = year+'-'+month;

			  // Hitung tanggal awal minggu
			  //let startDate = new Date(year, month, 1 + currentWeek * 7);

			  // Jika ingin mulai dari hari Minggu
				//startDate.setDate(startDate.getDate() - startDate.getDay()); 

				// Jika ingin mulai dari hari Senin
				/*startDate.setDate(startDate.getDate() - ((startDate.getDay() + 6) % 7));*/

			  

			  // Set header tanggal
			  /*for (let i = 0; i < 7; i++) {
			    let tgl = new Date(startDate);
			    tgl.setDate(startDate.getDate() + i);
			    let id = "tgl" + (i + 1);
			    let el = document.getElementById(id);
			    el.textContent = isValidDate(tgl) ? tgl.toISOString().slice(0, 10) : 'Tanggal tidak valid';
			    el.dataset.tgl = el.textContent;
			  }*/

			  /*for (let i = 0; i < 7; i++) {
  let tgl = new Date(startDate);
  tgl.setDate(startDate.getDate() + i);

  let id = "tgl" + (i + 1);
  let el = document.getElementById(id);
console.log(tgl);
  if (isValidDate(tgl)) {
    // Ambil nama hari sesuai tanggalnya secara dinamis (lokal Indonesia)
    let namaHari = tgl.toLocaleDateString('id-ID', { weekday: 'long' });
    let tanggalStr = tgl.toISOString().slice(0, 10);
    
    el.innerHTML = `${namaHari}<br>${tanggalStr}`;
    el.dataset.tgl = tanggalStr;
  } else {
    el.textContent = 'Tanggal tidak valid';
  }
}*/


				
			  let startDate = new Date(year, month, 1 + currentWeek * 7);

for (let i = 0; i < 7; i++) {
  let tgl = new Date(year, month, 1 + currentWeek * 7 + i); // 👍 LEBIH AMAN
  
  let id = "tgl" + (i + 1);
  let el = document.getElementById(id);

  

  if (isValidDate(tgl)) {
    let namaHari = tgl.toLocaleDateString('id-ID', { weekday: 'long' });
    /*let tanggalStr = tgl.toISOString().slice(0, 10);*/
    let tanggalStr = formatDateLocal(tgl);




    
    el.innerHTML = `${namaHari}<br>${tanggalStr}`;
    el.dataset.tgl = tanggalStr;
  } else {
    el.textContent = 'Tanggal tidak valid';
  }
}






			 



			  let jadwalData = []; // penampung sementara semua shift yang di-drag
			  // Buat baris per karyawan
			  karyawanList.forEach(emp => {
			    let tr = document.createElement('tr');
			    tr.innerHTML = `<td><strong>${emp.full_name}</strong></td>`;

			    for (let i = 0; i < 7; i++) {
			      let tgl = document.getElementById("tgl" + (i + 1)).dataset.tgl;
			      let td = document.createElement('td');
			      td.className = 'drop-cell';
			      td.dataset.karyawan = emp.full_name;
			      td.dataset.tanggal = tgl;


			      const spltgl = tgl.split("-");
						let tglX = spltgl[2];



						/*let jadwalTersimpan = [
						  { nama: "Rara Tiara", tanggal: "2025-06-01", shift: "1" },
						  { nama: "Rara Tiara", tanggal: "2025-06-02", shift: "2" },
						  { nama: "Ivan Maulana Agusti", tanggal: "2025-06-01", shift: "3" }
						];*/


			      // Cek apakah ada shift yang disimpan sebelumnya
			      const jadwal = jadwalTersimpan.find(j => j.employee_name === emp.full_name && j.tanggal === tgl);
			   
			      if (jadwal) { 
			      	
			        const shiftClass = jadwal.shift.toLowerCase().replace(/\s+/g, '');
			        const shiftName = jadwal.shift;
			        td.innerHTML = `
			          <div class="assigned ${shiftClass}" onclick="deleteShift(this, '${emp.full_name}', '${tgl}')">
			            ${shiftName}
			          </div>`;
			        
			        // Push ke jadwalData juga
		          var id = emp.id;
					  	var empname = emp.full_name;
					    jadwalData.push({ id, empname, tgl, shiftName });


			      }




			      td.addEventListener('dragover', e => e.preventDefault());
			      td.addEventListener('drop', e => {
			        e.preventDefault();
			        let shift = e.dataTransfer.getData('text/plain');
			        td.innerHTML = `<div class="assigned ${shift.toLowerCase().replace(/\s+/g, '')}" onclick="deleteShift(this, '${emp.full_name}', '${tgl}')">${shift}</div>`;

			        // Simpan ke PHP
			        /*fetch('save.php', {
			          method: 'POST',
			          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
			          body: `karyawan=${encodeURIComponent(nama)}&shift=${encodeURIComponent(shift)}&tanggal=${tgl}`
			        });*/

			        
			        const existing = jadwalData.find(item => item.nama === emp.full_name && item.tgl === tgl);
						  if (existing) {
						    existing.shift = shift;
						  } else {
						  	var id = emp.id;
						  	var empname = emp.full_name;
						    jadwalData.push({ id, empname, tgl, shift });
						    $('[name="selectedshift"]').val(JSON.stringify(jadwalData)); 
						  }


			        //console.log("Tanggal ke-" + (i + 1), tgl);
			      });

			      tr.appendChild(td);
			    }

			    tbody.appendChild(tr);
			  });



				
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



function renderSchedule_old(currentWeek, jadwalTersimpan='',jadwalData='',tanggalList=''){

	let month = document.getElementById('bulan').value;
	let year = document.getElementById('tahun').value;


	if(month == '' && year == ''){ 
		let month = new Date().getMonth(); 
		let year = new Date().getFullYear();
	}


	 $.ajax({
		type: "POST",
    url : module_path+'/get_shift',
		data: { },
		cache: false,		
    dataType: "JSON",
    success: function(data)
    {
			if(data != false){ 

				let karyawanList = data;
				
				/*let month = 5; // Juni (0-indexed, jadi 5)
				let year = 2025;*/

				
				/*document.getElementById('bulan').value = month;
				document.getElementById('tahun').value = year;*/


			  let tbody = document.getElementById('jadwal-body');
			  tbody.innerHTML = "";

			  // Hitung tanggal awal minggu
			  let startDate = new Date(year, month, 1 + currentWeek * 7);

			  // Set header tanggal
			  for (let i = 0; i < 7; i++) {
			    let tgl = new Date(startDate);
			    tgl.setDate(startDate.getDate() + i);
			    let id = "tgl" + (i + 1);
			    let el = document.getElementById(id);
			    el.textContent = isValidDate(tgl) ? tgl.toISOString().slice(0, 10) : 'Tanggal tidak valid';
			    el.dataset.tgl = el.textContent;


			  }

			  let jadwalData = []; // penampung sementara semua shift yang di-drag
			  // Buat baris per karyawan
			  karyawanList.forEach(emp => {
			    let tr = document.createElement('tr');
			    tr.innerHTML = `<td><strong>${emp.full_name}</strong></td>`;

			    for (let i = 0; i < 7; i++) {
			      let tgl = document.getElementById("tgl" + (i + 1)).dataset.tgl;
			      let td = document.createElement('td');
			      td.className = 'drop-cell';
			      td.dataset.karyawan = emp.full_name;
			      td.dataset.tanggal = tgl;

			      td.addEventListener('dragover', e => e.preventDefault());
			      td.addEventListener('drop', e => {
			        e.preventDefault();
			        let shift = e.dataTransfer.getData('text/plain');
			        td.innerHTML = `<div class="assigned ${shift.toLowerCase().replace(/\s+/g, '')}" onclick="deleteShift(this, '${emp.full_name}', '${tgl}')">${shift}</div>`;

			        // Simpan ke PHP
			        /*fetch('save.php', {
			          method: 'POST',
			          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
			          body: `karyawan=${encodeURIComponent(nama)}&shift=${encodeURIComponent(shift)}&tanggal=${tgl}`
			        });*/

			        
			        const existing = jadwalData.find(item => item.nama === emp.full_name && item.tgl === tgl);
						  if (existing) {
						    existing.shift = shift;
						  } else {
						  	var id = emp.id;
						  	var empname = emp.full_name;
						    jadwalData.push({ id, empname, tgl, shift });
						    $('[name="selectedshift"]').val(JSON.stringify(jadwalData)); 
						  }


			        //console.log("Tanggal ke-" + (i + 1), tgl);
			      });

			      tr.appendChild(td);
			    }

			    tbody.appendChild(tr);
			  });



				
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


function pilihShift(){

	var bln = document.getElementById('bulan').value;
	var thn = document.getElementById('tahun').value;

	if(bln != '' && thn != ''){
		document.getElementById('shiftModal').style.display = 'block';
	}else{
		alert('Silahkan pilih bulan & tahun');
	}

	
}


// Navigasi minggu
function changeWeek(delta) { 
	/*let currentWeek = 0;*/ // minggu ke-0 = awal bulan
	let month = document.getElementById('bulan').value;
	let year = document.getElementById('tahun').value;

	

	if(month == '' && year == ''){
		let month 	= new Date().getMonth();
		let year 		= new Date().getFullYear();
	}


  const nextStart = new Date(year, month, 1 + (currentWeek + delta) * 7); 
  if (nextStart.getMonth() == month) {    // hanya izinkan jika masih dalam bulan yang sama
    currentWeek += delta; 
    renderSchedule(currentWeek);
  }
}

// Drag shift
document.querySelectorAll('.shift-btn').forEach(btn => {
  btn.addEventListener('dragstart', e => {
    e.dataTransfer.setData('text/plain', btn.dataset.shift);
  });
});



function deleteShift_old(el, karyawan, tanggal) {
  // Konfirmasi hapus
  if (confirm(`Hapus shift ${el.innerText} untuk ${karyawan} di ${tanggal}?`)) {
    el.parentElement.innerHTML = ''; // Hapus dari tampilan

    // Hapus dari database
    /*fetch('delete.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `karyawan=${encodeURIComponent(karyawan)}&tanggal=${tanggal}`
    }).then(r => r.text()).then(console.log);*/
  }
}



function deleteShift(el, nama, tanggal) { 
  // Hapus tampilan shift di dalam cell
  el.parentElement.innerHTML = "";


  getselectedShift = document.getElementById("selectedshift").value;
  selectedShift = JSON.parse(getselectedShift);
  
  // Hapus dari selectedShift berdasarkan nama & tanggal
  selectedShift = selectedShift.filter(item => !(item.empname === nama && item.tgl === tanggal));
  $('[name="selectedshift"]').val(JSON.stringify(selectedShift)); 
}



function resetToBulanTahun() { 
  month = parseInt(document.getElementById('bulan').value); 
  year = parseInt(document.getElementById('tahun').value);
  
  currentWeek = 0;
  renderSchedule(currentWeek);
}


function isValidDate(d) {
  return d instanceof Date && !isNaN(d);
}




let selectedShiftData = {}; // untuk menyimpan info saat klik shift

function openShiftModal(nama, shift, tanggal) {
  selectedShiftData = { nama, shift, tanggal };
  document.getElementById('modalKaryawan').innerText = nama;
  document.getElementById('modalTanggal').innerText = tanggal;
  document.getElementById('modalShift').innerText = shift;
  document.getElementById('shiftModal').style.display = 'block';
}

function closeShiftModal() {
  document.getElementById('shiftModal').style.display = 'none';
}

// Drag dari dalam modal
function drag(ev) {
  ev.dataTransfer.setData("text", ev.target.getAttribute('data-shift'));
}

// Fungsi hapus shift
function confirmDeleteShift() {
  if (confirm(`Hapus shift ${selectedShiftData.shift} untuk ${selectedShiftData.nama} di ${selectedShiftData.tanggal}?`)) {
    // Hapus dari tampilan
    const allCells = document.querySelectorAll('td');
    allCells.forEach(td => {
      if (td.innerText.includes(selectedShiftData.shift) && td.innerText.includes(selectedShiftData.nama)) {
        td.innerHTML = '';
      }
    });

    // Hapus dari database (jika perlu)
    fetch('delete.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `karyawan=${encodeURIComponent(selectedShiftData.nama)}&tanggal=${selectedShiftData.tanggal}`
    }).then(res => res.text()).then(alert);

    closeShiftModal();
  }
}





<?php } ?>




</script>