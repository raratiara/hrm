<style>
  .tblShiftView {
    width: 100%;
    border-collapse: collapse;
  }

  .tblShiftView th,
  .tblShiftView td {
    border: 1px solid #ccc;
    padding: 6px;
    text-align: center;
  }

  .table-responsive {
    width: 100%;
    overflow-x: auto;
  }

  /* Responsif untuk layar <= 768px (tablet & hp) */
  @media (max-width: 768px) {
    label {
      display: block;
      margin-bottom: 8px;
    }

    label input {
      width: 100%;
      font-size: 12px;
      padding: 4px;
    }

    .tblShiftView th,
    .tblShiftView td {
      font-size: 12px;
      padding: 4px;
    }

    .btnweekView {
      font-size: 12px;
      padding: 4px 8px;
      margin-top: 4px;
    }
  }

  /* Responsif ekstra kecil <= 480px (hp kecil) */
  @media (max-width: 480px) {

    .tblShiftView th,
    .tblShiftView td {
      font-size: 10px;
      padding: 3px;
    }

    label {
      font-size: 12px;
    }

    label input {
      font-size: 10px;
    }

    .btnweekView {
      font-size: 10px;
      padding: 3px 6px;
    }
  }
</style>

<label>Month:
  <input type="text" readonly id="bulanViewName" name="bulanViewName" class="form-control" />
  <input type="hidden" id="bulanView" name="bulanView" class="form-control" />
</label>

<label>Year:
  <input type="text" readonly id="tahunView" name="tahunView" class="form-control" />
</label>

<input type="hidden" name="selectedshiftView" id="selectedshiftView">
<input type="hidden" name="hdnjadwalTersimpanView" id="hdnjadwalTersimpanView">

<div class="table-responsive">
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
</div>

<!-- Tombol navigasi minggu -->
<div>
  <button type="button" class="btnweekView" onclick="changeWeekView(-1)">⏪ Previous Week</button>
  <button type="button" class="btnweekView" onclick="changeWeekView(1)">Next Week ⏩</button>
</div>