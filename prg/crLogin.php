<?php

include 'msg.php';
include 'log.php';
include 'pesqCli.php';

$DirDados = Configuracao('DIR_DADOS');
$DirModelos = Configuracao('DIR_MODELOS_AREACLIENTE');
$DirAnexos = Configuracao('DIR_ANEXOS');
$ServicosExtras = (Configuracao('SERVICOS_EXTRAS_COND') == 'SIM');
$ExibePlanilhaGas = (Configuracao('PLANILHA_GAS_COND') == 'SIM');
$ExibeAnexos = (Configuracao('EXIBE_COND_ANEXOS') == 'SIM');
$ListaCondominos = (Configuracao('EXIBE_LISTA_CONDOMINOS') == 'SIM');
$BoletosEmArvore = (Configuracao('BOLETOS_EM_ARVORE') == 'SIM');
$DirBoletos = Configuracao('DIR_BOLETOS');
$ExtratoComOrdemBloco = (Configuracao('EXTRATO_ORDEMBLOCO') == 'SIM');
$MesesNoSite = Configuracao('MESES_NO_SITE', 3);
$MesesNoSiteBoleto = Configuracao('MESES_NO_SITE_BOLETOS', 2);
$MesesNoSiteExtratoCond = Configuracao('MESES_NO_SITE_EXTRATOCOND', $MesesNoSite);
$ExibeExtratoCondAtual = (Configuracao('EXIBE_EXTRATOCOND_ATUAL', 'SIM') == 'SIM');
$TrocaSenha = (Configuracao('TROCA_SENHA', 'SIM') == 'SIM');
$ExibeDocRetido = (Configuracao('EXIBE_DOC_RETIDO', 'SIM') == 'SIM');
$ExibeResumos = (Configuracao('EXIBE_RESUMOS_COND', 'NAO') == 'SIM');

$CondominiosDoUsuario = array();

//--------------------------------------------------------------------------------
//	Obtem o campo do arquivo de boleto
//--------------------------------------------------------------------------------

function GetField($handle, $field)
{
	$Valor = '';
	rewind($handle);
	while (!feof ($handle))
	{
		$buffer = fgets($handle, 1024);
		if (empty($buffer))
			break;

		$out = explode('=', $buffer);
		if ($out[0] == $field)
		{
			$Valor = trim($out[1]);
			break;
		}
	}

	return $Valor;
}

//----------------------------------------------------------------------------------
function GetObsComerc(&$model, $pId)
{
/*
	Verifica se o usuario tem observacoes de comercializacao.
*/
	GLOBAL $DirDados;

	$bObs = false;
	if (Configuracao('EXIBE_OBS_COMERCIALIZACAO') == 'SIM')
	{
		$file = $DirDados.'obscomerc/P'.$pId.'.txt';
		$bObs = is_file($file);
	}

	$model->assign('OBS_ID', $pId);
	$model->assign('TEM_OBSCOMERC', $bObs? 'T' : '');

	return $bObs;
}

