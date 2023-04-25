<html>
<head>
<title>Anuncie seu im&oacute;vel - Imobili&aacute;ria S&atilde;o Geraldo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta content="Encontre o imóvel que você procura para comprar ou alugar na imobiliária São Geraldo" name="description">
<link href="estilos.css" rel="stylesheet" type="text/css">

<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

<link rel="shortcut icon" href="favicon.png" type="favicon.png" /> 

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
	   <p><span class="titulos">Anuncie seu im&oacute;vel conosco </span></p>
	   <div style="height:20px;"></div>
	   <div class="fonte_site">Se você é proprietário, utilize nossos serviços para venda ou locação do seu imóvel. Envie os dados do seu imóvel, preenchendo o formulário abaixo.</div>
	   <div style="height:20px;"></div>
	   <table width="700" border="0">

         <tr>
           <td width="700" valign="top"><form onSubmit="return checa_formulario(this)" action="envia.php" method="post" enctype="multipart/form-data" name="email">
<input type="hidden" name="email" value="email@email.com.br" />
<input type="hidden" name="assunto" value="Envio de curriculo via site" />
<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
  <tr>
    <td height="50"><div align="right">Interesse&nbsp;</div></td>
    <td height="50"><label>
      <select name="interesse" id="interesse" style="width:500px; height:35;">
        <option>Vender meu im&oacute;vel</option>
        <option>Alugar meu im&oacute;vel</option>
      </select>
      </label></td>
  </tr>
  <tr>
    <td height="50"><div align="right">Tipo&nbsp;</div></td>
    <td height="50"><label>
      <select name="interesse" id="interesse" style="width:500px; height:35;">
        <option>Apartamento</option>
        <option>Casa</option>
        <option>Loja</option>
            </select>
    </label></td>
  </tr>
  <tr>
    <td height="50"><div align="right">Endere&ccedil;o&nbsp;</div></td>
    <td height="50"><input name="endereco" type="text" id="endereco" style="width:500px; height:35;"></td>
  </tr>
  <tr>
    <td height="50"><div align="right">Bairro&nbsp;</div></td>
    <td height="50"><input name="bairro" type="text" id="bairro" style="width:500px; height:35;"></td>
  </tr>
  <tr>
    <td height="50"><div align="right">Cidade&nbsp;</div></td>
    <td height="50"><input name="cidade" type="text" id="cidade" style="width:500px; height:35;"></td>
  </tr>
  <tr>
    <td height="50"><div align="right">&Aacute;rea Total &nbsp;</div></td>
    <td height="50"><input name="area_total" type="text" id="area_total" style="width:500px; height:35;"></td>
  </tr>
  <tr>
    <td height="50"><div align="right">&Aacute;rea Privativa</div></td>
    <td height="50"><input name="area_privativa" type="text" id="area_privativa" style="width:500px; height:35;"></td>
  </tr>
  <tr>
    <td height="50"><div align="right">Dormit&oacute;rios&nbsp;</div></td>
    <td height="50"><input name="endereco" type="text" id="endereco" style="width:500px; height:35;"></td>
  </tr>
  <tr>
    <td height="50"><div align="right">Banheiros&nbsp;</div></td>
    <td height="50"><input name="banheiros" type="text" id="banheiros" style="width:500px; height:35;"></td>
  </tr>
  <tr>
    <td height="50"><div align="right">Vagas de Garagem&nbsp;</div></td>
    <td height="50"><input name="endereco" type="text" id="endereco" style="width:500px; height:35;"></td>
  </tr>
  <tr>
    <td height="50"><div align="right">Propriet&aacute;rio</div></td>
    <td height="50"><input name="nome_para" type="text" id="nome_para" style="width:500px; height:35;"></td>
  </tr>
  <tr>
    <td width="19%" height="40"><div align="right">Email&nbsp;</div></td>
    <td width="81%" height="40"><input name="email1" type="text" id="email1" style="width:500px; height:35;"></td>
  </tr> 
<tr> 
<td height="50"><div align="right">Telefone&nbsp;</div></td> 
<td height="50"><input name="fone" type="text" id="fone" style="width:500px; height:35;"></td> 
</tr> 
<tr> 
<td height="50"><div align="right">Observa&ccedil;&otilde;es  &nbsp;</div></td> 
<td height="50"><textarea name="mensagem" cols="50" rows="3" id="mensagem" style="width:500px; height:35;"></textarea></td> 
</tr> 
<tr> 
<td height="50">&nbsp;</td> 
<td><input type="submit" name="Submit" value="Enviar" style="width:100px; height:35;">
  <input type="reset" name="Submit2" value="Limpar" style="width:100px; height:35;"></td> 
</tr> 
</table> 
</form></td>
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