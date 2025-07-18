


<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bypass extends API_Controller
{
	/* Module */
 	//private $model_name				= "api_model";


 	/* upload */
 	//di LOCAL ->//protected $attachment_folder	= "./uploads/absensi"; 
 	/*protected $attachment_folder	= "hrm.sandboxxplore.com/uploads/absensi"; 
	protected $allow_type			= "gif|jpeg|jpg|png|pdf|xls|xlsx|doc|docx|txt";
	protected $allow_size			= "0";*/ // 0 for limit by default php conf (in Kb)


   	public function __construct()
	{
      	parent::__construct();

		//$this->load->model($this->model_name);
   	}

    public function index()
    {
        $response = [
            'message' => 'Access denied',
            "error" => 'Not allowed root access.'
            ];

		$this->render_json($response, 400);
		exit;
	}


	public function tes(){

		echo 'tesss'; die();

	}


	public function sendmail_reportabsensi()
	{
		echo 'tesss'; die();
		/*error_reporting(E_ALL);
		ini_set('display_errors', 1);*/

	    //$key = random_string('alnum', _ACCOUNT_KEYLENGTH);
	    //$baseurl = 'http://localhost/_hrm';
		$mail = array();
		$mail['subject'] = 'Report Absensi';
		$mail['preheader'] = '';
		$mail['from_name'] = 'HR System';//_MAIL_SYSTEM_NAME;
		$mail['from_email'] = 'noreply-billing@huma.net.id';//_MAIL_SYSTEM_EMAIL;
		$mail['to_name'] = 'HR Team';
		$mail['to_email'] = 'tiarasanir@gmail.com';
		$mail['template'] = 'report-absensi';
		/*$path = WRITEPATH . 'uploads/user_manual_billing.docx';*/
		$path = _URL.'uploads/report_absensi_bulanan/export_absensi_' . date('Y-m') . '.zip'; 
		$mail['attach'] = $path;
		//$mail['key'] = $key;
	    $output = $this->sendmail($mail);
		// if($output){
		// 	echo 'sukses email'; die();
		// }else{
		// 	echo 'gagal email'; die();
		// 	//echo $this->email->print_debugger(['headers']); die();
		// }	


	}


	// For sending email
	private function sendmail($mail)
	{
		/*error_reporting(E_ALL);
		ini_set('display_errors', 1);*/


		//Load email library 
		$this->load->library('email');

		$data = array();
		$data['bln'] = date('F'); // July
		$data['thn'] = date('Y'); // 2025
		$data['preheader'] = $mail['preheader'];
		$data['corp'] = _COMPANY_NAME;
		$data['account_title'] = _ACCOUNT_TITLE;
		$data['link_site'] = _URL;
		$data['link_logo'] = _ASSET_LOGO; //'http://localhost/_hrm/public/assets/images/logo/gerbangdata.jpg';//_ASSET_LOGO;//_ASSET_LOGO_FRONT;
		

		$message = $this->load->view(_TEMPLATE_EMAIL.$mail['template'],$data,TRUE); // load email message using view template
		$cc = 'raratiara02@ymail.com';
		$this->email->from($mail['from_email'], $mail['from_name']); 
		$this->email->to($mail['to_email'], $mail['to_name']);
		$this->email->cc($cc);
		$this->email->subject($mail['subject']); 
		$this->email->message($message); 
		$this->email->attach($mail['attach'],'attachment'); 
	   
		 //Send mail 
		 if($this->email->send()) { echo 'sukses'; die();
			return true; 
		 } else { echo 'gagal'; die();
			return false; 
			//show_error($this->email->print_debugger());
		 }
	}



	/// download report absensi stiap tgl 25 jam 8 pagi
	public function downloadAbsenceReport(){
		error_reporting(E_ALL);
		ini_set('display_errors', 1);


		$dateNow = date('Y-m-d'); //'2025-07-25';

		$timestamp = strtotime($dateNow);
		$yearPrev = date("Y", strtotime("-1 month", $timestamp));
		$monthPrev = date("m", strtotime("-1 month", $timestamp));
		$dateFrom = $yearPrev . '-' . $monthPrev . '-24';
		$dateTo = date('Y-m-24', strtotime($dateNow));

		//tgl 24 bln kemarin SAMPAI tgl 24 bulan ini


		$where_date=" and (a.date_attendance between '".$dateFrom."' and '".$dateTo."') ";
		$filter_periode = $dateNow;
		/*if($_GET['fldatestart'] != '' && $_GET['fldatestart'] != 0 && $_GET['fldateend'] != '' && $_GET['fldateend'] != 0){
			$where_date = " and a.date_attendance between '".$_GET['fldatestart']."' and '".$_GET['fldateend']."' ";
			$filter_periode = $_GET['fldatestart'].' to '.$_GET['fldateend'];
		}*/

		$where_emp=""; 
		/*if($_GET['flemployee'] != '' && $_GET['flemployee'] != 0){
			$where_emp = " and a.employee_id = '".$_GET['flemployee']."' ";
		}*/


		$groupedByDivision = [];
		$emp_absen = $this->db->query("select distinct(a.employee_id), b.division_id from time_attendances a left join employees b on b.id = a.employee_id where b.status_id = 1 ".$where_emp.$where_date." ")->result();
		foreach ($emp_absen as $rowemp_absen) {
		    $groupedByDivision[$rowemp_absen->division_id][] = $rowemp_absen->employee_id;
		}

		$zip = new ZipArchive();
		/*$zipFilename = FCPATH . 'uploads/report_absensi_bulanan/export_absensi_' . date('Ymd_His') . '.zip';*/
		$zipFilename = FCPATH . 'uploads/report_absensi_bulanan/export_absensi_' . date('Y-m') . '.zip';
		$zip->open($zipFilename, ZipArchive::CREATE | ZipArchive::OVERWRITE);


		foreach ($groupedByDivision as $divisionId => $employeeIds) {
			// CLEAR/RESET DATA SEBELUM MENGISI UNTUK DIVISI BERIKUTNYA
    		unset($valSummary, $valrows, $valfooter, $dataSheets);

		    ob_start(); // mulai buffer output

		    /*header("Content-Type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=\"absence_report.xls\"");*/

			echo '<?xml version="1.0"?>
			<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
			 xmlns:o="urn:schemas-microsoft-com:office:office"
			 xmlns:x="urn:schemas-microsoft-com:office:excel"
			 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">
			 <Styles>
			   <Style ss:ID="TitleStyle">
			     <Font ss:Bold="1" ss:Size="14"/>
			   </Style>
			   <Style ss:ID="SubTextStyle">
			     
			   </Style>
			   <Style ss:ID="HeaderStyle">
			     <Font ss:Bold="1"/>
			     <Interior ss:Color="#D3D3D3" ss:Pattern="Solid"/>
			   </Style>
			 </Styles>';


			$dataSheets = [];

			$emp_absen = $this->db->query("select distinct(a.employee_id), b.division_id, c.name as division_name, b.full_name from time_attendances a left join employees b on b.id = a.employee_id left join divisions c on c.id = b.division_id where b.status_id = 1 and b.division_id = '".$divisionId."' ".$where_emp.$where_date." order by b.full_name asc ")->result(); 
			if(count($emp_absen) != 0){ 
				$no=1;
				
				foreach($emp_absen as $rowemp_absen){
					
					$sql = 'select a.*, b.full_name, if(a.is_late = "Y","Late", "") as "is_late_desc", 
								(case 
								when a.leave_type != "" then concat("(",c.name,")") 
								when a.is_leaving_office_early = "Y" then "Leaving Office Early"
								else ""
								end) as is_leaving_office_early_desc
								, d.name as branch_name, e.full_name as direct_name
								,(case when a.leave_absences_id is not null then "1" else "" end) as cuti 
								,(case when a.leave_absences_id is null and a.date_attendance_in is not null then "1" else "" end) as masuk 
								, "" as piket
								,(case when a.leave_absences_id is null and a.date_attendance_in is not null and a.work_location = "wfh" then "1" else "" end) as wfh
								, a.notes as keterangan
								from time_attendances a left join employees b on b.id = a.employee_id
								left join master_leaves c on c.id = a.leave_type
								left join branches d on d.id = b.branch_id
								left join employees e on e.id = b.direct_id
								where a.employee_id = "'.$rowemp_absen->employee_id.'" '.$where_date.'
				   			ORDER BY id ASC
					';

					$res = $this->db->query($sql);
					$data = $res->result();

					
					$ttl_cuti=0; $ttl_masuk=0; $ttl_piket=0; $ttl_wfh=0;
					$valrows=[]; $valfooter=[];
					foreach($data as $rowdata){ 
						
						$valrows[] = [
							$rowdata->date_attendance,
							$rowdata->cuti,
							$rowdata->masuk,
							$rowdata->piket,
							$rowdata->wfh,
							$rowdata->keterangan
						];


						if($rowdata->cuti != ''){
							$ttl_cuti += $rowdata->cuti;
						}
						if($rowdata->masuk != ''){
							$ttl_masuk += $rowdata->masuk;
						}
						if($rowdata->piket != ''){
							$ttl_piket += $rowdata->piket;
						}
						if($rowdata->wfh != ''){
							$ttl_wfh += $rowdata->wfh;
						}

					}

					$valSummary[] = [
						$no,
						$data[0]->full_name,
						$ttl_cuti,
						$ttl_masuk,
						$ttl_piket,
						$ttl_wfh
					];

					$valfooter[] = [
						'Total',
						$ttl_cuti,
						$ttl_masuk,
						$ttl_piket,
						$ttl_wfh
					];
					

					$dataSheets[$data[0]->full_name] = [
				        'title' => 'DATA ABSENSI/ACTIVITY KARYAWAN',
				        'headers' => ['Tanggal', 'Cuti', 'Masuk', 'Piket', 'WFH', 'Keterangan'],
				        'rows' => $valrows, /*[
				            ['2025-06-12', '1', '4', '1', '7', ''],
				            ['2025-06-12', '3', '2', '0', '2', ''],
				        ],*/
				        'subtitle' => [
				        	['Nama', $data[0]->full_name],
				            ['Area', $data[0]->branch_name],
				            ['Leader', $data[0]->direct_name],
				            ['Periode', $filter_periode],
				        ],
				        'footer' => $valfooter
				    ];

				    $no++;
				}


				if($where_emp==""){ //ada sheet summary
					$dataSheets['Summary'] = [
				        'title' => 'DATA ABSENSI/ACTIVITY KARYAWAN',
				        'headers' => ['No', 'Nama', 'Cuti', 'Masuk', 'Piket', 'WFH'],
				        'rows' => $valSummary, 
				        'subtitle' => [
				        	['Division', $rowemp_absen->division_name],
				            ['Area', ''],
				            ['Leader', ''],
				            ['Periode', $filter_periode],
				        ],
				        'footer' => []	    
					];
				}
				
				if($where_emp==""){ //ada sheet summary, tampikan di paling depan
					// Ambil sheet terakhir
					$lastKey = array_key_last($dataSheets);
					$lastSheet = [$lastKey => $dataSheets[$lastKey]];

					// Hapus dari array asli
					unset($dataSheets[$lastKey]);

					// Gabungkan ulang: sheet terakhir jadi pertama
					$dataSheets = $lastSheet + $dataSheets;
				}

			}else{ //tidak ada data
				$dataSheets['Summary'] = [
			        'title' => 'DATA ABSENSI/ACTIVITY KARYAWAN',
			        'headers' => ['No', 'Nama', 'Cuti', 'Masuk', 'Piket', 'WFH'],
			        'rows' => [
				            ['No Data', 'No Data', 'No Data', 'No Data', 'No Data', 'No Data']
				        ], 
			        'subtitle' => [
			            ['Area', ''],
			            ['Leader', ''],
			            ['Periode', $filter_periode],
			        ],
			        'footer' => []	    
				];
			}

			

			foreach ($dataSheets as $sheetName => $sheetData) {
			    echo '<Worksheet ss:Name="' . htmlspecialchars($sheetName) . '">';
			    echo '<Table>';

			    // Tambahkan kata-kata di atas (judul)
			    echo '<Row>';
			    echo '<Cell ss:MergeAcross="' . (count($sheetData['headers']) - 1) . '" ss:StyleID="TitleStyle">';
			    echo '<Data ss:Type="String">' . htmlspecialchars($sheetData['title']) . '</Data>';
			    echo '</Cell>';
			    echo '</Row>';

			    // Kosongkan 1 baris (opsional)
			    echo '<Row></Row><Row></Row>';


				foreach ($sheetData['subtitle'] as $row_S) {
					echo '<Row>';
			        foreach ($row_S as $cell_S) {
			            $type_S = is_numeric($cell_S) ? 'Number' : 'String';
			            echo '<Cell ss:StyleID="SubTextStyle"><Data ss:Type="' . $type_S . '">' . htmlspecialchars($cell_S) . '</Data></Cell>';
			        }
			        echo '</Row>';
				}


				echo '<Row></Row><Row></Row>';


			    // Header
			    echo '<Row>';
			    foreach ($sheetData['headers'] as $headerCell) {
			        echo '<Cell ss:StyleID="HeaderStyle"><Data ss:Type="String">' . htmlspecialchars($headerCell) . '</Data></Cell>';
			    }
			    echo '</Row>';

			    // Data rows
			    foreach ($sheetData['rows'] as $row) {
			        echo '<Row>';
			        foreach ($row as $cell) {
			            $type = is_numeric($cell) ? 'Number' : 'String';
			            echo '<Cell><Data ss:Type="' . $type . '">' . htmlspecialchars($cell) . '</Data></Cell>';
			        }
			        echo '</Row>';
			    }

			    echo '<Row></Row>';
			    foreach ($sheetData['footer'] as $row_F) {
					echo '<Row>';
			        foreach ($row_F as $cell_F) {
			            $type_F = is_numeric($cell_F) ? 'Number' : 'String';
			            echo '<Cell ss:StyleID="SubTextStyle"><Data ss:Type="' . $type_F . '">' . htmlspecialchars($cell_F) . '</Data></Cell>';
			        }
			        echo '</Row>';
				}


			    echo '</Table>';
			    echo '</Worksheet>';
			}

			echo '</Workbook>';


			$divname = strtolower(trim($rowemp_absen->division_name));
			$words = explode(' ', $divname);
			if (count($words) > 1) {
				$divname = str_replace(" ","_",$divname);
			}

		    // Di akhir:
		    $content = ob_get_clean(); // ambil isi output
		    $filename = "absensi_division_" . $divname . ".xls";
		    $zip->addFromString($filename, $content);
		}

		$zip->close();

		if (file_exists($zipFilename)) {
			$this->sendmail_reportabsensi();
		} else {
		    echo "Gagal menyimpan file ZIP.";
		}

		/*header('Content-Type: application/zip');
		header('Content-disposition: attachment; filename=' . basename($zipFilename));
		header('Content-Length: ' . filesize($zipFilename));*/
		//readfile($zipFilename); //hilangkan download di browser
		//unlink($zipFilename); // hapus file zip setelah diunduh (opsional)
		//exit;



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
			$rowEmp = $this->db->query("select * from employees where status_id = 1")->result();

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



		$emp = $this->db->query("select * from employees where status_id = 1")->result();

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
					/*$dt = $this->db->query("select a.*, b.periode
							, b.`".$tgl."` as 'shift' 
							, c.time_in, c.time_out, c.name 
							from shift_schedule a
							left join group_shift_schedule b on b.id = a.group_shift_schedule_id 
							left join master_shift_time c on c.id = b.`".$tgl."`
							where a.employee_id = '".$row_emp->id."' and b.periode = '".$period."' ")->result(); */

					$dt = $this->db->query("select a.*, b.`".$tgl."` as 'shift', c.time_in, c.time_out, c.name 
						from shift_schedule a left join group_shift_schedule b on b.shift_schedule_id = a.id left join master_shift_time c on c.id = b.`".$tgl."`
						where b.employee_id = '".$row_emp->id."' and a.period = '".$period."' ")->result(); 

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
