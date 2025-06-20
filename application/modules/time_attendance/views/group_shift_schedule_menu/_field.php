<!-- <div class="row">
	
	<div class="col-md-4 col-sm-12">
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Group</label>
			<div class="col-md-8">
				<?=$selgroup;?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-4 control-label no-padding-right">Period</label>
			<div class="col-md-8">
				<input type="month" id="monthPicker" name="period" class="form-control">
			</div>
		</div>
		
	</div>
		
	
</div>



<div class="calendar-header">
  <h2 id="monthYear"></h2>
</div>

<div class="calendar-grid" id="calendar">
  
</div> 


<button href="#tabemplist" data-toggle="tab" id="btnAccordion" class="accordion">Employee List</button>

<div class="panel" id="tabemplist">
	<div class="row emplist">
	    <div class="col-md-12">
			<div class="portlet box">
				<div class="portlet-title">
					<div class="caption"> </div>
					<div class="tools">
						<input type="button" class="btn btn-default blue btn-outline btn-circle btn-sm active" id="addemplist" value="Add Row" />
					</div>
				</div>
				<div class="portlet-body">
					<div class="table-scrollable tablesaw-cont"> 
					<table class="table table-striped table-bordered table-hover emp-list tablesaw tablesaw-stack" data-tablesaw-mode="stack" id="tblDetailEmpList">
						<thead>
							<tr>
								<th scope="col">No</th>
								<th scope="col">Employee</th>
								<th scope="col"></th>
							</tr>
						</thead>
						<tbody>
							
						</tbody>

						<tfoot>
						</tfoot>
					</table>
					
					</div>
				</div>
			</div>
		</div>
	</div>
</div> -->



<!-- Modal Pop-Up -->
<div id="shiftModal" class="custom-modal">
  <div class="custom-modal-content">
    <span class="custom-close" onclick="closeShiftModal()">&times;</span>
    <h3 style="font-size: 14px;">Drag Shift</h3>

    <!-- Tombol Shift -->
    <div class="shift-options">
      <!-- <span class="shift-btn pagi" draggable="true" data-shift="Pagi" ondragstart="drag(event)">☀ Shift Pagi</span>
      <span class="shift-btn malam" draggable="true" data-shift="Malam" ondragstart="drag(event)">🌙 Shift Malam</span> -->
      <span class="shift-btn shift1" draggable="true" data-shift="Shift 1" ondragstart="drag(event)">Shift 1</span>
      <span class="shift-btn shift2" draggable="true" data-shift="Shift 2" ondragstart="drag(event)">Shift 2</span>
      <span class="shift-btn shift3" draggable="true" data-shift="Shift 3" ondragstart="drag(event)">Shift 3</span>

    </div>

    <!-- <p><strong>Karyawan:</strong> <span id="modalKaryawan"></span></p>
    <p><strong>Tanggal:</strong> <span id="modalTanggal"></span></p>
    <p><strong>Shift Saat Ini:</strong> <span id="modalShift"></span></p>
    
    <button onclick="confirmDeleteShift()">🗑 Hapus Shift Ini</button> -->
  </div>
</div>




<label>Bulan:
  <select id="bulan" name="bulan" onchange="resetToBulanTahun()">
  	<!-- <select id="bulan" name="bulan" > -->
    <script>
      for (let i = 0; i < 12; i++) {
        document.write(`<option value="${i}">${new Date(0, i).toLocaleString('id', { month: 'long' })}</option>`);
      }
    </script>
  </select>
</label>

<label>Tahun:
  <select id="tahun" name="tahun" onchange="resetToBulanTahun()">
  	<!-- <select id="tahun" name="tahun" > -->
    <script>
      const now = new Date().getFullYear();
      for (let y = now - 2; y <= now + 2; y++) {
        document.write(`<option value="${y}" ${y === now ? 'selected' : ''}>${y}</option>`);
      }
    </script>
  </select>
</label>

<input type="hidden" name="selectedshift" id="selectedshift">
<input type="text" name="hdnjadwalTersimpan" id="hdnjadwalTersimpan">


<div>
	  <button type="button" class="btnpilihshift" id="btnpilihshift" onclick="pilihShift();"> Pilih Shift</button>