//----------------------------------------------------------------------------------
function GetBoletos(&$model, $pId)
{
/*
	Busca todos os boletos do usuario.
	Obs. Estes arquivos são gerados com "html entities"
*/
	GLOBAL $DirBoletos, $UsingXmlModel, $BoletosEmArvore, $MesesNoSiteBoleto, $CondominiosDoUsuario, $ExibeDocRetido;

//echo "<!-- GetBoletos($pId) -->\n";
	$bTemBoleto = false;
	if ($BoletosEmArvore)
		$dir = sprintf("%s%05d000/0%d00/%d", $DirBoletos, $pId/1000, ($pId%1000)/100, $pId);
	else
		$dir = $DirBoletos.$pId;
//echo "<!-- DIR=$dir\n";
	if (is_dir($dir))
	{
		$Bloquetos = array();
		$fh = opendir($dir);
		while (false !== ($dirEntry = readdir($fh)))
		{
			if ($dirEntry == '.' || $dirEntry == '..')
				continue;
			if (strstr($dirEntry, '.txt') != '.txt')
				continue;
//echo "\n$dirEntry: ";
			$filename = $dir.'/'.$dirEntry;
			$stat = @stat($filename);
			if ($stat === false || $stat['size'] <= 0)
				continue;

			$param = '';
			if (substr($dirEntry, 0, 1) == 'C')
				$Codigo = intval(substr($dirEntry, 1));
			else
				$Codigo = intval($dirEntry);

			$competencia = strstr($dirEntry, 'E');
			if (empty($competencia))
			{
				$docextra = '';
				$bIsDocExtra = false;
			}
			else
			{
				// DOC extra
				$ano = substr($competencia, 1, 4);
				$mes = substr($competencia, 5, 2);
				$dia = substr($competencia, 7, 2);
				if (!checkdate($mes,$dia,$ano))
					continue;

				$datalimite = mktime(0,0,0,$mes+$MesesNoSiteBoleto,$dia,$ano);
				if (time() > $datalimite && strstr($filename, '.sempre.txt') != '.sempre.txt')
				{
                                    // Bloqueto extra ja' expirou e nao e' nome especial que indica ser 
                                    // sempre valido entao nao exibe e tenta remove-lo.
                                    @unlink($filename);
                                    continue;
				}

				$datalimite = mktime(0,0,0,$mes-1,$dia,$ano);
				if (time() <= $datalimite) {
					// Bloqueto extra de mes adiante nao exibe
//echo "datalimite=$datalimite ";
					continue;
				}
				$docextra = ' EXTRA';
				$bIsDocExtra = true;
			}

			if (($handle = @fopen ($filename, 'r')) === false)
				continue;
			if (!$ExibeDocRetido && GetField($handle, 'IsRetido') == 'S')
			{
//echo "DOC RETIDO ";
				fclose ($handle);
				continue;
			}

			$Tipo = GetField($handle, 'TIPO');
			if ($Tipo == 'L')
			{
				$TipoDoc = "DOC$docextra: Loca&ccedil;&atilde;o &nbsp;&nbsp;";
				$param = ' doc_tipo="L" imovel_codigo="'.$Codigo.'"';
			}
			else if ($Tipo == 'C')
			{
				// Extrai o codcondom
				$Aux = explode(':', GetField($handle, 'IsId1'));
				$CodCondom = intval($Aux[1]);
/* DESATIVADO
				// Verifica se condominio esta ativo
				if (!array_key_exists($CodCondom, $CondominiosDoUsuario))
				{
					fclose ($handle);
					continue;
				}
*/
				$TipoDoc = "DOC$docextra: Condom&iacute;nio &nbsp;&nbsp;";
				$param = ' doc_tipo="C" condominio="'.$CodCondom.'" economia_codigo="'.$Codigo.'"';
			}
			else
			{
				fclose ($handle);
				continue;
			}

			$sDataVenc = GetField($handle, 'IsVencto');
			$dia = substr($sDataVenc, 0, 2);
			$mes = substr($sDataVenc, 3, 2);
			$ano = substr($sDataVenc, 6, 4);
//echo "vencto=$dia/$mes/$ano (extraido de $sDataVenc) ";
			if (checkdate($mes,$dia,$ano) !== false)
			{
				$datalimite = mktime(0,0,0,$mes+$MesesNoSiteBoleto,$dia,$ano);
				if (time() > $datalimite)
				{
					// Bloqueto ja' expirou entao nao exibe e tenta remove-lo
					if (strstr($filename, '.sempre.txt') != '.sempre.txt')
					{
						// Nao e' nome especial que indica ser sempre valido
						fclose ($handle);
						@unlink($filename);
						continue;
					}
				}
			}

			$CodBarra = GetField($handle, 'IsCodBarra1');
			$Quitado = '';
			if (empty($CodBarra))
			{
				$DebConta = GetField($handle, 'IsDebConta');
				if (stristr($DebConta, 'Quitado') != false)
					$Quitado = '&nbsp;(QUITADO)';
			}

			$Aux = explode(':', GetField($handle, 'IsId2'));
			$Ender = trim($Aux[1]);
			if ($Tipo == 'C')
			{
				// Acrescenta nome da economia (AP, SALA, etc.)
				$Aux = explode(':', GetField($handle, 'IsId4'));
				if (isset($Aux[2]))
					// Tem outras informacoes no fim desta linha entao retira-las
					$extrair = strlen(strrchr($Aux[1], ' '));
				else
					$extrair = 0;
					
				if ($extrair > 0)
					$Ender .= ' - '.trim(substr($Aux[1], 0, -$extrair));
				else
					$Ender .= ' - '.trim($Aux[1]);
			}
			$Bloquetos[$filename] = array(
				'vencto' => HTMLtoModel("Vencimento: $sDataVenc<br>"),
				'ender' => HTMLtoModel($Ender.$Quitado),
				'extra' => $bIsDocExtra ? "S" : "N",
				'param' => $param,
				'tipo' => HTMLtoModel($TipoDoc) );
			fclose ($handle);
			$bTemBoleto = true;
		}
		closedir($fh);

		$ultCod = 0;
		$cont = 0;
		krsort($Bloquetos);
//echo "\n"; print_r($Bloquetos);
		foreach ($Bloquetos as $file => $infos)
		{
			$aPath = explode('/', $file);
			$arq = $aPath[count($aPath)-1];
			$cod = intval(substr($arq, 1));
			if ($infos['extra'] == "N")
			{
				// So faz contagem de DOC normal
				if ($cod != $ultCod) 
					$cont = 1;
				else 
					$cont++;
				if ($cont > $MesesNoSiteBoleto)
					continue;
			}

			$model->assign('DESCR_PARAM', $infos['param']);
			$model->assign('VENC_BOLETO', $infos['vencto']);
			$model->assign('END_BOLETO', $infos['ender']);
			$model->assign('TIPO_BOLETO', $infos['tipo']);
			$model->assign('FBOLETO', $file);
			$model->assign('BTN_SEGUNDAVIA', '<input type=submit value=" Exibir" name="btn"><BR><BR>');  // Forma antiga com toda tag do botao
			$model->parse('.LISTA_BOLETOS');
			$ultCod = $cod;
		}
	}
//echo "-->\n";

	return $bTemBoleto;
}

