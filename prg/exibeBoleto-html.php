<?php
include 'msg.php'; 
include 'BarCode.php';

header('Content-Type: text/html; charset=ISO-8859-1');

$DirModelos = Configuracao('DIR_MODELOS');
$DirImagens = Configuracao('DIR_IMAGENS');

if(!function_exists('fnmatch')) {
	// Esta funcao nao esta disponivel no Windows
    function fnmatch($pattern, $string) {
        return preg_match("#^".strtr(preg_quote($pattern, '#'), array('\*' => '.*', '\?' => '.'))."$#i", $string);
    }

}

//----------------------------------------------------------------------------------
function GetTag($Field, $VetField)
{
	if (empty($VetField))
		return '';

	reset ($VetField);
	while (list ($chave, $valor) = each ($VetField))
	{
		if ($chave == $Field)
			return $valor;
	}
	return '';
}

//---main-------------------------------------------------------------------------

$usuario = GetSessao('usuario');
$usuario_id = GetSessao('usuario_id');
if (empty($usuario) || empty($usuario_id))
{
	// Ja foi efetuado um logout, deve ser pagina anterior.
	$sUrl = GetSessao('login_url');
	if (empty($sUrl))
		Mensagem('Erro', 'Sess&atilde;o encerrada, efetue o LOGIN!');
	else
		header('Location: ' .$sUrl);
	exit;
}

$filename = CampoObrigatorio('fboleto');
echo('<!-- '.substr($filename,16)." -->\n");
$stat = @stat($filename);
if ($stat === false || $stat['size'] <= 0 || ($handle=fopen($filename, 'r')) === false)
{
	Mensagem('Aviso', 'DOC n&atilde;o est&aacute; dispon&iacute;vel no momento!');
	exit (0);
}

$model = new DTemplate($DirModelos);
$model->define_templates( array( 'bolonline' => 'boleto.shtml'));

$sLocalLogo = $DirImagens.'logos';
if (!is_dir($sLocalLogo))
{
	Mensagem('Aviso', 'Diretorio de logotipos de bancos n&atilde;o dispon&iacute;vel. Favor contactar a Imobili&aacute;ria!');
	return;
}

//Vetor associativo que contem 
//campo do arquivo => tag do shtml
$VetFieldTag = array (
	'TIPO' => 'TIPO',
	'IsTipoDoc' => 'IsTipoDoc',
	'IsImob1' => 'IsImob1',
	'IsImob2' => 'IsImob2',
	'IsImob3' => 'IsImob3',
	'IsId1' => 'IsId1',
	'IsId2' => 'IsId2',
	'IsId3' => 'IsId3',
	'IsId4' => 'IsId4',
	'IsId5' => 'IsId5',
	'IsVencto' => 'IsVencto',
	'IsDetLin1' => 'IsDetLin1',
	'IsDetLin2' => 'IsDetLin2',
	'IsDetLin3' => 'IsDetLin3',
	'IsDetLin4' => 'IsDetLin4',
	'IsDetLin5' => 'IsDetLin5',
	'IsDetLin6' => 'IsDetLin6',
	'IsDetLin7' => 'IsDetLin7',
	'IsDetLin8' => 'IsDetLin8',
	'IsDetLin9' => 'IsDetLin9',
	'IsDetLin10' => 'IsDetLin10',
	'IsDetLin11' => 'IsDetLin11',
	'IsDetLin12' => 'IsDetLin12',
	'IsDetLin13' => 'IsDetLin13',
	'IsDetLin14' => 'IsDetLin14',
	'IsDetLin15' => 'IsDetLin15',
	'IsDetLin16' => 'IsDetLin16',
	'IsDetLin17' => 'IsDetLin17',
	'IsDetLin18' => 'IsDetLin18',
	'IsDetLin19' => 'IsDetLin19',
	'IsDetLin20' => 'IsDetLin20',
	'IsDetLin21' => 'IsDetLin21',
	'IsDetLin22' => 'IsDetLin22',
	'IsDetLin23' => 'IsDetLin23',
	'IsDetLin24' => 'IsDetLin24',
	'IsDetLin25' => 'IsDetLin25',
	'IsDetLin26' => 'IsDetLin26',
	'IsDetLin27' => 'IsDetLin27',
	'IsDetLin28' => 'IsDetLin28',
	'IsDetLin29' => 'IsDetLin29',
	'IsDetLin30' => 'IsDetLin30',
	'IsDetLin31' => 'IsDetLin31',
	'IsDetLin32' => 'IsDetLin32',
	'IsDetLin33' => 'IsDetLin33',
	'IsDetLin34' => 'IsDetLin34',
	'IsDetLin35' => 'IsDetLin35',
	'IsDetLin36' => 'IsDetLin36',
	'IsDetLin37' => 'IsDetLin37',
	'IsDetLin38' => 'IsDetLin38',
	'IsDetLin39' => 'IsDetLin39',
	'IsDetLin40' => 'IsDetLin40',
	'IsCodBanco' => 'IsCodBanco',
	'IsLinDig' => 'IsLinDig',
	'IsLocalPagto' => 'IsLocalPagto',
	'IsDataVenc' => 'IsDataVenc',
	'IsNomeCedente' => 'IsNomeCedente',
	'IsAgeCodCedente' => 'IsAgeCodCedente',
	'IsDataDoc' => 'IsDataDoc',
	'IsDataProc' => 'IsDataProc',
	'IsNumeroDoc' => 'IsNumeroDoc',
	'IsNossoNumero' => 'IsNossoNumero',
	'IsCarteira' => 'IsCarteira',
	'IsUsoBanco' => 'IsUsoBanco',
	'IsAceite' => 'IsAceite',
	'IsEspecieDoc' => 'IsEspecieDoc',
	'IsMoeda' => 'IsMoeda',
	'IsValorDoc' => 'IsValorDoc',
	'IsOutroAcres' => 'IsOutroAcres',
	'IsDesconto' => 'IsDesconto',
	'IsOutraDed' => 'IsOutraDed',
	'IsMoraMulta' => 'IsMoraMulta',
	'IsValorCobrado' => 'IsValorCobrado',
	'IsInstru1' => 'IsInstru1',
	'IsInstru2' => 'IsInstru2',
	'IsInstru3' => 'IsInstru3',
	'IsInstru4' => 'IsInstru4',
	'IsInstru5' => 'IsInstru5',
	'IsInstru6' => 'IsInstru6',
	'IsInstru7' => 'IsInstru7',
	'IsInstru8' => 'IsInstru8',
	'IsInstru9' => 'IsInstru9',
	'IsSacado1' => 'IsSacado1',
	'IsSacado2' => 'IsSacado2',
	'IsSacado3' => 'IsSacado3',
	'IsQnt' => 'IsQnt'
);

