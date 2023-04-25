<?php

//-----------------------------------------------------------------------------
function phpDebug($oper, $fname)
{
	global $_POST;
	global $_GET;
	global $_SESSION;
	global $_SERVER;

	if ( strcmp($oper, "SAVE") == 0 )
	{
		$file = fopen($fname, "w");
		if ($file < 0)
			return 0;

		if (isset($_GET) && is_array($_GET) && !empty($_GET))
		{ 
			for (reset($_GET); $key = key($_GET); next($_GET))
				fwrite($file, '$_GET|'.$key.'|'.serialize($_GET[$key])."\n");
		}

		if (isset($_POST) && is_array($_POST) && !empty($_POST))
		{ 
			for (reset($_POST); $key = key($_POST); next($_POST))
				fwrite($file, '$_POST|'.$key.'|'.serialize($_POST[$key])."\n");
		}

		if (isset($_SESSION) && is_array($_SESSION) && !empty($_SESSION))
		{ 
			for (reset($_SESSION); $key = key($_SESSION); next($_SESSION))
				fwrite($file, '$_SESSION|'.$key.'|'.serialize($_SESSION[$key])."\n");
		}

		if (isset($_SERVER['PHP_SELF']))
			fwrite($file, '$_SERVER|PHP_SELF|'.serialize($_SERVER['PHP_SELF'])."\n");
		if (isset($_SERVER['CONTENT_LENGTH']))
			fwrite($file, '$_SERVER|CONTENT_LENGTH|'.serialize($_SERVER['CONTENT_LENGTH'])."\n");
		if (isset($_SERVER['CONTENT_TYPE']))
			fwrite($file, '$_SERVER|CONTENT_TYPE|'.serialize($_SERVER['CONTENT_TYPE'])."\n");
		if (isset($_SERVER['GATEWAY_INTERFACE']))
			fwrite($file, '$_SERVER|GATEWAY_INTERFACE|'.serialize($_SERVER['GATEWAY_INTERFACE'])."\n");
		if (isset($_SERVER['HTTP_ACCEPT']))
			fwrite($file, '$_SERVER|HTTP_ACCEPT|'.serialize($_SERVER['HTTP_ACCEPT'])."\n");
		if (isset($_SERVER['HTTP_COOKIE']))
			fwrite($file, '$_SERVER|HTTP_COOKIE|'.serialize($_SERVER['HTTP_COOKIE'])."\n");
		if (isset($_SERVER['HTTP_HOST']))
			fwrite($file, '$_SERVER|HTTP_HOST|'.serialize($_SERVER['HTTP_HOST'])."\n");
		if (isset($_SERVER['HTTP_REFERER']))
			fwrite($file, '$_SERVER|HTTP_REFERER|'.serialize($_SERVER['HTTP_REFERER'])."\n");
		if (isset($_SERVER['HTTP_USER_AGENT']))
			fwrite($file, '$_SERVER|HTTP_USER_AGENT|'.serialize($_SERVER['HTTP_USER_AGENT'])."\n");
		if (isset($_SERVER['PATH']))
			fwrite($file, '$_SERVER|PATH|'.serialize($_SERVER['PATH'])."\n");
		if (isset($_SERVER['PATH_TRANSLATED']))
			fwrite($file, '$_SERVER|PATH_TRANSLATED|'.serialize($_SERVER['PATH_TRANSLATED'])."\n");
		if (isset($_SERVER['QUERY_STRING']))
			fwrite($file, '$_SERVER|QUERY_STRING|'.serialize($_SERVER['QUERY_STRING'])."\n");
		if (isset($_SERVER['REMOTE_ADDR']))
			fwrite($file, '$_SERVER|REMOTE_ADDR|'.serialize($_SERVER['REMOTE_ADDR'])."\n");
		if (isset($_SERVER['REQUEST_METHOD']))
			fwrite($file, '$_SERVER|REQUEST_METHOD|'.serialize($_SERVER['REQUEST_METHOD'])."\n");
		if (isset($_SERVER['SCRIPT_NAME']))
			fwrite($file, '$_SERVER|SCRIPT_NAME|'.serialize($_SERVER['SCRIPT_NAME'])."\n");
		if (isset($_SERVER['SERVER_NAME']))
			fwrite($file, '$_SERVER|SERVER_NAME|'.serialize($_SERVER['SERVER_NAME'])."\n");
		if (isset($_SERVER['SERVER_PORT']))
			fwrite($file, '$_SERVER|SERVER_PORT|'.serialize($_SERVER['SERVER_PORT'])."\n");
		if (isset($_SERVER['SERVER_PROTOCOL']))
			fwrite($file, '$_SERVER|SERVER_PROTOCOL|'.serialize($_SERVER['SERVER_PROTOCOL'])."\n");
		if (isset($_SERVER['SERVER_SOFTWARE']))
			fwrite($file, '$_SERVER|SERVER_SOFTWARE|'.serialize($_SERVER['SERVER_SOFTWARE'])."\n");
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
			fwrite($file, '$_SERVER|HTTP_X_FORWARDED_FOR|'.serialize($_SERVER['HTTP_X_FORWARDED_FOR'])."\n");

		if (!empty($AUTH_TYPE))
			fwrite($file, 'GLOBAL|$AUTH_TYPE='.serialize($AUTH_TYPE)."\n");
		if (!empty($REMOTE_HOST))
			fwrite($file, 'GLOBAL|$REMOTE_HOST|'.serialize($REMOTE_HOST)."\n");
		if (!empty($REMOTE_IDENT))
			fwrite($file, 'GLOBAL|$REMOTE_IDENT|'.serialize($REMOTE_IDENT)."\n");
		if (!empty($REMOTE_USER))
			fwrite($file, 'GLOBAL|$REMOTE_USER|'.serialize($REMOTE_USER)."\n");

		fclose($file);
		return 1;

	}
	else if ( strcmp($oper, "LOAD") == 0 )
	{
		$file = fopen($fname, "r");
		if ($file < 0)
			return 0;

		while (!feof ($file)) {
			$reg = fgets($file, 1024);
			if(!$reg)
				break;
			list($tipo,$var,$valor) = explode("|",$reg);
			if (substr($tipo, 0, 2) == "##")
				continue;
			if(strcmp($tipo, '$_GET') == 0)
			{
				$_GET[$var] = unserialize(substr($valor, 0, strlen($valor)-1));
			}
			elseif(strcmp($tipo, '$_POST') == 0)
			{
				$_POST[$var] = unserialize(substr($valor, 0, strlen($valor)-1));
			}
			elseif(strcmp($tipo, '$_SERVER') == 0)
			{
				$_SERVER[$var] = unserialize(substr($valor, 0, strlen($valor)-1));
			}
			elseif(strcmp($tipo, '$_SESSION') == 0)
			{
				$_SESSION[$var] = unserialize(substr($valor, 0, strlen($valor)-1));
			}
			elseif(strcmp($tipo, 'GLOBAL') == 0)
			{
				if(strcmp($var, '$AUTH_TYPE') == 0)
					$AUTH_TYPE = unserialize(substr($valor, 0, strlen($valor)-1));
				elseif(strcmp($var, '$REMOTE_HOST') == 0)
					$REMOTE_HOST = unserialize(substr($valor, 0, strlen($valor)-1));
				elseif(strcmp($var, '$REMOTE_IDENT') == 0)
					$REMOTE_IDENT = unserialize(substr($valor, 0, strlen($valor)-1));
				elseif(strcmp($var, '$REMOTE_USER') == 0)
					$REMOTE_USER = unserialize(substr($valor, 0, strlen($valor)-1));
			}
		}
		fclose($file);
		return 1;

	}
	else 
		return 0;

	fclose($file);
	return 1;
}

?>
