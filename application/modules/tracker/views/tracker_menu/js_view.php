
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<link href="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />


<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"> -->


<script type="text/javascript">
var module_path = "<?php echo base_url($folder_name);?>"; //for save method string

$(document).ready(function() {
   	$(function() {

   		initMap();       // inisialisasi map satu kali
  		getMaps();       // panggil data awal


   		/*$('input[name="fldashperiod"]').daterangepicker();*/
   		$('input[name="fldashperiod"]').daterangepicker({
		    autoUpdateInput: false, // <-- ini kuncinya
		    /*locale: {
		        cancelLabel: 'Clear'
		    }*/
		});

		// Event saat user memilih tanggal
		$('input[name="fldashperiod"]').on('apply.daterangepicker', function(ev, picker) { 
		    $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));

		    var fldashemp = $('#fldashemp').val();
		    var fldashperiod = $('#fldashperiod').val();
		    getMaps(fldashemp,fldashperiod);
		});

		// Event saat user klik tombol "Cancel" (Clear)
		$('input[name="fldashperiod"]').on('cancel.daterangepicker', function(ev, picker) {
		    $(this).val('');
		});

   	});
});




var map; // global
var markers = []; // untuk menyimpan semua marker agar bisa dibersihkan


function initMap() {
  map = L.map('map').setView([-6.224598, 106.992416], 13);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
  }).addTo(map);
}




function getMaps(empid = '',period='') {
  $.ajax({
    type: "POST",
    url: module_path + '/get_maps',
    data: { empid: empid, period: period },
    cache: false,
    dataType: "JSON",
    success: function (data) {
      if (data !== false) {
        console.log(data);

        // Bersihkan marker lama
        markers.forEach(function(marker) {
          map.removeLayer(marker);
        });
        markers = [];

        // Tambah marker baru
        data.forEach(function (titik) {
          var marker = L.marker([titik.lat, titik.lng])
            .addTo(map)
            /*.bindPopup("<b>" + titik.nama + "</b>");*/
            .bindTooltip(titik.nama, { permanent: true, direction: "top" })
  			.openTooltip();
          markers.push(marker);
        });

      } else {
        bootbox.dialog({
          message: '<div class="text-center" style="padding-top:20px;padding-bottom:10px;"><i class="fa fa-exclamation-circle fa-5x" style="color:red"></i></div>' +
                   '<center><p>Gagal peroleh data.</p><br/><button class="btn blue" data-dismiss="modal">OK</button></center>'
        });
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      bootbox.dialog({
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



function getMaps_old(empid=''){


	$.ajax({
		type: "POST",
        url : module_path+'/get_maps',
		data: {empid: empid },
		cache: false,		
        dataType: "JSON",
        success: function(data)
        { 
		if(data != false){ 

			console.log(data);


			var container = L.DomUtil.get('map');
	      	if(container != null){
		        container._leaflet_id = null;
	      	}
			
			// Inisialisasi peta
			  var map = L.map('map').setView([-6.224598, 106.992416], 13); // titik awal (latitude, longitude, zoom)

			  // Tambahkan tile layer (peta dasar)
			  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			    attribution: '&copy; OpenStreetMap contributors'
			  }).addTo(map);

			  // Contoh array titik koordinat
			  /*var titikKoordinat = [
			    { nama: "Summarecon Mall Bekasi", lat: -6.224598, lng: 106.992416 },
			    { nama: "Pakuwon Mall Bekasi", lat: -6.25608, lng: 106.9894 },
			    { nama: "Contoh Lokasi Lain", lat: -6.2400, lng: 106.9800 }
			  ];*/
			  var titikKoordinat = data;

			  // Tambahkan marker untuk setiap titik
			  titikKoordinat.forEach(function(titik) {
			    L.marker([titik.lat, titik.lng])
			      .addTo(map)
			      .bindPopup("<b>" + titik.nama + "</b>")
			      .openPopup();
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

function updateSelectBoxHeight() {
    const selectedCount = $('#fldashemp').select2('data').length;

    // Atur tinggi dinamis berdasarkan jumlah yang dipilih (misalnya 36px per baris)
    let height = 36; // minimum height
    let height_cont = height + 90;
    let top_box2 = height + 30;
    if (selectedCount > 1) {
      height = selectedCount * 18 + 10; // 18px per item + padding
      height_cont = height + 90;
      top_box2 = height + 30;
    }

    $('.select2-selection--multiple').css('height', height + 'px');
    $('.select2-selection__rendered').css('height', 'auto');

    $('.video-container').css('height', height_cont + 'px');
    $('.box-title2').css('top', top_box2 + 'px');
  }


$('#fldashemp').on('change', function () { 
 	var employee = $('#fldashemp').val(); // array of selected IDs
 	/*var employee = $("#fldashemp option:selected").val();*/
 	updateSelectBoxHeight();


    var fldashperiod = $('#fldashperiod').val();
    getMaps(employee,fldashperiod);

});




</script>