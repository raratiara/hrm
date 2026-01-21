<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Absence_report_os_menu extends MY_Controller
{
	/* Module */
 	const  LABELMODULE				= "absence_report_os_menu"; // identify menu
 	const  LABELMASTER				= "Menu Report Absensi Karyawan OS";
 	const  LABELFOLDER				= "hr_menu"; // module folder
 	const  LABELPATH				= "absence_report_os_menu"; // controller file (lowercase)
 	const  LABELNAVSEG1				= "hr_menu"; // adjusted 1st sub parent segment
 	const  LABELSUBPARENTSEG1		= "Master"; // 
 	const  LABELNAVSEG2				= ""; // adjusted 2nd sub parent segment
 	const  LABELSUBPARENTSEG2		= ""; // 
	
	/* View */
	public $icon 					= 'fa-database';
	public $tabel_header 			= ["ID","Day", "Date","Employee Name","Project","Employee Type","Time In","Time Out","Attendance IN","Attendance OUT","Late Desc","Leave Desc","Num of Working Hours"];

	
	/* Export */
	public $colnames 				= ["ID","Date","Employee Name","Project","Employee Type","Time In","Time Out","Attendance IN","Attendance OUT","Late Desc","Leave Desc","Num of Working Hours"];
	public $colfields 				= ["id","date_attendance","full_name","project_label","attendance_type","time_in","time_out","date_attendance_in","date_attendance_out","is_late_desc","is_leaving_office_early_desc","num_of_working_hours"];


	/* Form Field Asset */
	public function form_field_asset()
	{
		

		$field = [];
		$field['txtdateattendance']		= $this->self_model->return_build_txt('','date_attendance','date_attendance');
		// $msemp 							= $this->db->query("select * from employees where status_id = 1 order by full_name asc")->result(); 
		$msemp = $this->db->query("
				SELECT * 
				FROM employees 
				WHERE status_id = 1
				AND emp_source IN ('outsource','outsourcing')
				ORDER BY full_name ASC
			")->result();

		$field['selemployee'] 			= $this->self_model->return_build_select2me($msemp,'','','','employee','employee','','','id','full_name',' ','','','',3,'-');
		$field['selflemployee'] 		= $this->self_model->return_build_select2me($msemp,'','','','flemployee','flemployee','','','id','full_name',' ','','','',3,'-');
	
		$msproject = $this->db->query("
			SELECT id,
				CONCAT_WS(' - ', code, project_name) AS project_label
			FROM project_outsource
			ORDER BY project_name ASC
		")->result();

		$field['selflproject'] = $this->self_model->return_build_select2me(
			$msproject,
			'',
			'',
			'',
			'flproject',
			'flproject',
			'',
			'',
			'id',
			'project_label',
			' ',
			'',
			'',
			'',
			3,
			'-'
		);

		$field['txtimein'] 				= $this->self_model->return_build_txt('','time_in','time_in','','','readonly');
		$field['txtattendancein'] 		= $this->self_model->return_build_txt('','attendance_in','attendance_in');
		$field['txtlatedesc'] 			= $this->self_model->return_build_txt('','late_desc','late_desc','','','readonly');
		$field['txtemptype'] 			= $this->self_model->return_build_txt('','emp_type','emp_type','','','readonly');
		$field['txtimeout'] 			= $this->self_model->return_build_txt('','time_out','time_out','','','readonly');
		$field['txtattendanceout'] 		= $this->self_model->return_build_txt('','attendance_out','attendance_out');
		$field['txtleavingearlydesc']	= $this->self_model->return_build_txt('','leaving_early_desc','leaving_early_desc','','','readonly');



		
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


	public function getAbsenceReport(){

	    $dateNow = date("Y-m-d");

	    $where_date = " and a.date_attendance = '".$dateNow."' ";
	    $filter_periode = $dateNow;
	    if($_GET['fldatestart'] != '' && $_GET['fldatestart'] != 0 && $_GET['fldateend'] != '' && $_GET['fldateend'] != 0){
	        $where_date = " and a.date_attendance between '".$_GET['fldatestart']."' and '".$_GET['fldateend']."' ";
	        $filter_periode = $_GET['fldatestart'].' to '.$_GET['fldateend'];
	    }

	    $where_emp = "";
		if($_GET['flemployee'] != '' && $_GET['flemployee'] != 0){
			$where_emp = " and a.employee_id = '".$_GET['flemployee']."' ";
		}

		$where_project = "";
		if(isset($_GET['flproject']) && $_GET['flproject'] != '' && $_GET['flproject'] != 0){
			$where_project = " and b.project_outsource_id = '".$_GET['flproject']."' ";
		}


	    // kelompok berdasarkan divisi
	    $groupedByDivision = [];
	    $emp_absen = $this->db->query("select distinct(a.employee_id), b.division_id from time_attendances a left join employees b on b.id = a.employee_id where b.status_id = 1 and b.emp_source in ('outsource','outsourcing','OS') ".$where_emp.$where_project.$where_date." ")->result();
	    foreach ($emp_absen as $rowemp_absen) {
	        $groupedByDivision[$rowemp_absen->division_id][] = $rowemp_absen->employee_id;
	    }

	    // --- PREPARE ZIP ---
	    $pathExport = FCPATH . 'uploads/report_absensi_bulanan/';
	    if(!file_exists($pathExport)) mkdir($pathExport, 0777, true);

	    $zipFilename = $pathExport . 'export_absensi_' . date('Y-m') . '.zip';
	    $zip = new ZipArchive();
	    $zip->open($zipFilename, ZipArchive::CREATE | ZipArchive::OVERWRITE);

	    foreach ($groupedByDivision as $divisionId => $employeeIds) {

	        unset($valSummary, $valrows, $valfooter, $dataSheets);
	        ob_start(); // MULAI BUFFER TULIS XML (TANPA HEADER DOWNLOAD)

	        echo '<?xml version="1.0"?>
	        <Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
	         xmlns:o="urn:schemas-microsoft-com:office:office"
	         xmlns:x="urn:schemas-microsoft-com:office:excel"
	         xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">
	         <Styles>
	           <Style ss:ID="TitleStyle"><Font ss:Bold="1" ss:Size="14"/></Style>
	           <Style ss:ID="SubTextStyle"></Style>
	           <Style ss:ID="HeaderStyle"><Font ss:Bold="1"/><Interior ss:Color="#D3D3D3" ss:Pattern="Solid"/></Style>
	         </Styles>';

	        $dataSheets = [];
	        $emp_absen = $this->db->query("select distinct(a.employee_id), b.division_id, c.name as division_name, b.full_name 
	            from time_attendances a 
	            left join employees b on b.id = a.employee_id 
	            left join divisions c on c.id = b.division_id 
	            where b.status_id = 1 and b.division_id = '".$divisionId."' ".$where_emp.$where_project.$where_date."
	            order by b.full_name asc")->result(); 

	        if(count($emp_absen) != 0){ 
	            $no = 1;

	            foreach($emp_absen as $rowemp_absen){

	                $sql = 'select a.*, b.full_name, if(a.is_late = "Y","Late", "") as "is_late_desc", 
								(case when a.leave_type != "" then concat("(",c.name,")") 
									  when a.is_leaving_office_early = "Y" then "Leaving Office Early"
									  else "" end) as is_leaving_office_early_desc,
								d.name as branch_name, e.full_name as direct_name,
								(case when a.leave_absences_id is not null and a.leave_type != 5 and h.status_approval = 2 then "1" else "" end) as cuti,
								(case when a.leave_absences_id is null and a.date_attendance_in is not null then "1" else "" end) as masuk,
								(case when a.leave_absences_id is null and a.date_attendance_in is not null and a.work_location = "onsite" then "1" else "" end) as piket,
								(case when a.leave_absences_id is null and a.date_attendance_in is not null and a.work_location = "wfh" then "1" else "" end) as wfh,
								a.notes as keterangan
								,b.emp_code, f.name as dept_name, g.name as work_location_name
								,CONCAT_WS(" - ", j.code, j.project_name) as project_label
								,(case when a.leave_absences_id is null and a.date_attendance_in is not null and a.work_location = "wfo" then "1" else "" end) as wfo
								,(case when a.leave_absences_id is not null and leave_type = 5 and h.status_approval = 2 then "1" else "" end) as sakit

								(case when a.is_late = "Y" then "1" else "" end) as late,
								(case when a.is_leaving_office_early = "Y" then "1" else "" end) as leaving_early,
							    i.num_of_hour as overtime_num_of_hour,
							    i.amount as overtime_amount
							from time_attendances a 
							left join employees b on b.id = a.employee_id
							left join project_outsource j on j.id = b.project_outsource_id
							left join master_leaves c on c.id = a.leave_type
							left join branches d on d.id = b.branch_id
							left join employees e on e.id = b.direct_id
							left join departments f on f.id = b.department_id
							left join master_work_location g on g.id = b.work_location
							left join leave_absences h on h.id = a.leave_absences_id
							left join overtimes i on i.public $colnames  = ["ID","Date","Employee Name","Employee Type","Time In","Time Out","Attendance IN","Attendance OUT","Late Desc","Leave Desc","Num of Working Hours"]; = a.employee_id 
							and (a.date_attendance = DATE_FORMAT(i.datetime_start, "%Y-%m-%d"))
							and i.type = 1 and i.status_id = 2
							where a.employee_id = "'.$rowemp_absen->employee_id.'" '.$where_date.'
							ORDER BY id ASC';

	                $res = $this->db->query($sql);
	                $data = $res->result();

	                $ttl_cuti=0; $ttl_masuk=0; $ttl_piket=0; $ttl_wfh=0;
	                $ttl_wfo=0; $ttl_sakit=0; $ttl_working_hours=0; $ttl_late=0; $ttl_leaving_early=0;
	                $ttl_overtime_num_of_hour=0; $ttl_overtime_amount=0;
	                $valrows=[]; $valfooter=[];

	                $no_dtl = 1;
	                foreach($data as $rowdata){
	                    $valrows[] = [
	                    	$no_dtl,
	                        $rowdata->date_attendance,
	                        $rowdata->attendance_type,
	                        $rowdata->wfo,
	                        $rowdata->wfh,
	                        $rowdata->piket,
	                        $rowdata->sakit,
	                        $rowdata->cuti,
	                        $rowdata->date_attendance_in,
	                        $rowdata->date_attendance_out,
	                        $rowdata->num_of_working_hours,
	                        $rowdata->late,
	                        $rowdata->leaving_early,
	                        $rowdata->overtime_num_of_hour,
	                        $rowdata->overtime_amount,
	                        $rowdata->keterangan
	                    ];

	                    $ttl_cuti += ($rowdata->cuti != '' ? $rowdata->cuti : 0);
	                    $ttl_masuk += ($rowdata->masuk != '' ? $rowdata->masuk : 0);
	                    $ttl_piket += ($rowdata->piket != '' ? $rowdata->piket : 0);
	                    $ttl_wfh   += ($rowdata->wfh != '' ? $rowdata->wfh : 0);
	                    $ttl_wfo   += ($rowdata->wfo != '' ? $rowdata->wfo : 0);
	                    $ttl_sakit += ($rowdata->sakit != '' ? $rowdata->sakit : 0);
	                    $ttl_working_hours += ($rowdata->num_of_working_hours != '' ? $rowdata->num_of_working_hours : 0);
	                    $ttl_late += ($rowdata->late != '' ? $rowdata->late : 0);
	                    $ttl_leaving_early += ($rowdata->leaving_early != '' ? $rowdata->leaving_early : 0);
	                    $ttl_overtime_num_of_hour += ($rowdata->overtime_num_of_hour != '' ? $rowdata->overtime_num_of_hour : 0);
	                    $ttl_overtime_amount += ($rowdata->overtime_amount != '' ? $rowdata->overtime_amount : 0);


	                    $no_dtl++;
	                }

	                $valSummary[] = [
	                    $no,
	                    $data[0]->emp_code,
	                    $data[0]->full_name,
						($data[0]->project_label ?? '-'),
	                    $data[0]->dept_name,
	                    $data[0]->work_location_name,
	                    $data[0]->attendance_type,
	                    $ttl_wfo,
	                    $ttl_wfh,
	                    $ttl_piket, //onsite,
	                    $ttl_sakit,
	                    $ttl_cuti,
	                    $ttl_working_hours, 
	                    $ttl_late,
	                    $ttl_leaving_early,
	                    $ttl_overtime_num_of_hour, //lembur jam
	                    $ttl_overtime_amount, //lembur rp
	                    '' //keterangan
	                ];

	                $valfooter[] = ['Total','','', $ttl_wfo, $ttl_wfh, $ttl_piket, $ttl_sakit, $ttl_cuti, '', '', $ttl_working_hours, $ttl_late, $ttl_leaving_early ];

	                /*$dataSheets[$data[0]->full_name] = [
	                    'title' => 'DATA ABSENSI/ACTIVITY KARYAWAN',
	                    'headers' => ['Tanggal', 'Cuti', 'Masuk', 'Piket', 'WFH', 'Keterangan'],
	                    'rows' => $valrows,
	                    'subtitle' => [
	                        ['Nama', $data[0]->full_name],
	                        ['Area', $data[0]->branch_name],
	                        ['Leader', $data[0]->direct_name],
	                        ['Periode', $filter_periode],
	                    ],
	                    'footer' => $valfooter
	                ];*/

	                $dataSheets[$data[0]->full_name] = [
	                    'title' => 'DATA ABSENSI/ACTIVITY KARYAWAN',
	                    'headers' => ['No', 'Tanggal', 'Shift', 'WFO', 'WFH', 'Onsite', 'Sakit', 'Ijin/Cuti', 'Jam Masuk', 'Jam Pulang', 'Total Jam', 'Datang Terlambat', 'Pulang Cepat', 'Lembur (jam)', 'Lembur (Rp)', 'Keterangan'],
	                    'rows' => $valrows,
	                    'subtitle' => [
	                    	['NIK', $data[0]->emp_code],
	                        ['Nama', $data[0]->full_name],
							['Project', ($data[0]->project_label ?? '-')],
	                        ['Departemen', $data[0]->dept_name],
	                        ['Area', $data[0]->branch_name],
	                        ['Leader', $data[0]->direct_name],
	                        ['Periode', $filter_periode],
	                    ],
	                    'footer' => $valfooter
	                ];

	                $no++;
	            }

	            if($where_emp==""){
	                /*$dataSheets['Summary'] = [
	                    'title' => 'DATA ABSENSI/ACTIVITY KARYAWAN',
	                    'headers' => ['No', 'Nama', 'Cuti', 'Masuk', 'Piket', 'WFH'],
	                    'rows' => $valSummary,
	                    'subtitle' => [
	                        ['Division', $rowemp_absen->division_name],
	                        ['Area', 'All'],
	                        ['Leader', 'All'],
	                        ['Periode', $filter_periode],
	                    ],
	                    'footer' => []
	                ];*/
	                $dataSheets['Summary'] = [
	                    'title' => 'DATA ABSENSI/ACTIVITY KARYAWAN',
	                    'headers' => ['No', 'NIK', 'Nama', 'Project',  'Departemen', 'Lokasi Kerja', 'Shift', 'WFO', 'WFH', 'Onsite', 'Sakit',  'Ijin/Cuti', 'Total Jam', 'Datang Terlambat', 'Pulang Cepat', 'Lembur (jam)', 'Lembur (Rp)', 'Keterangan'],
	                    'rows' => $valSummary,
	                    'subtitle' => [
	                        ['Division', $rowemp_absen->division_name],
	                        ['Area', 'All'],
	                        ['Leader', 'All'],
	                        ['Periode', $filter_periode],
	                    ],
	                    'footer' => []
	                ];
	            }

	            if($where_emp==""){
	                $lastKey = array_key_last($dataSheets);
	                $lastSheet = [$lastKey => $dataSheets[$lastKey]];
	                unset($dataSheets[$lastKey]);
	                $dataSheets = $lastSheet + $dataSheets;
	            }

	        } else {
	            $dataSheets['Summary'] = [
	                'title' => 'DATA ABSENSI/ACTIVITY KARYAWAN',
	                'headers' => ['No', 'NIK', 'Nama', 'Departemen', 'Lokasi Kerja', 'Shift', 'WFO', 'WFH', 'Onsite', 'Sakit',  'Ijin/Cuti', 'Total Jam', 'Datang Terlambat', 'Pulang Cepat', 'Lembur (jam)', 'Lembur (Rp)', 'Keterangan'],
	                'rows' => [['No Data', 'No Data', 'No Data', 'No Data', 'No Data', 'No Data','No Data', 'No Data', 'No Data', 'No Data', 'No Data', 'No Data','No Data', 'No Data', 'No Data', 'No Data', 'No Data']],
	                'subtitle' => [
	                    ['Area', 'All'],
	                    ['Leader', 'All'],
	                    ['Periode', $filter_periode],
	                ],
	                'footer' => []
	            ];
	        }

	        // CETAK WORKSHEET
	        foreach ($dataSheets as $sheetName => $sheetData) {
	            echo '<Worksheet ss:Name="' . htmlspecialchars($sheetName) . '"><Table>';

	            echo '<Row><Cell ss:MergeAcross="' . (count($sheetData['headers']) - 1) . '" ss:StyleID="TitleStyle"><Data ss:Type="String">' . htmlspecialchars($sheetData['title']) . '</Data></Cell></Row>';
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

	            echo '<Row>';
	            foreach ($sheetData['headers'] as $headerCell) {
	                echo '<Cell ss:StyleID="HeaderStyle"><Data ss:Type="String">' . htmlspecialchars($headerCell) . '</Data></Cell>';
	            }
	            echo '</Row>';

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

	            echo '</Table></Worksheet>';
	        }

	        echo '</Workbook>';

	        $content = ob_get_clean();
	        $divname = strtolower(trim($rowemp_absen->division_name));
	        $divname = str_replace(" ","_",$divname);

	        $filename = "absensi_division_" . $divname . ".xls";
	        $zip->addFromString($filename, $content);
	    }

	    $zip->close();

	    // --- DOWNLOAD ZIP AMAN ---
	    if (ob_get_level()) {
    ob_end_clean();
}
	    header('Content-Type: application/zip');
	    header('Content-disposition: attachment; filename=' . basename($zipFilename));
	    header('Content-Length: ' . filesize($zipFilename));
	    readfile($zipFilename);
	    unlink($zipFilename);
	    exit;
	}




	public function getAbsenceReport_old2(){

		$dateNow = date("Y-m-d");

		$where_date=" and a.date_attendance = '".$dateNow."' ";
		$filter_periode =$dateNow;
		if($_GET['fldatestart'] != '' && $_GET['fldatestart'] != 0 && $_GET['fldateend'] != '' && $_GET['fldateend'] != 0){
			$where_date = " and a.date_attendance between '".$_GET['fldatestart']."' and '".$_GET['fldateend']."' ";
			$filter_periode = $_GET['fldatestart'].' to '.$_GET['fldateend'];
		}

		$where_emp=""; 
		if($_GET['flemployee'] != '' && $_GET['flemployee'] != 0){
			$where_emp = " and a.employee_id = '".$_GET['flemployee']."' ";
		}


		$groupedByDivision = [];
		$emp_absen = $this->db->query("select distinct(a.employee_id), b.division_id from time_attendances a left join employees b on b.id = a.employee_id where b.status_id = 1 and b.emp_source in ('outsource','outsourcing','OS') ".$where_emp.$where_project.$where_date." ")->result();
		foreach ($emp_absen as $rowemp_absen) {
		    $groupedByDivision[$rowemp_absen->division_id][] = $rowemp_absen->employee_id;
		}

		$zip = new ZipArchive();
		/*$zipFilename = FCPATH . 'uploads/export_absensi_' . date('Ymd_His') . '.zip';
		$zip->open($zipFilename, ZipArchive::CREATE | ZipArchive::OVERWRITE);*/


		$zipFilename = FCPATH . 'uploads/report_absensi_bulanan/export_absensi_' . date('Y-m') . '.zip';
		$zip->open($zipFilename, ZipArchive::CREATE | ZipArchive::OVERWRITE);


		foreach ($groupedByDivision as $divisionId => $employeeIds) {
			// CLEAR/RESET DATA SEBELUM MENGISI UNTUK DIVISI BERIKUTNYA
    		unset($valSummary, $valrows, $valfooter, $dataSheets);

		    ob_start(); // mulai buffer output

		    header("Content-Type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=\"absence_report.xls\"");

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

			$emp_absen = $this->db->query("select distinct(a.employee_id), b.division_id, c.name as division_name from time_attendances a left join employees b on b.id = a.employee_id left join divisions c on c.id = b.division_id where b.status_id = 1 and b.division_id = '".$divisionId."' ".$where_emp.$where_date." order by b.full_name asc ")->result(); 
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
								,(case when a.leave_absences_id is null and a.date_attendance_in is not null and a.work_location = "onsite" then "1" else "" end) as piket
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
				            ['Area', 'All'],
				            ['Leader', 'All'],
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
			            ['Area', 'All'],
			            ['Leader', 'All'],
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


		header('Content-Type: application/zip');
		header('Content-disposition: attachment; filename=' . basename($zipFilename));
		header('Content-Length: ' . filesize($zipFilename));
		readfile($zipFilename);
		unlink($zipFilename); // hapus file zip setelah diunduh (opsional)
		exit;



	}



	public function getAbsenceReport_old(){

		
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=\"absence_report.xls\"");

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



	 	$dateNow = date("Y-m-d");

		$where_date=" and a.date_attendance = '".$dateNow."' ";
		$filter_periode =$dateNow;
		if($_GET['fldatestart'] != '' && $_GET['fldatestart'] != 0 && $_GET['fldateend'] != '' && $_GET['fldateend'] != 0){
			$where_date = " and a.date_attendance between '".$_GET['fldatestart']."' and '".$_GET['fldateend']."' ";
			$filter_periode = $_GET['fldatestart'].' to '.$_GET['fldateend'];
		}

		$where_emp=""; 
		if($_GET['flemployee'] != '' && $_GET['flemployee'] != 0){
			$where_emp = " and a.employee_id = '".$_GET['flemployee']."' ";
		}

		
		$dataSheets = [];

		$emp_absen = $this->db->query("select distinct(a.employee_id), b.division_id from time_attendances a left join employees b on b.id = a.employee_id where b.status_id = 1 and b.emp_source in ('outsource','outsourcing','OS') ".$where_emp.$where_project.$where_date." ")->result(); 
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
							,(case when a.leave_absences_id is null and a.date_attendance_in is not null and a.work_location = "onsite" then "1" else "" end) as piket
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
			        	['Division', 'All'],
			            ['Area', 'All'],
			            ['Leader', 'All'],
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
		            ['Area', 'All'],
		            ['Leader', 'All'],
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



	}


	/// download report absensi stiap tgl 25 jam 8 pagi
	public function downloadAbsenceReport(){

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
		$emp_absen = $this->db->query("select distinct(a.employee_id), b.division_id from time_attendances a left join employees b on b.id = a.employee_id where b.status_id = 1 and b.emp_source in ('outsource','outsourcing','OS') ".$where_emp.$where_project.$where_date." ")->result();
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

		    header("Content-Type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=\"absence_report.xls\"");

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

			$emp_absen = $this->db->query("select distinct(a.employee_id), b.division_id, c.name as division_name from time_attendances a left join employees b on b.id = a.employee_id left join divisions c on c.id = b.division_id 
			where b.status_id = 1 and b.division_id = '".$divisionId."' ".$where_emp.$where_project.$where_date."
			order by b.full_name asc ")->result(); 
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
								,(case when a.leave_absences_id is null and a.date_attendance_in is not null and a.work_location = "onsite" then "1" else "" end) as piket
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
				            ['Area', 'All'],
				            ['Leader', 'All'],
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
			            ['Area', 'All'],
			            ['Leader', 'All'],
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


		header('Content-Type: application/zip');
		header('Content-disposition: attachment; filename=' . basename($zipFilename));
		header('Content-Length: ' . filesize($zipFilename));
		readfile($zipFilename);
		//unlink($zipFilename); // hapus file zip setelah diunduh (opsional)
		exit;



	}


	

}
