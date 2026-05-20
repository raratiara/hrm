<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pph21_adjustment_menu extends MY_Controller
{
	const LABELMODULE = "pph21_adjustment_menu";
	const LABELMASTER = "PPh21 Adjustment";
	const LABELFOLDER = "payroll_internal";
	const LABELPATH = "pph21_adjustment_menu";
	const LABELNAVSEG1 = "payroll_internal";
	const LABELSUBPARENTSEG1 = "Master";
	const LABELNAVSEG2 = "";
	const LABELSUBPARENTSEG2 = "";

	public $icon = 'fa-balance-scale';
	public $tabel_header = ["ID", "Tahun Pajak", "NIK", "Karyawan", "Type", "Amount", "Status", "Proses Ke"];

	public $colnames = ["ID", "Tahun Pajak", "NIK", "Karyawan", "Type", "Amount", "Status", "Proses Ke"];
	public $colfields = ["id", "tahun_pajak", "emp_code", "full_name", "type", "amount", "status", "periode_proses"];

	public function form_field_asset()
	{
		$field = [];

		$months = $this->db->query("select id, name_indo from master_month order by id asc")->result();
		$status = [
			(object)['id' => 'pending', 'name' => 'Pending'],
			(object)['id' => 'processed', 'name' => 'Processed'],
			(object)['id' => 'cancelled', 'name' => 'Cancelled']
		];

		$field['selmonth'] = $this->self_model->return_build_select2me($months,'','','','proses_ke_bulan_penggajian','proses_ke_bulan_penggajian','','','id','name_indo',' ','','','',3,'-');
		$field['txtyear'] = $this->self_model->return_build_txt('','proses_ke_tahun_penggajian','proses_ke_tahun_penggajian','','','required maxlength="4"');
		$field['selstatus'] = $this->self_model->return_build_select2me($status,'','','','status','status','','','id','name',' ','','','',3,'-');

		return $field;
	}

	public function __construct()
	{
		parent::__construct();
		$akses = $this->self_model->user_akses($this->module_name);
		define('_USER_ACCESS_LEVEL_VIEW',$akses["view"]);
		define('_USER_ACCESS_LEVEL_ADD',$akses["add"]);
		define('_USER_ACCESS_LEVEL_UPDATE',$akses["edit"]);
		define('_USER_ACCESS_LEVEL_DELETE',$akses["del"]);
		define('_USER_ACCESS_LEVEL_DETAIL',$akses["detail"]);
		define('_USER_ACCESS_LEVEL_IMPORT',$akses["import"]);
		define('_USER_ACCESS_LEVEL_EKSPORT',$akses["eksport"]);
	}

	public $folder_name = self::LABELFOLDER."/".self::LABELPATH;
	public $module_name = self::LABELMODULE;
	public $model_name = self::LABELPATH."_model";

	public $parent_menu = self::LABELFOLDER;
	public $subparent_menu = self::LABELNAVSEG1;
	public $subparentitem_menu = self::LABELNAVSEG2;
	public $sub_menu = self::LABELMODULE;

	public $label_parent_modul = self::LABELFOLDER;
	public $label_subparent_modul = self::LABELSUBPARENTSEG1;
	public $label_subparentitem_modul = self::LABELSUBPARENTSEG2;
	public $label_modul = self::LABELMASTER;
	public $label_list_data = "Daftar Data ".self::LABELMASTER;
	public $label_add_data = "Tambah Data ".self::LABELMASTER;
	public $label_update_data = "Edit Data ".self::LABELMASTER;
	public $label_sukses_disimpan = "Data berhasil disimpan";
	public $label_gagal_disimpan = "Data gagal disimpan";
	public $label_delete_data = "Hapus Data ".self::LABELMASTER;
	public $label_sukses_dihapus = "Data berhasil dihapus";
	public $label_gagal_dihapus = "Data gagal dihapus";
	public $label_detail_data = "Detail Data ".self::LABELMASTER;
	public $label_import_data = "Import Data ".self::LABELMASTER;
	public $label_sukses_diimport = "Data berhasil diimport";
	public $label_gagal_diimport = "Import data di baris : ";
	public $label_export_data = "Export";
	public $label_gagal_eksekusi = "Eksekusi gagal karena ketiadaan data";
}
