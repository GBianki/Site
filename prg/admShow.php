<?php
include "class.DTemplate.php";
include "cont.php";

//------------------------------------------------------------------------------
function DtBaseMenor()
{
	$dtIdx = dtbase_get("../prg/dados/cont_idx.txt");
	$dtCli = dtbase_get("../prg/dados/cont_cli.txt");
	$dtLoc = dtbase_get("../prg/dados/cont_loc.txt");
	$dtVen = dtbase_get("../prg/dados/cont_ven.txt");

	$dtMenor = mktime(0,0,0, substr($dtIdx,2,2) , substr($dtIdx,0,2), substr($dtIdx,4,2));
	$aux = mktime(0,0,0, substr($dtCli,2,2) , substr($dtCli,0,2), substr($dtCli,4,2));
	if( $aux < $dtMenor)
		$dtMenor = $aux;
	$aux = mktime(0,0,0, substr($dtLoc,2,2) , substr($dtLoc,0,2), substr($dtLoc,4,2));
	if( $aux < $dtMenor)
		$dtMenor = $aux;
	$aux = mktime(0,0,0, substr($dtVen,2,2) , substr($dtVen,0,2), substr($dtVen,4,2));
	if( $aux < $dtMenor)
		$dtMenor = $aux;

	return date("d/m/y", $dtMenor);
}

//------------------------------------------------------------------------------
function MaiorDe($aContIdx, $aContCli, $aContLoc, $aContVen)
{
	$maior = strlen($aContIdx);
	if(strlen($aContCli) > $maior)
		$maior = strlen($aContCli);
	if(strlen($aContLoc) > $maior)
		$maior = strlen($aContLoc);
	if(strlen($aContVen) > $maior)
		$maior = strlen($aContVen);
	return $maior;
}

