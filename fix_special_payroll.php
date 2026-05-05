<?php
$file = 'c:/xampp/htdocs/_hrm/application/modules/special_payroll_internal/models/Hitung_gaji_int_menu_model.php';
$content = file_get_contents($file);

// Fix the corrupted single line (input display section)
$old = "\$dt .= '<td>'.\$this->return_build_txt(\$bpjs_jht,'bpjs_jht_gaji['.\$row.']','','bpjs_jht_gaji','text-align: right;','data-id=\"'.\$row.'\"' readonly ').'</td>'; \t\t\t\t\t\$dt .= '<td>'.\$this->return_build_txt(\$bpjs_jp,'bpjs_jp_gaji['.\$row.']','','bpjs_jp_gaji','text-align: right;','data-id=\"'.\$row.'\"' readonly ').'</td>'; \t\t\t\t\t\$dt .= '<td>'.\$this->return_build_txt(\$bpjs_jkk,'bpjs_jkk_gaji['.\$row.']','','bpjs_jkk_gaji','text-align: right;','data-id=\"'.\$row.'\"' readonly ').'</td>'; \t\t\t\t\t\$dt .= '<td>'.\$this->return_build_txt(\$bpjs_jkm,'bpjs_jkm_gaji['.\$row.']','','bpjs_jkm_gaji','text-align: right;','data-id=\"'.\$row.'\"' readonly ').'</td>';";

$new = "\t\t\t\t\t\$dt .= '<td>'.\$this->return_build_txt(\$bpjs_jht,'bpjs_jht_gaji['.\$row.']','','bpjs_jht_gaji','text-align: right;','data-id=\"'.\$row.'\" readonly ').'</td>';\n";
$new .= "\t\t\t\t\t\$dt .= '<td>'.\$this->return_build_txt(\$bpjs_jp,'bpjs_jp_gaji['.\$row.']','','bpjs_jp_gaji','text-align: right;','data-id=\"'.\$row.'\" readonly ').'</td>';\n";
$new .= "\t\t\t\t\t\$dt .= '<td>'.\$this->return_build_txt(\$bpjs_jkk,'bpjs_jkk_gaji['.\$row.']','','bpjs_jkk_gaji','text-align: right;','data-id=\"'.\$row.'\" readonly ').'</td>';\n";
$new .= "\t\t\t\t\t\$dt .= '<td>'.\$this->return_build_txt(\$bpjs_jkm,'bpjs_jkm_gaji['.\$row.']','','bpjs_jkm_gaji','text-align: right;','data-id=\"'.\$row.'\" readonly ').'</td>';";

if (strpos($content, $old) !== false) {
    $content = str_replace($old, $new, $content);
    echo "Fixed input display section\n";
} else {
    echo "Could not find corrupted input display section\n";
    // Try to find what's there
    $lines = explode("\n", $content);
    for ($i = 1173; $i < 1182; $i++) {
        echo "Line " . ($i+1) . ": " . substr($lines[$i], 0, 80) . "\n";
    }
}

// Fix read-only display section (old tp_jkk etc)
$old2 = "\t\t\t\t\t\$dt .= '<td>'.\$tp_jkk.'</td>';\n";
$old2 .= "\t\t\t\t\t\$dt .= '<td>'.\$tp_jkm.'</td>';\n";
$old2 .= "\t\t\t\t\t\$dt .= '<td>'.\$tp_jht.'</td>';\n";
$old2 .= "\t\t\t\t\t\$dt .= '<td>'.\$tp_jp.'</td>';\n";
$old2 .= "\t\t\t\t\t\$dt .= '<td>'.\$pgk_jht.'</td>';\n";
$old2 .= "\t\t\t\t\t\$dt .= '<td>'.\$pgk_jp.'</td>';\n";
$old2 .= "\t\t\t\t\t\$dt .= '<td>'.\$tp_jkes.'</td>';\n";
$old2 .= "\t\t\t\t\t\$dt .= '<td>'.\$pgk_jkes.'</td>';";

$new2 = "\t\t\t\t\t\$dt .= '<td>'.\$bpjs_jht.'</td>';\n";
$new2 .= "\t\t\t\t\t\$dt .= '<td>'.\$bpjs_jp.'</td>';\n";
$new2 .= "\t\t\t\t\t\$dt .= '<td>'.\$bpjs_jkk.'</td>';\n";
$new2 .= "\t\t\t\t\t\$dt .= '<td>'.\$bpjs_jkm.'</td>';";

if (strpos($content, $old2) !== false) {
    $content = str_replace($old2, $new2, $content);
    echo "Fixed read-only display section\n";
} else {
    echo "Could not find read-only display section\n";
}

file_put_contents($file, $content);
echo "Done\n";
