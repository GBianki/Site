<html>
<head>

<title>InetSoft - Sistema Imobiliar.</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta content="Seu site para Imobiliárias" name="description">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<link rel="shortcut icon" href="favicon.png" type="favicon.png" /> 

<link href="estilos.css" rel="stylesheet" type="text/css">

<link rel="stylesheet" href="styles.css">

<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

<style>
    body{
        .carousel-item {
  height: 65vh;
  min-height: 350px;
  background: no-repeat center center scroll;
  -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;
}
    }
</style>

</head>
<body>
   <!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
  <div class="container">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <img src=img/logo.png width=10%>
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item active">
          <a class="nav-link" href="index.php">Página Inicial</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="empresa.php">Quem somos</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="avaliacao.php">Anuncie Conosco</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="contato.php">Contato</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
  </div>
  <div>
  <center>
  <div style="height:8px;"></div>
<? require_once(dirname(__FILE__) . '/prg/pesqImov.php'); ?>
  </div></center>
</div>
<!-- end menu -->

<!-- Busca im�veis capa -->
<div align="center">
	<div style="height:20px;"></div>
	<div style="width:900px;">
	   <p><span class="titulos">Anuncie seu im&oacute;vel conosco </span></p>
	   <div style="height:20px;"></div>
	   <div class="fonte_site">Utilize o formulário abaixo para preencher com dados do seu imóvel. Após, entraremos em contato para avaliação.</div>
	   <div style="height:20px;"></div>
	   <table width="700" border="0">

         <tr>
           <td width="700" valign="top">
		   			<form action="sendmail.php" method="post" name="Contato" id="Contato">
			    	<input type="hidden" name="resposta" value="avaliacao_enviada.php">
            		<input type="hidden" name="destinatario" value="gabriel.inetsoft@gmail.com">
                	<input type="hidden" name="subject" value="Avaliacao de imovel">
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
      <select name="tipo" id="tipo" style="width:500px; height:35;">
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
    <td height="50"><input name="dormitorios" type="text" id="dormitorios" style="width:500px; height:35;"></td>
  </tr>
  <tr>
    <td height="50"><div align="right">Banheiros&nbsp;</div></td>
    <td height="50"><input name="banheiros" type="text" id="banheiros" style="width:500px; height:35;"></td>
  </tr>
  <tr>
    <td height="50"><div align="right">Vagas de Garagem&nbsp;</div></td>
    <td height="50"><input name="vaga_garagem" type="text" id="vaga_garagem" style="width:500px; height:35;"></td>
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
<td height="50"><textarea name="observacoes" cols="50" rows="3" id="observacoes" style="width:500px;"></textarea></td> 
</tr> 
<tr> 
<td height="50">&nbsp;</td> 
<td><input type="submit" name="Submit" value="Enviar" style="width:100px; height:35;">
  <input type="reset" name="Submit" value="Limpar" style="width:100px; height:35;"></td> 
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
<footer class="bg-light text-center text-lg-start">
  <!-- Grid container -->
  <div class="container p-4">
    <!--Grid row-->
    <div class="row">
      <!--Grid column-->
      <div class="col-lg-6 col-md-12 mb-4 mb-md-0">
        <h5 class="text-uppercase">Horário de Atendimento</h5>

        <p>
          Atendemos de Segunda à Sexta-feira<br>
          Das 8:30 às 17:30
        </p>
      </div>
      <!--Grid column-->

      <!--Grid column-->
      <div class="col-lg-6 col-md-12 mb-4 mb-md-0">
        <h5 class="text-uppercase">Localização</h5>

        <p>
          Nossa sede está localizada em:<br>
          Rua Felipe de Oliveira, 1397 - Petrópolis<br>
          Porto Alegre - RS
        </p>
      </div>
      <!--Grid column-->
    </div>
    <!--Grid row-->
  </div>
  <!-- Grid container -->

  <!-- Copyright -->
  <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
    Site feito por:
    <a class="text-dark" href="https://inetsoft.com.br/">InetSoft</a>
  </div>
  <!-- Copyright -->
</footer>
<!-- end menu inf -->

</body>
</html>