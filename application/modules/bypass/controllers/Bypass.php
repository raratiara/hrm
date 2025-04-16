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
	

	// cron jalan setiap hari di jam 06.00 pagi
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


	// cron jalan setiap hari di jam 06.00 pagi
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


}
