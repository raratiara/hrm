<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hitung_summary_absen_os_menu extends MY_Controller
{
	/* Module */
 	const  LABELMODULE				= "hitung_summary_absen_os_menu"; // identify menu
 	const  LABELMASTER				= "Menu Hitung Summary Absen OS";
 	const  LABELFOLDER				= "hr_menu"; // module folder
 	const  LABELPATH				= "hitung_summary_absen_os_menu"; // controller file (lowercase)
 	const  LABELNAVSEG1				= "hr_menu"; // adjusted 1st sub parent segment
 	const  LABELSUBPARENTSEG1		= "Master"; // 
 	const  LABELNAVSEG2				= ""; // adjusted 2nd sub parent segment
 	const  LABELSUBPARENTSEG2		= ""; // 
	
	/* View */
	public $icon 					= 'fa-database';
	public $tabel_header 			= ["ID","Bulan Penggajian", "Tahun Penggajian","Tgl Start Absen","Tgl End Absen","Employee","Total Hari Kerja","Total Masuk","Total Ijin","Total Cuti","Total Alfa","Total Lembur","Total Jam Kerja","Total Jam Lembur"];

	
	/* Export */
	public $colnames 				= ["ID","Bulan Penggajian", "Tahun Penggajian","Tgl Start Absen","Tgl End Absen","Employee","Total Hari Kerja","Total Masuk","Total Ijin","Total Cuti","Total Alfa","Total Lembur","Total Jam Kerja","Total Jam Lembur"];
	public $colfields 				= ["id","bulan","tahun","tgl_start","tgl_end","full_name","total_hari_kerja","total_masuk","total_ijin","total_cuti","total_alfa","total_lembur","total_jam_kerja","total_jam_lembur"];


	/* Form Field Asset */
	public function form_field_asset()
	{
		

		$field = [];
		$field['txtperiodstart']	= $this->self_model->return_build_txt('','period_start','period_start');
		$field['txtperiodend']		= $this->self_model->return_build_txt('','period_end','period_end');
		$field['txtyear']			= $this->self_model->return_build_txt('','penggajian_year','penggajian_year');
		$field['txtperiodstart_edit']	= $this->self_model->return_build_txt('','period_start_edit','period_start_edit');
		$field['txtperiodend_edit']		= $this->self_model->return_build_txt('','period_end_edit','period_end_edit');
		$field['txtyear_edit']			= $this->self_model->return_build_txt('','penggajian_year_edit','penggajian_year_edit');
		
		$msmonth 					= $this->db->query("select * from master_month order by id asc")->result(); 
		$field['selmonth'] 			= $this->self_model->return_build_select2me($msmonth,'','','','penggajian_month','penggajian_month','','','id','name_indo',' ','','','',3,'-');
		$field['selmonth_edit'] 			= $this->self_model->return_build_select2me($msmonth,'','','','penggajian_month_edit','penggajian_month_edit','','','id','name_indo',' ','','','',3,'-');
		/*$field['is_all_employee'] 	= $this->self_model->return_build_radio('Ya', [['Ya','Ya'],['Tidak','Tidak']], 'is_all_employee', '', 'inline');*/
		$msemp 						= $this->db->query("select * from employees where emp_source = 'outsource' and status_id = 1 order by full_name asc")->result(); 
		$field['selemployeeids'] 	= $this->self_model->return_build_select2me($msemp,'multiple','','','employeeIds[]','employeeIds','','','id','full_name',' ','','','',3,'-');
		
		$field['selflemployee'] 	= $this->self_model->return_build_select2me($msemp,'','','','flemployee','flemployee','','','id','full_name',' ','','','',3,'-');

		$field['is_all_project'] 	= $this->self_model->return_build_radio('Semua', [['Semua','Semua'],['Sebagian','Sebagian'],['Karyawan','Per Karyawan']], 'is_all_project', '', 'inline');
		$msproject 					= $this->db->query('select id,
										(case when jenis_pekerjaan != "" and lokasi != "" then concat(code," (",lokasi," - ",jenis_pekerjaan,")")
										when jenis_pekerjaan != "" and lokasi = "" then concat(code," (",jenis_pekerjaan,")")
										when lokasi != "" and jenis_pekerjaan = "" then concat(code," (",lokasi,")")
										else code end
										) as project_desc, project_name
										from project_outsource order by code asc')->result(); 
		$field['selprojectids'] 	= $this->self_model->return_build_select2me($msproject,'multiple','','','projectIds[]','projectIds','','','id','project_name',' ','','','',3,'-');
		$field['selproject_edit'] 	= $this->self_model->return_build_select2me($msproject,'','','','project_edit','project_edit','','','id','project_name',' ','','','',3,'-');

		
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

	    // kelompok berdasarkan divisi
	    $groupedByDivision = [];
	    $emp_absen = $this->db->query("select distinct(a.employee_id), b.division_id from time_attendances a left join employees b on b.id = a.employee_id where b.status_id = 1 ".$where_emp.$where_date." ")->result();
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
	            where b.status_id = 1 and b.division_id = '".$divisionId."' ".$where_emp.$where_date." 
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
								,b.emp_code, f.name as dept_name, g.name as work_location_name,
								(case when a.leave_absences_id is null and a.date_attendance_in is not null and a.work_location = "wfo" then "1" else "" end) as wfo,
								(case when a.leave_absences_id is not null and leave_type = 5 and h.status_approval = 2 then "1" else "" end) as sakit,
								(case when a.is_late = "Y" then "1" else "" end) as late,
								(case when a.is_leaving_office_early = "Y" then "1" else "" end) as leaving_early,
							    i.num_of_hour as overtime_num_of_hour,
							    i.amount as overtime_amount
							from time_attendances a 
							left join employees b on b.id = a.employee_id
							left join master_leaves c on c.id = a.leave_type
							left join branches d on d.id = b.branch_id
							left join employees e on e.id = b.direct_id
							left join departments f on f.id = b.department_id
							left join master_work_location g on g.id = b.work_location
							left join leave_absences h on h.id = a.leave_absences_id
							left join overtimes i on i.employee_id = a.employee_id 
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
	                    'headers' => ['No', 'NIK', 'Nama', 'Departemen', 'Lokasi Kerja', 'Shift', 'WFO', 'WFH', 'Onsite', 'Sakit',  'Ijin/Cuti', 'Total Jam', 'Datang Terlambat', 'Pulang Cepat', 'Lembur (jam)', 'Lembur (Rp)', 'Keterangan'],
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
	    ob_end_clean(); // buang sisa output
	    header('Content-Type: application/zip');
	    header('Content-disposition: attachment; filename=' . basename($zipFilename));
	    header('Content-Length: ' . filesize($zipFilename));
	    readfile($zipFilename);
	    unlink($zipFilename);
	    exit;
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
		//unlink($zipFilename); // hapus file zip setelah diunduh (opsional)
		exit;



	}


	public function genabsenosrow()
	{ 
		if(_USER_ACCESS_LEVEL_VIEW == "1")
		{ 
			$post = $this->input->post(null, true);

			if(isset($post['count']))
			{  
				$row = trim($post['count']); 
				echo $this->self_model->getNewAbsenOSRow($row);
			} else if(isset($post['id'])) { 
				$row = 0;
				$id = trim($post['id']);
				$view = (isset($post['view']) && $post['view'] == TRUE)? TRUE:FALSE;
				echo json_encode($this->self_model->getNewAbsenOSRow($row,$id,$view));
			}
		}
		else
		{ 
			$this->load->view('errors/html/error_hacks_401');
		}
	}


	public function rekapitulasi(){
		
		$karyawan_id = $_SESSION['worker'];

		$post = $this->input->post(null, true);
		$id = $post['id'];

		$getperiod_start 	= date_create($post['period_start']); 
		$getperiod_end 		= date_create($post['period_end']); 
		$period_start 		= date_format($getperiod_start,"Y-m-d");
		$period_end 		= date_format($getperiod_end,"Y-m-d");
		

		if($id != ''){

			$data = [
				'bulan' 			=> trim($post['penggajian_month']),
				'tahun' 			=> trim($post['penggajian_year']),
				'tgl_start' 		=> $period_start,
				'tgl_end' 			=> $period_end,
				/*'emp_id' 			=> $emp_id,*/
				'total_hari_kerja'  => $post['ttl_hari_kerja'],
				'total_masuk'  		=> $post['ttl_masuk'],
				'total_ijin'  		=> $post['ttl_ijin'],
				'total_cuti'  		=> $post['ttl_cuti'],
				'total_alfa'  		=> $post['ttl_alfa'],
				'total_lembur'  	=> $post['ttl_lembur'],
				'total_jam_kerja'  	=> $post['ttl_jam_kerja'],
				'total_jam_lembur'  => $post['ttl_jam_lembur'],
				'updated_at'		=> date("Y-m-d H:i:s"),
				'updated_by' 		=> $_SESSION['worker']
			];

			$rs = $this->db->update('summary_absen_outsource', $data, "id = '".$id."'");


			return $rs;
			
		}else return null;

		echo json_encode($rs);

	}


	public function getAbsenProject(){
		$post = $this->input->post(null, true);
		$project 	= $post['project'];
		$bln 	= $post['bln'];
		$thn 	= $post['thn'];

		$rs =  $this->self_model->getAbsenProject($project,$bln, $thn);
		

		echo json_encode($rs);
	}


	public function geneditabsenrow()
	{ 
		if(_USER_ACCESS_LEVEL_VIEW == "1")
		{ 
			$post = $this->input->post(null, true);
			$bln 	= $post['bln'];
			$thn 	= $post['thn'];

			if(isset($post['count']))
			{  
				$row = trim($post['count']); 
				echo $this->self_model->getNewEditAbsenRow($row);
			} else if(isset($post['project'])) { 
				$row = 0;
				$id = trim($post['project']);
				$view = (isset($post['view']) && $post['view'] == TRUE)? TRUE:FALSE;
				echo json_encode($this->self_model->getNewEditAbsenRow($row,$id,$bln,$thn,$view));
			}
		}
		else
		{ 
			$this->load->view('errors/html/error_hacks_401');
		}
	}


	public function save_edit_per_project_old(){
		$post = $this->input->post();

		$project 			= $post['project_edit'];
		$getperiod_start 	= date_create($post['period_start_edit']); 
		$getperiod_end 		= date_create($post['period_end_edit']); 
		$period_start 		= date_format($getperiod_start,"Y-m-d");
		$period_end 		= date_format($getperiod_end,"Y-m-d");

		if(!empty($project) && !empty($post['penggajian_month_edit']) && !empty($post['penggajian_year_edit']) ){ 
			if (strlen($post['penggajian_year_edit']) == 4) {

				$cek_data = $this->db->query("select a.*, b.project_id from summary_absen_outsource a 
							left join employees b on b.id = a.emp_id
							where a.bulan = '".$post['penggajian_month_edit']."' and a.tahun = '".$post['penggajian_year_edit']."' and b.project_id = '".$project."'")->result();
				if(!empty($cek_data)){
					if($period_start == ''){
						$period_start = $cek_data[0]->tgl_start;
					}
					if($period_end == ''){
						$period_end = $cek_data[0]->period_end;
					}


					
					if(isset($post['hdnempid'])){
						$item_num = count($post['hdnempid']); // cek sum
						$item_len_min = min(array_keys($post['hdnempid'])); // cek min key index
						$item_len = max(array_keys($post['hdnempid'])); // cek max key index
					} else {
						$item_num = 0;
					}

					if($item_num>0){
						for($i=$item_len_min;$i<=$item_len;$i++) 
						{
							$hdnid = trim($post['hdnid_edit'][$i]);

							if(!empty($hdnid)){ //update
								if(isset($post['hdnempid'][$i])){
									$itemData = [
										'tgl_start'			=> $period_start,
										'tgl_end' 			=> $period_end,
										'total_hari_kerja' 	=> trim($post['ttl_hari_kerja'][$i]),
										'total_masuk'		=> trim($post['ttl_masuk'][$i]),
										'total_ijin' 		=> trim($post['ttl_ijin'][$i]),
										'total_cuti' 		=> trim($post['ttl_cuti'][$i]),
										'total_alfa' 		=> trim($post['ttl_alfa'][$i]),
										'total_lembur' 		=> trim($post['ttl_lembur'][$i]),
										'total_jam_kerja' 	=> trim($post['ttl_jam_kerja'][$i]),
										'total_jam_lembur' 	=> trim($post['ttl_jam_lembur'][$i]),
										'updated_at'		=> date("Y-m-d H:i:s"),
										'updated_by' 		=> $_SESSION['worker']
									];

									$rs = $this->db->update("summary_absen_outsource", $itemData, "id = '".$hdnid."'");
								}
							}else{ //insert
								if(isset($post['hdnempid'][$i])){
									$itemData = [
										'bulan' 			=> $post['penggajian_month_edit'],
										'tahun' 			=> $post['penggajian_year_edit'],
										'tgl_start'			=> $period_start,
										'tgl_end' 			=> $period_end,
										'emp_id' 			=> trim($post['hdnempid'][$i]),
										'total_hari_kerja' 	=> trim($post['ttl_hari_kerja'][$i]),
										'total_masuk'		=> trim($post['ttl_masuk'][$i]),
										'total_ijin' 		=> trim($post['ttl_ijin'][$i]),
										'total_cuti' 		=> trim($post['ttl_cuti'][$i]),
										'total_alfa' 		=> trim($post['ttl_alfa'][$i]),
										'total_lembur' 		=> trim($post['ttl_lembur'][$i]),
										'total_jam_kerja' 	=> trim($post['ttl_jam_kerja'][$i]),
										'total_jam_lembur' 	=> trim($post['ttl_jam_lembur'][$i]),
										'created_at'		=> date("Y-m-d H:i:s"),
										'created_by' 		=> $_SESSION['worker']
									];

									$rs = $this->db->insert('summary_absen_outsource', $itemData);
								}
							}
						}
					}

					return $rs;

				}else{
					
					echo json_encode([
			            'status'  => false,
			            'message' => 'Data Absen di data Project, Bulan & Tahun penggajian tersebut tidak ditemukan. Mohon untuk Hitung Absen terlebih dahulu'
			        ]);
			        return;
				}

				
			}
			else{
				
				echo json_encode([
		            'status'  => false,
		            'message' => 'Tahun tidak valid'
		        ]);
		        return;
			}


		}


	}



	public function save_edit_per_project()
	{
	    $post = $this->input->post();

	    // ================= VALIDASI AWAL =================
	    if (
	        empty($post['project_edit']) ||
	        empty($post['penggajian_month_edit']) ||
	        empty($post['penggajian_year_edit'])
	    ) {
	        echo json_encode([
	            'status'  => false,
	            'message' => 'Data wajib belum lengkap'
	        ]);
	        return;
	    }

	    if (!preg_match('/^\d{4}$/', $post['penggajian_year_edit'])) {
	        echo json_encode([
	            'status'  => false,
	            'message' => 'Tahun tidak valid'
	        ]);
	        return;
	    }

	    // ================= SET VARIABLE =================
	    $project = $post['project_edit'];

	    $period_start = !empty($post['period_start_edit'])
	        ? date('Y-m-d', strtotime($post['period_start_edit']))
	        : null;

	    $period_end = !empty($post['period_end_edit'])
	        ? date('Y-m-d', strtotime($post['period_end_edit']))
	        : null;

	    // ================= CEK DATA =================
	    $cek_data = $this->db->query("
	        SELECT a.*, b.project_id
	        FROM summary_absen_outsource a
	        LEFT JOIN employees b ON b.id = a.emp_id
	        WHERE a.bulan = ?
	          AND a.tahun = ?
	          AND b.project_id = ?
	    ", [
	        $post['penggajian_month_edit'],
	        $post['penggajian_year_edit'],
	        $project
	    ])->result();

	    if (empty($cek_data)) {
	        echo json_encode([
	            'status'  => false,
	            'message' => 'Data absen tidak ditemukan. Silakan hitung absen terlebih dahulu.'
	        ]);
	        return;
	    }

	    if (empty($period_start)) {
	        $period_start = $cek_data[0]->tgl_start;
	    }
	    if (empty($period_end)) {
	        $period_end = $cek_data[0]->tgl_end;
	    }

	    // ================= PROSES DETAIL =================
	    if (!isset($post['hdnempid']) || count($post['hdnempid']) == 0) {
	        echo json_encode([
	            'status'  => false,
	            'message' => 'Data detail absen tidak ditemukan'
	        ]);
	        return;
	    }

	    $success = false;

	    $item_len_min = min(array_keys($post['hdnempid']));
	    $item_len_max = max(array_keys($post['hdnempid']));

	    for ($i = $item_len_min; $i <= $item_len_max; $i++) {

	        if (!isset($post['hdnempid'][$i])) {
	            continue;
	        }

	        $hdnid = isset($post['hdnid_edit'][$i]) ? trim($post['hdnid_edit'][$i]) : '';

	        $itemData = [
	            'tgl_start'         => $period_start,
	            'tgl_end'           => $period_end,
	            'total_hari_kerja'  => trim($post['ttl_hari_kerja_edit'][$i]),
	            'total_masuk'       => trim($post['ttl_masuk_edit'][$i]),
	            'total_ijin'        => trim($post['ttl_ijin_edit'][$i]),
	            'total_cuti'        => trim($post['ttl_cuti_edit'][$i]),
	            'total_alfa'        => trim($post['ttl_alfa_edit'][$i]),
	            'total_lembur'      => trim($post['ttl_lembur_edit'][$i]),
	            'total_jam_kerja'   => trim($post['ttl_jam_kerja_edit'][$i]),
	            'total_jam_lembur'  => trim($post['ttl_jam_lembur_edit'][$i]),
	        ];

	        if (!empty($hdnid)) {
	            // UPDATE
	            $itemData['updated_at'] = date('Y-m-d H:i:s');
	            $itemData['updated_by'] = $_SESSION['worker'];

	            if ($this->db->update('summary_absen_outsource', $itemData, ['id' => $hdnid])) {
	                $success = true;
	            }
	        } else {
	            // INSERT
	            $itemData['bulan']      = $post['penggajian_month_edit'];
	            $itemData['tahun']      = $post['penggajian_year_edit'];
	            $itemData['emp_id']     = trim($post['hdnempid'][$i]);
	            $itemData['created_at'] = date('Y-m-d H:i:s');
	            $itemData['created_by'] = $_SESSION['worker'];

	            if ($this->db->insert('summary_absen_outsource', $itemData)) {
	                $success = true;
	            }
	        }
	    }

	    // ================= RESPONSE FINAL =================
	    if ($success) {
	        echo json_encode([
	            'status'  => true,
	            'message' => 'Data berhasil disimpan'
	        ]);
	    } else {
	        echo json_encode([
	            'status'  => false,
	            'message' => 'Tidak ada data yang berhasil diproses'
	        ]);
	    }
	}



}
