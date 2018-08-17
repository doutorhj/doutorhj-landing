<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    
    <link rel="shortcut icon" href="/libs/comvex-template/img/favicon.ico">
    <meta name="description" content="Comvex">
    <meta name="keywords" content="doutorhj saúde consulta médico sus plano de saúde">
    <meta name="author" content="Theogenes Ferreira Duarte">
  
    <title>@yield('title', 'Doutor HJ')</title>

    @push('style')
    
    	<!-- Google fonts -->
    	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700" rel="stylesheet">
    	
    	<!-- Template css -->
        <link type="text/css" rel="stylesheet" href="/libs/home-template/css/bootstrap.min.css" />
        <link type="text/css" rel="stylesheet" href="/libs/font-awesome-4.7.0/css/font-awesome.min.css" />
        
        <!-- Switchery CSS-->
    	<link rel="stylesheet" href="/libs/switchery/switchery.min.css">

        <!-- Sweet Alert css -->
        <link href="/libs/sweet-alert/sweetalert2.css" rel="stylesheet" type="text/css" />
        
        <link type="text/css" rel="stylesheet" href="/libs/home-template/css/jquery.datetimepicker.min.css" />
        <link type="text/css" rel="stylesheet" href="/libs/home-template/css/style.css" />
        <link type="text/css" rel="stylesheet" href="/libs/select2/css/select2.min.css" />
        
        <!-- Template theme CSS -->
    	<!-- <link rel="stylesheet" href="/libs/comvex-template/css/style_dark.css"> -->
        
        <!-- JQueryUI -->
    	<!-- <link rel="stylesheet" href="/libs/jquery-ui-themes/jquery-ui.css"> -->
    	
    	<!-- JQuery Autocomplete -->
    	<link rel="stylesheet" href="/libs/jquery-autocomplete/css/styles.css">
    	
        <!-- DoutorHJ Reset CSS -->
    	<link rel="stylesheet" href="/css/doutorhj.style.css">
    	
    	<!--[if lt IE 10]>
        <div style="background: #212121; padding: 10px 0; box-shadow: 3px 3px 5px 0 rgba(0,0,0,.3); clear: both; text-align:center; position: relative; z-index:1;"><a href="http://windows.microsoft.com/en-US/internet-explorer/"><img src="/libs/home-template/img/ie8-panel/warning_bar_0000_us.jpg" border="0" height="42" width="820" alt="You are using an outdated browser. For a faster, safer browsing experience, upgrade for free today."></a></div>
        <script src="js/html5shiv.min.js"></script>
		<![endif]-->    	
        
        <script src="/libs/comvex-template/js/jquery.min.js"></script>
        <script src="/libs/comvex-template/js/modernizr.min.js"></script>
        <script src="/libs/home-template/js/jquery.datetimepicker.full.min.js"></script>
        <script src="/libs/home-template/js/popper.min.js"></script>
    	<script src="/libs/home-template/js/bootstrap.min.js"></script>
        <script src="/libs/select2/js/select2.min.js"></script>
        <script src="/libs/select2/js/i18n/pt-BR.js"></script>
        <script src="/libs/jquery-autocomplete/js/jquery.autocomplete.min.js"></script>
        <script src="/libs/switchery/switchery.min.js"></script>
        
        <script src="/libs/jquery-credit-card/jquery.creditCardValidator.js"></script>
        
        <script src="/js/doutorhj.script.js"></script>
        
        <script type="text/javascript">
        	var laravel_token = '{{ csrf_token() }}';
        </script>

    @endpush
    
    @stack('style')
