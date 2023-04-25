<?php

//------------------------------------------------------------------------------
function dtbase_get($file)
{
	$fp=fopen($file,"r");
	//consome o contador
	$count=fgets($fp, 8);
	//pega a data
	$Aux=fgets($fp,8);
	$data = substr($Aux,0,6);

	fclose($fp);
	return $data;
}

//------------------------------------------------------------------------------
function count_get($file, $mes)
{
	$fp=fopen($file,"r");
	$count=fgets($fp, 8);

	$Aux=fgets($fp,8);
	$data = substr($Aux,0,6);
	$ret = $data;
	$ret .= substr($count, 0, 6);

	$diafinal = date("d", mktime(0,0,0, substr($mes,0,2)+1, 0, substr($mes,2,2)));

	$dtinicio = mktime(0,0,0, substr($mes,0,2) , 1, substr($mes,2,2));

	$dtbase = mktime(0,0,0, substr($data,2,2) , substr($data,0,2), substr($data,4,2));

	if (intval($dtinicio) < intval($dtbase))
	{
		if (strcmp( substr($mes,0,2), substr($data,2,2) ) == 0)
			$dtinicio = $dtbase;
		else
			return NULL;
	}

	$dias = intval(($dtinicio-$dtbase)/(60*60*24)); 
//echo $dias." ";

	$pos = ($dias * 7) + 14;
//echo $pos;
	
	$i = date("d", $dtinicio);

//	for($i = 1; $i<=$diafinal; $i++)
	for($i; $i<=$diafinal; $i++)
	{
		fseek($fp, $pos , SEEK_SET);
		$count_dia = fgets($fp,8);
		if($count_dia == false)
			break;
		$ret .= substr($count_dia, 0, 6);
		$pos = $pos + 7;
	}
	fclose($fp);
	return $ret;
}

//------------------------------------------------------------------------------
function count_hit($file)
{
	if(!file_exists($file))
	{
		$fp=fopen($file,"wb");
		fclose($fp);
	}

	$fp=fopen($file,"rb");
	if($fp != false)
	{
		fseek($fp, 0);
		$Tcount=fgets($fp,8);
		$Aux=fgets($fp,8);
		$data = substr($Aux,0,6);
	}
	else
		return;

	if(!$Tcount)
		$Tcount= 0;

	$hoje = date("dmy");
	if(!$data)
	{
		// O arquivo está vazio e vai colocar a data pela primeira vez
		$data = date("dmy");
	}
	if (strcmp($hoje, $data) == 0)
		$pos = 7 + 7;
	else
	{
		$dia = date("d", mktime(0,0,0,date("m")-substr($data,2,2) ,(date("d")-substr($data,0,2)), date("y")-substr($data,4,2) ));
		$pos = ($dia * 7) + 21;
	}
	fseek($fp, 0, SEEK_END);
	$fim = ftell($fp);
	if( intval($fim) <= intval($pos) )
	{
		if($fim == 0) // O aquivo está vazio
			$count = "000001\n";
		else
		{
			if( intval($fim) == intval($pos) ) // Está no dia corrente, le e incrementa.
			{
				fseek($fp, $pos - 7, SEEK_SET);
				$count=fgets($fp,7);
				$Aux = $count + 1;
				$count = sprintf("%06d\n",intval($Aux));
			}
			else
			{
				// completa os dias q faltam com zero
				$preenche = (($pos - $fim)/7) - 1;
				$count = "";
				if ($preenche > 0)
					$count = str_repeat("000000\n", $preenche );
				$count .="000001\n";
			}
		}
	}
	else
	{
		fseek($fp, $pos, SEEK_SET);
		$count=fgets($fp,7);
		$Aux = $count + 1;
		$count = sprintf("%06d\n",intval($Aux));
	}
	fclose($fp);

	$Aux = sprintf("%06d\n",intval($Tcount)+1);
	$Aux .= $data."\n";

	$fw=fopen($file,"r+b");
	fseek($fw, 0);
	$countnovo=fwrite($fw,$Aux, strlen($Aux));

	if( intval($fim) <= intval($pos) )
	{
			if( intval($fim) == intval($pos) ) // Está no dia corrente
				fseek($fw, $pos - 7, SEEK_SET);
			else
				fseek($fw, 0, SEEK_END);
	}
	else
	{
		fseek($fw, $pos, SEEK_SET);
	}
	$countnovo=fwrite($fw,$count, strlen($count));

	fclose($fw);
	return;
}

?>