//----------------------------------------------------------------------------------
function GetPlanilhas(&$model, $CodCondom)
{
	GLOBAL $DirDados, $UsingXmlModel, $PlanilhasGeradas;
	
//echo "<!-- GetPlanilhas($CodCondom) -->\n";
	$model->assign('DESCR_PARAM','');
	$DirGasAgua = $DirDados.'gasagua/'.$CodCondom;
	if (file_exists($DirGasAgua))
	{
//echo "<!-- DirGasAgua=$DirGasAgua -->\n";
		// Acrescenta botao para exibir planilha de gas separadamente para o sindico.
		// Ex.: .../gasagua/00108/G00108UNC-201404.csv
		$dtAtual = date('Ym', mktime(0,0,0, date('m'), 1, date('y')));
		$dtAnt = date('Ym', mktime(0,0,0, date('m')-1, 1, date('y')));
		$aDir = scandir($DirGasAgua);
		rsort($aDir);
//echo "<!-- Arq's GasAgua\n";
//print_r($aDir);
//echo "PlanilhasGeradas=$PlanilhasGeradas\n";
//echo "-->\n";

		for ($i = 0; $i < 2; $i++)
		{
		    $tipoPlan = ($i == 0 ? 'G' : 'A');
			$UltBloco = '';
		    $Cont = 0;
//echo "\n<!-- tipoPlan=$tipoPlan -->\n";

			foreach ($aDir as $UserFile)
			{
//echo "<!-- UserFile=$UserFile -->\n";
				if ($UserFile[0] != $tipoPlan[0] || !is_file("$DirGasAgua/$UserFile"))
					continue;

				if (preg_match('/ '.substr($UserFile,0,-11).' /', $PlanilhasGeradas))
					continue;

				$bloco = substr($UserFile, 6, 3);
				if ($bloco == $UltBloco)
					continue;

				$dt = $dtAtual;
//echo '<!-- preg_match(/'.$tipoPlan.'.*-'.$dt.'\.csv/, "'.$UserFile."\") -->\n";
				$bTem = preg_match('/'.$tipoPlan.'.*-'.$dt.'\.csv$/', $UserFile);
				if (!$bTem)
				{
					$dt = $dtAnt;
//echo '<!-- preg_match(/'.$tipoPlan.'.*-'.$dt.'\.csv/, "'.$UserFile."\") -->\n";
					$bTem = preg_match('/'.$tipoPlan.'.*-'.$dt.'\.csv$/', $UserFile);
				}

				if ($bTem)
				{
//echo "<!-- [[ ACHOU planilha $UserFile ]] -->\n";
					$UltBloco = $bloco;
					$File = @fopen("$DirGasAgua/$UserFile", 'r+');
					if ($File === FALSE)
						continue;
					$aReg = fgetcsv($File, 1024, ';');
					fclose($File);

					$PlanilhasGeradas .= substr($UserFile,0,-11).' ';
					if ($Cont == 0)
					{
						$model->assign('PRODUTO', ISO8859_1toModel('Planilha de Consumo de '.($tipoPlan == 'G' ? 'Gás' : 'Água')));
						$model->assign('PROD', $tipoPlan);
						$model->assign('DESCR', ISO8859_1toModel($aReg[3])); // Nome do Condominio
						$model->assign('BTN_PHP', 'planilhaGas.php');
						$model->assign('CHAVE', $CodCondom);
					}
					// Forma nova com a tag do botao no shtml
					$bloco = $aReg[4].' - '.substr($dt,4,2).'/'.substr($dt,0,4); // Nome do Bloco e competencia
					if ($UsingXmlModel)
						$model->assign('BTN_VAL', ISO8859_1toModel($UserFile.'|'.$bloco));
					else
					{
						$model->assign('BTN_VAL', ISO8859_1toModel($bloco));
						$model->assign('BTN_VAL_EXTRA', $UserFile);
					}
					$model->assign('BTN_SEQ', 1);
					$model->assign('BTN_ARQ', $UserFile);
					$model->parse($Cont == 0 ? 'LISTA_BOTOES' : '.LISTA_BOTOES');
					$model->assign('BTN_MES', ''); // Forma antiga com toda tag do botao
					$Cont++;
//echo "<!-- [[ Botao $bloco ]] -->\n";
				}
//else echo "<!-- [[ NAO serve a planilha $UserFile ]] -->\n";
			}

			if ($Cont != 0)
				$model->parse('.LISTA_SERVICOS');
		}
	}
}

