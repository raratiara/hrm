<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript">
var module_path = "<?php echo base_url($folder_name);?>"; //for save method string
var myTable;
var validator;
var save_method; //for save method string
var idx; //for save index string
var ldx; //for save list index string

// ====== GLOBAL CARD STATE (dipakai untuk reload setelah save/delete) ======
var lmsState = {
	page: 1,
	length: 9,
	search: '',
	category: ''
};

function escapeHtml(str){
	if(!str) return '';
	return String(str)
		.replace(/&/g, '&amp;')
		.replace(/</g, '&lt;')
		.replace(/>/g, '&gt;')
		.replace(/"/g, '&quot;')
		.replace(/'/g, '&#039;');
}

function renderPagination(total, page, length){
	var $pg = $('#lmsPagination');
	if($pg.length === 0) return;

	$pg.empty();

	var totalPages = Math.ceil(total / length);
	if(totalPages <= 1) return;

	function li(label, p, disabled, active){
		var cls = [];
		if(disabled) cls.push('disabled');
		if(active) cls.push('active');
		return '<li class="'+cls.join(' ')+'"><a href="#" data-page="'+p+'">'+label+'</a></li>';
	}

	$pg.append(li('&laquo;', page-1, page<=1, false));

	var start = Math.max(1, page-2);
	var end = Math.min(totalPages, page+2);
	for(var i=start; i<=end; i++){
		$pg.append(li(i, i, false, i===page));
	}

	$pg.append(li('&raquo;', page+1, page>=totalPages, false));
}

function renderCards(res){
	// update stats
	if($('#stat_total').length) $('#stat_total').text(res.total || 0);
	if($('#stat_active').length) $('#stat_active').text(res.active || 0);
	if($('#stat_inactive').length) $('#stat_inactive').text(res.inactive || 0);

	var rows = res.records || [];
	var $grid = $('#lmsGrid');
	if($grid.length === 0) return;

	$grid.empty();

if (rows.length === 0) {
    $grid.append(
        '<div class="col-md-12">' +
            '<div class="alert alert-info" style="border-radius:12px; padding:20px; text-align:center;">' +
                '<i class="fa fa-info-circle" style="font-size:22px; margin-bottom:8px; display:block;"></i>' +
                '<b>No data found.</b><br>' +
                '<span style="font-size:13px;">Coba ubah keyword pencarian atau filter kategori.</span>' +
            '</div>' +
        '</div>'
    );

    if ($('#lmsPagination').length) {
        $('#lmsPagination').empty();
    }
    return;
}


	rows.forEach(function(r){
		var statusBadge = (String(r.is_active) === "1")
			? '<span class="badge-status" style="background:#dcfce7;color:#16a34a;">Active</span>'
			: '<span class="badge-status" style="background:#fee2e2;color:#b91c1c;">Not Active</span>';

		var checkbox = '';
		<?php if (_USER_ACCESS_LEVEL_DELETE == "1") { ?>
		checkbox = '<label class="select-box">'
			+ '<input type="checkbox" class="data-check" name="ids[]" value="'+escapeHtml(r.id)+'" />'
			+ '<span>Select</span>'
			+ '</label>';
		<?php } ?>

		var actions = '<div class="actions">';
		<?php if (_USER_ACCESS_LEVEL_DETAIL == "1") { ?>
		actions += '<a class="btn btn-xs btn-success" style="background-color:#112D80;border-color:#112D80;" href="javascript:void(0);" onclick="detail(\''+escapeHtml(r.id)+'\')"><i class="fa fa-search-plus"></i></a> ';
		<?php } ?>
		<?php if (_USER_ACCESS_LEVEL_UPDATE == "1") { ?>
		actions += '<a class="btn btn-xs btn-primary" style="background-color:#FFA500;border-color:#FFA500;" href="javascript:void(0);" onclick="edit(\''+escapeHtml(r.id)+'\')"><i class="fa fa-pencil"></i></a> ';
		<?php } ?>
		<?php if (_USER_ACCESS_LEVEL_DELETE == "1") { ?>
		actions += '<a class="btn btn-xs btn-danger" style="background-color:#A01818;border-color:#A01818;" href="javascript:void(0);" onclick="deleting(\''+escapeHtml(r.id)+'\')"><i class="fa fa-trash"></i></a>';
		<?php } ?>
		actions += '</div>';

		var html = ''
			+ '<div class="col-md-4 col-sm-6" style="margin-bottom:16px;">'
			+ '  <div class="lms-card">'
			+ '    <div class="cover">'
			+          statusBadge
			+ '    </div>'
			+ '    <div class="body">'
			+ '      <div class="title">'+escapeHtml(r.course_name)+'</div>'
			+ '      <div class="meta">'
			+ '        <div><i class="fa fa-tag"></i> '+escapeHtml(r.category || '-')+'</div>'
			+ '        <div><i class="fa fa-users"></i> '+escapeHtml(r.department_names || '-')+'</div>'
			+ '        <div><i class="fa fa-align-left"></i> '+escapeHtml(r.description || '-')+'</div>'
			+ '      </div>'
			+ '    </div>'
			+ '    <div class="footer">'
			+        checkbox
			+        actions
			+ '    </div>'
			+ '  </div>'
			+ '</div>';

		$grid.append(html);
	});

	renderPagination(res.filtered_total || res.total || 0, lmsState.page, lmsState.length);
}

// ====== GLOBAL function supaya bisa dipanggil dari save/delete di common_module_js ======
function loadCards(){
	$.ajax({
		url: module_path + '/get_cards',
		type: 'GET',
		dataType: 'json',
		data: {
			page: lmsState.page,
			length: lmsState.length,
			search: lmsState.search,
			category: lmsState.category
		},
		success: function(res){
			renderCards(res);
		},
		error: function(xhr){
			if($('#lmsGrid').length){
				$('#lmsGrid').html(
					'<div class="col-md-12"><div class="alert alert-danger">'
					+ 'Failed load data: '+xhr.status+' '+xhr.statusText+
					'</div></div>'
				);
			}
		}
	});
}

// supaya bisa dipanggil dari luar scope (misal di common_module_js)
window.loadCards = loadCards;

$(document).ready(function() {
	$(function() {
		// kosong, biarin
	});
});

<?php if  (_USER_ACCESS_LEVEL_VIEW == "1") { ?>
jQuery(function($) {

	/* ===============================
	   LOAD TABLE LIST (DICOMMENT)
	   =============================== */
	// myTable =
	// $('#dynamic-table')
	// .DataTable( {
	// 	fixedHeader: {
	// 		headerOffset: $('.page-header').outerHeight()
	// 	},
	// 	responsive: true,
	// 	bAutoWidth: false,
	// 	"aoColumnDefs": [
	// 	  { "bSortable": false, "aTargets": [ 0,1 ] },
	// 	  { "sClass": "text-center", "aTargets": [ 0,1 ] }
	// 	],
	// 	"aaSorting": [
	// 	  	[2,'asc']
	// 	],
	// 	"sAjaxSource": module_path+"/get_data",
	// 	"bProcessing": true,
	//     "bServerSide": true,
	// 	"pagingType": "bootstrap_full_number",
	// 	"colReorder": true
	// } );

	/* ===============================
	   LOAD CARD LIST (BARU)
	   =============================== */
	loadCards();

	// search (debounce)
	var tmr = null;
	$(document).on('input', '#lmsSearch', function(){
		clearTimeout(tmr);
		var v = $(this).val();
		tmr = setTimeout(function(){
			lmsState.search = v;
			lmsState.page = 1;
			loadCards();
		}, 350);
	});

	// filter category
	$(document).on('click', '.lms-filters button[data-category]', function(e){
		e.preventDefault();
		$('.lms-filters button').removeClass('active');
		$(this).addClass('active');

		lmsState.category = $(this).data('category') || '';
		lmsState.page = 1;
		loadCards();
	});

	// pagination click
	$(document).on('click', '#lmsPagination a[data-page]', function(e){
		e.preventDefault();
		var p = parseInt($(this).data('page'), 10);
		if(!p || p < 1) return;

		lmsState.page = p;
		loadCards();
	});

	<?php if  (_USER_ACCESS_LEVEL_ADD == "1" || _USER_ACCESS_LEVEL_UPDATE == "1") { ?>
	validator = $("#frmInputData").validate({
		errorElement: 'span',
		errorClass: 'help-block help-block-error',
		focusInvalid: false,
		ignore: "",
		rules: {
			title: { required: true },
			module_name: { required: true },
			url: { required: true }
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

	<?php if  (_USER_ACCESS_LEVEL_DELETE == "1") { ?>
	//check all
	$(document).on('click', '#check-all', function () {
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

					$('[name="course_name"]').val(data.course_name);
					$('[name="desc"]').val(data.description);
					$('[name="is_active"][value="'+data.is_active+'"]').prop('checked', true);
					$('[name="category"][value="'+data.category+'"]').prop('checked', true);

					var deptIds = [];
					if (data.department_ids) {
					    deptIds = data.department_ids.split(',');
					}

					$('select#departments')
					    .val(deptIds)
					    .trigger('change');

					$.uniform.update();
					$('#mfdata').text('Update');
					$('#modal-form-data').modal('show');
				}
				if(save_method == 'detail'){
					$('span.departments').html(data.department_names);
					$('span.course_name').html(data.course_name);
					$('span.description').html(data.description);
					$('span.category').html(data.category);
					$('span.is_active').html(data.is_active_desc);

					$('#modal-view-data').modal('show');
				}
			} else {
				title = '<div class="text-center" style="padding-top:20px;padding-bottom:10px;"><i class="fa fa-exclamation-circle fa-5x" style="color:red"></i></div>';
				btn = '<br/><button class="btn blue" data-dismiss="modal">OK</button>';
				msg = '<p>Gagal peroleh data.</p>';
				var dialog = bootbox.dialog({
					message: title+'<center>'+msg+btn+'</center>'
				});
				if(response && response.status){
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
</script>
