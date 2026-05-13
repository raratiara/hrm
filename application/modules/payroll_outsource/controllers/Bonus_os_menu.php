<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bonus_os_menu extends MY_Controller
{
	const LABELMODULE = "bonus_os_menu";
	const LABELMASTER = "Menu Perhitungan Bonus Outsource";
	const LABELFOLDER = "payroll_outsource";
	const LABELPATH = "bonus_os_menu";
	const LABELNAVSEG1 = "payroll_outsource";
	const LABELSUBPARENTSEG1 = "Master";
	const LABELNAVSEG2 = "";
	const LABELSUBPARENTSEG2 = "";

	public $icon = 'fa-gift';
	public $tabel_header = ["ID", "Project", "Periode", "Total Bonus"];

	public $colnames = ["ID", "Project", "Bulan", "Tahun", "Total Bonus"];
	public $colfields = ["id", "project_name", "month_name", "periode_tahun", "total_nominal"];

	public function form_field_asset()
	{
		$field = [];

		$msmonth = $this->db->query("select * from master_month order by id asc")->result();
		$field['selmonth'] = $this->self_model->return_build_select2me($msmonth, '', '', '', 'periode_bulan', 'periode_bulan', '', '', 'id', 'name_indo', ' ', '', '', 'required', 3, '-');

		$msproject = $this->db->query("select id, project_name from project_outsource order by project_name asc")->result();
		$field['selproject'] = $this->self_model->return_build_select2me($msproject, '', '', '', 'project_id', 'project_id', '', '', 'id', 'project_name', ' ', '', '', 'required', 3, '-');
		$field['selflproject'] = $this->self_model->return_build_select2me($msproject, '', '', '', 'flproject', 'flproject', '', '', 'id', 'project_name', ' ', '', '', '', 3, '-');

		$field['txtyear'] = $this->self_model->return_build_txt('', 'periode_tahun', 'periode_tahun', '', '', 'required maxlength="4"');
		$field['txtnotes'] = $this->self_model->return_build_txtarea('', 'notes', 'notes', 3);
		$field['nominal_label'] = 'Bonus';

		return $field;
	}

	public function __construct()
	{
		parent::__construct();
		$akses = $this->self_model->user_akses($this->module_name);
		define('_USER_ACCESS_LEVEL_VIEW', $akses["view"]);
		define('_USER_ACCESS_LEVEL_ADD', $akses["add"]);
		define('_USER_ACCESS_LEVEL_UPDATE', $akses["edit"]);
		define('_USER_ACCESS_LEVEL_DELETE', $akses["del"]);
		define('_USER_ACCESS_LEVEL_DETAIL', $akses["detail"]);
		define('_USER_ACCESS_LEVEL_IMPORT', $akses["import"]);
		define('_USER_ACCESS_LEVEL_EKSPORT', $akses["eksport"]);
	}

	public $folder_name = self::LABELFOLDER."/".self::LABELPATH;
	public $module_name = self::LABELMODULE;
	public $model_name = "bonus_os_menu_model";

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

	public function genbonusrowthr()
	{
		if(_USER_ACCESS_LEVEL_VIEW == "1") {
			$post = $this->input->post(null, true);
			$id = isset($post['id']) ? trim($post['id']) : 0;
			$project = isset($post['project']) ? trim($post['project']) : 0;
			$view = (isset($post['view']) && in_array($post['view'], [TRUE, 'true', '1', 1], TRUE)) ? TRUE : FALSE;

			echo json_encode($this->self_model->getNewBonusThrRows($id, $project, $view));
		} else {
			$this->load->view('errors/html/error_hacks_401');
		}
	}
}
