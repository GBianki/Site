<?php

//---------------------------------------
function id_browser() {
	$browser=$_SERVER['HTTP_USER_AGENT'];

	if(preg_match('#Opera(/| )([0-9]{1,2}.[0-9]{1,2})#', $browser))
		$browser='OPERA';
	else if(preg_match('#MSIE ([0-9]{1,2}.[0-9]{1,2})#', $browser))
		$browser='IE';
	else if(preg_match('#OmniWeb/([0-9]{1,2}.[0-9]{1,2})#', $browser))
		$browser='OMNIWEB';
	else if(preg_match('#(Konqueror/)(.*)#', $browser))
		$browser='KONQUEROR';
	else if(preg_match('#Mozilla/([0-9]{1,2}.[0-9]{1,2})#', $browser))
		$browser='MOZILLA';
	else
		$browser='OTHER';

	return $browser;
}
//---------------------------------------
function decodeStr($str) {
	$str = str_replace('@', '%', $str);
	return urldecode($str);
}
//---------------------------------------
function ExibePdf($file) {
	global $UsingXmlModel;
	
	if (!empty($file) && @file_exists($file))
	{
		header('Content-type: application/pdf');
		header('Content-disposition: attachment; filename="'.basename(decodeStr($file)).'"');
		@set_time_limit(120);
		@readfile($file);
	}
	else if ($UsingXmlModel)
		Mensagem('Aviso', 'Imagem não disponível!');
	else
		die('<html><meta http-equiv="Content-Type" content="text/html">
			 <body OnLoad="javascript: alert(\'O arquivo com o documento n&atilde;o est&aacute; dispon&iacute;vel!\');window.close();" bgcolor="#F0F0F0"></body></html>');

	exit;
}
//---------------------------------------
function ExibeUmaImagem($file) {
	global $UsingXmlModel;
	
	if ($UsingXmlModel) 
	{
		header('Content-type: image/jpeg');
		header('Content-disposition: attachment; filename="'.basename($file).'"');
		@set_time_limit(120);
		@readfile($file);
	}
	else if (!empty($file) && @file_exists($file))
		header("Location: ".$file);
	else
		die('<html><meta http-equiv="Content-Type" content="text/html">
			 <body OnLoad="javascript: alert(\'O arquivo de imagem n&atilde;o est&aacute; dispon&iacute;vel!\');window.close();" bgcolor="#F0F0F0"></body></html>');

	exit;
}
//---------------------------------------
function BuscaImagens($sDir) {
	// Verifica se existe imagem neste diretorio
	$aArqs = array();
	$aDir = @scandir($sDir);
	if ($aDir !== false) {
		foreach ($aDir as $sArq) {
			$sExt = substr($sArq, strlen($sArq)-3);
			if ($sExt == "jpg")
				$aArqs[] = "$sDir/$sArq";
		}
	}
	return $aArqs;
}
//---------------------------------------
function ExibeTelaComImagens($aArqs, $iCod, $sParamExtra='') {
	$DirModelos = Configuracao('DIR_MODELOS_AREACLIENTE');
	$model = new DTemplate($DirModelos);

	$ArqModelo = 'imagens';
	$model->define_templates( array('imagens' => Modelo($DirModelos, $ArqModelo)));
	$model->define_dynamic('IMAGEM', 'imagens');

	$model->assign('SCRIPT', basename($_SERVER["SCRIPT_NAME"]));
	$model->assign('PARAM', $sParamExtra);
	$model->assign('COD', $iCod);

	if (Configuracao('THUMBNAILS_RESIZE') == 'NAO')
		$Resize = '&resize=no';
	else
		$Resize = '';

	$iSeq = 0;
	foreach ($aArqs as $sArq) {
		$model->assign('IMAGEM_ARQ', $aArqs[$iSeq].$Resize);
		$iSeq++;
		$model->assign('IMAGEM_SEQ', $iSeq);
		$model->parse($iSeq == 0 ? 'IMAGEM' : '.IMAGEM');
	}

	$model->parse('imagens');
	$model->DPrint('imagens');
	if (!$UsingXmlModel)
		echo "\n<!-- $ArqModelo.shtml -->\n";
}
?>
