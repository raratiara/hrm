<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pembayaran_lembur_os_menu extends MY_Controller
{
	/* Module */
 	const  LABELMODULE				= "pembayaran_lembur_os_menu"; // identify menu
 	const  LABELMASTER				= "Menu Pembayaran Lembur Outsource";
 	const  LABELFOLDER				= "payroll_outsource"; // module folder
 	const  LABELPATH				= "pembayaran_lembur_os_menu"; // controller file (lowercase)
 	const  LABELNAVSEG1				= "payroll_outsource"; // adjusted 1st sub parent segment
 	const  LABELSUBPARENTSEG1		= "Master"; // 
 	const  LABELNAVSEG2				= ""; // adjusted 2nd sub parent segment
 	const  LABELSUBPARENTSEG2		= ""; // 
	


	/* View */
	public $icon 					= 'fa-database';
	public $tabel_header 			= ["ID","Project", "Bulan Penggajian","Tahun Penggajian","Status"];

	
	/* Export */
	public $colnames 				= ["ID","Project", "Bulan Penggajian","Tahun Penggajian","Status"];
	public $colfields 				= ["id","project_name","month_name","tahun_penggajian","status"];

	/* Form Field Asset */
	public function form_field_asset()
	{
		
		

		$field = [];
		
		$msproject = $this->db->query("select * FROM project_outsource ORDER BY project_name ASC ")->result();
		$field['selproject'] = $this->self_model->return_build_select2me($msproject,'','','','project','project','','','id','project_name',' ','','','required',3,'-');
		$field['selflproject'] = $this->self_model->return_build_select2me($msproject,'','','','flproject','flproject','','','id','project_name',' ','','','',3,'-');

		$msperiodegaji 					= array();
		$field['selperiodegaji'] 		= $this->self_model->return_build_select2me($msperiodegaji,'','','','periode_gaji','periode_gaji','','','id','periode_penggajian',' ','','','required',3,'-');

		$field['txtstartabsen'] = $this->self_model->return_build_txt('','start_absen','start_absen','','','readonly');
		$field['txtendabsen'] = $this->self_model->return_build_txt('','end_absen','end_absen','','','readonly');

		$msstatus = $this->db->query("select * FROM master_payroll_status ORDER BY id ASC ")->result();
		$field['selstatus'] = $this->self_model->return_build_select2me($msstatus,'','','','status','status','','','id','name',' ','','','',3,'-');
		
		
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


 	public function getDaftarLemburOS(){

	    $payroll_id = (int) $_GET['payroll_id'];

	    // ================== GET DATA ==================
	    $sql = "
	        select b.full_name, a.total_nominal_lembur, b.bank_acc_no, b.bank_name
	        from payroll_slip_detail a
	        left join employees b on b.id = a.employee_id
	        where a.payroll_slip_id = ".$payroll_id."
	        order by b.full_name asc
	    ";

	    $data = $this->db->query($sql)->result();

	    // ================== PISAHKAN DATA ==================
	    $data_mandiri = [];
	    $data_lain    = [];

	    foreach ($data as $row) {
	        if (stripos($row->bank_name, 'mandiri') !== false) {
	            $data_mandiri[] = $row;
	        } else {
	            $data_lain[] = $row;
	        }
	    }

	    // ================== GET PERIODE ==================
	    $getperiode = $this->db->query("
	        select a.*, 
	        concat(b.name_indo,' ',a.tahun_penggajian) as periode_penggajian,
	        c.project_name  
	        from payroll_slip a 
	        left join master_month b on b.id = a.bulan_penggajian
	        left join project_outsource c on c.id = a.project_id
	        where a.id = ".$payroll_id."
	    ")->row();

	    $periode_penggajian = $getperiode->periode_penggajian;
	    $project_name       = $getperiode->project_name;

	    ob_start();

	    echo '<?xml version="1.0" encoding="UTF-8"?>';
	    ?>
		<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
		 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">

		<Styles>
		    <Style ss:ID="Title">
		        <Font ss:Bold="1" ss:Size="14"/>
		    </Style>
		    <Style ss:ID="Title2">
		        <Font ss:Bold="1" ss:Size="10"/>
		    </Style>
		    <Style ss:ID="Header">
		        <Font ss:Bold="1"/>
		        <Interior ss:Color="#D9D9D9" ss:Pattern="Solid"/>
		    </Style>
		</Styles>

		<?php
		// ================== FUNCTION CETAK SHEET ==================
		function generateSheet($sheetName, $dataset, $periode, $project){

		    echo '<Worksheet ss:Name="'.$sheetName.'">
		            <Table>';

		    // ===== TITLE =====
		    echo '<Row>
		            <Cell ss:MergeAcross="4" ss:StyleID="Title">
		                <Data ss:Type="String">DATA LEMBUR PT.MANDIRI AGANGTA SEJAHTERA</Data>
		            </Cell>
		          </Row>';

		    echo '<Row>
		            <Cell ss:MergeAcross="4" ss:StyleID="Title2">
		                <Data ss:Type="String">Periode : '.$periode.'</Data>
		            </Cell>
		          </Row>';

		    echo '<Row>
		            <Cell ss:MergeAcross="4" ss:StyleID="Title2">
		                <Data ss:Type="String">Project : '.$project.'</Data>
		            </Cell>
		          </Row>';

		    echo '<Row></Row>';

		    // ===== HEADER =====
		    $headers = ['No','Nama Karyawan','Bank','No Rekening','Jumlah'];

		    echo '<Row>';
		    foreach ($headers as $h){
		        echo '<Cell ss:StyleID="Header">
		                <Data ss:Type="String">'.$h.'</Data>
		              </Cell>';
		    }
		    echo '</Row>';

		    // ===== DATA =====
		    $no = 1;
		    foreach ($dataset as $row){
		        echo '<Row>';
		        echo '<Cell><Data ss:Type="Number">'.$no++.'</Data></Cell>';
		        echo '<Cell><Data ss:Type="String">'.htmlspecialchars($row->full_name).'</Data></Cell>';
		        echo '<Cell><Data ss:Type="String">'.htmlspecialchars($row->bank_name).'</Data></Cell>';
		        echo '<Cell><Data ss:Type="String">'.htmlspecialchars($row->bank_acc_no).'</Data></Cell>';
		        echo '<Cell><Data ss:Type="Number">'.$row->total_nominal_lembur.'</Data></Cell>';
		        echo '</Row>';
		    }

		    echo '</Table></Worksheet>';
		}

		// ================== GENERATE 2 SHEET ==================
		generateSheet('Bank Mandiri', $data_mandiri, $periode_penggajian, $project_name);
		generateSheet('Bank Lain', $data_lain, $periode_penggajian, $project_name);

		echo '</Workbook>';

		$content = ob_get_clean();

		$safeProjectName = preg_replace('/[^A-Za-z0-9 _-]/', '', $project_name);

		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=Daftar Lembur - ".$safeProjectName.".xls");
		header("Cache-Control: max-age=0");

		echo $content;
		exit;
	}


 	

	public function getDataPeriodeGaji(){
		$post 		= $this->input->post(null, true);
		$project 	= $post['project'];

		$rs =  $this->self_model->getDataPeriodeGaji($project);
		

		echo json_encode($rs);
	}


	public function getDataPayroll(){
		$post 		= $this->input->post(null, true);
		$payroll_id = $post['payroll_id'];

		$rs =  $this->self_model->getDataPayroll($payroll_id);
		

		echo json_encode($rs);
	}


}