</div>

<!-- Shift drag -->
<!-- <div style="margin-top: 10px;">
  <strong>Drag Shift:</strong>
  <span class="shift-btn pagi" draggable="true" data-shift="Pagi">Pagi</span>
  <span class="shift-btn malam" draggable="true" data-shift="Malam">Malam</span>
</div> -->

<!-- Tabel -->
<table>
  <thead>
    <tr>
      <th>Karyawan</th>
      <th class="date-header" id="tgl1"></th>
      <th class="date-header" id="tgl2"></th>
      <th class="date-header" id="tgl3"></th>
      <th class="date-header" id="tgl4"></th>
      <th class="date-header" id="tgl5"></th>
      <th class="date-header" id="tgl6"></th>
      <th class="date-header" id="tgl7"></th>
    </tr>
  </thead>
  <tbody id="jadwal-body">
    <!-- Karyawan diisi dari JS -->
  </tbody>
</table>

<!-- Tombol navigasi minggu -->
<div>
  <button type="button" class="btnweek" onclick="changeWeek(-1)">⏪ Minggu Sebelumnya</button>
  <button type="button" class="btnweek" onclick="changeWeek(1)">Minggu Berikutnya ⏩</button>
</div>



<!-- <script>
let karyawanList = ['Andi', 'Budi', 'Citra'];
let currentWeek = 0; // minggu ke-0 = awal bulan
let month = 5; // Juni (0-indexed, jadi 5)
let year = 2025;

/*var month = new Date().getMonth();
var year = new Date().getFullYear();*/
 
function pilihShift(){

	var bln = document.getElementById('bulan').value;
	var thn = document.getElementById('tahun').value;

	if(bln != '' && thn != ''){
		document.getElementById('shiftModal').style.display = 'block';
	}else{
		alert('Silahkan pilih bulan & tahun');
	}

	
}

// Inisialisasi jadwal
function renderSchedule() {
	/*let month = 5; 
	let year = 2025;*/

	/*var month = new Date().getMonth();
	var year = new Date().getFullYear();*/

	document.getElementById('bulan').value = month;
	document.getElementById('tahun').value = year;


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

  // Buat baris per karyawan
  karyawanList.forEach(nama => {
    let tr = document.createElement('tr');
    tr.innerHTML = `<td><strong>${nama}</strong></td>`;

    for (let i = 0; i < 7; i++) {
      let tgl = document.getElementById("tgl" + (i + 1)).dataset.tgl;
      let td = document.createElement('td');
      td.className = 'drop-cell';
      td.dataset.karyawan = nama;
      td.dataset.tanggal = tgl;

      td.addEventListener('dragover', e => e.preventDefault());
      td.addEventListener('drop', e => {
        e.preventDefault();
        let shift = e.dataTransfer.getData('text/plain');
        td.innerHTML = `<div class="assigned" onclick="deleteShift(this, '${nama}', '${tgl}')">${shift}</div>`;

        // Simpan ke PHP
        /*fetch('save.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: `karyawan=${encodeURIComponent(nama)}&shift=${encodeURIComponent(shift)}&tanggal=${tgl}`
        });*/
      });

      tr.appendChild(td);
    }

    tbody.appendChild(tr);
  });
}

// Navigasi minggu
function changeWeek(delta) {
  const nextStart = new Date(year, month, 1 + (currentWeek + delta) * 7);
  if (nextStart.getMonth() === month) {  // hanya izinkan jika masih dalam bulan yang sama
    currentWeek += delta;
    renderSchedule();
  }
}

// Drag shift
document.querySelectorAll('.shift-btn').forEach(btn => {
  btn.addEventListener('dragstart', e => {
    e.dataTransfer.setData('text/plain', btn.dataset.shift);
  });
});

renderSchedule();



function deleteShift(el, karyawan, tanggal) {
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



function resetToBulanTahun() {
  month = parseInt(document.getElementById('bulan').value);
  year = parseInt(document.getElementById('tahun').value);
  currentWeek = 0;
  renderSchedule();
}


function isValidDate(d) {
  return d instanceof Date && !isNaN(d);
}




let selectedShiftData = {}; 

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



</script> -->