//----------------------------------------------------------------------------------
function GetServices(&$model, $CondLoc, $pId)
{
/*
	registro de acesso{
	id             10;
	produto         1;
	chave           8;
	assessor       35;
	tipo            1;
	assessor_email 50;
	descricao      80;
	condomino       1;      'S'indico; 'N'ormal;
	----> Formato original: 186 bytes ate aqui <----
	tipo_bloco      1;      'N'ormal; 'F'undo Reserva;
	ordembloco      2;
}
*/
	GLOBAL $DirDados, $DirAnexos, $ServicosExtras, $ExtratoComOrdemBloco, $ExibeAnexos, 
			$ListaCondominos, $CondominiosDoUsuario, $ExibeExtratoCondAtual, $ExibeResumos,
			$MesesNoSite, $MesesNoSiteExtratoCond, $ExibePlanilhaGas, $UsingXmlModel;

	$iTamReg = 186;
	$PlanilhasGeradas = ' ';

	if ($CondLoc == 'Loc')
	{
		$name = $DirDados.'AcessoLoc.txt';
		$bInadimpSeparado = false;
		$MesesExibir = $MesesNoSite;
		$ExibeExtratoAtual = true;
	}
	else
	{
		$name = $DirDados.'AcessoCond.txt';
		$bInadimpSeparado = (Configuracao('EXIBE_INADIMPL_SEPARADO') == 'SIM');
		$MesesExibir = $MesesNoSiteExtratoCond;
		$ExibeExtratoAtual = $ExibeExtratoCondAtual;
	}
	$stat = @stat($name);
	if ($stat === false || $stat['size'] <= 0)
		return 0;

	$file = @fopen($name, 'r');
	if ($file === false)
		return 0;
	$bNenhum = 1;
	$slast = '';
	$pId = trim($pId);
	$UltAnexo = '';
	$UltLista = '';
	$UltPlanilha = '';
    $UltResumo = '';
	$aBlocos = array();

	$cont = 1;
	for(;;)
	{
		$sReg = fgets($file, 4096);
		if (empty($sReg) || strlen($sReg) < $iTamReg+1)
			break;

		$Id = trim(substr($sReg, 0, 10));
		if ( strcmp($pId, $Id) == 0)
		{
//echo "<!-- $sReg -->\n";
			$Tipo = substr($sReg, 54, 1);
			if ( strcmp($Tipo, 'P') == 0)
				$Tipo = 'Proprietário';
			elseif ( strcmp($Tipo, 'C') == 0)
				$Tipo = 'Condômino';
			elseif ( strcmp($Tipo, 'S') == 0)
				$Tipo = 'Síndico';

			// Verifica o produto.
			$TipoProd = substr($sReg, 10, 1);
			if ($TipoProd == 'C')
				$Produto = 'Extrato de Condomínio';
			elseif ($TipoProd == 'A')
				$Produto = 'Extrato de Proprietário';
			elseif ($TipoProd == 'S')
				$Produto = 'Demonstrativo Sintético';
			elseif ($TipoProd == 'L')
				$Produto = 'Demonstrativo de Proprietário';
			elseif ($TipoProd == 'I')
				$Produto = 'Inadimplências';
			else
				continue;

			$Assessor = substr($sReg, 19, 35);
			$EAssessor = substr($sReg, 55, 50);
			$Chave = trim(substr($sReg, 11, 8));
			$Descr = trim(substr($sReg, 105, 80));
			$Condomino = substr($sReg, 185, 1);
			SetSessao('tipo_cond', $Condomino);
			$model->assign('DESCR_PARAM','');

			if ($CondLoc == 'Loc')
			{
				$TipoBloco = '?';
				$Filial = trim(substr($sReg, 186, 3));
				if (!empty($Filial))
				{
					$Descr .= " (Filial $Filial)";
					$Chave = $Chave.'-'.$Filial;
				}
			}
			else
			{
				$CodCondom = intval(substr($Chave, 0, 5));
//				if (array_key_exists($CodCondom, $CondominiosDoUsuario))
                    // Este condominio ja' foi tratado.
//                    continue;

                $CodBloco = substr($Chave, 5, 3);
				$NomeCondom = trim(substr($sReg, 105, 39));
				$TipoBloco = trim(substr($sReg, 186, 1));
				if (empty($TipoBloco) || $TipoBloco == "\n")
					// Formato antigo entao assume como normal.
					$TipoBloco = ($CodBloco == 'XXX' ? ' ' : 'N');

				if ($ExtratoComOrdemBloco)
				{
					$Ordem = substr($sReg, 187, 2);
					if (!empty($Ordem))
						$Chave = sprintf("%s_%02d_%s", substr($Chave, 0, 5),$Ordem,$CodBloco);
				}

				if ($ServicosExtras)
				{
                    $model->assign('CODIGO_CONDOMINIO', $CodCondom);
                    $model->assign('NOME_CONDOMINIO', ISO8859_1toModel($NomeCondom));
                    $model->assign('USUARIO_TIPO', $Condomino);
                    $model->parse(count($CondominiosDoUsuario)==0 ? 'SERVICOS_EXTRAS_COND' : '.SERVICOS_EXTRAS_COND');
				}
				$CondominiosDoUsuario[$CodCondom] = array($NomeCondom);
				$model->assign('DESCR_PARAM', ' condominio="'.$CodCondom.'" bloco="'.$CodBloco.'" bloco_tipo="'.$TipoBloco.'"');
			}
//echo "<!-- $Chave -->\n";
			$model->assign('PRODUTO', ISO8859_1toModel($Produto));
			$model->assign('DESCR', ISO8859_1toModel($Descr));
			$model->assign('BTN_PHP', 'exibeDoc.php');
			$model->assign('ID', $Id);
			$model->assign('PROD', $TipoProd);
			$model->assign('ASSESSOR', ISO8859_1toModel(rtrim($Assessor)));
			$model->assign('ASSESSOR_EMAIL', rtrim($EAssessor));
			$model->assign('TIPO', $UsingXmlModel ? urlencode($Tipo) : $Tipo);
			$model->assign('TIPO_CONDOMINIO', $TipoBloco);
			$model->assign('TIPO_CONDOMINO', $Condomino);
			$model->assign('CHAVE', $Chave);
			$model->assign('DESC_SERV', $UsingXmlModel ? urlencode($Descr) : ISO8859_1toModel($Descr));

			// Monta nome do arquivo fisico
			$btn = '';
			$mes = date('m');
			$ano = date('y');
//echo "<!-- $mes/$ano -->\n";
			$iContItem = 0;
			for($i=0; $i<$MesesExibir; $i++)
			{
				if ($ExibeExtratoAtual || $i > 0) {
					$dt = date('ym', mktime(0,0,0, $mes, 1, $ano));
					$UserFile = $TipoProd.$Chave.'.'.$dt;
					if (@file_exists($DirDados.$UserFile))
					{
						if (filesize($DirDados.$UserFile) > 0)
						{
//echo "<!-- $UserFile -->\n";
							$data = substr($dt,2,2).'/20'.substr($dt,0,2);
							$btn .= "<input type=submit value=\" Exibir $data\" name=\"btn\" ><!-- $UserFile ($iContItem)--><BR><BR>\n";

							// Forma nova com a tag do botao no shtml
							$model->assign('BTN_VAL', $data);
							$model->assign('BTN_SEQ', $iContItem+1);
							$model->assign('BTN_ARQ', $UserFile);
							$model->parse($iContItem++==0 ? 'LISTA_BOTOES' : '.LISTA_BOTOES');
                                                        
							if ($TipoBloco == 'N')
								$aBlocos[] = $UserFile;
						}
					}
//else echo "<!-- NAO ABRIU $UserFile -->\n";
				}
				$mes--;
				if ($mes <= 0) {
					$mes = 12;
					$ano--;
				}
			}
			$model->assign('BTN_MES', $btn); // Forma antiga com toda tag do botao

			if ($iContItem > 0)
			{
				$model->parse('.LISTA_SERVICOS');
				$bNenhum = 0;

				if ($TipoBloco == 'N')
				{
					if ($bInadimpSeparado)
					{
						// Acrescenta botao para exibir inadimplencia separadamente para o sindico
						$dt = date('ym', mktime(0,0,0, date('m'), 1, date('y')));
						$UserFile = $TipoProd.$Chave.'.'.$dt;
						if (($File=@fopen($DirDados.$UserFile, 'r')) !== false)
						{
							// Pula reg. header
							for (;;) {
								$sReg = fgets($File, 4098);
								if (empty($sReg) || substr($sReg, 0, 1) == 'S')
									break;
							}
							if (substr($sReg, 0, 1) == 'S')
							{
								// E' reg. de Sindico
								$bConselheiro = false;
								$conselho = explode(',', substr(trim($sReg),1));
								foreach ($conselho as $aux)
								{
									$aux = intval($aux);
									if ($aux == intval($Id))
									{
										$bConselheiro = true;
										break;
									}
								}

								if ($bConselheiro)
								{
									$model->assign('PRODUTO', ISO8859_1toModel('Inadimplência'));
									$model->assign('PROD', 'I');
									$model->assign('BTN_PHP', 'exibeDoc.php');

									// Forma nova com a tag do botao no shtml
									$data = substr($dt,2,2).'/20'.substr($dt,0,2);
									$model->assign('BTN_VAL', $data);
									$model->assign('BTN_SEQ', 1);
									$model->assign('BTN_ARQ', $UserFile);
									$model->parse('LISTA_BOTOES');

									$btn = "<input type=submit value=\" Exibir $data\" name=\"btn\"><!-- $UserFile --><BR><BR>\n";
									$model->assign('BTN_MES', $btn); // Forma antiga com toda tag do botao

									$model->parse('.LISTA_SERVICOS');
								}
							}
							fclose($File);
						}
					}

					if ($ListaCondominos && $TipoProd == 'C' && $Condomino == 'S' && $CodCondom != $UltLista)
					{
						// Acrescenta botao para exibir lista de condominos
						$UserFile = 'I'.str_pad($CodCondom, 5, '0', STR_PAD_LEFT).'.TXT';
						$arq = @fopen($DirDados.$UserFile, 'r');
						if ($arq !== false)
						{
							$model->assign('PRODUTO', ISO8859_1toModel('Lista de Condôminos'));
							$model->assign('PROD', 'L');
							$model->assign('BTN_PHP', 'condominos.php');

							$Descr = fgets($arq, 4096);
							$TipoReg = $Descr{0};
							if ($TipoReg == 'C')	// Cabecalho
							{
								$Descr = substr($Descr, 6, 60);
								$iPos = strpos($Descr, '-');
								$Descr = trim(substr($Descr, $iPos+1));
								$model->assign('DESCR', ISO8859_1toModel($Descr));
							}
							fclose($arq);

							// Forma nova com a tag do botao no shtml
							$model->assign('BTN_VAL', '');
							$model->assign('BTN_SEQ', 1);
							$model->assign('BTN_ARQ', $UserFile);
							$model->parse('LISTA_BOTOES');

							$model->assign('BTN_MES', ''); // Forma antiga com toda tag do botao

							$model->parse('.LISTA_SERVICOS');
						}
						$UltLista = $CodCondom;
					}

					if ($ExibeAnexos && $TipoProd == 'C' && $CodCondom != $UltAnexo)
					{
						$DirAnexosCond = $DirAnexos.'Condom/'.$CodCondom;
						if (is_dir($DirAnexosCond))
						{
							$arq = @fopen($DirDados.$UserFile, 'r');
							if ($arq !== false)
							{
								$Descr = fgets($arq, 4096);
								if ($Descr{0} == ' ')	// Cabecalho
								{
									$Descr = substr($Descr, 26, 60);
									$model->assign('DESCR', ISO8859_1toModel(trim($Descr)));
									$model->assign('DESC_SERV', ISO8859_1toModel(trim($Descr)));
								}
								fclose($arq);
							}
							$model->assign('PRODUTO', ISO8859_1toModel('Documentos do Condomínio (atas, convocações, etc.)'));
							$model->assign('PROD', 'C');
							$model->assign('BTN_PHP', 'anexo.php');
							$model->assign('CHAVE', $CodCondom);

							// Forma nova com a tag do botao no shtml
							$model->assign('BTN_VAL', 'Documentos');
							$model->assign('BTN_SEQ', 1);
							$model->assign('BTN_ARQ', $UserFile);
							$model->assign('DESCR_PARAM', ' condominio="'.$CodCondom.'"');
							$model->parse('LISTA_BOTOES');

							$model->assign('BTN_MES', ''); // Forma antiga com toda tag do botao

							$model->parse('.LISTA_SERVICOS');
						}
						$UltAnexo = $CodCondom;
					}

					$Planilha = substr($Chave, 0, 5);
					if ($ExibePlanilhaGas && $TipoProd == 'C' && $Condomino == 'S' && $Planilha != $UltPlanilha)
					{
//echo "<!-- UltChave=$UltPlanilha -->\n";
						$UltPlanilha = $Planilha;
						GetPlanilhas($model, $Planilha);
					}
                    
					if ($ExibeResumos && $TipoProd == 'C' && $Condomino == 'S' && $CodCondom != $UltResumo)
					{
						// Acrescenta botao para exibir resumo de DOCs
						$UserFile = 'RDOC'.str_pad($CodCondom, 5, '0', STR_PAD_LEFT);
						$arq = @fopen($DirDados.$UserFile.'N.csv', 'r');
						if ($arq === false)
    						$arq = @fopen($DirDados.$UserFile.'E.csv', 'r');
						if ($arq !== false)
						{
							$model->assign('PRODUTO', ISO8859_1toModel('Resumo de DOCs'));
							$model->assign('PROD', 'RDOC');
							$model->assign('BTN_PHP', 'resumosCond.php');

                            $aReg = fgetcsv($arq, 1024, ';');

							if ($aReg[0] == 'HE')	// Cabecalho
							{
								$Descr = substr($Descr, 6, 60);
								$iPos = strpos($Descr, '-');
								$Descr = trim(substr($Descr, $iPos+1));
								$model->assign('DESCR', ISO8859_1toModel($aReg[4]));
							}
							fclose($arq);

							// Forma nova com a tag do botao no shtml
							$model->assign('BTN_VAL', '');
							$model->assign('BTN_SEQ', 1);
							$model->assign('BTN_ARQ', '');
							$model->parse('LISTA_BOTOES');

							$model->assign('BTN_MES', ''); // Forma antiga com toda tag do botao

							$model->parse('.LISTA_SERVICOS');
						}

						// Acrescenta botao para exibir resumo de receitas e despesas
						$UserFile = 'RRD'.str_pad($CodCondom, 5, '0', STR_PAD_LEFT).'.csv';
						$arq = @fopen($DirDados.$UserFile, 'r');
						if ($arq !== false)
						{
							$model->assign('PRODUTO', ISO8859_1toModel('Resumo de Receitas e Despesas'));
							$model->assign('PROD', 'RRD');
							$model->assign('BTN_PHP', 'resumosCond.php');

                            $aReg = fgetcsv($arq, 1024, ';');

							if ($aReg[0] == 'HE')	// Cabecalho
							{
								$Descr = substr($Descr, 6, 60);
								$iPos = strpos($Descr, '-');
								$Descr = trim(substr($Descr, $iPos+1));
								$model->assign('DESCR', ISO8859_1toModel($aReg[4]));
							}
							fclose($arq);

							// Forma nova com a tag do botao no shtml
							$model->assign('BTN_VAL', '');
							$model->assign('BTN_SEQ', 1);
							$model->assign('BTN_ARQ', '');
							$model->parse('LISTA_BOTOES');

							$model->assign('BTN_MES', ''); // Forma antiga com toda tag do botao

							$model->parse('.LISTA_SERVICOS');
						}

                        $UltResumo = $CodCondom;
					}
				}
			}
		}
	}

	fclose($file);
	SetSessao('blocos', $aBlocos);

	if ($bNenhum)
		return 0;
	return 1;
}

