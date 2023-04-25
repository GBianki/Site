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

<header>

  <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
      <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
      <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
      <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
    </div>
    <div class="carousel-inner">
      <div class="carousel-item active" style="background-image: url('https://img.freepik.com/fotos-gratis/chaves-e-casa-de-modelo-de-brinquedo-de-alta-vista_23-2148301692.jpg?w=2000')">
        <div class="carousel-caption">
          <h5>A segurança no seu aluguel</h5>
          <p>Alugue conosco sem dificuldades</p>
        </div>
      </div>
      <div class="carousel-item" style="background-image: url('https://blog.kenlo.com.br/wp-content/uploads/2017/03/iStock-507755522.jpg')">
        <div class="carousel-caption">
          <h5>Compre com os melhores benefícios</h5>
          <p>Cobrimos qualquer benefício do mercado</p>
        </div>
      </div>
      <div class="carousel-item" style="background-image: url('http://www.cimentoitambe.com.br/wp-content/uploads/2019/12/mercado_imobiliario.jpg')">
        <div class="carousel-caption">
          <h5>Seu boleto em um toque</h5>
          <p>Contamos com um fácil acesso a seus boletos, sem dificuldades</p>
        </div>
      </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>
</header>

  </div>
  <div id="superior_bg">
  <center>
  <div style="height:8px;"></div>
<? require_once(dirname(__FILE__) . '/prg/pesqImov.php'); ?>
  </div></center>
</div>
<!-- end menu -->


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