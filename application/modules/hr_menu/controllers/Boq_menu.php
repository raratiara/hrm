<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Boq_menu extends MY_Controller
{
	/* Module */
 	const  LABELMODULE				= "boq_menu"; // identify menu
 	const  LABELMASTER				= "Menu BOQ";
 	const  LABELFOLDER				= "hr_menu"; // module folder
 	const  LABELPATH				= "boq_menu"; // controller file (lowercase)
 	const  LABELNAVSEG1				= "hr_menu"; // adjusted 1st sub parent segment
 	const  LABELSUBPARENTSEG1		= "Master"; // 
 	const  LABELNAVSEG2				= ""; // adjusted 2nd sub parent segment
 	const  LABELSUBPARENTSEG2		= ""; // 
	
	/* View */
	public $icon 					= 'fa-database';
	public $tabel_header 			= ["ID","Customer","Project","Periode"];

	
	/* Export */
	public $colnames 				= ["ID","Customer","Project","Periode"];
	public $colfields 				= ["id","customer_name","project_name","tahun"];

	/* Form Field Asset */
	public function form_field_asset()
	{
		
		$field = [];

	
		$mscust 					= $this->db->query("select * from data_customer order by name asc")->result(); 
		$field['selcustomer'] 		= $this->self_model->return_build_select2me($mscust,'','','','customer_boq','customer_boq','','','id','name',' ','','','',1,'-');
		$msproject 					= array();
		$field['selproject'] 		= $this->self_model->return_build_select2me($msproject,'','','','project_boq','project_boq','project_boq','','id','project_desc',' ','','','',1,'-');
		

		$field['txtperiode'] 		= $this->self_model->return_build_txt('','periode','periode','','','readonly');


		
		
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


 	public function getDataProject(){
		$post 		= $this->input->post(null, true);
		$customer 	= $post['customer'];

		$rs =  $this->self_model->getDataProject($customer);
		

		echo json_encode($rs);
	}


	public function getDataProjectOutsource(){
		$post 		= $this->input->post(null, true);
		$project 	= $post['project'];

		$rs =  $this->db->query("select * from project_outsource where id = '".$project."' ")->result(); 
		

		echo json_encode($rs);
	}


	public function genboqrow()
	{ 
		if(_USER_ACCESS_LEVEL_VIEW == "1")
		{ 
			$post = $this->input->post(null, true);
			$customer = trim($post['customer']);
			$project = trim($post['project']);

			if(isset($post['count']))
			{  
				$row = trim($post['count']); 
				echo $this->self_model->getNewBoqRow($row);
			} else if(isset($post['id'])) { 
				$row = 0;
				$id = trim($post['id']);
				$view = (isset($post['view']) && $post['view'] == TRUE)? TRUE:FALSE;
				echo json_encode($this->self_model->getNewBoqRow($row,$id,$customer,$project,$view));
			}
		}
		else
		{ 
			$this->load->view('errors/html/error_hacks_401');
		}
	}



	public function print_pdf($id)
	{ 
	    error_reporting(E_ALL);
	    ini_set('display_errors', 1);

	    // ambil 1 header saja (row, bukan result)
	    $header = $this->db->query(
	        'select a.*,
			(case when t.jenis_pekerjaan != "" and t.lokasi != "" then concat(t.code," (",t.lokasi," - ",t.jenis_pekerjaan,")")
				when t.jenis_pekerjaan != "" and t.lokasi = "" then concat(t.code," (",t.jenis_pekerjaan,")")
				when t.lokasi != "" and t.jenis_pekerjaan = "" then concat(t.code," (",t.lokasi,")")
				else t.code end
				) as project_name,
			    b.name as customer_name,
			    if(t.periode_start != "" and t.periode_end != "", concat(t.periode_start," s/d ",t.periode_end),"") as periode, t.management_fee
			from project_outsource_boq a
			left join project_outsource t on t.id = a.project_outsource_id
			left join data_customer b on b.id = t.customer_id
			where a.id = ?',
	        [$id]
	    )->row();

	    if (!$header) {
	        show_error('Data tidak ditemukan');
	        return;
	    }

	    // ambil detail (pakai id sebagai foreign key misalnya)
	    $detail = $this->db->query(
	        "select b.master_header_boq_id, b.name, b.is_active, b.parent_id, b.no_urut, a.jumlah,
				a.harga_satuan, a.jumlah_harga,
				bb.name AS header_name, bb.id as header_id,
					cc.name AS parent_name, bb.no_urut as no_urut_header, cc.no_urut as no_urut_parent
			from project_outsource_boq_detail a
			left join master_boq_detail b on b.id = a.ms_boq_detail_id
			left join master_boq_header c on c.id = b.master_header_boq_id
			LEFT JOIN master_boq_header bb ON bb.id = b.master_header_boq_id
			LEFT JOIN master_boq_parent_detail cc ON cc.id = b.parent_id
			where a.boq_id = ?
			ORDER BY 
				bb.no_urut ASC,
				cc.no_urut ASC,
				b.no_urut ASC
			",
	        [$id]
	    )->result();

	    $data = [
	        'header' => $header,
	        'detail' => $detail
	    ];

	    $filename = 'BOQ_' . date('Ymd_His') . '.pdf';

	    $this->load->library('html_pdf');
	    $this->html_pdf->filename = $filename;

	    $this->html_pdf->load_view('pdf/boq', $data);
	    $this->html_pdf->render_pdf();
	    $this->html_pdf->stream_pdf(false); // tampil di browser
	}





}
