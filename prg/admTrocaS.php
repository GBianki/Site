<?php
include "setSession.php";
include "msg.php"; 
include "admShow.php"; 

//---main-------------------------------------------------------------------------

	if ($_POST['trocar'])
	{
		$Pass = CampoObrigatorio('senha');
		$nPass1 = CampoObrigatorio('nsenha1');
		$nPass2 = CampoObrigatorio('nsenha2');

		if (!$Pass || !$nPass1 || !$nPass2)
		{
			Mensagem("Erro", "Todos os campos devem ser preenchidos!");
			return 0;
		}

		$SenhaConf = $_SESSION["SENHA_ADMIN"];
		if (!$SenhaConf || strlen($SenhaConf) == 0 )
		{
			Mensagem("Erro Interno", "Falta SENHA_ADMIN no conf.");
			return 0;
		}

		if (strcmp(trim($SenhaConf), trim($Pass)) != 0)
		{
			Mensagem("Erro", "Senha atual inválida!");
			return 0;
		}

		if (strcmp(trim($nPass1), trim($nPass2)) != 0)
		{
			Mensagem("Erro", "Nova senha não confere!");
			return 0;
		}

		// Altera senha do administrador no arquivo de configuracao
		changeVarSession("site.conf", "SENHA_ADMIN=".$SenhaConf, "SENHA_ADMIN=".$nPass1);
	}

	// Retorna para a tela de informacoes da administracao
	admShow(); 

?>