</head>
<body>
	<div class="tudo">
        <header>
        	<nav class="navbar navbar-expand-xl">
                <div class="container">
                    <a class="navbar-brand" href="/">
                        <h1>Doutor Hoje</h1>
                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarMobile" aria-controls="navbarMobile" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fa fa-bars"></i>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarMobile">
                        <ul class="navbar-nav ml-auto">                            
                            <li class="nav-item">
                                <a class="nav-link" href="#area-sobre">O que é o Doutor Hoje?</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#como-funciona">Como funciona?</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#vantagens">Vantagens</a>
                            </li>
                            <li class="nav-item btn-profissional">
                                <a class="nav-link" href="https://prestador.doctorhoje.com.br">Sou profissional de saúde</a>
                            </li>
                            <li class="nav-item btn-entrar">
                                <a class="nav-link" href="{{ route('login') }}">Entrar</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
        <section class="pagina-erro">
            <div class="">
                <div class="error">
                    <div class="container-floud">
                        <div class="col-xs-12 ground-color text-center">
                            <div class="container-error-404">
                                <div class="clip"><div class="shadow"><span class="digit thirdDigit"></span></div></div>
                                <div class="clip"><div class="shadow"><span class="digit secondDigit"></span></div></div>
                                <div class="clip"><div class="shadow"><span class="digit firstDigit"></span></div></div>
                                {{--<div class="msg">OOPS!<span class="triangle"></span></div>--}}
                            </div>
                            <h2 class="h1">Desculpe! Página não encontrada.</h2>
                            <div class="action-link-wrap">
                                <a onclick="history.back(-1)" href="javascript:void(0)" class="btn btn-light waves-effect waves-light m-t-20"><i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar Página anterior</a>
                                <a href="{{ route('landing-page') }}" class="btn btn-secondary waves-effect waves-light m-t-20"><i class="fa fa-home" aria-hidden="true"></i> Ir para Home</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <script>
            function randomNum()
            {
                "use strict";
                return Math.floor(Math.random() * 9)+1;
            }
            var loop1,loop2,loop3,time=30, i=0, number, selector3 = document.querySelector('.thirdDigit'), selector2 = document.querySelector('.secondDigit'),
                selector1 = document.querySelector('.firstDigit');
            loop3 = setInterval(function()
            {
                "use strict";
                if(i > 40)
                {
                    clearInterval(loop3);
                    selector3.textContent = 4;
                }else
                {
                    selector3.textContent = randomNum();
                    i++;
                }
            }, time);
            loop2 = setInterval(function()
            {
                "use strict";
                if(i > 80)
                {
                    clearInterval(loop2);
                    selector2.textContent = 0;
                }else
                {
                    selector2.textContent = randomNum();
                    i++;
                }
            }, time);
            loop1 = setInterval(function()
            {
                "use strict";
                if(i > 100)
                {
                    clearInterval(loop1);
                    selector1.textContent = 4;
                }else
                {
                    selector1.textContent = randomNum();
                    i++;
                }
            }, time);
        </script>
		{{--<div class="ex-page-content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <svg class="svg-box" width="380px" height="500px" viewBox="0 0 837 1045" version="1.1"
                             xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                             xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">
                            <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"
                               sketch:type="MSPage">
                                <path d="M353,9 L626.664028,170 L626.664028,487 L353,642 L79.3359724,487 L79.3359724,170 L353,9 Z"
                                      id="Polygon-1" stroke="#3bafda" stroke-width="6" sketch:type="MSShapeGroup"></path>
                                <path d="M78.5,529 L147,569.186414 L147,648.311216 L78.5,687 L10,648.311216 L10,569.186414 L78.5,529 Z"
                                      id="Polygon-2" stroke="#7266ba" stroke-width="6" sketch:type="MSShapeGroup"></path>
                                <path d="M773,186 L827,217.538705 L827,279.636651 L773,310 L719,279.636651 L719,217.538705 L773,186 Z"
                                      id="Polygon-3" stroke="#f76397" stroke-width="6" sketch:type="MSShapeGroup"></path>
                                <path d="M639,529 L773,607.846761 L773,763.091627 L639,839 L505,763.091627 L505,607.846761 L639,529 Z"
                                      id="Polygon-4" stroke="#00b19d" stroke-width="6" sketch:type="MSShapeGroup"></path>
                                <path d="M281,801 L383,861.025276 L383,979.21169 L281,1037 L179,979.21169 L179,861.025276 L281,801 Z"
                                      id="Polygon-5" stroke="#ffaa00" stroke-width="6" sketch:type="MSShapeGroup"></path>
                            </g>
                        </svg>
                    </div>

                    <div class="col-lg-6">
                        <div class="message-box">
                            <h1 class="m-b-0">404</h1>
                            <h4>Página Não Encontrada</h4>
                            <div class="buttons-con">
                                <div class="action-link-wrap">
                                    <a onclick="history.back(-1)" href="" class="btn btn-info waves-effect waves-light m-t-20">Voltar Página anterior</a>
                                    <a href="" class="btn btn-primary waves-effect waves-light m-t-20">Ir para Home</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>--}}
        <footer>
            <div class="container">
                <div class="area-logo-rodape">
                    <img src="/libs/home-template/img/logo-branca.png" alt="Doutor Hoje">
                </div>
                <!-- teste smartgit -->
                <div class="row">
                    <div class="col-sm-6 col-md-3">
                        <img class="logo-rodape" src="/libs/home-template/img/logo-branca.png" alt="Doutor Hoje">
                        <div class="info-atendimento">
                            <p>Central de Atendimento</p>
                            <p><strong>(61) 3221-5350</strong></p>
                            <p>Horário de atendimento das 9h às 18h, de segunda à sexta-feira, excetos feriados.</p>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <h5>Sobre Nós</h5>
                        <ul>
                            <li><a href="#area-sobre">O que é o Doutor Hoje?</a></li>
                            <li><a href="#como-funciona">Como Funciona</a></li>
                            <li><a href="#vantagens">Vantagens</a></li>
                            <li><a href="{{ route('landing-page') }}#" data-toggle="modal" data-target="#modalTermos">Termo de Uso & Política de Privacidade</a></li>
                            <li><a href="">Trabalhe Conosco</a></li>
                        </ul>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <h5>Clientes</h5>
                        <ul>
                            <li><a href="">Seja Cliente</a></li>
                            <li><a href="">Área restrita</a></li>
                            <li><a href="">Agende Consulta</a></li>
                            <li><a href="">Agende Exame</a></li>
                            <li><a href="">Vantagens</a></li>
                        </ul>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <h5>PROFISSIONAIS DE SAÚDE </h5>
                        <ul>
                            <li><a href="">Seja um parceiro</a></li>
                            <li><a href="">Vantagens</a></li>
                            <li><a href="">Área restrita</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="endereco-site">
                <a href="{{ route('landing-page') }}">doutorhoje.com.br</a>
            </div>
        </footer>        
    </div>
    <script>
    		var resizefunc = [];
    	</script>
    	
        <script src="/libs/switchery/switchery.min.js"></script>
        
    	<!-- Counter Up  -->
        <script src="/libs/waypoints/lib/jquery.waypoints.min.js"></script>
        <script src="/libs/counterup/jquery.counterup.min.js"></script>
        
        <!-- skycons -->
        <script src="/libs/skyicons/skycons.min.js" type="text/javascript"></script>
		 
        <!-- Sweet Alert Js  -->
        <script src="/libs/sweet-alert/sweetalert2.min.js"></script>
        <script src="/libs/comvex-template/pages/jquery.sweet-alert.init.js"></script>
        
        <!-- Multi Select Js Quicksearch Js  -->
        <script type="text/javascript" src="/libs/multiselect/js/jquery.multi-select.js"></script>
        <script type="text/javascript" src="/libs/jquery-quicksearch/jquery.quicksearch.js"></script>
        
        <!-- Notification js -->
        <script src="/libs/notifyjs/dist/notify.min.js"></script>
        <script src="/libs/notifications/notify-metro.js"></script>
        
        <!-- Plugins  -->
        <script type="text/javascript" src="/libs/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js"></script>
        <script type="text/javascript" src="/libs/jquery-quicksearch/jquery.quicksearch.js"></script>
        <script type="text/javascript" src="/libs/select2/js/select2.min.js"></script>
        <script type="text/javascript" src="/libs/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js"></script>
		
        <script type="text/javascript" src="/libs/moment/moment.js"></script>
        <script type="text/javascript" src="/libs/mjolnic-bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
                
        <script type="text/javascript" src="/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
        <script type="text/javascript" src="/libs/bootstrap-daterangepicker/daterangepicker.js"></script>
		



		<script type="text/javascript" src="/js/jquery.maskMoney.min.js"></script>
		
        <!-- Custom main Js -->
        <script src="/libs/comvex-template/js/jquery.core.js"></script>
        <!-- <script src="/libs/comvex-template/js/jquery.app.js"></script> -->
        <!-- <script src="/libs/comvex-template/js/jquery.form-advanced.init.js"></script> -->
        <!-- <script src="/libs/comvex-template/js/jquery.tree.js"></script> -->
        
		<!-- <script src="/libs/jquery-ui/jquery-ui.js"></script> -->
		
		<script src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>
    	<script src="/js/utilitarios.js"></script>
    
</body>
</html>