//----------------------------------------------------------------------------------
function GetInfoIR(&$model, $pId, $bMultiplos)
{
/*
	Busca todos as informacoes de IR do usuario.
*/
	GLOBAL $DirDados;

//echo("<!-- GetInfoIR(model, $pId, $bMultiplos) -->\n");
	if (Configuracao('EXIBE_IR_ANUAL') != 'SIM')
		return false;

	$DirIRanual = Configuracao('DIR_IRANUAL');
	$iContItem = 0;
	$bTemInfo = false;
	$pId = intval($pId);
	$dir = sprintf('%s%d000/%d', $DirIRanual, $pId/1000, $pId);
	if (is_dir($dir))
	{
		$files = scandir($dir);
		foreach ($files as $dirEntry)
		{
			if (intval(substr($dirEntry, 0, 8)) != $pId || strstr($dirEntry, '.csv') != '.csv')
				continue;

			$file = $dir.'/'.$dirEntry;
			$stat = @stat($file);
			if ($stat === false || $stat['size'] <= 0)
				continue;

			$bTemInfo = true;
			$Comp = substr($dirEntry, 9, 4);
			$model->assign('COMPETENCIA', $Comp);
			$model->assign('IR_ID', $bMultiplos ? 'Cod.'.$pId : '');
			$model->parse($iContItem++==0 ? 'LISTA_INFOS_IR' : '.LISTA_INFOS_IR');
		}
	}

	if ($bTemInfo)
	{
		$model->assign('ID', $pId);
		$model->parse('.INFOS_IR_ANUAL');
	}

//echo("<!-- GetInfoIR: $bTemInfo -->\n");
	return $bTemInfo;
}

