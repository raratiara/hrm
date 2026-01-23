



<!-- Modal Pop-Up -->
<div id="shiftModal" class="custom-modal">
  <div class="custom-modal-content">
    <span class="custom-close" onclick="closeShiftModal()">&times;</span>
    <h3 style="font-size: 14px;"><b>Drag Shift</b></h3>

    <!-- Tombol Shift -->
    <div class="shift-options">
     
      <span class="shift-btn shift1" draggable="true" data-shift="Shift 1" ondragstart="drag(event)">Shift 1</span>
      <span class="shift-btn shift2" draggable="true" data-shift="Shift 2" ondragstart="drag(event)">Shift 2</span>
      <span class="shift-btn shift3" draggable="true" data-shift="Shift 3" ondragstart="drag(event)">Shift 3</span>

    </div>

  
  </div>
</div>




<label>Month:
  
  <select id="bulan" name="bulan" class="form-control">
    <script>
      for (let i = 0; i < 12; i++) {
        
        const monthName = new Date(0, i).toLocaleString('en-US', { month: 'long' });
        document.write(`<option value="${i}">${monthName}</option>`);
      }
    </script>
  </select>
</label>

<label>Year:
 
  <select id="tahun" name="tahun" class="form-control">
    <script>
      const now = new Date().getFullYear();
      for (let y = now - 1; y <= now + 1; y++) {
        document.write(`<option value="${y}" ${y === now ? 'selected' : ''}>${y}</option>`);
      }
    </script>
  </select>
</label>

<input type="hidden" name="selectedshift" id="selectedshift">
<input type="hidden" name="hdnjadwalTersimpan" id="hdnjadwalTersimpan">


<div>
  <button type="button" class="btnpilihshift" id="btnpilihshift" onclick="pilihShift();"> Select Shift</button>
</div>



<!-- Tabel -->
<div style="overflow-x: auto; width: 100%;">
  <table class="tblShift">
    <thead>
      <tr>
        <th>Employee</th>
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
</div>

<!-- Tombol navigasi minggu -->
<div>
  <button type="button" class="btnweek" onclick="changeWeek(-1)">⏪ Previous Week</button>
  <button type="button" class="btnweek" onclick="changeWeek(1)">Next Week ⏩</button>
</div>

