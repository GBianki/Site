<?php
session_cache_limiter('public');
//$cache = session_cache_expire(5); 
session_start();
session_unset();

//-----------------------------------------------------------------------------
function changeVarSession($ini_file, $regOld, $regNew)
{
	$fp = fopen($ini_file, "r");
	$contents = fread($fp, filesize($ini_file));
	fclose($fp);

	$new_contents = ereg_replace($regOld, $regNew, $contents);

	$fp = fopen($ini_file, "w");
	fwrite($fp, $new_contents);
	fclose($fp);
}

//---main-------------------------------------------------------------------------

if( ($File = fopen( "site.conf", "r" )) == NULL)
	return 0;

while (!feof ($File)) {
	$Linha = fgets($File, 512);
	$value = strstr($Linha, "=");
	if ($value)
	{
		$var = substr($Linha, 0, strlen($Linha) - strlen($value));
		$value = substr($value, 1, strlen($value)-1);
		$_SESSION[$var] = $value;
	}
}
fclose ($File);
// DEBUG = Para ver o conteúdo da sessão descomente a linha abaixo.
//var_dump($_SESSION);
session_write_close ();
?>
