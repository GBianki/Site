<?php
include "cont.php";

$file = "dados/".$HTTP_SERVER_VARS['QUERY_STRING'];
count_hit($file);

?>
