
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<link href="<?php echo _ASSET_GLOBAL_METRONIC_TEMPLATE; ?>plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />


<!-- Tambahin CSS & JS markercluster -->
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.Default.css" />
<script src="https://unpkg.com/leaflet.markercluster/dist/leaflet.markercluster.js"></script>

<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>




<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"> -->


<script type="text/javascript">
var baseUrl = "<?php echo base_url($base_url); ?>";
var module_path = "<?php echo base_url($folder_name);?>"; //for save method string

// $(document).ready(function() {
//    	$(function() {

//    		initMap();       // inisialisasi map satu kali
//   		getMaps();       // panggil data awal


//    		/*$('input[name="fldashperiod"]').daterangepicker();*/
//    		$('input[name="fldashperiod"]').daterangepicker({
// 		    autoUpdateInput: false, // <-- ini kuncinya
// 		    /*locale: {
// 		        cancelLabel: 'Clear'
// 		    }*/
// 		});

// 		// Event saat user memilih tanggal
// 		$('input[name="fldashperiod"]').on('apply.daterangepicker', function(ev, picker) { 
// 		    $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));

// 		    var fldashemp = $('#fldashemp').val();
// 		    var fldashperiod = $('#fldashperiod').val();
// 		    getMaps(fldashemp,fldashperiod);
// 		});

// 		// Event saat user klik tombol "Cancel" (Clear)
// 		$('input[name="fldashperiod"]').on('cancel.daterangepicker', function(ev, picker) {
// 		    $(this).val('');
// 		});

//    	});
// });



$(document).ready(function() {
    $(function() {

        initMap();       // inisialisasi map satu kali
        getMaps();       // panggil data awal

        // Aktifkan date range picker dengan time picker
        $('input[name="fldashperiod"]').daterangepicker({
            autoUpdateInput: false,
            timePicker: true,
            timePicker24Hour: true,
            timePickerSeconds: false, // kalau mau sampai detik, ubah jadi true
            locale: {
                format: 'YYYY-MM-DD HH:mm',
                cancelLabel: 'Clear',
                applyLabel: 'Apply'
            }
        });

        // Event saat user memilih tanggal & jam
        $('input[name="fldashperiod"]').on('apply.daterangepicker', function(ev, picker) {
            // Format dengan jam dan menit
            $(this).val(
                picker.startDate.format('YYYY-MM-DD HH:mm') + ' - ' +
                picker.endDate.format('YYYY-MM-DD HH:mm')
            );

            var fldashemp = $('#fldashemp').val();
            var fldashperiod = $('#fldashperiod').val();
            getMaps(fldashemp, fldashperiod);
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



// Icon biru (default)
var blueIcon = L.icon({
  iconUrl: 'https://maps.gstatic.com/mapfiles/ms2/micons/blue-dot.png',
  iconSize: [32, 32],
  iconAnchor: [16, 32],
  popupAnchor: [0, -32]
});

// Icon merah (untuk tanggal bukan hari ini)
var redIcon = L.icon({
  iconUrl: 'https://maps.gstatic.com/mapfiles/ms2/micons/red-dot.png',
  iconSize: [32, 32],
  iconAnchor: [16, 32],
  popupAnchor: [0, -32]
});


// Buat clusterGroup di luar ajax
var markersCluster = L.markerClusterGroup();

function getMaps_new(empid = '', period = '') { 
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

        Object.keys(coordMap).forEach(key => {
          const group = coordMap[key];
          const lat = parseFloat(group[0].lat);
          const lng = parseFloat(group[0].lng);

          group.forEach((titik, index) => {
            const url_photo = `http://localhost/_hrm/uploads/absensi/${titik.photo}`;

            // cek apakah tanggal attendance = hari ini
            const today = moment().format('YYYY-MM-DD');
            const dateAttendance = moment(titik.date_attendance).format('YYYY-MM-DD');

            // pilih warna icon (biru = hari ini, merah = bukan hari ini)
            const icon = (dateAttendance === today) ? blueIcon : redIcon;

            // tambahkan jitter halus supaya marker dan tooltip tidak numpuk
            const latJitter = (Math.random() - 0.5) * 0.00005; // ±5 meter
            const lngJitter = (Math.random() - 0.5) * 0.00005;

            const marker = L.marker([lat + latJitter, lng + lngJitter], { icon: icon });

            // offset tooltip di atas icon, dengan sedikit variasi biar gak numpuk
            const jitterX = (Math.random() - 0.5) * 10; // ±5 px horizontal
            const jitterY = (Math.random() - 0.5) * 5;  // ±2.5 px vertical

            marker.bindTooltip(titik.nama, {
              permanent: true,
              direction: 'top',
              offset: L.point(jitterX, -25 + jitterY), // posisi tepat di atas icon
              className: 'tooltip-nama',
            });

            // popup detail + foto di kiri
            marker.bindPopup(`
              <div style="display: flex; align-items: flex-start; gap: 10px; min-width: 300px;">
                <div style="flex: 0 0 100px;">
                  <img src="${url_photo}" alt="photo"
                       style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px; border: 1px solid #ccc;">
                </div>
                <div style="flex: 1;">
                  <strong>${titik.nama}</strong><br>
                  <b>Date:</b> ${titik.datetime_attendance}<br>
                  <b>Type:</b> ${titik.tipe}<br>
                  <b>Location:</b> ${titik.work_location}
                </div>
              </div>
            `);

            // tambahkan ke cluster
            markersCluster.addLayer(marker);
          });
        });

        map.addLayer(markersCluster);
      }
    }
  });
}


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
            url_photo = 'http://localhost/_hrm/uploads/absensi/'+titik.photo+'';
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


            // Cek apakah tanggal attendance = hari ini
            const today = moment().format('YYYY-MM-DD');
            const dateAttendance = moment(titik.date_attendance).format('YYYY-MM-DD');
            // Tentukan warna icon
            const icon = (dateAttendance === today) ? blueIcon : redIcon;
            const tooltipClass = (dateAttendance === today) ? 'tooltip-nama tooltip-blue' : 'tooltip-nama tooltip-red';


            const marker = L.marker([lat, lng], { icon: icon })
            .bindTooltip(titik.nama, {
              permanent: true,
              direction: dir,
              /*offset: offset,*/
              offset: [0, -25], // semakin kecil, semakin dekat ke icon
              /*className: 'tooltip-nama',*/
              className: tooltipClass, // warna sesuai tanggal
              interactive: true // penting biar tooltip bisa diklik
            })
            /*.bindPopup(`
              <div style="display: flex; align-items: flex-start; gap: 10px; min-width: 300px;">
                <div style="flex: 0 0 100px;">
                  <img src="${url_photo}" alt="photo" style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px; border: 1px solid #ccc;">
                </div>
                <div style="flex: 1;">
                  <strong>${titik.nama}</strong><br>
                  <b>Date:</b> ${titik.datetime_attendance}<br>
                  <b>Type:</b> ${titik.tipe}<br>
                  <b>Location:</b> ${titik.work_location}
                </div>
              </div>
            `);*/
            .bindPopup(`
              <div class="popup-card">
                <div class="popup-image">
                  <img src="${url_photo}" alt="photo">
                </div>
                <div class="popup-info">
                  <h4>${titik.nama}</h4>
                  <p><b>Date:</b> ${titik.datetime_attendance}</p>
                  <p><b>Type:</b> ${titik.tipe}</p>
                  <p><b>Location:</b> ${titik.work_location}</p>
                </div>
              </div>
            `)

              

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