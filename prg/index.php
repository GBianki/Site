<!DOCTYPE html>
<html lang="pt-br">

<head>

    <!-- <meta charset="utf-8"> 
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
	
<title>Imobili&aacute;ria S&atilde;o Geraldo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta content="Encontre o imovel que voce procura para comprar ou alugar na imobiliaria Sao Geraldo" name="description">

<link rel="shortcut icon" href="favicon.png" type="favicon.png" /> 

<link href="estilos.css" rel="stylesheet" type="text/css">

<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Bootstrap Core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Theme CSS -->
    <link href="css/freelancer.min.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
	
	<!-- jQuery -->
    <script src="vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>

    <!-- Theme JavaScript -->
    <script src="js/freelancer.min.js"></script>
	
<style>

.style1 {
font-size: 18px;
color: #000000;
}

.imgExpande {
list-style: none;
padding: 0;
}
.imgExpande li {
display: inline;
}
.imgExpande li img {
width: 310px; /* Aqui ? o tamanho da imagem */
margin-left: 10px; /* Espa?o entre as imagem */

-webkit-transition: 1s all ease; /* Chrome e Safari */
-moz-transition: 1s all ease; /* Firefox */
-o-transition: 1s all ease; /* Opera */

}
.imgExpande li img:hover {
-moz-transform: scale(1.1);
-webkit-transform: scale(1.1);
-o-transform: scale(1.1);
}

</style>
  
	
</head>
<body>

<!-- Menu -->
<div align="center" id="superior-bg">
	<div>
	   <img src="img/sup.jpg" width="1000" height="114" border="0" usemap="#menu_sup" />
<map name="menu_sup">
  <area shape="rect" coords="497,82,576,105" href="/seguros.php">
  <area shape="rect" coords="53,5,168,110" href="/index.php">
  <area shape="rect" coords="598,81,681,106" href="/contato.php">
  <area shape="rect" coords="395,82,469,106" href="/vendas.php">
  <area shape="rect" coords="291,82,374,107" href="/alugueis.php">
  <area shape="rect" coords="189,82,272,107" href="/empresa.php">
</map>
  </div>
  <div>
  <div style="height:8px;"></div>
<? require_once('pesqImov.php'); ?>
  </div>
</div>

<!-- Banner meio -->
    <div>
	    <img src="img/empresa_imob_sao_geraldo_capa.jpg" width="100%" border="0" />	  	   
    </div>


<div style="background-color: #E17100; height:5px;"></div>

<!-- Busca imoveis capa -->
<div align="center">
	<div style="height:30px;"></div>
	<div>
	   <img src="img/imoveis_capa.jpg" width="1000" height="347" border="0" usemap="#divulga" />
<map name="divulga">
  <area shape="rect" coords="343,18,982,316" href="empresa.php">
  <area shape="rect" coords="15,18,333,317" href="avaliacao.php">
</map>
	</div>
	<div style="height:30px;"></div>
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