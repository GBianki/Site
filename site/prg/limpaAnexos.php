<?php
include "msg.php";

header("Content-type: text/plain");

$geraCmdFTP = false;
$bSetTimeLimit = true;
$contDel = 0;
$DirAnexos = Configuracao("DIR_ANEXOS");

//----------------------------------------------------------------------------------
function myErrorHandler($type, $info, $file, $row)
{
	global $bSetTimeLimit;
	$bSetTimeLimit = false;
}

//----------------------------------------------------------------------------------
function LimpaAnexos($dir, $subdir)
{
	global $DirAnexos, $bSetTimeLimit, $geraCmdFTP, $contDel;

//echo "LimpaAnexos($dir, $subdir);\n";

	if ($contDel > 600)
		return 0;

	if ($bSetTimeLimit) set_time_limit(60);
	$fh = opendir($dir);
	if ($fh === false)
		return 0;

	$cont = 0;
	while (false !== ($dirEntry = readdir($fh)))
	{
		if ($dirEntry{0} == '.')
			continue;
		$file = $dir."/".$dirEntry;
		if (is_dir($file))
		{
			$cont += LimpaAnexos($file, $subdir.'/'.$dirEntry);
			continue;
		}
		if (!is_file($file))
			continue;

		if ((++$cont % 100) == 0)
			if ($bSetTimeLimit) set_time_limit(60);

		if ($contDel > 500)
			break;
	}

	closedir($fh);

	if ($cont == 0)
	{
		if ($dir != $DirAnexos)
		{
			// Apagar diretorio vazio
			$contDel++;
			if (!empty($geraCmdFTP) || @rmdir($dir) === false)
			{
				echo "rmdir $subdir\n";
				flush();
				$geraCmdFTP = true;
			}
		}
	}

	return $cont;
}

//---- main ------------------------------------------------------------------------------
set_error_handler("myErrorHandler");
set_time_limit(60);
restore_error_handler();

/* Prepara chamada do FTP. */
echo "pwd\n";
flush();

/* Remove diretorios de anexos vazios. */
$fh = opendir($DirAnexos);
while (false !== ($dirEntry = readdir($fh)))
{
	if ($dirEntry == "." || $dirEntry == "..")
		continue;

	$dir = $DirAnexos.$dirEntry;
	if (is_dir($dir))
		LimpaAnexos($dir, $dirEntry);

	if ($contDel > 500)
		break;
}

closedir($fh);

/* Finaliza chamada do FTP. */
echo "#OK $contDel\n";
?>
