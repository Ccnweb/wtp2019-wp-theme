<?php
namespace wtp;

function gen_html_dropdown($arr, $opt = []) {
    /**
     * Generates a simple HTML dropdown from an array like {opt1: label1, opt2: label2, ...}
     * 
     */

    $str = '<select';
    if (!empty($opt['id'])) $str .= ' id="'.$opt['id'].'"';
    if (!empty($opt['name'])) $str .= ' name="'.$opt['name'].'"';
    if (!empty($opt['class'])) $str .= ' class="'.$opt['class'].'"';
    $str .= '>';

    $i = 0;
    foreach ($arr as $key => $val) {
        if (\is_numeric($key) && empty($opt['numeric'])) $key = $val;
        $str .= '<option value="'.$key.'">'.$val.'</option>';
    }
    return $str.= '</select>';
}

?>