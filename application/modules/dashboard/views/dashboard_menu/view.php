

	
	<!-- <div class="chart_monthly_att_summ">
	    <h2>Monthly Attendance Summary</h2>
	    <canvas id="monthly_att_summ"></canvas>
  	</div> -->

  	

	<!-- <div class="dashboard">
		<div class="info-box" style="width:180px; height: 60px;">
	      <div class="info-title">Select Date</div>
	      <div class="info-value"> </div>
	      <div class="info-footer"></div>
	    </div>
		<div class="info-box" style="width:180px; height: 60px">
	      <div class="info-title">Select Employee</div>
	      <div class="info-value"> </div>
	      <div class="info-footer"></div>
	    </div>
	    <div class="info-box" style="width:180px; height: 60px">
	      <div class="info-title">Total Employee</div>
	      <div class="info-value"> </div>
	      <div class="info-footer"></div>
	    </div>
	    <div class="info-box" style="width:180px; height: 60px">
	      <div class="info-title">Projects</div>
	      <div class="info-value"> </div>
	      <div class="info-footer"></div>
	    </div>
	    <div class="info-box" style="width:180px; height: 100px">
	      <div class="info-title">Attendance Percentage</div>
	      <div class="info-value"> </div>
	      <div class="info-footer"></div>
	    </div> -->


	    <!-- <div class="info-box">
	      <div class="info-title">Total Employee</div>
	      <div class="info-value">12,450 </div>
	      <div class="info-footer">Updated 5 mins ago</div>
	    </div>

	    <div class="info-box">
	      <div class="info-title">Projects</div>
	      <div class="info-value">340 </i></div>
	      <div class="info-footer"></div>
	    </div>
	    
	    <div class="info-box">
	      <div class="info-title">Attendance</div>
	      <div class="info-value">2,345</div>
	      <div class="info-footer">This month</div>
	    </div>

	    <div class="info-box">
	      <div class="info-title">Leave Taken</div>
	      <div class="info-value">895</div>
	      <div class="info-footer"></div>
	    </div>

	    <div class="info-box">
	      <div class="info-title">Reimbursement Amount</div>
	      <div class="info-value">Rp 121.750.000 </div>
	      <div class="info-footer">This month</div>
	    </div>

	    <div class="info-box">
	      <div class="info-title">Overtime</div>
	      <div class="info-value">327 hrs </div>
	      <div class="info-footer"></div>
	    </div> -->
	    
  	<!-- </div> -->




  	<!-- <div class="chart_att_statistic">
	    <h2>Attendance Statistics</h2>
	    <canvas id="att_statistic"></canvas>
  	</div>


  	<div class="chart_empbydeptgender">
	    <h2>Employees by Department & Gender</h2>
	    <canvas id="empby_dept_gender"></canvas>
  	</div>


  	
	<div class="chart_empbygen">
	    <h2>Employees by Generation</h2>
	    <canvas id="empby_gen"></canvas>
  	</div> -->


 	<!-- <div class="chart_attpercentage">
	    <h2>Attendance Percentage</h2>
	    <canvas id="att_percentage"></canvas>
  	</div> -->


  	<div class="dashboard-grid">

	  	<div class="box box-1"> 
	  		<div class="box-title">Select Datee</div>
	      	<div class="box-value">
	      		<!-- <input type="text" style="width: 150px; height:20px; font-size: 10px;" class="form-control" id="fldashdateperiod" name="fldashdateperiod"> -->
	      		<input type="month" style="width: 150px; height:20px; font-size: 10px;" id="fldashdateperiod" name="fldashdateperiod" class="form-control">
	      	</div>
	      	<div class="info-footer"></div>
	  	</div>

	  <div class="box box-2">
	  	<div class="box-title">Select Employee</div>
	      	<div class="box-value">
	      		<!-- <?=$selemp?> -->
	      		<select style="width: 130px; height:20px; font-size: 10px;" id="fldashemp" name="fldashemp">
	      			<option></option>
	      			<?php
	      			foreach($master_emp as $row){
	      				?>
	      				<option value="<?=$row->id?>"><?=$row->full_name?></option>
	      				<?php
	      			}
	      			?>
	      			
	      		</select>
	      	</div>
	      	<div class="info-footer"></div>
	  </div>

	  <div class="box box-3">
	  	<div class="box-title">Total Employee</div>
      	<div class="box-value"><span id="ttl_employee"></span> </div>
      	<!-- <div class="box-footer">Updated 5 mins ago</div> -->
	  </div>

	  <div class="box box-4">
	  	<div class="box-title">Projects</div>
	    <div class="box-value"><span id="ttl_projects"></span> </div>
	    <!-- <div class="info-footer"></div> -->
	  </div>

	  <div class="box box-5">
	  	<div class="box-title">Attendance Percentage</div>
	    <div class="box-value">
	    	<canvas id="att_percentage"></canvas>
	    </div>
	  </div>

	  <div class="box box-6">
	  	<div class="box-title">Monthly Attendance Summary</div>
	    <div class="box-value">
	    	<canvas id="monthly_att_summ"></canvas>
	    </div>
	  </div>

	  <div class="box box-7">
	  	<div class="box-title">Attendance</div>
      	<div class="box-value"><span id="ttl_attendance"></span></div>
      	<!-- <div class="info-footer">This month</div> -->
	  </div>

	  <div class="box box-8">
	  	<div class="box-title">Reimbursement Amount</div>
      	<div class="box-value"><span id="ttl_reimbursement"></span> </div>
      	<!-- <div class="info-footer">This month</div> -->
	  </div>

	  <div class="box box-9">
	  	<div class="box-title">Leave Taken</div>
      	<div class="box-value"><span id="ttl_leave"></span></div>
      	<!-- <div class="info-footer"></div> -->
	  </div>

	  <div class="box box-10">
	  	<div class="box-title">Overtime</div>
      	<div class="box-value"><span id="ttl_overtime"></span> </div>
      	<!-- <div class="info-footer"></div> -->
	  </div>

	  <div class="box box-11">
	  	<div class="box-title">Worked Hours Percentage</div>
	    <div class="box-value">
	    	<canvas id="workhrs_percentage"></canvas>
	    </div>
	  </div>

	  <div class="box box-12"></div>

	  <div class="box box-13"></div>

	  <div class="box box-14">
  		<div class="box-title">Attendance Statistics</div>
	    <div class="box-value">
	    	<canvas id="att_statistic"></canvas>
	    </div>
	  </div>

	  <div class="box box-15">
	  	<div class="box-title">Employees by Department & Gender</div>
	    <div class="box-value">
	    	<canvas id="empby_dept_gender"></canvas>
	    </div>
	  </div>

	  <div class="box box-16">
	  	<div class="box-title">Employees by Generation</div>
	    <div class="box-value">
	    	<canvas id="empby_gen"></canvas>
	    </div>
	  </div>
	 
	</div>



	<!-- <div class="dashboard-flex">
	  
	  <div class="column">
	    <div class="box box-1">Box 1</div>
	    <div class="box box-6">Box 6</div>
	    <div class="box box-11">11</div>
	  </div>

	  <div class="column">
	    <div class="box box-2">Box 2</div>
	    <div class="box box-7">Box 7</div>
	    <div class="box box-12">Box 12</div>
	  </div>

	  <div class="column">
	    <div class="box box-3">Box 3</div>
	    <div class="box box-8">Box 8</div>
	    <div class="box box-13">Box 13</div>
	    <div class="box box-16">Box 16</div>
	  </div>

	  <div class="column">
	    <div class="box box-4">Box 4</div>
	    <div class="box box-9">Box 9</div>
	    <div class="box box-14">Box 14</div>
	  </div>

	  <div class="column">
	    <div class="box box-5">Box 5</div>
	    <div class="box box-10">Box 10</div>
	    <div class="box box-15">Box 15</div>
	  </div>
	</div>
 -->










