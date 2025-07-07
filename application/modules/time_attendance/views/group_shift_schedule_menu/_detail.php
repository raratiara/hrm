<label>Month:
  <input type="text" readonly id="bulanViewName" name="bulanViewName" class="form-control" />
  <input type="hidden" id="bulanView" name="bulanView" class="form-control" />
</label>

<label>Year:
  <input type="text" readonly id="tahunView" name="tahunView" class="form-control" />
</label>

<input type="hidden" name="selectedshiftView" id="selectedshiftView">
<input type="hidden" name="hdnjadwalTersimpanView" id="hdnjadwalTersimpanView">


<table class="tblShiftView">
  <thead>
    <tr>
      <th>Employee</th>
      <th class="date-header" id="tglview1"></th>
      <th class="date-header" id="tglview2"></th>
      <th class="date-header" id="tglview3"></th>
      <th class="date-header" id="tglview4"></th>
      <th class="date-header" id="tglview5"></th>
      <th class="date-header" id="tglview6"></th>
      <th class="date-header" id="tglview7"></th>
    </tr>
  </thead>
  <tbody id="jadwal-bodyView">
    <!-- Karyawan diisi dari JS -->
  </tbody>
</table>

<!-- Tombol navigasi minggu -->
<div>
  <button type="button" class="btnweekView" onclick="changeWeekView(-1)">⏪ Previous Week</button>
  <button type="button" class="btnweekView" onclick="changeWeekView(1)">Next Week ⏩</button>
</div>