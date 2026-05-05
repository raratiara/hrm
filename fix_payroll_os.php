<?php
$file = 'c:/xampp/htdocs/_hrm/application/modules/payroll_outsource/models/Hitung_gaji_os_menu_model.php';
$content = file_get_contents($file);

// Replace second BPJS detail calc section
$old = "\t\t\t\t\t///informasi detail bpjs\r\n";
$old .= "\t\t\t\t\t\$tp_jkk = (float)\$this->calculateComponentValue(\$benefit, 'tp_jkk', \$salaryCalc, \$resolvedValues);\r\n";
$old .= "\t\t\t\t\t\$tp_jkm = (float)\$this->calculateComponentValue(\$benefit, 'tp_jkm', \$salaryCalc, \$resolvedValues);\r\n";
$old .= "\t\t\t\t\t\$tp_jht = (float)\$this->calculateComponentValue(\$benefit, 'tp_jht', \$salaryCalc, \$resolvedValues);\r\n";
$old .= "\t\t\t\t\t\$tp_jp = (float)\$this->calculateComponentValue(\$benefit, 'tp_jp', \$salaryCalc, \$resolvedValues);\r\n";
$old .= "\t\t\t\t\t\$pgk_jht = (float)\$this->calculateComponentValue(\$benefit, 'pgk_jht', \$salaryCalc, \$resolvedValues);\r\n";
$old .= "\t\t\t\t\t\$pgk_jp = (float)\$this->calculateComponentValue(\$benefit, 'pgk_jp', \$salaryCalc, \$resolvedValues);\r\n";
$old .= "\t\t\t\t\t\$tp_jkes = (float)\$this->calculateComponentValue(\$benefit, 'tp_jkes', \$salaryCalc, \$resolvedValues);\r\n";
$old .= "\t\t\t\t\t\$pgk_jkes = (float)\$this->calculateComponentValue(\$benefit, 'pgk_jkes', \$salaryCalc, \$resolvedValues);";

$new = "\t\t\t\t\t///informasi detail bpjs - dari salary_bpjs via benefit deduction\r\n";
$new .= "\t\t\t\t\t\$bpjs_jht = \$bpjs_tk_detail['bpjs_jht'];\r\n";
$new .= "\t\t\t\t\t\$bpjs_jp  = \$bpjs_tk_detail['bpjs_jp'];\r\n";
$new .= "\t\t\t\t\t\$bpjs_jkk = \$bpjs_tk_detail['bpjs_jkk'];\r\n";
$new .= "\t\t\t\t\t\$bpjs_jkm = \$bpjs_tk_detail['bpjs_jkm'];";

if (strpos($content, $old) !== false) {
    $content = str_replace($old, $new, $content);
    echo "Fixed second BPJS detail calc section\n";
} else {
    echo "Could not find second BPJS detail calc section\n";
}

file_put_contents($file, $content);
echo "Done\n";
