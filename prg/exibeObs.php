<?php
include 'msg.php';
include 'pesqImov.inc.php';

$DirDados = Configuracao('DIR_DADOS');
$DirModelos = Configuracao('DIR_MODELOS_AREACLIENTE');

//--------------------------------------------------------------------------------
function MoneyFormat($sVal)
{
	$sVal = str_replace('.', '' , $sVal);
	$sVal = str_replace(',', '.' , $sVal);
	return number_format($sVal, 2, ',', '.');
}

//--------------------------------------------------------------------------------
function BuscaMotivo($Motivo)
{
	GLOBAL $DirDados;
	static $handle = false;

	$Motivo = intval($Motivo);

	if ($handle === false)
	{
		$file = $DirDados.'obscomerc/motivo.txt';
		$stat = stat($file);
		if ($stat === false || $stat['size'] <= 0 || ($handle=fopen($file, 'r')) === false)
			return $Motivo;
	}
	else
		rewind($handle);

	while (!feof ($handle))
	{
		$buffer = fgets($handle, 4098);
		if (empty($buffer))
			break;

		if (intval(substr($buffer,0,4)) == $Motivo)
			return substr($buffer,4);
	}

	return $Motivo;
}

//--------------------------------------------------------------------------------
function BuscaResumo($Resumo)
{
	$aResumo = explode(',', $Resumo);
	$Motivo = BuscaMotivo($aResumo[0]);

	if (!empty($Motivo))
		$Motivo .= ' ('.$aResumo[1].')';

	return $Motivo;
}

//--------------------------------------------------------------------------------
function BuscaOpiniao($Lista)
{
	$aLista = explode(',', $Lista);
	$Opiniao = '';

	for ($i = 0; $i < count($aLista); $i++)
		$Opiniao .= BuscaMotivo($aLista[$i]).', ';

	return substr($Opiniao, 0, strlen($Opiniao)-2);
}

//---main-------------------------------------------------------------------------

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

$Imob = getenv('IMOB_NAME');
$Id = CampoObrigatorio('id');

// Pega data  e hora atual
$DataAtual = date('d/m/Y H:m:s');

// Monta nome do arquivo fisico
$File = 'P'.$Id.'.txt';
$UserFile = $DirDados.'obscomerc/'.$File;
$stat = @stat($UserFile);
if ($stat === false || $stat['size'] <= 0 || ($File=fopen($UserFile, 'r')) === false)
{
	Mensagem('Atenção', 'Dados não disponíveis no momento!', $File);
	exit(1);
}

$model = new DTemplate($DirModelos);
$model-> define_templates( array ( 'obscomerc' => 'comercpropr.shtml' )); 
$model->define_dynamic('IMOVEL', 'obscomerc');   //body is the parent of table
$model->define_dynamic('IMOVEL_PESSOA', 'IMOVEL');   //body is the parent of table
$model->define_dynamic('IMOVEL_RESUMO', 'IMOVEL');   //body is the parent of table

$bExibePessoa = (Configuracao('EXIBE_OBS_COMERC_PESSOA') == 'SIM');
$bTemImovel = 0;
$bTemPessoa = 0;
$iContPessoa = 0;
$iContResumo = 0;
$model->assign('TEM_IMOVEIS', ''); 
$model->assign('TEM_PORDIA', ''); 
fseek ($File, 0);

for(;;)
{
	$sReg = fgets($File, 4098);
	if (empty($sReg) || strlen($sReg) < $iTamReg+1)
		break;

	$TipoReg = substr($sReg, 0, 1);

	if($TipoReg == 'I')
	{
		if ($bTemImovel)
		{
			$model->parse('.IMOVEL');
			$iContPessoa = 0;
			$iContResumo = 0;
		}
		$bTemImovel = 1;
		$Imov = trim(substr($sReg, 1, 8));
		$Situacao = substr($sReg, 9, 3);
		$Venda = substr($sReg, 12, 1);
		$Locacao = substr($sReg, 13, 1);
		$VendVal = trim(substr($sReg, 14, 12));
		$LocVal = trim(substr($sReg, 26, 12));
		$Ender = trim(substr($sReg, 38));

		$Situacao = BuscaSituacao($Situacao);
		$Venda = ($Venda == 'S') ? ' &nbsp; Venda='.MoneyFormat($VendVal) : '';
		$Locacao = ($Locacao == 'S') ? ' &nbsp; Loca&ccedil;&atilde;o='.MoneyFormat($LocVal) : '';
		$Descr = 'Im&oacute;vel: $Imov &nbsp;&nbsp; $Ender &nbsp;&nbsp; $Situacao$Venda$Locacao';
		$model->assign('IMOVEL_DESCR', $Descr);
	}
	else if ($bTemImovel)
	{
		if ($TipoReg == 'P')
		{
			$Data = substr($sReg, 1, 10);
			$model->assign('DATA', $Data);
			if ($bExibePessoa)
				$Nome = trim(substr($sReg, 19));
			else
				$Nome = '';
			$model->assign('NOME', $Nome);
		}
		else if ($TipoReg == 'M')
		{
			$Opiniao = BuscaOpiniao(substr($sReg, 1));
			$model->assign('OPINIAO', $Opiniao);
			$model->parse($iContPessoa++==0 ? 'IMOVEL_PESSOA' : '.IMOVEL_PESSOA');
		}
		else if($TipoReg == 'R')
		{
			$Resumo = BuscaResumo(substr($sReg, 1));
			if (!empty($Resumo))
			{
				$model->assign('RESUMO', $Resumo);
				$model->parse($iContResumo++==0 ? 'IMOVEL_RESUMO' : '.IMOVEL_RESUMO');
			}
		}
	}
}

fclose ($File);

$model->parse('.IMOVEL');
$model->assign('IMOB',$Imob); 
$model->assign('DESCR', $_POST['DESC_SERV']);
$model->assign('DATA_ATUAL', $DataAtual);
$model->assign('USUARIO', $usuario);

$model->parse('obscomerc');
$model->DPrint('obscomerc');
echo "<!-- $File -->\n";

session_write_close();
?>
