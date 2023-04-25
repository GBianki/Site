<?php
include "setSession.php";
include "admShow.php"; 
include "msg.php"; 

	$_SESSION["ORIGEM_MSG"] = "A"; //admin

	$pPass = CampoObrigatorio('SENHA');
	if (!$pPass)
	{
		Mensagem("Erro", "Senha inválida!");
		return 0;
	}

	// Valida senha do administrador
	$SenhaConf = $_SESSION["SENHA_ADMIN"];
	if (!$SenhaConf || strlen($SenhaConf) == 0 )
	{
		Mensagem("Erro Interno", "Falta SENHA_ADMIN no conf.");
		return 0;
	}
	if (strcmp(trim($SenhaConf), trim($pPass)) != 0)
	{
		Mensagem("Erro", "Senha inválida!");
		return 0;
	}

	// Exibe a tela de informacoes da administracao
	admShow();

?>