//Le arquivo do cliente
$Obs = '';
$sCodBarra = '';
$bInformativo = false;
$bBalancete = false;
$bTaxas = true;
$sRetido = '';

while (!feof ($handle))
{
	$buffer = fgets($handle, 4096);
	if (empty($buffer))
		break;

	if ($buffer[0] != '#')
	{
		$out = explode('=', $buffer);
		if (isset($out[1]))
		{
			$Value =  trim($out[1]);
			if (empty($Value))
				$Value = '&nbsp;';
		}
		else
			$Value = '&nbsp;';
		$Tag = GetTag($out[0], $VetFieldTag);

		if ($out[0] == 'TIPO')
			$sTipo = $Value;
		else if ($out[0] == 'IsVencto')
			$sVencto = $out[1];
		else if ($out[0] == 'IsRetido')
			$sRetido = $out[1];
		else if ($out[0] == 'IsCodBarra1')
			$sCodBarra = $out[1];
		else if ($out[0] == 'IsDebConta')
			$sDebConta = $out[1];
		else if ($out[0] == 'IsCodBanco')
		{
//echo '<!-- ';
			$sLogo = 'logo_'.Trim($out[1]).'.gif';
//echo "$sLogo| ";
			if (!file_exists($sLocalLogo.'/'.$sLogo))
			{
				$sLogo = 'logo_'.substr(Trim($out[1]),0,3).'*.gif';
//echo "$sLogo| ";
				$dir = @dir($sLocalLogo);
				if (empty($dir))
				{
					Mensagem('Aviso', 'Logotipo de banco n&atilde;o dispon&iacute;vel. Favor contactar a Imobili&aacute;ria!');
					return;
				}
				while (($file = $dir->read()) !== false)
				{
//echo "$file| ";
					if($file != '.' && $file != '..' && fnmatch($sLogo, $file))
					{
						$sLogo = $file;
						break;
					}
				}
				$dir->close();
			}
			$sLogo = $sLocalLogo.'/'.$sLogo;
			if (!file_exists($sLogo))
				$sLogo = $sLocalLogo.'/none.gif';
//echo "\n[$sLogo] -->\n";
		}
		else if (substr($out[0],0,8) == 'IsDetLin')
		{
			if (strpos($Value, 'I N F O R M A T I V O') !== false)
			{
				$bTaxas = false;
				$bBalancete = false;
				$bInformativo = true;
				$Value = str_replace('-','',$Value);
				$Value = '</td><td colspan=4 style="font-size: 7pt">----------'.$Value.'----------';
			}
			else if (strpos($Value, 'BALANCETE DEMONSTRATIVO') !== false)
			{
				$bTaxas = false;
				$bBalancete = true;
				$bInformativo = false;
				$Value = str_replace('-','',$Value);
				$Value = '</td><td colspan=4 style="font-size: 7pt">-----'.$Value.'-----';
			}
			else if ($bInformativo)
			{
				$Value = trim($Value);
				$Value = '</td><td colspan=4 style="font-size: 6pt">'.(empty($Value)?'&nbsp;':$Value);
			}
			else if ($bBalancete)
			{
				$col1 = trim(substr($Value,0,31));
				$col2 = trim(substr($Value,31,10));
				$col3 = trim(substr($Value,56,31));
				$col4 = trim(substr($Value,87,10));
				$Value = '</td><td nowrap>'.(empty($col1)?'&nbsp;':$col1).
						 '</td><td align="right">'.(empty($col2)?'&nbsp;':$col2).
						 '</td><td nowrap>'.(empty($col3)?'&nbsp;':$col3).
						 '</td><td align="right">'.(empty($col4)?'&nbsp;':$col4);
			}
			else if ($Value{0} == '*')
			{	// Trata-se de mensagem extra
				if (empty($Obs))
					$Obs = trim($Value);
				else
					$Obs .= '<br>'.trim($Value);
				$Value = '&nbsp;';
				$bTaxas = false;
				$bBalancete = false;
				$bInformativo = false;
			}
			else
			{
				$Value = trim($Value);
				if ($bTaxas)
				{
					$pos = strrpos($Value,' ');
					if ($pos === false)
					{
						$col1 = $Value;
						$col2 = '';
					}
					else
					{
						$col1 = trim(substr($Value,0,$pos));
						$col2 = '&nbsp;'.trim(substr($Value,$pos));
					}
					if (substr($Value,0,13) == 'TOTAL C/MULTA')
						$bTaxas = false;
				}
				else
				{
					$len = strlen($Value);
					if ($len > 80)
					{
						$col1 = '<table width="100%" border=0><tr><td style="font-size: 6pt">'.$Value.'</td></tr></table>';
						$col2 = '';
					}
					else
					{
						$col1 = $Value;
						$col2 = '';
					}
				}

				if (empty($col1))
					$col1 = '&nbsp;';
				if (empty($col2))
					$Value = '</td><td colspan=4 nowrap>'.$col1;
				else
					$Value = '</td><td colspan=3 nowrap>'.$col1.'</td><td align="right">'.$col2;
			}
		}

		if ($Tag != '')
			$model->assign($Tag, $Value);
	}
}