//------------------------------------------------------------------------------
function admShow()
{
	$model = new DTemplate("modelos"); 
	$model-> define_templates( array ( 'adminfo' => 'adminfo.shtml' )); 
	$model->define_dynamic('LISTA_PORDIA', 'adminfo');   //body is the parent of table

	$mes = $_GET['mes'];
	if(!$mes || strlen($mes) <= 0)
		$mes = date("my");
	for($i = 0; $i<12; $i++)
	{
		$aux = date("M / Y", mktime(0,0,0, date("m")-$i, 1, date("Y")));
		$auxval =date("my", mktime(0,0,0, date("m")-$i, 1, date("y")));
		if (strcmp($mes, $auxval) == 0)
			$optmes .= "<option selected value =".$auxval." >".$aux."</option>";
		else
			$optmes .= "<option value =".$auxval." >".$aux."</option>";
	}

	$aux = date("my");
	if (strcmp($mes, $aux) == 0)
		$diafinal = date("d");
	else
		$diafinal = date("d", mktime(0,0,0, substr($mes,0,2)+1, 0, substr($mes,2,2)));

	$aContIdx = count_get("../prg/dados/cont_idx.txt", $mes);
	$aContCli = count_get("../prg/dados/cont_cli.txt", $mes);
	$aContLoc = count_get("../prg/dados/cont_loc.txt", $mes);
	$aContVen = count_get("../prg/dados/cont_ven.txt", $mes);


	$dtMenor = DtBaseMenor();

	$model->assign(DT_ACESSO, "Total desde<br>".$dtMenor);

	$model->assign(CONT_IDX_T, intval(substr($aContIdx, 6, 6)));
	$model->assign(CONT_CLI_T, intval(substr($aContCli, 6, 6)));
	$model->assign(CONT_LOC_T, intval(substr($aContLoc, 6, 6)));
	$model->assign(CONT_VEN_T, intval(substr($aContVen, 6, 6)));

	$totmesIdx = 0;
	$totmesCli = 0;
	$totmesLoc = 0;
	$totmesVen = 0;

	$diaLimIdx = substr($aContIdx, 0, 2);
	if( substr($mes,0,2) != substr($aContIdx, 2, 2) )
		$diaLimIdx = 1;

	$diaLimCli = substr($aContCli, 0, 2);
	if( substr($mes,0,2) != substr($aContIdx, 2, 2) )
		$diaLimCli = 1;

	$diaLimLoc = substr($aContLoc, 0, 2);
	if( substr($mes,0,2) != substr($aContIdx, 2, 2) )
		$diaLimLoc = 1;

	$diaLimVen = substr($aContVen, 0, 2);
	if( substr($mes,0,2) != substr($aContIdx, 2, 2) )
		$diaLimVen = 1;

	$auxIdx = strlen($aContIdx);
	$auxCli = strlen($aContCli);
	$auxLoc = strlen($aContLoc);
	$auxVen = strlen($aContVen);

	$tamIdx = ($auxIdx-12)/6;
	$tamCli = ($auxCli-12)/6;
	$tamLoc = ($auxLoc-12)/6;
	$tamVen = ($auxVen-12)/6;
	for($i = $diafinal; $i>=1; $i--)
	{
		$dt = sprintf("%02d", $i);
		$dt .= "/".substr($mes,0,2)."/".substr($mes,2,2);
		$model->assign(DT_ACESSO, $dt);

		if( $i >= ($diaLimIdx+$tamIdx) || $i < $diaLimIdx )
		{
			$model->assign(CONT_IDX, 0);
		}
		else
		{
			if( ($auxIdx-6) < 12 || $auxIdx > strlen($aContIdx) )
				$model->assign(CONT_IDX, 0);
			else
			{
				$tmp = intval(substr($aContIdx, $auxIdx-6, 6));
				$totmesIdx += $tmp;
				$model->assign(CONT_IDX, $tmp);
			}
			$auxIdx = $auxIdx-6;
		}

		if( $i >= ($diaLimCli+$tamCli) || $i < $diaLimCli )
		{
			$model->assign(CONT_CLI, 0);
		}
		else
		{
			if( ($auxCli-6) < 12 || $auxCli > strlen($aContCli) )
				$model->assign(CONT_CLI, 0);
			else
			{
				$tmp = intval(substr($aContCli, $auxCli-6, 6));
				$totmesCli += $tmp;
				$model->assign(CONT_CLI, $tmp);
			}
			$auxCli = $auxCli-6;
		}

		if( $i >= ($diaLimLoc+$tamLoc) || $i < $diaLimLoc )
		{
			$model->assign(CONT_LOC, 0);
		}
		else
		{
			if( ($auxLoc-6) < 12 || $auxLoc > strlen($aContLoc) )
				$model->assign(CONT_LOC, 0);
			else
			{
				$tmp = intval(substr($aContLoc, $auxLoc-6, 6));
				$totmesLoc += $tmp;
				$model->assign(CONT_LOC, $tmp);
			}
			$auxLoc = $auxLoc-6;
		}

		if( $i >= ($diaLimVen+$tamVen) || $i < $diaLimVen )
		{
			$model->assign(CONT_VEN, 0);
		}
		else
		{
			if( ($auxVen-6) < 12 || $auxVen > strlen($aContVen) )
				$model->assign(CONT_VEN, 0);
			else
			{
				$tmp = intval(substr($aContVen, $auxVen-6, 6));
				$totmesVen += $tmp;
				$model->assign(CONT_VEN, $tmp);
			}
			$auxVen = $auxVen-6;
		}

//printf("dia=%s ",$dt);
		$model->parse('.LISTA_PORDIA');
	}

	// Preenche os totais de cada mes
//	$model->assign(DT_ACESSO, "Total do mês");
	$model->assign(CONT_IDX, $totmesIdx);
	$model->assign(CONT_CLI, $totmesCli);
	$model->assign(CONT_LOC, $totmesLoc);
	$model->assign(CONT_VEN, $totmesVen);
	$model->parse('.LISTA_PORDIA');


	$model->assign(MES_OPTIONS, $optmes);
	$model->parse('adminfo'); 
	$model->DPrint('adminfo');
	return;
}

	$mes = $_GET['mes'];
	if($mes && strlen($mes) > 0)
		admShow();

?>
