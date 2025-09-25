

<script type="text/javascript">
var module_path = "<?php echo base_url($folder_name);?>"; //for save method string
var myTable;
var validator;
var save_method; //for save method string
var idx; //for save index string
var ldx; //for save list index string



$(document).ready(function() {
   	$(function() {
   		
   		$( "#join_date" ).datepicker({
        	//startDate: '+1d'
        });

        $( "#contract_sign_date" ).datepicker({
        	//startDate: '+1d'
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
	var getUrl = window.location;
	/*local=>*/ //var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];
	var baseUrl = getUrl .protocol + "//" + getUrl.host;


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
					
					$('[name="position"]').val(data.position_name);
					$('[name="name"]').val(data.full_name);
					$('[name="email"]').val(data.email);
					$('[name="phone"]').val(data.phone);
					$('select#status').val(data.status_id).trigger('change.select2');
					/*$('[name="join_date"]').val(data.join_date);
					$('[name="contract_sign_date"]').val(data.contract_sign_date);*/

					var join_date = dateFormat(data.join_date);
					$('[name="join_date"]').datepicker('setDate', join_date);
					var contract_sign_date = dateFormat(data.contract_sign_date);
					$('[name="contract_sign_date"]').datepicker('setDate', contract_sign_date);
					var end_prob_date = dateFormat(data.end_prob_date);
					$('[name="end_prob_date"]').datepicker('setDate', end_prob_date);

					$('[name="hdnfile"]').val(data.cv);

					const fileName = data.cv; // ini bisa dari PHP atau hasil upload
				    const fileUrl = baseUrl+"/uploads/candidates/"+data.candidate_code+"/" + fileName;

				    // CLEAR link sebelumnya
					// document.getElementById("file-link").innerHTML = '<i class="fa fa-download"></i>';

				    // const link = document.createElement('a');
				    // link.href = fileUrl;
				    // link.textContent = " ";
				    // link.target = "_blank";

				    // document.getElementById("file-link").appendChild(link);


				    const link = document.createElement('a');
					link.href = fileUrl;
					link.target = "_blank";
					link.innerHTML = '<i class="fa fa-download"></i>'; // pakai icon sebagai isi link
					link.style.textDecoration = "none";
					link.style.color = "#007bff"; // warna biru (atau sesuaikan)

					document.getElementById("file-link").innerHTML = "";
					document.getElementById("file-link").appendChild(link);


				  	getStep(data.id, save_method);
					
					$.uniform.update();
					$('#mfdata').text('Update');
					$('#modal-form-data').modal('show');
				}
				if(save_method == 'detail'){ 
					$('span.position').html(data.position_name);
					$('span.full_name').html(data.full_name);
					$('span.phone').html(data.phone);
					$('span.email').html(data.email);
					$('span.status').html(data.status_name);
					$('span.join_date').html(data.join_date);
					$('span.contract_sign_date').html(data.contract_sign_date);
					$('span.end_prob_date').html(data.end_prob_date);


					const fileName = data.cv; // ini bisa dari PHP atau hasil upload
				    const fileUrl = baseUrl+"/uploads/candidates/"+data.candidate_code+"/" + fileName;

				    const link = document.createElement('a');
					link.href = fileUrl;
					link.target = "_blank";
					link.innerHTML = '<i class="fa fa-download"></i>'; // pakai icon sebagai isi link
					link.style.textDecoration = "none";
					link.style.color = "#007bff"; // warna biru (atau sesuaikan)

					$('span.file-link-view').html();
					$('span.file-link-view').html(link);


					getStep(data.id, save_method);
					
					
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
<?php } ?>


function downloadFile(filename) { 
    const link = document.createElement('a');
    link.href = module_path+'/downloadFile?file=' + encodeURIComponent(filename);

    link.setAttribute('download', filename);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}


function getStep(id, save_method) {

		$.ajax({
			type: "POST",
			url: module_path + '/getDataStep',
			data: {id: id, save_method: save_method },
			cache: false,
			dataType: "JSON",
			success: function (data) {
				if (data != null) {
					if (save_method == 'detail') {
						$('span#tblstep_detail').html(data.tblstep);
					} else {
						$('span#tblstep').html(data.tblstep);
					}

				} else {
					if (save_method == 'detail') {
						$('span#tblstep_detail').html('');
					} else {
						$('span#tblstep').html('');
					}
				}

			},
			error: function (jqXHR, textStatus, errorThrown) {
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




	// tombol LIST VIEW
	$("#btnListView").on("click", function () {
		setActiveBtn("btnListView");
	    // tampilkan tabel
	    $('#table-container').show();
	    $('#card-container').hide();

	    // toggle btn style
	    $(this).addClass('btn-primary').removeClass('btn-secondary');
	    $('#btnKanbanView').removeClass('btn-primary').addClass('btn-secondary');

	    // reload datatable
	    if (myTable) {
	        myTable.ajax.reload(null, false);
	    }

	    subFilter();
	});


	// tombol KANBAN VIEW
	$("#btnKanbanView").on("click", function () {
		setActiveBtn("btnKanbanView");
	    // sembunyikan tabel
	    $('#table-container').hide();
	    // tampilkan card container
	    $('#card-container').show().html('<div class="text-center p-3">Loading...</div>');

	    // toggle btn style
	    $(this).addClass('btn-primary').removeClass('btn-secondary');
	    $('#btnListView').removeClass('btn-primary').addClass('btn-secondary');

	    // load view kanban via AJAX lalu panggil loadCardView()
	    $("#card-container").load("<?= site_url('request_recruitment/candidates_menu/kanban_view') ?>", function() {
	        loadCardView(); // isi data setelah view kanban diload
	    });
	});


	function loadCardView() {
	    var division = $('#filter-division').val();
	    var position = $('#filter-position').val();

	    $.ajax({
	        url: module_path + "/get_card_data",
	        type: "POST",
	        data: {
	            division: division,
	            position: position
	        },
	        dataType: "json",
	        success: function(response) { 
	            console.log(response);
	            if (response.success) {
	                let html = '<div class="row kanban-board">';

	                // mapping status ke class
	                const statusMap = {
	                    "Not Started": "not-started",
	                    "In Process": "in-process",
	                    "Hired": "hired",
	                    "Not Passed": "not-passed",
	                    "Rejected": "rejected"
	                };

	                $.each(response.data, function(i, group) {
	                    let statusClass = statusMap[group.status] || group.status.toLowerCase().replace(/\s+/g, '-');

	                    html += `
	                        <div class="col-md-2 kanban-column">
	                            <div class="kanban-header ${statusClass}">
	                                ${group.status} (${group.count})
	                            </div>
	                            <div class="kanban-items">
	                    `;

	                    if (group.items.length > 0) {
                            $.each(group.items, function(i, item) {
                                html += `
                                    <div class="kanban-card card mb-2 shadow-sm">
                                        <div class="card-body p-2">
                                            <h6 class="card-title mb-1" title="${capitalize(item.name)}">${capitalize(item.name)}</h6>
                                            <small class="truncate" title="${capitalize(item.position) || ''}">${capitalize(item.position) || ''}</small><br>
                                            <small class="truncate" title="${item.email || ''}">${item.email || ''}</small><br>
                                            <small class="truncate" title="${item.phone || ''}">${item.phone || ''}</small>
                                            <a class="btn btn-xs circle btn-primary"
                                               href="javascript:void(0);" 
                                               onclick="downloadFile('${item.cv}')" role="button">
                                               <i class="fa fa-download" style="font-size:10px"></i><span style="font-size:10px"> CV</span>
                                            </a>
                                			<br><br>
                                        	<a class="btn btn-xs btn-success detail-btn" 
	                                           style="background-color: #343851; border-color: #343851;" 
	                                           href="javascript:void(0);" 
	                                           onclick="detail('${item.id}')" role="button">
	                                           <i class="fa fa-search-plus"></i>
	                                        </a>
	                            			<a class="btn btn-xs btn-primary" style="background-color: #FFA500; border-color: #FFA500;" href="javascript:void(0);" onclick="edit('${item.id}')" role="button"><i class="fa fa-pencil"></i></a>
                                        </div>
                                    </div>
                                `;
                            });
                        } else {
                            html += `<div class="text-muted small text-center">No Data</div>`;
                        }

	                    html += `</div></div>`; // close items & column
	                });

	                html += '</div>';
	                $('#candidate-container').html(html);
	            }
	        },
	        error: function(xhr, status, error) {
	            console.log("Error loadCardView:", error);
	        }
	    });
	}



	function loadCardView_old() {
	    var division = $('#filter-division').val();
	    var position = $('#filter-position').val();

	    $.ajax({
	        url: module_path + "/get_card_data",
	        type: "POST",
	        data: {
	            division: division,
	            position: position
	        },
	        dataType: "json",
	        success: function(response) {
	            if (response.success) {
	                let html = '<div class="row kanban-board">';

	                // mapping status ke class
	                const statusMap = {
	                    "Not Started": "not-started",
	                    "In Process": "in-process",
	                    "Hired": "hired",
	                    "Not Passed": "not-passed",
	                    "Rejected": "rejected"
	                };

	                $.each(response.data, function(i, group) {
	                    let statusClass = statusMap[group.status] || group.status.toLowerCase().replace(/\s+/g, '-');

	                    html += `
	                        <div class="col-md-2 kanban-column">
	                            <div class="kanban-header ${statusClass}">${group.status}</div>
	                            <div class="kanban-items">
	                    `;

	                    if (group.items.length > 0) {
	                        $.each(group.items, function(i, item) {
	                            html += `
	                                <div class="kanban-card card mb-2 shadow-sm">
	                                    <div class="card-body p-2">
	                                        <h6 class="card-title mb-1">${capitalize(item.name)}</h6>
	                                        <small>${capitalize(item.position) || ''}</small><br>
	                                        <small>${item.email || ''}</small><br>
	                                        <small>${item.phone || ''}</small>
	                            			<a class="btn btn-xs circle btn-primary" 
	                                           href="javascript:void(0);" 
	                                           onclick="downloadFile('${item.cv}')" role="button">
	                                           <i class="fa fa-download"></i> CV
	                                        </a>
	                                        <br><br>
	                                        

	                                        <a class="btn btn-xs btn-success detail-btn" 
	                                           style="background-color: #343851; border-color: #343851;" 
	                                           href="javascript:void(0);" 
	                                           onclick="detail('${item.id}')" role="button">
	                                           <i class="fa fa-search-plus"></i>
	                                        </a>
	                            			<a class="btn btn-xs btn-primary" style="background-color: #FFA500; border-color: #FFA500;" href="javascript:void(0);" onclick="edit('${item.id}')" role="button"><i class="fa fa-pencil"></i></a>
	                                    </div>
	                                </div>
	                            `;
	                        });
	                    } else {
	                        html += `<div class="text-muted small text-center">No Data</div>`;
	                    }

	                    html += `</div></div>`; // close items & column
	                });

	                html += '</div>';
	                $('#candidate-container').html(html);
	            }
	        },
	        error: function(xhr, status, error) {
	            console.log("Error loadCardView:", error);
	        }
	    });
	}


	$(document).on('change', '#filter-division', function () {
	    
	    loadCardView();
	    subFilter();
	});

	$(document).on('change', '#filter-position', function () {
	    
	    loadCardView();
	    subFilter();

	});

	// pertama kali load
	$(document).ready(function() {
	    loadCardView();
	});



	function subFilter(){
		
		var division = $('#filter-division').val();
	    var position = $('#filter-position').val();

	    if(division == ''){
			division=0;
		}
		if(position == ''){
			position=0;
		}
		

		
		$('#dynamic-table').DataTable().clear().destroy(); 
		$('#dynamic-table')
		.DataTable( {
			fixedHeader: {
				headerOffset: $('.page-header').outerHeight()
			},
			responsive: true,
			bAutoWidth: false,
			"aoColumnDefs": [
			  { "bSortable": false, "aTargets": [ 0,1 ] },
			  { "sClass": "text-center", "aTargets": [ 0,1 ] },
			],
			"aaSorting": [
			  	[2,'asc'] 
			],
			"sAjaxSource": module_path+"/get_data?fldivision="+division+"&flposition="+position+"",
			"bProcessing": true,
	        "bServerSide": true,
			"pagingType": "bootstrap_full_number",
			"colReorder": true
	    } );

	}


	function capitalize(str) {
      return str.charAt(0).toUpperCase() + str.slice(1);
    }



    function setActiveBtn(id) {
      document.getElementById("btnKanbanView").classList.remove("active","btn-primary");
      document.getElementById("btnListView").classList.remove("active","btn-primary");
      document.getElementById("btnKanbanView").classList.add("btn-outline-secondary");
      document.getElementById("btnListView").classList.add("btn-outline-secondary");
      document.getElementById(id).classList.add("active","btn-primary");
      document.getElementById(id).classList.remove("btn-outline-secondary");
    }






	



	

</script>