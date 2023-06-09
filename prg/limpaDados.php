<?php
include "msg.php";

header('Content-type: text/plain');

$geraCmdFTP = false;
$bSetTimeLimit = true;
$contDel = 0;
$DirDados = Configuracao('DIR_DADOS');
$MesesNoSite = Configuracao("MESES_NO_SITE_EXTRATOCOND", Configuracao("MESES_NO_SITE", 3));


//----------------------------------------------------------------------------------
function myErrorHandler($type, $info, $file, $row)
{
	global $bSetTimeLimit;
	$bSetTimeLimit = false;
}

//----------------------------------------------------------------------------------
function limpaDados($dir)
{
	global $bSetTimeLimit, $geraCmdFTP, $contDel, $MesesNoSite;

//echo "limpaDados($dir)\n";

	if ($contDel > 500)
		return 0;

	// Monta menor nome de extensao valida
	$ano = date('y');
	$mes = date('m') - $MesesNoSite + 1;
	if ($mes <= 0) {
		$mes += 12;
		$ano--;
	}
	$extOK = sprintf('%02d%02d', $ano,$mes);
//echo "extOK=$extOK\n";

	// Percorre diretorio comparando extensoes
	$cont = 0;
	if ($bSetTimeLimit) set_time_limit(60);
	$fh = opendir($dir);
	if ($fh === false)
		return 0;

	while (false !== ($dirEntry = readdir($fh)))
	{
		if ($dirEntry{0} == '.')
			continue;

		$pos = strrpos($dirEntry, '.');
		if ($pos === false)
			continue;
		$ext = substr($dirEntry, $pos+1);

//echo "\t$dirEntry: $ext\n";
		if (!is_numeric($ext))
			continue;
		if ($ext >= $extOK)
			continue;

		$file = $dir.$dirEntry;
		if (!is_file($file))
			continue;

		// Apagar arquivo antigo
		if (empty($geraCmdFTP) && @unlink($file) === false)
			$geraCmdFTP = true;

		if (!empty($geraCmdFTP))
		{
			echo 'del "'.$file."\"\n";
			flush();
			$contDel++;
			$geraCmdFTP = true;
		}

		if ((++$cont % 100) == 0)
			if ($bSetTimeLimit) set_time_limit(60);

		if ($contDel > 500)
			break;
	}

	closedir($fh);

	return $cont;
}

//---- main ------------------------------------------------------------------------------
set_error_handler('myErrorHandler');
set_time_limit(60);
restore_error_handler();

/* Prepara chamada do FTP. */
echo "pwd\n";
flush();

limpaDados($DirDados);

/* Finaliza chamada do FTP. */
echo "#OK $contDel\n";
?>
