<?php
include 'msg.php';
include 'imagens.inc.php';

$DirAnexos = Configuracao('DIR_ANEXOS');
$DirImagens = Configuracao('DIR_IMAGENS');
$DirModelos = Configuracao('DIR_MODELOS_AREACLIENTE');

//---main---------------------------------------------------------------------

$usuario = GetSessao('usuario');
$usuario_id = GetSessao('usuario_id');
if (empty($usuario) || empty($usuario_id))
{
	// Ja' foi efetuado um logout, deve ser pagina anterior.
	$sUrl = GetSessao('login_url');
	if (empty($sUrl))
		Mensagem('Erro', 'Sessão encerrada, efetue o LOGIN!');
	else
		header('Location: ' .$sUrl);
	exit;
}

$orig = CampoObrigatorio('PROD');
$codigo = CampoObrigatorio('CHAVE');
$val = intval($codigo);
if ($orig == 'C'){
	$Relacion = 'CONDOMÍNIO';
	$dirOrigem = 'Condom';
}
/*
else if ($orig == 'L')
	$Relacion = 'LOCAÇÃO';
	$dirOrigem = 'Locacao';
 *
 */
else {
	Mensagem('Aviso', 'Esta informação não está disponível!');
	exit;
}

// Definicoes do template
$model = new DTemplate($DirModelos);
$model-> define_templates( array ( 'anexo' => Modelo($DirModelos, 'anexo')));
$model->define_dynamic('CATEGORIA', 'anexo');
$model->define_dynamic('ANEXO', 'CATEGORIA');

$model->assign('TIPO_RELACIONAMENTO', ISO8859_1toModel($Relacion));

if ($UsingXmlModel)
{
	$model->define_dynamic('URL', 'ANEXO');
	$model->assign('CODIGO', $codigo);
	$model->assign('USUARIO', ISO8859_1toModel($usuario));
	$model->assign('DATA_ATUAL', date('d/m/Y H:i'));
	$UrlBase = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"]).'/exibeAnexos.php';
}
else
{
	$model->assign('DESCR_SERV', ISO8859_1toModel(Campo('DESC_SERV')));
}

// Monta a(s) tabela(s) de categoria(s) dos anexos
$iContCateg = 0;
$subdirAnexos = sprintf('%s/%d', $dirOrigem, $codigo);
if (is_dir($DirAnexos.$subdirAnexos))
{
	// Obtem todas as categorias - diretorios
	$categs = scandir($DirAnexos.$subdirAnexos);
	foreach( $categs as $categ )
	{
		if ($categ == '.' || $categ == '..')
			continue;

		$model->assign('CATEG_ANEXO', decodeStr($categ));
		
		$dirCateg = $subdirAnexos.'/'.$categ;
		if (is_dir($DirAnexos.$dirCateg)){
			// Obtem todas as descricoes das categoria
			$iContAnexos = 0;
			$descrs = scandir($DirAnexos.$dirCateg);
			foreach( $descrs as $descr )
			{
				if ($descr == '.' || $descr == '..')
					continue;

				$tipoImg = $dirCateg.'/'.$descr.'/AI';
				$tipoPdf = $dirCateg.'/'.$descr.'/AD';
				if (file_exists($DirAnexos.$tipoImg) && is_dir($DirAnexos.$tipoImg))
				{
					// Anexos do tipo imagem
					// Cria link para abrir pagina de navegacao de fotos
					$dirImagem = $categ.'/'.$descr.'/AI';
					$model->assign('ANEXO_DESCR', ISO8859_1toModel(decodeStr($descr)));
					$model->assign('ANEXO_TIPO_ALT', 'Imagem');
					if ($UsingXmlModel)
					{
						$iSeq = 1 ;
						$sDir = sprintf($DirAnexos."%s/%d/%s/", $dirOrigem, $codigo, $dirImagem);
						$aArqs = BuscaImagens($sDir);
						foreach ($aArqs as $sArq) {
							$stat = stat($DirAnexos.$sArq);
							if ($stat === false || $stat['size'] <= 0)
								continue;

							//$link = sprintf('%s?cod=%d&idx=%d&param=%s&t=%d', $UrlBase, $codigo, $iSeq, urlencode($dirImagem), $stat['mtime']);
							//$model->assign('ANEXO_LINK', $link);
							$model->assign('ANEXO_LINK', GetFullUrl($DirAnexos.$sArq));
							$model->parse($iSeq++ == 1 ? 'URL' : '.URL');
						}
					}
					else
					{
						$link = "AbreImagens($codigo, '$dirImagem')";
						$model->assign('ANEXO_LINK', $link);
						$icone = $DirImagens.'anexo-img.png';
						$model->assign('ANEXO_TIPO', $icone);
					}
					$model->parse($iContAnexos++==0 ? 'ANEXO' : '.ANEXO');
				}
				else if (file_exists($DirAnexos.$tipoPdf) && is_dir($DirAnexos.$tipoPdf))
				{
					// Anexos do tipo documento - apenas PDF
					// Cria link de todos os arquivos
					$icone = $DirImagens.'anexo-pdf.png';
					$iContDocs = 0;
					$docs = scandir($DirAnexos.$tipoPdf);
					foreach( $docs as $doc )
					{
						if ($doc == '.' || $doc == '..')
							continue;

						$sArq = $tipoPdf.'/'.$doc;
						$stat = stat($DirAnexos.$sArq);
						if ($stat === false || $stat['size'] <= 0)
							continue;

						$model->assign('ANEXO_DESCR', ISO8859_1toModel(decodeStr($descr)));
						$model->assign('ANEXO_TIPO_ALT', 'PDF');
						if ($UsingXmlModel)
						{
							//$link = sprintf('%s?pdf=%s&t=%d', $UrlBase, urlencode($sArq), $stat['mtime']);
							//$model->assign('ANEXO_LINK', $link);
							$model->assign('ANEXO_LINK', GetFullUrl($DirAnexos.$sArq));
							$model->parse('URL');
						}
						else
						{
							$link = "AbrePdf('$sArq')";
							$model->assign('ANEXO_LINK', $link);
							$model->assign('ANEXO_TIPO', $icone);
						}
						$model->parse($iContAnexos++==0 ? 'ANEXO' : '.ANEXO');
						break;//Aceita apenas um arquivo PDF por pasta!!!
					}
				}
			}
		}

		if ($iContAnexos > 0)
			$model->parse($iContCateg++==0 ? 'CATEGORIA' : '.CATEGORIA');
	}
}

if ($iContCateg == 0)
{
	Mensagem('Aviso', 'Nenhum anexo no momento!');
	exit;
}

$model->parse('anexo');
$model->DPrint('anexo');
?>
