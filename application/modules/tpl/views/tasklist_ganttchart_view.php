<!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script> -->

<div style="background-color:white; display:flex; flex-direction:column; align-items:center; margin: 20px auto;">
    
  

 <!--  <div style="width:98%; text-align:right; margin-top:10px;">
    <label style="margin-right:6px; font-weight:600;">View:</label>
    <select id="viewModeSelect" class="form-select form-select-sm" style="width:auto; display:inline-block;">
      <option value="Day">Day</option>
      <option value="Week" selected>Week</option>
      <option value="Month">Month</option>
    </select>
  </div> -->

  <div style="width:98%; display:flex; justify-content:space-between; align-items:flex-start; margin-top:10px;">
    
    <!-- Kiri: Filter Employee & Project -->
    <div style="display:grid; grid-template-columns:100px 200px; row-gap:6px; column-gap:8px; align-items:center;">
      <label for="employeeFilter" style="font-weight:600; text-align:right;">Employee:</label>
      <?=$selflemployee?>
      <!-- <select id="employeeFilter" class="form-select form-select-sm" style="width:100%;">
        <option value="">All</option>
        <option value="1">John Doe</option>
        <option value="2">Jane Smith</option>
        <option value="3">Michael Lee</option>
      </select> -->

      <label for="projectFilter" style="font-weight:600; text-align:right;">Project:</label>
      <?=$selflproject?>
      <!-- <select id="projectFilter" class="form-select form-select-sm" style="width:100%;">
        <option value="">All</option>
        <option value="P1">Project Alpha</option>
        <option value="P2">Project Beta</option>
        <option value="P3">Project Gamma</option>
      </select> -->
    </div>

    <!-- Kanan: View Mode -->
    <div style="display:flex; align-items:center; gap:8px;">
      <label style="font-weight:600;">View:</label>
      <select id="viewModeSelect" class="form-select form-select-sm" style="width:auto; display:inline-block;">
        <option value="Day">Day</option>
        <option value="Week" selected>Week</option>
        <option value="Month">Month</option>
      </select>
    </div>
  </div>



  <div style="width:98%; overflow:auto; margin: 20px auto;">
    <!-- <h4 style="text-align:center;margin-bottom:10px;">title</h4> -->
    <div id="gantt"></div>
  </div>

  <!-- <button onclick="addRow()">➕ Add Row</button> -->
  <!-- <table id="taskTable" class="table table-bordered table-striped mt-3" style="width:98%; margin: 0 auto;"> -->
  <span style="font-size: 10px; color: red;">*Data will be saved if the <b>task</b>, <b>status</b> and <b>due date</b> are filled in.</span>
  <table id="taskTable" class="display table table-bordered table-striped" style="width:98% !important; overflow:auto !important; ">
    <thead>
      <tr>
        <th>ID</th>
        <th>Employee</th>
        <th style="width: 100px !important;">Task</th>
        <th>Status</th>
        <th style="width: 5px !important;">Progress (%)</th>
        <!-- <th>Request Date</th>
        <th>Progress Date</th>
        <th>Close Date</th> -->
        <th style="width: 5px !important;">Due Date</th>
        <th>Project</th>
        <th style="width: 5px !important;">Task Parent</th>
        <th>Description</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>

</div>






