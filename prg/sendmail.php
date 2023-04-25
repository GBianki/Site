<?
/*
	Processador de formulários
	desenvolvido por Marcelo Rezende
	última atualização: 02/08/2003
	suporte: marcelo_rezende@yahoo.com
*/
global $_POST;
$block = "";
$pagina_sucesso = $_POST['resposta'];
$pagina_erro = $_POST['erro'];
$subject = $_POST['subject'];
$to = $_POST['destinatario'];

// lista de campos excluidos do processamento
$lista[] = "resposta";
$lista[] = "erro";
$lista[] = "destinatario";
$lista[] = "subject";
$lista[] = "botao";

foreach($_POST as $varName => $value) {
	if (!in_array($varName,$lista)) {
		if (($value!="")||($value>0)) {
			$block .= "$varName : $value\n";
		}
	}
}
if (mail($to,$subject,$block,"From: $to\r\n")){
	header("Location: $pagina_sucesso");
} else {
	header("Location: $pagina_erro");
}
?>