//---main-------------------------------------------------------------------------

//Mensagem('Aviso', 'Site em manutenção!'); return 0;

	SetSessao('ORIGEM_MSG', 'O'); //cliente on-line
	if (isset($_SERVER['HTTP_REFERER']))
		SetSessao('ORIGEM_TELA', $_SERVER['HTTP_REFERER']); 

	$pId = Campo('LOGIN');
	if (!empty($pId))
	{
		// Verifica se deve gerar XML.
		$XmlMode = (strcasecmp(Campo('FORMATO'), 'XML') == 0);
		SetSessao('XML_MODE', $XmlMode);

		// As paginas podem ser geradas com 3 diferentes charsets.
		$ModelCharset = Campo('CHARSET');
		if (empty($ModelCharset))
			$ModelCharset = $XmlMode ? CHARSET_ISO8859_1 : CHARSET_HTML;
		else if (strcasecmp($ModelCharset, 'ISO-8859-1') == 0 || strcasecmp($ModelCharset, 'ISO8859-1') == 0)
			$ModelCharset = CHARSET_ISO8859_1;
		else if (strcasecmp($ModelCharset, 'UTF-8') == 0 || strcasecmp($ModelCharset, 'UTF8') == 0)
			$ModelCharset = CHARSET_UTF8;
		else if (strcasecmp($ModelCharset, 'HTML') == 0)
			$ModelCharset = CHARSET_HTML;
		else
		{
			Mensagem('ERRO', 'CHARSET com valor inválido');
			exit;
		}
		SetSessao('MODEL_CHARSET', $ModelCharset);

		// Trata demais campos do login.
		$pPass = CampoObrigatorio('SENHA');
		$usuario_id = ValidaLogin($pId, $pPass);
//echo "<!-- "; print_r($usuario_id); print(" -->\n");

		$usuario = array_shift($usuario_id);
		$usuario_cpfcnpj = array_shift($usuario_id);
		$usuario_email = array_shift($usuario_id);
		SetSessao('usuario', $usuario);
		SetSessao('usuario_cpfcnpj', $usuario_cpfcnpj);
		SetSessao('usuario_email', $usuario_email);
		SetSessao('usuario_id', $usuario_id);

		$sSenhaDefault = Configuracao('SENHA_DEFAULT', '');
		if (!empty($sSenhaDefault))
		{
			$sValorDefault = @eval("return $sSenhaDefault;");
//echo "<!-- usuario_id="; print_r($usuario_id); print(" sSenhaDefault='$sSenhaDefault' sValorDefault='$sValorDefault' -->\n");
			if ($pPass == $sValorDefault)
			{
				Submeter('alteraSenha.php',
					array('OPERACAO'=>'expirou','ORIG'=>'crLogin','REFERER'=>$_SERVER["HTTP_REFERER"],
							'SENHA'=>$pPass, 'SENHADEFAULT'=>$sValorDefault));
			}
		}
	}
	else
	{
		$logout = GetSessao('logout_url');
		$usuario = GetSessao('usuario');
		$usuario_id = GetSessao('usuario_id');
		$usuario_cpfcnpj = GetSessao('usuario_cpfcnpj');
		$usuario_email = GetSessao('usuario_email');
		if (!empty($logout) || empty($usuario) || empty($usuario_id))
		{
			$page = Campo('page');
			if (empty($page))
				$page = GetSessao('login_url');

			if (empty($page))
				Mensagem('Erro', 'Sessão expirada, efetue novo LOGIN!');
			else
			{
				SetSessao('login_url', $page);
				header("Location: $page");
			}
			exit;
		}
		$pId = $usuario_id[0];
	}

	// Vai preencher as linhas da tabela com os servicos disponiveis
	$model = new DTemplate($DirModelos);
	$model->define_templates( array ( 'clionline' => Modelo($DirModelos, 'clionline_new')));
	$model->define_dynamic('LISTA_SERVICOS', 'clionline');		//body is the parent of table
	$model->define_dynamic('LISTA_BOTOES', 'LISTA_SERVICOS');	//body is the parent of table
	$model->define_dynamic('LISTA_BOLETOS', 'clionline');
	$model->define_dynamic('SERVICOS_EXTRAS_COND', 'clionline');
	$model->define_dynamic('INFOS_IR_ANUAL', 'clionline');
	$model->define_dynamic('LISTA_INFOS_IR', 'INFOS_IR_ANUAL');

	$model->assign('ID', $pId);
	$model->assign('USUARIO', ISO8859_1toModel($usuario));
	$model->assign('USUARIO_EMAIL', $usuario_email);
	if ($UsingXmlModel)
	{
		$model->assign('SESSID',session_id());
		$model->assign('TIMESTAMP', time());
		$model->assign('HOST', 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"]).'/');
	}
    else
        $model->assign('TROCA_SENHA', $TrocaSenha ? "" : "none");

	$cont = count($usuario_id) - 1;
	$ServicosL = $ServicosC = $ServicosB = $ServicosO = $ServicosIR = false;

	for ($i = $cont; $i >= 0; $i--)
		$ServicosL |= GetServices($model, 'Loc', $usuario_id[$i]);
	for ($i = $cont; $i >= 0; $i--)
		$ServicosC |= GetServices($model, 'Cond', $usuario_id[$i]);
	for ($i = $cont; $i >= 0; $i--)
		$ServicosB |= GetBoletos($model, $usuario_id[$i]);
	for ($i = $cont; $i >= 0; $i--)
		$ServicosO |= GetObsComerc($model, $usuario_id[$i]);
	for ($i = $cont; $i >= 0; $i--)
		$ServicosIR |= GetInfoIR($model, $usuario_id[$i], $cont>0);

	if (!$ServicosL && !$ServicosC && !$ServicosB && !$ServicosO && !$ServicosIR)
	{
		Mensagem('Aviso', 'Não existem informações para este usuário!');
		return 0;
	}
	if (!$ServicosL && !$ServicosC)
		$model->clear_dynamic('LISTA_SERVICOS');
	if (!$ServicosC)
		// Se nao tem condominios entao nao tem servicos extras de condominio
		$ServicosExtras = false;
	if (!$ServicosExtras)
		$model->clear_dynamic('SERVICOS_EXTRAS_COND');
	if (!$ServicosB)
		$model->clear_dynamic('LISTA_BOLETOS');
	if (!$ServicosIR)
		$model->clear_dynamic('INFOS_IR_ANUAL');

	SetSessao('produto', 'cred');
	SetSessao('logout_url', '');
	phpLog('LI', 'Cliente-online', $pId);

/*
echo "<!--\n";
echo '$_SESSION = ';
print_r($_SESSION);
global $HTTP_SESSION_VARS;
echo '$HTTP_SESSION_VARS = ';
print_r($HTTP_SESSION_VARS);
echo "\n-->\n";
*/
	$model->parse('clionline');
	$model->DPrint('clionline');

?>
