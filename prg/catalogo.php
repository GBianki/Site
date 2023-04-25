<?php
include "msg.php";

header('Content-type: text/plain');

$contArqs = 0;
$bRmdirOK = true;
$bRecursivo = false;
$bSetTimeLimit = true;
$DirBase = Configuracao('DIR_DADOS');

//----------------------------------------------------------------------------------
function myErrorHandler($type, $info, $file, $row)
{
	global $bSetTimeLimit;
	$bSetTimeLimit = false;
//echo "#SEM set_time_limit()\n";
}

//----------------------------------------------------------------------------------
/*
	Monta catalogo do diretorio.
*/
function Catalogo($siteDir, $catalogDir, $ext)
{
	global $DirBase, $bSetTimeLimit, $contArqs, $bRecursivo, $bRmdirOK;

//echo "Catalogo('$siteDir', '$catalogDir', '$ext');\n";
	$cont = 0;
	if ($bSetTimeLimit) set_time_limit(60);
	$fh = opendir($siteDir);
	if ($fh === false)
		return 0;

	while (false !== ($siteEntry = readdir($fh)))
	{
		if ($siteEntry{0} == '.')
			continue;
		$file = $siteDir.'/'.$siteEntry;
		if (is_dir($file))
		{
			if ($bRecursivo)
				$cont += Catalogo($file, $catalogDir.$siteEntry.'/', $ext);
			continue;
		}
		if (!is_file($file))
			continue;
			
		if (!empty($ext) && strstr($siteEntry, $ext) != $ext)
			continue;

		$infos = stat($file);
		$size = $infos[7];
		$mtime = $infos[9];
		echo "File: $catalogDir$siteEntry\nSize: $size\n";
		echo 'Date: '.$mtime.' ('.gmdate('Y-m-d H:i:s', $mtime).")\n\n";
		$contArqs++;

		if ((++$cont % 100) == 0)
			if ($bSetTimeLimit) set_time_limit(60);
	}

	closedir($fh);

	if ($cont == 0 && $bRmdirOK)
	{
		if ($siteDir != $DirBase)
			// Apagar diretorio vazio
			if (@rmdir($siteDir) === false)
				$bRmdirOK = false;
	}
	
	return $cont;
}

//---- main ------------------------------------------------------------------------
set_error_handler("myErrorHandler");
set_time_limit(60);
restore_error_handler();

echo "#ORIGEM: catalogo.php\n";

$dir = Campo('dir');
if (!empty($dir))
{
	if ($dir[0] == '@')
	{	// Trata-se de referencia indireta para dir. de configuracao
		$conf = substr($dir,1);
		if ($conf == 'DIR_PRG')
			$DirBase = '.';
		else
		{
			$dir = Configuracao($conf, '', false);
			if (empty($dir))
			{
				echo("ERRO: parametro 'dir' com referencia inexistente (@$conf)\n");
				exit(1);
			}
			$DirBase = $dir;
		}
	}
	else 
		$DirBase .= $dir;
}
	
$aux = Campo('recursivo');
if ($aux == 'sim')
	$bRecursivo = true;

echo '#SUBDIR: '.dirname($_SERVER["SCRIPT_NAME"]).'/'.$DirBase."\n#RECURSIVO=".($bRecursivo?'sim':'nao')."\n";

$ext = Campo('ext');
if (empty($ext))
	$ext = "";
else
	echo "#EXT: $ext\n";
echo "\n";

Catalogo($DirBase, '', $ext);

echo "#OK $contArqs\n";
?>
