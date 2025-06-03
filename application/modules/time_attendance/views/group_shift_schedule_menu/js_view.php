


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

	document.getElementById("btnAccordion").style.display = "none";

   	$(function() {
        const picker = document.getElementById('monthPicker');

				picker.addEventListener('change', function () {
				  const value = this.value; // format is "YYYY-MM"
				  const [year, month] = value.split('-');
				  /*console.log("Year:", year);
				  console.log("Month:", month);*/

				  generate();
				});		

				const acc = document.querySelector('.accordion');
				const panel = document.querySelector('.panel');

		  	acc.addEventListener('click', function() {
			    acc.classList.toggle('active');
			    panel.classList.toggle('show');
		  	});
		
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
					$('[name="id"]').val(data.id);
					$('[name="period"]').val(data.periode);
				
					$('select#group').val(data.master_group_shift_id).trigger('change.select2');
					

					/*var shiftData = {
					  "2025-06-01": "1",
					  "2025-06-02": "1",
					  "2025-06-03": "1",
					  "2025-06-04": "2",
					  "2025-06-05": "2",
					  "2025-06-06": "2",
					  "2025-06-07": "0",
					  "2025-06-08": "0",
					  "2025-06-09": "3"
					};*/

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








      	/*const box = document.createElement('div');
      	box.className = 'day-box';

      	const dayNum = document.createElement('div');
      	dayNum.className = 'day-number';
      	dayNum.textContent = day;*/


        // Create the <select> element
		/*const select = document.createElement("select");
		select.name = "shift[]";
		select.id = "shiftSelect";*/
		/*select.className = "form-control";*/

		// Create and append <option> elements with values
		/*const shifts  = [
		  { label: "", value: "" },
		  { label: "Shift 1", value: "1" },
		  { label: "Shift 2", value: "2" },
		  { label: "Shift 3", value: "3" },
		  { label: "OFF", value: "0" }
		];*/

		/*shifts.forEach(shift => {
		  const option = document.createElement("option");
		  option.textContent = shift.label; // Text shown to the user
		  option.value = shift.value;       // The actual value submitted
		  select.appendChild(option);
		});*/

		// Append the select to the DOM
		//document.body.appendChild(select);


      	//box.appendChild(dayNum);
      	//box.appendChild(select);

      	//calendar.appendChild(box);
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



<?php } ?>




</script>