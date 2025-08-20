<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tidakabsenmasuk extends MY_Controller
{
	/* Module */
 	const  LABELMODULE				= "tidakabsenmasuk"; // identify menu
 	const  LABELMASTER				= "Menu Data Tidak Absen Masuk";
 	const  LABELFOLDER				= "check_absensi"; // module folder
 	const  LABELPATH				= "tidakabsenmasuk"; // controller file (lowercase)
 	const  LABELNAVSEG1				= "check_absensi"; // adjusted 1st sub parent segment
 	const  LABELSUBPARENTSEG1		= "Master"; // 
 	const  LABELNAVSEG2				= ""; // adjusted 2nd sub parent segment
 	const  LABELSUBPARENTSEG2		= ""; // 
	
	/* View */
	public $icon 					= 'fa-database';
	public $tabel_header 			= ["Employee Name","Absence Type","Time In","Time Out"];

	
	/* Export */
	public $colnames 				= ["Employee Name","Absence Type"];
	public $colfields 				= ["full_name","shift_type"];

	/* Form Field Asset */
	public function form_field_asset()
	{
		$datetimeNow = date("Y-m-d H:i:s");
		$dateNow = date("Y-m-d");
		$period = date("Y-m");
		$tgl = date("d");
		$date_attendance = $dateNow;
		$dateTomorrow='';


		$getdata = $this->db->query("select * from user where user_id = '".$_SESSION['id']."'")->result(); 
		$karyawan_id = $getdata[0]->id_karyawan;
		/*$whr='';
		if($getdata[0]->id_groups != 1 && $getdata[0]->id_groups != 4){ //bukan super user && bukan HR admin
			$whr=' and id = "'.$karyawan_id.'" or direct_id = "'.$karyawan_id.'" ';
		}*/

		$empData = $this->db->query("select full_name, shift_type from employees where id = '".$karyawan_id."'")->result(); 
		$emp_shift_type=1; $time_in=""; $time_out=""; $attendance_type="";
		if($empData[0]->shift_type == 'Reguler'){
			$dt = $this->db->query("select * from master_shift_time where shift_type = 'Reguler' ")->result(); 
			
		}else if($empData[0]->shift_type == 'Shift'){
			$data_attendances = $this->db->query("select * from time_attendances where date_attendance = '".$dateNow."' and employee_id = '".$karyawan_id."'")->result(); 
			//jika sudah ada absen hari ini, maka akan cek shift besok, kalau dapet shift 3, maka bisa checkin. Karna shift 3 jadwalnya tengah malam, jadi bisa checkin di tgl sebelumnya.
			if((!empty($data_attendances)) && $data_attendances[0]->date_attendance_in != null && $data_attendances[0]->date_attendance_in != '0000-00-00 00:00:00' && $data_attendances[0]->date_attendance_out != null && $data_attendances[0]->date_attendance_out != '0000-00-00 00:00:00'){

				$dateTomorrow = date("Y-m-d", strtotime($dateNow . " +1 day"));
				$period  = date('Y-m', strtotime($dateTomorrow));
				$tgl = date('d', strtotime($dateTomorrow));
			}

			$dt = $this->db->query("select a.*, b.periode, b.`".$tgl."` as 'shift', c.time_in, c.time_out, c.name 
					from shift_schedule a left join group_shift_schedule b on b.shift_schedule_id = a.id 
					left join master_shift_time c on c.shift_id = b.`".$tgl."`
					where b.employee_id = '".$karyawan_id."' and a.period = '".$period."' ")->result(); 
			
			if($dt[0]->shift != 3){ //bukan shift 3, tidak bisa checkin di tgl sebelumnya
				//$emp_shift_type=0;
				$period = date("Y-m");
				$tgl = date("d");
				$dt = $this->db->query("select a.*, b.periode, b.`".$tgl."` as 'shift', c.time_in, c.time_out, c.name 
					from shift_schedule a left join group_shift_schedule b on b.shift_schedule_id = a.id 
					left join master_shift_time c on c.shift_id = b.`".$tgl."`
					where b.employee_id = '".$karyawan_id."' and a.period = '".$period."' ")->result();

			}else{
				$date_attendance = $dateTomorrow;
			}

		}else{ //tidak ada shift type
			$emp_shift_type=0;
		} 

		if($emp_shift_type==1){
			$time_in 			= $dt[0]->time_in;
			$time_out 			= $dt[0]->time_out;
			$attendance_type 	= $dt[0]->name;
		}



		$field = [];
		$field['empid'] = $karyawan_id; 	
		/*$field['txtdateattendance']		= $this->self_model->return_build_txt('','date_attendance','date_attendance');*/
		$field['txtdateattendance']		= $this->self_model->return_build_txt($date_attendance,'date_attendance','date_attendance','','','readonly');
		/*$msemp 							= $this->db->query("select * from employees where status_id = 1 ".$whr." order by full_name asc")->result(); */
		/*$field['selemployee'] 			= $this->self_model->return_build_select2me($msemp,'','','','employee','employee','','','id','full_name',' ','','','',3,'-');*/
		$field['selemployee'] 			= $this->self_model->return_build_txt($empData[0]->full_name,'employee','employee','','','readonly');
		/*$field['txtimein'] 				= $this->self_model->return_build_txt('','time_in','time_in','','','readonly');*/
		$field['txtimein'] 				= $this->self_model->return_build_txt($time_in,'time_in','time_in','','','readonly');
		$field['txtattendancein'] 		= $this->self_model->return_build_txt($datetimeNow,'attendance_in','attendance_in','','','readonly');
		$field['txtlatedesc'] 			= $this->self_model->return_build_txt('','late_desc','late_desc','','','readonly');
		/*$field['txtemptype'] 			= $this->self_model->return_build_txt('','emp_type','emp_type','','','readonly');*/
		$field['txtemptype'] 			= $this->self_model->return_build_txt($attendance_type,'emp_type','emp_type','','','readonly');
		/*$field['txtimeout'] 			= $this->self_model->return_build_txt('','time_out','time_out','','','readonly');*/
		$field['txtimeout'] 			= $this->self_model->return_build_txt($time_out,'time_out','time_out','','','readonly');
		/*$field['txtattendanceout'] 		= $this->self_model->return_build_txt('','attendance_out','attendance_out');*/
		$field['txtattendanceout'] 		= $this->self_model->return_build_txt('','attendance_out','attendance_out','','','readonly');
		$field['txtleavingearlydesc']	= $this->self_model->return_build_txt('','leaving_early_desc','leaving_early_desc','','','readonly');
		$field['txtdesc'] 				= $this->self_model->return_build_txtarea('','description','description');


		$raw = [
		    ['id' => 'wfo', 'name' => 'WFO'],
		    ['id' => 'wfh', 'name' => 'WFH'],
		    ['id' => 'onsite', 'name' => 'On Site']
		];
		$msLoc = [];
		foreach ($raw as $row_raw) {
		    $obj = new stdClass();
		    $obj->id = $row_raw['id'];
		    $obj->name = $row_raw['name'];
		    $msLoc[] = $obj;
		}
		$field['selloc'] 			= $this->self_model->return_build_select2me($msLoc,'','','','location','location','','','id','name',' ','','','',3,'-');



		
		return $field;
	}

	//========================== Considering Already Fixed =======================//
 	/* Construct */
	public function __construct() {
        parent::__construct();
		# akses level
		$akses = $this->self_model->user_akses($this->module_name);
		define('_USER_ACCESS_LEVEL_VIEW',$akses["view"]);
		define('_USER_ACCESS_LEVEL_ADD',$akses["add"]);
		define('_USER_ACCESS_LEVEL_UPDATE',$akses["edit"]);
		define('_USER_ACCESS_LEVEL_DELETE',$akses["del"]);
		define('_USER_ACCESS_LEVEL_DETAIL',$akses["detail"]);
		define('_USER_ACCESS_LEVEL_IMPORT',$akses["import"]);
		define('_USER_ACCESS_LEVEL_EKSPORT',$akses["eksport"]);
    }

	/* Module */
 	public $folder_name				= self::LABELFOLDER."/".self::LABELPATH; // module path
 	public $module_name				= self::LABELMODULE;
 	public $model_name				= self::LABELPATH."_model";

	/* Navigation */
 	public $parent_menu				= self::LABELFOLDER;
 	public $subparent_menu			= self::LABELNAVSEG1;
 	public $subparentitem_menu		= self::LABELNAVSEG2;
 	public $sub_menu 				= self::LABELMODULE;

	/* Label */
 	public $label_parent_modul		= self::LABELFOLDER;
 	public $label_subparent_modul	= self::LABELSUBPARENTSEG1;
 	public $label_subparentitem_modul	= self::LABELSUBPARENTSEG2;
 	public $label_modul				= self::LABELMASTER;
 	public $label_list_data			= "Daftar Data ".self::LABELMASTER;
 	public $label_add_data			= "Tambah Data ".self::LABELMASTER;
 	public $label_update_data		= "Edit Data ".self::LABELMASTER;
 	public $label_sukses_disimpan 	= "Data berhasil disimpan";
 	public $label_gagal_disimpan 	= "Data gagal disimpan";
 	public $label_delete_data		= "Hapus Data ".self::LABELMASTER;
 	public $label_sukses_dihapus 	= "Data berhasil dihapus";
 	public $label_gagal_dihapus 	= "Data gagal dihapus";
 	public $label_detail_data		= "Datail Data ".self::LABELMASTER;
 	public $label_import_data		= "Import Data ".self::LABELMASTER;
 	public $label_sukses_diimport 	= "Data berhasil diimport";
 	public $label_gagal_diimport 	= "Import data di baris : ";
 	public $label_export_data		= "Export";
 	public $label_gagal_eksekusi 	= "Eksekusi gagal karena ketiadaan data";

	//============================== Additional Method ==============================//


 	public function getDataEmp(){
		$post = $this->input->post(null, true);
		$empid = $post['empid'];

		$rs =  $this->self_model->getDataEmployee($empid);
		

		echo json_encode($rs);
	}

	

	public function startDevice(){

		$token = "I4EMpL6raAUKRgQtZ2FshcC8mof35zkq0TG9ViO7";


		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://dash.pushwa.com/api/startDevice',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_POSTFIELDS => json_encode([
		    'token' => $token
		  ]),
		  CURLOPT_HTTPHEADER => array(
		    'Content-Type: application/json'
		  ),
		));

		$response = curl_exec($curl);

		curl_close($curl);

		$parse = json_decode($response, true);

		if(isset($parse)){
			$status 	= $parse['status'];
			$message 	="";
			if(isset($parse['message'])){
				$message = $parse['message'];
			}
			if(isset($parse['qr'])){
				$message = "Please scan qr again";
			}
		}

		$data = [
			'name' 		=> "Reminder Absensi - startDevice",
			'status' 	=> $status,
			'notes' 	=> $message,
			'datetime'	=> date("Y-m-d H:i:s")
		];
		$this->db->insert('log_send_wa', $data);

		$stsData = [
			'status' 	=> $status,
			'msg' 		=> $message
		];

		return $stsData; 

		
		/*if ( isset($parse['qr']) ) {
		  echo 'https://api.qrserver.com/v1/create-qr-code/?data='.urlencode($parse['qr']);
		}*/

	}


	public function checkStatusSendWA($token, $idMsgString){
	/*public function checkStatusSendWA(){
		$token="I4EMpL6raAUKRgQtZ2FshcC8mof35zkq0TG9ViO7";
		$idMsgString="653441,653442,653443";*/
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://dash.pushwa.com/api/statusMessage',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_POSTFIELDS =>'{
		    "token": "'.$token.'",
		    "idMsg" : "'.$idMsgString.'"
		}',
		  CURLOPT_HTTPHEADER => array(
		    'Content-Type: application/json'
		  ),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		return $response;

	}


	public function sendWAReminder(){
		$post = $this->input->post(null, true);
		$listunCheck = isset($post['listunCheck']) ? trim($post['listunCheck']) : '';


		$sts_startDevice = $this->startDevice();

		
		if(isset($sts_startDevice)){
			if($sts_startDevice['status'] == '1' && $sts_startDevice['msg'] == 'connected'){ 

				//kirim reminder absen
				$dateNow = date("Y-m-d");
				$sql = "
			        select id, full_name, nick_name, shift_type, personal_phone 
			        FROM employees 
			        WHERE status_id = 1
			          AND id NOT IN (
			              SELECT employee_id 
			              FROM time_attendances 
			              WHERE date_attendance = '".$dateNow."'
			          ) 
			    ";
			    // tambahkan kondisi kalau listunCheck tidak kosong
			    if ($listunCheck !== '') {
			        $sql .= " AND id NOT IN (".$listunCheck.")";
			    }

			    $rs = $this->db->query($sql)->result();
				
				if(!empty($rs)){ 

					$arrtarget = [];
				    foreach ($rs as $row) {
				        $personal_phone = $row->personal_phone;
				        $emp_name       = $row->full_name;

				        if ($personal_phone != '') {
				        	// Ubah 0 di depan jadi 62
			                if (substr($personal_phone, 0, 1) == '0') {
			                    $personal_phone = '62' . substr($personal_phone, 1);
			                }
			                //$personal_phone='6287881747918';
				            // Gabungkan nomor dan nama pakai |
				            $arrtarget[] = $personal_phone . "|" . $emp_name;
				        }
				    }
				    // Gabungkan semua elemen dengan koma
				    $targetString = implode(",", $arrtarget);


					$token = "I4EMpL6raAUKRgQtZ2FshcC8mof35zkq0TG9ViO7";

					$curl = curl_init();

					curl_setopt_array($curl, array(
					  CURLOPT_URL => 'https://dash.pushwa.com/api/kirimPesan',
					  CURLOPT_RETURNTRANSFER => true,
					  CURLOPT_ENCODING => '',
					  CURLOPT_MAXREDIRS => 10,
					  CURLOPT_TIMEOUT => 0,
					  CURLOPT_FOLLOWLOCATION => true,
					  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					  CURLOPT_CUSTOMREQUEST => 'POST',
					  CURLOPT_POSTFIELDS => json_encode([
					    'token' => $token,
					    'target' => $targetString, 
					    'type' => "text",
					    'delay' => "2",
					    'message' => "Hai *{var1}*,\n\nAku belum lihat kamu absen hari ini 😢\nJangan lupa absen yaa! 😁\n\n\n-HRM System-"

					  ]),
					  CURLOPT_HTTPHEADER => array(
					    'Content-Type: application/json'
					  ),
					));

					$response = curl_exec($curl); 
					// Ubah JSON menjadi array
					$data = json_decode($response, true);
					// Ambil nilai idMsg
					$idMsg = $data['idMsg']; 
					// Kalau mau jadi string dipisahkan koma
					$idMsgString = implode(',', $idMsg);
					$statusSend = $this->checkStatusSendWA($token, $idMsgString);


					//ubah jd array
					$responseArr  = json_decode($statusSend, true);
					$result = [];
					$items = explode(',', $targetString);

					foreach ($items as $index => $item) {
					    list($number, $emp) = explode('|', $item);
					    
					    $result[] = [
					        "phone"  => $number,
					        "emp"    => $emp,
					        "idMsg"  => $responseArr['data'][$index]['id'] ?? null,
					        "status" => $responseArr['data'][$index]['status'] ?? null
					    ];
					}
					$xxResult = json_encode($result, JSON_UNESCAPED_UNICODE);
					//end

					$Logdata = [
						'name' 		=> "Reminder Absensi - sendWA",
						/*'status' 	=> $status,*/
						'notes' 	=> $xxResult,
						'datetime'	=> date("Y-m-d H:i:s")
					]; 
					$this->db->insert('log_send_wa', $Logdata);


					curl_close($curl);
					//echo $response;
					$hasil="Sukses";

				}else{
					$hasil="Tidak ada data";
				}

			}else{
				if(isset($sts_startDevice['msg'])){
					$hasil=$sts_startDevice['msg'];
				}else{
					$hasil="gagal";
				}
			}
		}else{
			$hasil="gagal";
		}

		echo json_encode($hasil);

	}


	

}
