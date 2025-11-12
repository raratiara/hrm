
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<link href="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />


<!-- Tambahin CSS & JS markercluster -->
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.Default.css" />
<script src="https://unpkg.com/leaflet.markercluster/dist/leaflet.markercluster.js"></script>





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


function isOverlapping(rect1, rect2) {
  return !(
    rect1.right < rect2.left ||
    rect1.left > rect2.right ||
    rect1.bottom < rect2.top ||
    rect1.top > rect2.bottom
  );
}

function adjustTooltipPosition(el) {
  let moved = false;
  const maxTries = 10; // biar ga infinite loop
  let tries = 0;

  while (tries < maxTries) {
    let collided = false;
    const rect = el.getBoundingClientRect();

    document.querySelectorAll('.tooltip-nama').forEach(other => {
      if (other === el) return;
      const otherRect = other.getBoundingClientRect();
      if (isOverlapping(rect, otherRect)) {
        // geser ke bawah 20px kalau tabrakan
        const currentMargin = parseInt(el.style.marginTop || "0", 10);
        el.style.marginTop = (currentMargin + 20) + "px";
        collided = true;
        moved = true;
      }
    });

    if (!collided) break; // sudah aman
    tries++;
  }

  return moved;
}




// Buat clusterGroup di luar ajax
var markersCluster = L.markerClusterGroup();

function getMaps(empid = '', period = '') {
  $.ajax({
    type: "POST",
    url: module_path + '/get_maps',
    data: { empid: empid, period: period },
    cache: false,
    dataType: "JSON",
    success: function (data) {
      if (data !== false) {
        markersCluster.clearLayers(); // hapus cluster lama

        // kelompokkan data berdasarkan koordinat
        let coordMap = {};
        data.forEach(titik => {
          const key = `${titik.lat},${titik.lng}`;
          if (!coordMap[key]) coordMap[key] = [];
          coordMap[key].push(titik);
        });

        const directions = ['top', 'right', 'bottom', 'left']; // arah tooltip bergantian

        Object.keys(coordMap).forEach(key => {
          const group = coordMap[key];
          const lat = parseFloat(group[0].lat);
          const lng = parseFloat(group[0].lng);

          group.forEach((titik, index) => {
            // pilih arah bergantian
            const dir = directions[index % directions.length];

            // offset bertingkat per marker
            let offset;
            switch(dir) {
              case 'top':
                offset = L.point(0, -30 * Math.floor(index / directions.length) - 20);
                break;
              case 'bottom':
                offset = L.point(0, 30 * Math.floor(index / directions.length) + 20);
                break;
              case 'right':
                offset = L.point(60 * Math.floor(index / directions.length) + 20, 0);
                break;
              case 'left':
                offset = L.point(-60 * Math.floor(index / directions.length) - 20, 0);
                break;
            }

            // === Tambahin jitter acak biar makin jarang nabrak ===
            const jitterX = (Math.random() - 0.5) * 15; // -7.5 s/d +7.5
            const jitterY = (Math.random() - 0.5) * 15;
            offset = offset.add(L.point(jitterX, jitterY));

            const marker = L.marker([lat, lng])
            .bindTooltip(titik.nama, {
              permanent: true,
              direction: dir,
              offset: offset,
              className: 'tooltip-nama',
              interactive: true // penting biar tooltip bisa diklik
            })
            .bindPopup(
              "<strong>" + titik.nama + "</strong><br>" +
              "<b>Date:</b> " + titik.date_attendance + "<br>" +
              "<b>Location:</b> " + titik.work_location
            );
              

            markersCluster.addLayer(marker);

            // rapihin posisi tooltip setelah marker ditambah
            marker.on('add', function () {
              setTimeout(() => {
                const tooltip = marker.getTooltip();
                if (!tooltip) return;
                const el = tooltip.getElement();
                if (!el) return;
                adjustTooltipPosition(el);
              }, 100);
            });

            // klik tooltip juga buka popup
            marker.on("tooltipclick", function () {
              marker.openPopup();
            });
          });
        });

        map.addLayer(markersCluster);
      }
    }
  });
}



// function getMaps_old3(empid = '', period = '') {
//   $.ajax({
//     type: "POST",
//     url: module_path + '/get_maps',
//     data: { empid: empid, period: period },
//     cache: false,
//     dataType: "JSON",
//     success: function (data) {
//       if (data !== false) {
//         markersCluster.clearLayers(); // hapus cluster lama

//         // kelompokkan data berdasarkan koordinat
//         let coordMap = {};
//         data.forEach(titik => {
//           const key = `${titik.lat},${titik.lng}`;
//           if (!coordMap[key]) coordMap[key] = [];
//           coordMap[key].push(titik);
//         });

//         const directions = ['top', 'right', 'bottom', 'left']; // arah tooltip bergantian

