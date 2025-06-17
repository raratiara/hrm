
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<link href="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />


<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"> -->


<script type="text/javascript">
var module_path = "<?php echo base_url($folder_name);?>"; //for save method string

$(document).ready(function() {
   	$(function() {
   		getMaps();
        
   	});
});



function getMaps(){

	$.ajax({
		type: "POST",
        url : module_path+'/get_maps',
		data: { },
		cache: false,		
        dataType: "JSON",
        success: function(data)
        { 
		if(data != false){ 

			console.log(data);
			
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



function getMaps_old(){

	$.ajax({
		type: "POST",
        url : module_path+'/get_maps',
		data: { },
		cache: false,		
        dataType: "JSON",
        success: function(data)
        { 
		if(data != false){ 
			var locations = data;
			console.log(locations);
			//$('div#clMaps').html(data);

			/*var locations = [
			  ["LOCATION_1", 11.8166, 122.0942],
			  ["LOCATION_2", 11.9804, 121.9189],
			  ["LOCATION_3", 10.7202, 122.5621],
			  ["LOCATION_4", 11.3889, 122.6277],
			  ["LOCATION_5", 10.5929, 122.6325]
			];*/

			//L.map('map').remove();
			var container = L.DomUtil.get('map');
		      	if(container != null){
			        container._leaflet_id = null;
		      	}
			
			//var map = L.map('map').setView([11.206051, 122.447886], 8);
			var map = L.map('map').setView([-6.224598, 106.992416], 8);


			mapLink =
			  '<a href="http://openstreetmap.org">OpenStreetMap</a>';
			L.tileLayer(
			  'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			    attribution: '&copy; ' + mapLink + ' Contributors',
			    maxZoom: 18,
		  	}).addTo(map);



			for (var i = 0; i < locations.length; i++) {
			  /*marker = new L.marker([locations[i][1], locations[i][2]])
			    //.bindPopup(locations[i][0])
			  	.bindPopup(locations[i][0]).openPopup()
			    .addTo(map);*/
				var latlng = L.latLng(locations[i]['last_lat'], locations[i]['last_long']);
			    /*marker = new L.popup(latlng, {content: '<p>Hello world!<br />This is a nice popup.</p>'})
			    .addTo(map);*/
			   

			    marker = new L.popup()
			    .setLatLng(latlng)
			    .setContent('<div class="mydivclass" onclick="getDetail('+"'"+locations[i]['id']+"'"+')"> <p>'+locations[i]['full_name']+'</p> </div>')
			    .addTo(map);

			    /*marker.on('click', function() { alert("hahaha");
				    alert(ev.latlng); // ev is an event object (MouseEvent in this case)
				});*/
			    //var google = window.google.maps;

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





</script>