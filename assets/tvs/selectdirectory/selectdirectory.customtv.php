<?php

if (!IN_MANAGER_MODE) {
    die('<h1>ERROR:</h1><p>Please use the MODx Content Manager instead of accessing this file directly.</p>');
}

$categories = \EvolutionCMS\Ddafilters\Models\FilterCategory::all();
$output = '<select id="tv' . $row['id'] . '" name="tv' . $row['id'] . '" value="[+tv_value+]"><option value="">Выберите категорию</option>>';
foreach ($categories as $category) {
    $selected = ($row['value'] == $category->id) ? "selected" : "";
    $output .= '<option ' . $selected . ' value="' . $category->id . '">' . $category->name . '</option>';
}
$output .= '</select>';
echo $output;