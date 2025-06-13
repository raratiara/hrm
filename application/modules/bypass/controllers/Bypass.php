<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bypass extends MY_Controller
{
	/* Module */
 	//private $model_name				= "bypass_model";

   	public function __construct()
	{
      	parent::__construct();

		//$this->load->model($this->model_name);
   	}
	

	// cron jalan setiap hari di jam 08.00 pagi
	public function generate_jatah_cuti(){
		//generate h+1 dr period end

		$dateNow = date("Y-m-d");
		$dateYesterday = date('Y-m-d', strtotime('-1 day', strtotime($dateNow)) );


		$rs = $this->db->query("select * from total_cuti_karyawan where period_end = '".$dateYesterday."' ")->result(); 

		if(!empty($rs)){
			$data_generate = array();
			foreach ($rs as $row) {
				$cek = $this->db->query("select * from total_cuti_karyawan where employee_id = '".$row->employee_id."' and period_start = '".$dateNow."' ")->result(); 
				if(empty($cek)){
					$period_end = date('Y-m-d', strtotime('+1 year', strtotime($dateNow)) );
					$data = [

							'employee_id' 	=> $row->employee_id,
							'period_start' 	=> $dateNow,
							'period_end' 	=> $period_end,
							'sisa_cuti' 	=> 12,
							'status' 		=> 1,
							'created_date'	=> date("Y-m-d H:i:s")
							
						];

					$exec = $this->db->insert('total_cuti_karyawan', $data);

					if($exec){
						$exp_date = date('Y-m-d', strtotime('+6 month', strtotime($dateNow)) );

						$data2 = [

							'expired_date' 	=> $exp_date,
							'updated_date'	=> date("Y-m-d H:i:s")
							
						];
						$this->db->update('total_cuti_karyawan', $data2, "id = '".$row->id."'");

						$result['employee_id'] = $row->employee_id;
    					$data_generate[] = $result;
					}
				}

			}
			print_r($data_generate); die();
		}else{
			echo 'Tidak ada data yg di generate'; die();
		}

	}


	// cron jalan setiap hari di jam 08.00 pagi
	public function update_status_jatah_cuti(){

		$dateNow = date("Y-m-d");

		$rs = $this->db->query("select * from total_cuti_karyawan where expired_date = '".$dateNow."' ")->result(); 

		if(!empty($rs)){
			$data_update = array();
			foreach ($rs as $row) {
				$data = [
					'status' 		=> 0,
					'updated_date'	=> date("Y-m-d H:i:s")
				];
				$this->db->update('total_cuti_karyawan', $data, "id = '".$row->id."'");

				$result['employee_id'] = $row->employee_id;
				$data_update[] = $result;
			}

			print_r($data_update); die();
		}
		else{
			echo 'Tidak ada data yg di update'; die();
		}

	}


	public function generate_jatah_cuti_karyawan_baru(){
		$employee_id 	= $_GET['empid'];
		$period_start 	= $_GET['datestart'];

		if(!empty($employee_id) && !empty($period_start)){
			$rs = $this->db->query("select * from total_cuti_karyawan where employee_id = '".$employee_id."' ")->result(); 

			if(empty($rs)){
				$period_end = date('Y-m-d', strtotime('+1 year', strtotime($period_start)) );
				$data = [
						'employee_id' 	=> $employee_id,
						'period_start' 	=> $period_start,
						'period_end' 	=> $period_end,
						'sisa_cuti' 	=> 12,
						'status' 		=> 1,
						'created_date'	=> date("Y-m-d H:i:s")
					];

				$exec = $this->db->insert('total_cuti_karyawan', $data);
				echo 'Sukses Generate [Employee ID: '.$employee_id.']'; die();
			}
			else{
				echo 'Gagal Generate'; die();
			}
		}
		else{
			echo 'Gagal Generate. Data sudah ada'; die();
		}

	}


	public function submit_absen_holiday(){ //jalanin setiap hari jam 8 pagi

		$Holidays = $this->db->query("select * from master_holidays where date = '".date("Y-m-d")."'")->result();
		$holID = $Holidays[0]->id;

		if(!empty($Holidays)){
			$rowEmp = $this->db->query("select * from employees")->result();

			if(!empty($rowEmp)){ 
				foreach($rowEmp as $row){

					$data = [
						'date_attendance' 			=> date("Y-m-d"),
						'employee_id' 				=> $row->id,
						'attendance_type' 			=> $row->shift_type,
						'created_at'				=> date("Y-m-d H:i:s"),
						'holidays_id' 				=> $holID
					];
					$this->db->insert("time_attendances", $data);

					echo 'Data Absen Holiday di submit, employee ID ='.$row->id.' </br>'; 
				}

				
			}else{
				echo 'Tidak ada data yg disubmit'; die();
			}
		}else{
			echo 'Tidak ada data yg disubmit'; die();
		}


	}


	public function submit_daily_absen(){ // jalan setiap hari, jam 8 pagi
		$tanggal = date('Y-m-d');
		$yesterday = date('Y-m-d', strtotime('-1 day', strtotime($tanggal)));
		$period = date("Y-m", strtotime($yesterday));
		$tgl = date("d", strtotime($yesterday));

		
		$hari = date('w', strtotime($yesterday)); // 0 = Minggu, 6 = Sabtu
		$is_sabtuminggu = 0;
		if ($hari == 0 || $hari == 6) {
		   $is_sabtuminggu = 1;
		} 



		$emp = $this->db->query("select * from employees")->result();

		foreach($emp as $row_emp){
			$absen = $this->db->query("select * from time_attendances where date_attendance = '".$yesterday."' and employee_id = '".$row_emp->id."'")->result();

			if(count($absen) == 0){
				$emp_shift_type=1; $reguler_sabtuminggu=0;
				if($row_emp->shift_type == 'Reguler'){
					$dt = $this->db->query("select * from master_shift_time where shift_type = 'Reguler' ")->result(); 
					if($is_sabtuminggu == 1){
						$reguler_sabtuminggu=1;
					}
					
				}else if($row_emp->shift_type == 'Shift'){
					$dt = $this->db->query("select a.*, b.periode
							, b.`".$tgl."` as 'shift' 
							, c.time_in, c.time_out, c.name 
							from shift_schedule a
							left join group_shift_schedule b on b.id = a.group_shift_schedule_id 
							left join master_shift_time c on c.id = b.`".$tgl."`
							where a.employee_id = '".$row_emp->id."' and b.periode = '".$period."' ")->result(); 

				}else{ //tidak ada shift type
					$emp_shift_type=0;
				} 

				$attendance_type=""; $time_in=""; $time_out="";
				if($emp_shift_type==1){
					$attendance_type 	= $dt[0]->name;
					if($reguler_sabtuminggu!=1){
						$time_in 	= $dt[0]->time_in;
						$time_out 	= $dt[0]->time_out;
					}
				}


				$data = [
					'date_attendance' 			=> $yesterday,
					'employee_id' 				=> $row_emp->id,
					'attendance_type' 			=> $attendance_type,
					'time_in' 					=> $time_in,
					'time_out' 					=> $time_out,
					'created_at'				=> date("Y-m-d H:i:s")
				];
				$rs = $this->db->insert('time_attendances', $data);
			}
			
		}



	}


}
