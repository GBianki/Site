<?php
include 'msg.php'; 
include 'imagens.inc.php';

$DirAnexos = Configuracao('DIR_ANEXOS');
$UsingXmlModel = GetSessao('USING_XML_MODEL');

//--- main ------------------------------------

$arq = Campo('pdf');
if (!empty($arq))
	ExibePdf($DirAnexos.$arq);

$iCod = CampoObrigatorio('cod');
$sParam = Campo('param');

$categDescr = explode("/", $sParam);
$sDir = sprintf($DirAnexos.'Condom/%d/%s', $iCod, $sParam);
$aArqs = BuscaImagens($sDir);

$iIdx = Campo("idx");
if ($iIdx === false) {
	// Exibir a tela de lancamentos
	if (count($aArqs) < 2)
		ExibeUmaImagem($aArqs[0]);
	else
		ExibeTelaComImagens($aArqs, $iCod, $sParam);
} else {
	// Exibir apenas a imagem solicitada
	$iIdx = intval($iIdx) - 1;
	if (isset($aArqs[$iIdx]))
		ExibeUmaImagem($aArqs[$iIdx]);
	else
		Mensagem('Aviso', 'Imagem não disponível!');
}
?>
