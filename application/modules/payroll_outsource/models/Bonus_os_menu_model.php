<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once __DIR__.'/Bonus_thr_os_menu_model.php';

class Bonus_os_menu_model extends Bonus_thr_os_menu_model
{
	protected $folder_name = "payroll_outsource/bonus_os_menu";
	protected $table_name = _PREFIX_TABLE."bonus_os";
	protected $detail_table_name = _PREFIX_TABLE."bonus_os_detail";
	protected $detail_foreign_key = "bonus_os_id";
	protected $amount_field = "bonus_amount";
	protected $approval_matrix_type_id = 16;
}
