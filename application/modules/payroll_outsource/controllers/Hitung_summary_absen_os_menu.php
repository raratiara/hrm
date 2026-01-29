<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hitung_summary_absen_os_menu extends MY_Controller
{
	/* Module */
 	const  LABELMODULE				= "hitung_summary_absen_os_menu"; // identify menu
 	const  LABELMASTER				= "Menu Hitung Summary Absen OS";
 	const  LABELFOLDER				= "payroll_outsource"; // module folder
 	const  LABELPATH				= "hitung_summary_absen_os_menu"; // controller file (lowercase)
 	const  LABELNAVSEG1				= "payroll_outsource"; // adjusted 1st sub parent segment
 	const  LABELSUBPARENTSEG1		= "Master"; // 
 	const  LABELNAVSEG2				= ""; // adjusted 2nd sub parent segment
 	const  LABELSUBPARENTSEG2		= ""; // 
	
	/* View */
	public $icon 					= 'fa-database';
	public $tabel_header 			= ["ID","Bulan Penggajian", "Tahun Penggajian","Tgl Start Absen","Tgl End Absen","Employee","Total Hari Kerja","Total Masuk","Total Ijin","Total Cuti","Total Alfa","Total Lembur","Total Jam Kerja","Total Jam Lembur"];

	
	/* Export */
	public $colnames 				= ["ID","Bulan Penggajian", "Tahun Penggajian","Tgl Start Absen","Tgl End Absen","Employee","Total Hari Kerja","Total Masuk","Total Ijin","Total Cuti","Total Alfa","Total Lembur","Total Jam Kerja","Total Jam Lembur"];
	public $colfields 				= ["id","month_name","tahun","tgl_start","tgl_end","full_name","total_hari_kerja","total_masuk","total_ijin","total_cuti","total_alfa","total_lembur","total_jam_kerja","total_jam_lembur"];


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


	public function getAbsenceReportSummaryAbsenOS(){

	    $sql = "
	        select 
	            c.name_indo as month_name,
	            a.tahun,
	            a.tgl_start,
	            a.tgl_end,
	            b.full_name,
	            a.total_hari_kerja,
	            a.total_masuk,
	            a.total_ijin,
	            a.total_cuti,
	            a.total_alfa,
	            a.total_lembur,
	            a.total_jam_kerja,
	            a.total_jam_lembur
	        from summary_absen_outsource a 
	        left join employees b on b.id = a.emp_id 
	        left join master_month c on c.id = a.bulan
	        order by a.tahun desc, a.bulan asc, b.full_name asc
	    ";

	    $data = $this->db->query($sql)->result();

	    ob_start();

	    echo '<?xml version="1.0"?>
	    <Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
	     xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">

	     <Styles>
	        <Style ss:ID="Title">
	            <Font ss:Bold="1" ss:Size="14"/>
	        </Style>
	        <Style ss:ID="Header">
	            <Font ss:Bold="1"/>
	            <Interior ss:Color="#D9D9D9" ss:Pattern="Solid"/>
	        </Style>
	     </Styles>';

	    echo '<Worksheet ss:Name="Summary Absen Outsource">
	            <Table>';

	    // ===== TITLE =====
	    echo '<Row>
	            <Cell ss:MergeAcross="12" ss:StyleID="Title">
	                <Data ss:Type="String">SUMMARY ABSENSI OUTSOURCE</Data>
	            </Cell>
	          </Row>';

	    echo '<Row></Row>';

	    // ===== HEADER =====
	    $headers = [
	        'No',
	        'Bulan Penggajian',
	        'Tahun Penggajian',
	        'Periode Mulai',
	        'Periode Selesai',
	        'Nama Karyawan',
	        'Total Hari Kerja',
	        'Total Masuk',
	        'Total Ijin',
	        'Total Cuti',
	        'Total Alfa',
	        'Total Lembur (Hari)',
	        'Total Jam Kerja',
	        'Total Jam Lembur'
	    ];

	    echo '<Row>';
	    foreach ($headers as $h){
	        echo '<Cell ss:StyleID="Header">
	                <Data ss:Type="String">'.htmlspecialchars($h).'</Data>
	              </Cell>';
	    }
	    echo '</Row>';

	    // ===== DATA =====
	    $no = 1;
	    foreach ($data as $row){
	        echo '<Row>';
	        echo '<Cell><Data ss:Type="Number">'.$no++.'</Data></Cell>';
	        echo '<Cell><Data ss:Type="String">'.htmlspecialchars($row->month_name).'</Data></Cell>';
	        echo '<Cell><Data ss:Type="Number">'.$row->tahun.'</Data></Cell>';
	        echo '<Cell><Data ss:Type="String">'.$row->tgl_start.'</Data></Cell>';
	        echo '<Cell><Data ss:Type="String">'.$row->tgl_end.'</Data></Cell>';
	        echo '<Cell><Data ss:Type="String">'.htmlspecialchars($row->full_name).'</Data></Cell>';
	        echo '<Cell><Data ss:Type="Number">'.$row->total_hari_kerja.'</Data></Cell>';
	        echo '<Cell><Data ss:Type="Number">'.$row->total_masuk.'</Data></Cell>';
	        echo '<Cell><Data ss:Type="Number">'.$row->total_ijin.'</Data></Cell>';
	        echo '<Cell><Data ss:Type="Number">'.$row->total_cuti.'</Data></Cell>';
	        echo '<Cell><Data ss:Type="Number">'.$row->total_alfa.'</Data></Cell>';
	        echo '<Cell><Data ss:Type="Number">'.$row->total_lembur.'</Data></Cell>';
	        echo '<Cell><Data ss:Type="Number">'.$row->total_jam_kerja.'</Data></Cell>';
	        echo '<Cell><Data ss:Type="Number">'.$row->total_jam_lembur.'</Data></Cell>';
	        echo '</Row>';
	    }

	    echo '</Table></Worksheet></Workbook>';

	    $content = ob_get_clean();

	    header("Content-Type: application/vnd.ms-excel");
	    header("Content-Disposition: attachment; filename=summary_absen_outsource.xls");
	    header("Cache-Control: max-age=0");
	    echo $content;
	    exit;
	}



	public function getAbsenceReportSummaryAbsenOS_pdf()
	{
	    $sql = "
	        select 
	            c.name_indo AS month_name,
	            a.tahun,
	            a.tgl_start,
	            a.tgl_end,
	            b.full_name,
	            a.total_hari_kerja,
	            a.total_masuk,
	            a.total_ijin,
	            a.total_cuti,
	            a.total_alfa,
	            a.total_lembur,
	            a.total_jam_kerja,
	            a.total_jam_lembur
	        FROM summary_absen_outsource a
	        LEFT JOIN employees b ON b.id = a.emp_id
	        LEFT JOIN master_month c ON c.id = a.bulan
	        ORDER BY a.tahun DESC, a.bulan ASC, b.full_name ASC
	    ";

	    $data = $this->db->query($sql)->result();

	    $this->load->library('html_pdf');

	    $pdfData = [
	        'title' => 'SUMMARY ABSENSI OUTSOURCE',
	        'data'  => $data
	    ];

	    $pdfBinary = $this->html_pdf->render_to_string(
	        'pdf/report_summary_absen_os',
	        $pdfData
	    );

	    if (ob_get_level()) ob_end_clean();

	    header("Content-Type: application/pdf");
	    header("Content-Disposition: attachment; filename=summary_absen_outsource.pdf");
	    echo $pdfBinary;
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