fclose ($handle);

if (empty($sCodBarra))
{
	$Value = $sDebConta;
	if (stristr($sDebConta, 'Quitado') != false)
	{
		$bQuitado = true;
		$Value = "<h2>$Value</h2><br>";
	}
}
else
{
	$sCodBarra = trim($sCodBarra);
	$Value = fbarcode($sCodBarra);
	$bQuitado = false;
}

if (!$bQuitado)
{
	if (trim($sRetido) == 'S')
	{
		Mensagem('Aviso', 'Boleto retido ou cancelado. Favor contactar a Imobili&aacute;ria!');
		return;
	}

	if (strstr($filename, '.sempre.txt') != '.sempre.txt')
	{
		// Nao e' o nome especial que indica ser sempre valido entao
		// verifica a expiracao do tempo limite de exibicao.
		if ($sTipo == 'L')
			$iExpiracaoBloq = Configuracao('DIAS_VALIDADE_DOC_VENCIDO_LOC', -1);
		else
			$iExpiracaoBloq = Configuracao('DIAS_VALIDADE_DOC_VENCIDO_COND', -1);
		if ($iExpiracaoBloq < 0)
			$iExpiracaoBloq = Configuracao('DIAS_VALIDADE_DOC_VENCIDO', 30);

		$iExpiracaoBloq *= 86400;
		$iTempoVencido = time() - mktime(23,59,59,substr($sVencto, 3, 2),substr($sVencto, 0, 2),substr($sVencto, 6, 4));

printf("<!-- Tipo='%s' Vencto=%s Expira=%d -->\n", $sTipo, $iTempoVencido, $iExpiracaoBloq);
		if ($iTempoVencido > $iExpiracaoBloq)
		{
			Mensagem('Aviso', "Este boleto possui vencimento em '$sVencto' e j&aacute; expirou. Favor contactar a Imobili&aacute;ria!");
			return;
		}
	}
}

$model->assign('OBSERVACOES', $Obs);
$model->assign('IsCodBarra1', $Value);
$model->assign('BANCOLOGO', $sLogo);
$model->parse('bolonline'); 
$model->DPrint('bolonline');

?>
