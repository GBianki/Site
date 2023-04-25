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
	   <p><span class="titulos">Obrigado pela sua confiança!</span></p>
	   <div style="height:20px;"></div>
	   <div class="fonte_site">
	     <p>Seus dados foram enviados com sucesso.</p>
       </div>
	   <div style="height:20px;"></div>
	   <p class="fonte_site" align="justify">&nbsp;</p>
	   <p class="fonte_site" align="justify">&nbsp;</p>
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