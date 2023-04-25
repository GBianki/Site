<html>
<head>
<title>Entre em contato - Imobili&aacute;ria S&atilde;o Geraldo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta content="Encontre o imóvel que você procura para comprar ou alugar na imobiliária São Geraldo" name="description">
<link href="estilos.css" rel="stylesheet" type="text/css">

<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

<link rel="shortcut icon" href="favicon.png" type="favicon.png" /> 
<script src="vendor/jquery/jquery.min.js"></script>
</head>
<body>

<!-- Menu -->
<div align="center" id="superior-bg">
	<div>
	   <img src="img/sup.jpg" width="1000" height="114" border="0" usemap="#menu_sup" />
<map name="menu_sup">
  <area shape="rect" coords="497,82,576,105" href="seguros.php">
  <area shape="rect" coords="53,5,168,110" href="index.php">
  <area shape="rect" coords="598,81,681,106" href="contato.php">
  <area shape="rect" coords="395,82,469,106" href="vendas.php">
  <area shape="rect" coords="291,82,374,107" href="alugueis.php">
  <area shape="rect" coords="189,82,272,107" href="empresa.php">
</map>
  </div>
  <div>
  <div style="height:8px;"></div>
<? require_once('pesqImov.php'); ?>
	
  </div>
</div>

<!-- Busca imóveis capa -->
<div align="center">
	<div style="height:20px;"></div>
	<div style="width:900px;">
	   <p><span class="titulos">Entre em contato conosco </span></p>
	   <div style="height:20px;"></div>
	   <table width="900" border="0">
         <tr>
           <td colspan="2" valign="top"><div align="center">Preencha o formul&aacute;rio de contato que o mais breve poss&iacute;vel entraremos em contato com voc&ecirc; ou se preferir utilize nossos telefones</div></td>
         </tr>
         <tr>
           <td colspan="2" valign="top">&nbsp;</td>
         </tr>
         <tr>
           <td width="626" valign="top"><form action="sendmail.php" method="post" name="Contato" id="Contato">
			    	<input type="hidden" name="resposta" value="contato.php">     
            		<input type="hidden" name="destinatario" value="agilizenet@gmail.com">
                	<input type="hidden" name="subject" value="Contato via site"> 
<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14%" height="50"><div align="right">Nome&nbsp;</div></td>
    <td width="86%" height="40"><input name="nome_para" type="text" id="nome_para" style="width:500px; height:35;"></td>
  </tr>
  <tr>
    <td height="50"><div align="right">Email&nbsp;</div></td>
    <td height="50"><input name="email1" type="text" id="email1" style="width:500px; height:35;"></td>
  </tr> 
<tr> 
<td height="50"><div align="right">Telefone&nbsp;</div></td> 
<td height="50"><input name="fone" type="text" id="fone" style="width:500px; height:35;"></td> 
</tr> 
<tr> 
<td height="50"><div align="right">Mensagem&nbsp;</div></td> 
<td height="50"><textarea name="mensagem" cols="50" rows="5" id="mensagem" style="width:500px;"></textarea></td> 
</tr> 
<tr> 
<td height="50">&nbsp;</td> 
<td><input type="submit" name="Submit" value="Enviar" style="width:100px; height:35;">
  <input type="reset" name="Submit2" value="Limpar" style="width:100px; height:35;"></td> 
</tr> 
</table> 
</form></td>
           <td width="264" valign="top"><p><strong>Telefones:</strong></p>
           <p>(51) 3346.8333<br />
           (51) 99234.8246</p>
           <p>&nbsp; </p>
           <p><strong>Localiza&ccedil;&atilde;o:</strong></p>
           <p><span class="fonte_site">Rua Conde de Porto Alegre n&deg; 71</span><br />
           <span class="fonte_site">Bairro S&atilde;o Geraldo</span><br />
           <span class="fonte_site"> Porto Alegre</span></p></td>
         </tr>
       </table>
	   <p class="fonte_site" align="justify">&nbsp;</p>
  </div>
	   
	<div style="height:20px;"></div>
</div>

<!-- menu inf -->
<div align="center">
	<div id="rodape-bg">
	   <img src="img/inf.jpg" width="1000" height="137" border="0" usemap="#Menu_Inf" />
<map name="Menu_Inf"><area shape="rect" coords="34,31,358,127" href="empresa.html">
</map>	</div>
	<div style="background-color: #FF6600; height:20;">
	   <span class="font_rodape_peq">As informa&ccedil;&otilde;es e valores de im&oacute;veis deste site est&atilde;o sujeitas a altera&ccedil;&otilde;es sem aviso pr&eacute;vio.</span>	</div>
</div>

</body>
</html>