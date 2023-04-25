<?php
include "msg.php"; 
include "imagens.inc.php";

//--- main ------------------------------------

$iLancto = CampoObrigatorio("cod");
$iIdx = Campo("idx");

$DirLanctos = Configuracao('DIR_LANCAMENTOS');
$sDir = $DirLanctos.sprintf("%d/%d", intval($iLancto / 1000) * 1000, $iLancto);
$aArqs = BuscaImagens($sDir);
if (count($aArqs) < 1)
{
	Mensagem('Nao existe imagem para este lancamento ('.$iLancto.').');
	exit(1);
}

if (empty($iIdx)) {
	// Exibir a tela de lancamentos
	if (count($aArqs) < 2)
		ExibeUmaImagem($aArqs[0]);
	else 
		ExibeTelaComImagens($aArqs, $iLancto);
} else {
	// Exibir apenas a imagem solicitada
	if (intval($iIdx) <= 0)
		$iIdx = 1;
	ExibeUmaImagem($aArqs[$iIdx-1]);
}

?>