//         Object.keys(coordMap).forEach(key => {
//           const group = coordMap[key];
//           const lat = parseFloat(group[0].lat);
//           const lng = parseFloat(group[0].lng);

//           group.forEach((titik, index) => {
//             // pilih arah bergantian
//             const dir = directions[index % directions.length];

//             // offset bertingkat per marker
//             let offset;
//             switch(dir) {
//               case 'top':
//                 offset = L.point(0, -30 * Math.floor(index / directions.length) - 20);
//                 break;
//               case 'bottom':
//                 offset = L.point(0, 30 * Math.floor(index / directions.length) + 20);
//                 break;
//               case 'right':
//                 offset = L.point(60 * Math.floor(index / directions.length) + 20, 0);
//                 break;
//               case 'left':
//                 offset = L.point(-60 * Math.floor(index / directions.length) - 20, 0);
//                 break;
//             }
//             // === Tambahin jitter acak biar makin jarang nabrak ===
//             const jitterX = (Math.random() - 0.5) * 15; // -7.5 s/d +7.5
//             const jitterY = (Math.random() - 0.5) * 15;
//             offset = offset.add(L.point(jitterX, jitterY));

//             const marker = L.marker([lat, lng])
//               .bindTooltip(titik.nama, {
//                 permanent: true,
//                 direction: dir,
//                 offset: offset,
//                 className: 'tooltip-nama',
//                 interactive: true
//               })
//               .bindPopup(
//                 "<strong>" + titik.nama + "</strong><br>" +
//                 "<b>Tanggal:</b> " + titik.date_attendance + "<br>" +
//                 "<b>Lokasi:</b> " + titik.work_location
//               );

//             markersCluster.addLayer(marker);

//             /*marker.on('add', function () {
//               setTimeout(() => {
//                 const el = marker.getTooltip().getElement();
//                 if (!el) return;
//                 adjustTooltipPosition(el);
//               }, 100);
//             });*/

//             marker.on('add', function () {
//               setTimeout(() => {
//                 const tooltip = marker.getTooltip();
//                 if (!tooltip) return;

//                 const el = tooltip.getElement();
//                 if (!el) return;

//                 // atur posisi biar gak nabrak
//                 adjustTooltipPosition(el);

//                 // tambahin event klik di tooltip
//                 el.style.cursor = "pointer"; // biar kelihatan bisa di-klik
//                 el.addEventListener("click", function () {
//                   marker.openPopup();
//                 });
//               }, 100);
//             });

//             marker.on("tooltipclick", function () {
//               marker.openPopup();
//             });


//           });
//         });

//         map.addLayer(markersCluster);
//       }
//     }
//   });
// }


function getMaps_old2(empid = '',period='') {
  $.ajax({
    type: "POST",
    url: module_path + '/get_maps',
    data: { empid: empid, period: period },
    cache: false,
    dataType: "JSON",
    success: function (data) {
      if (data !== false) {
        markersCluster.clearLayers(); // hapus cluster lama

        data.forEach(function (titik,i) {
          var marker = L.marker([titik.lat, titik.lng])
            .bindPopup(
              "<div>" +
                "<strong>" + titik.nama + "</strong><br>" +
                "<b>Tanggal:</b> " + titik.date_attendance + "<br>" +
               /* "<b>Lokasi:</b> " + titik.lat + ", " + titik.lng +*/
                 "<b>Lokasi:</b> " + titik.work_location +
              "</div>"
            )
            //.bindTooltip(titik.nama, { permanent: true, direction: "top" });
            .bindTooltip(titik.nama, { 
				      permanent: true,
				      direction: i % 2 === 0 ? "left" : "right",  // ganti arah kiri/kanan biar gak tabrakan
				      offset: L.point(0, -15 * (i % 5))           // geser tiap tooltip biar tidak numpuk
				    });

          markersCluster.addLayer(marker);
        });


        map.addLayer(markersCluster);

      } else {
        bootbox.dialog({
          message: '<div class="text-center" style="padding-top:20px;padding-bottom:10px;"><i class="fa fa-exclamation-circle fa-5x" style="color:red"></i></div>' +
                   '<center><p>Gagal peroleh data.</p><br/><button class="btn blue" data-dismiss="modal">OK</button></center>'
        });
      }
    }
  });
}


function getMaps_old(empid = '',period='') {
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
          /*.bindPopup("<b>" + titik.date_attendance + "</b>")*/
          .bindPopup(
					    "<div>" +
					        "<strong>" + titik.nama + "</strong><br>" +
					        "<b>Tanggal:</b> " + titik.date_attendance + "<br>" +
					        /*"<b>Jam:</b> " + titik.jam + "<br>" +*/
					        "<b>Lokasi:</b> " + titik.lat + ", " + titik.lng +
					    "</div>"
					)
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