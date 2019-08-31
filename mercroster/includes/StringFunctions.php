<?php
function html_escape($input_string) {
    $result = htmlspecialchars($input_string, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    return $result;
}
if (!function_exists('mysql_result')) {
    function mysql_result($result, $number, $field=0) {
        mysqli_data_seek($result, $number);
        $row = mysqli_fetch_array($result);
        return $row[$field];
    }
}