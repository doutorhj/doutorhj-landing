@extends('layouts.base')

@section('title', 'Home - DoutorHJ')

@push('scripts')

@endpush

@section('content')
<section class="resultado">
    <div class="container">
        <div class="area-container">
            <div class="titulo">
                <strong>Resultados da sua pesquisa</strong>
                <p>Após a escoha do prestador, indique a data e horário para realizar o seu agendamento.</p>
            </div>
            <div class="area-alt-busca">
                <a class="btn btn-primary btn-alt-busca" data-toggle="collapse" href="#collapseFormulario" role="button" aria-expanded="false" aria-controls="collapseFormulario">Alterar Busca <i class="fas fa-edit"></i></a>
            </div>
            <div class="collapseFormulario collapse show" id="collapseFormulario">
                <form action="" class="form-busca-resultado">
                    <div class="row">
                        <div class="form-group col-md-12 col-lg-3">
                            <select class="form-control" id="tipo">
                                <option>Tipo de atendimento</option>
                                <option>Opção 1</option>
                                <option>Opção 2</option>
                                <option>Opção 3</option>
                                <option>Opção 4</option>
                            </select>
                        </div>
                        <div class="form-group col-md-12 col-lg-3">
                            <select class="form-control" id="especialidade">
                                <option>Especialidade</option>
                                <option>Opção 1</option>
                                <option>Opção 2</option>
                                <option>Opção 3</option>
                                <option>Opção 4</option>
                            </select>
                        </div>
                        <div class="form-group col-md-12 col-lg-3">
                            <select class="form-control" id="local">
                                <option>Local</option>
                                <option>Opção 1</option>
                                <option>Opção 2</option>
                                <option>Opção 3</option>
                                <option>Opção 4</option>
                            </select>
                        </div>
                        <div class="form-group col-md-12 col-lg-3">
                            <button type="button" class="btn btn-primary btn-vermelho"><i class="fas fa-search"></i> Alterar Busca</button>
                        </div>
                    </div>
                </form> 
            </div>                        
            <div class="lista-resultado">
                <div class="row">
                    <div class="col-md-12 col-lg-5">
                        <div class="ordenar-por div-filtro">
                            <select class="form-control" id="ordenar">
                                <option>Ordenar por...</option>
                                <option>Maior preço</option>
                                <option>Menor preço</option>
                            </select>
                        </div>
                        <div id="accordion">
                            <div class="card card-resultado">
                                <div class="card-body">
                                    <h5 class="card-title">ACREDITAR CLÍNICA MÉDICA S.A</h5>
                                    <h6 class="card-subtitle">Dr. Alexandre José</h6>
                                    <p class="card-text">Clínica médica</p>
                                    <p class="card-text">Edifício Pio X - 716 Sul (Asa Sul) Brasilia, DF <a class="link-mapa-mobile" href="https://goo.gl/maps/MPNHA8CLr812">Ver no mapa</a></p>
                                </div>
                                <div class="card-footer">
                                    <div class="form-check area-seleciona-profissional">
                                        <input id="inputProfissional1" class="form-check-input" name="radioProfissional" type="radio" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        <label class="form-check-label" for="inputProfissional1">
                                        Agendar com este profissional
                                        </label>
                                    </div>
                                    <strong>R$ 173,00</strong>
                                </div>
                                <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                    <div class="area-escolher-data">
                                        <div class="titulo-escolhe-data">
                                            Escolha data e horário
                                        </div>
                                        <div class="escolher-data">                                    
                                            <input id="selecionaData1" class="selecionaData" type="text" placeholder="Data">
                                            <label for="selecionaData1"><i class="far fa-calendar-alt"></i></label>
                                        </div>
                                        <div class="escolher-hora">                                    
                                            <input id="selecionaHora1" class="selecionaData" type="text" placeholder="Horário">
                                            <label for="selecionaHora1"><i class="far fa-clock"></i></label>
                                        </div>
                                        <div class="confirma-data">
                                            <span>26/03/2018 - Segunda-feira - 10h30min</span>
                                        </div>
                                        <div class="valor-total">
                                            <span><strong>Total a pagar:</strong> R$ 173,00</span>
                                        </div>
                                        <button type="button" class="btn btn-primary btn-vermelho">Prosseguir para pagamento</button>
                                    </div>
                                </div>
                            </div>
                            <div class="card card-resultado">
                                <div class="card-body">
                                    <h5 class="card-title">ACREDITAR CLÍNICA MÉDICA S.A</h5>
                                    <h6 class="card-subtitle">Dr. Alexandre José</h6>
                                    <p class="card-text">Clínica médica</p>
                                    <p class="card-text">Edifício Pio X - 716 Sul (Asa Sul) Brasilia, DF <a class="link-mapa-mobile" href="https://goo.gl/maps/MPNHA8CLr812">Ver no mapa</a></p>
                                </div>
                                <div class="card-footer">
                                    <div class="form-check area-seleciona-profissional">
                                        <input id="inputProfissional2" class="form-check-input" name="radioProfissional" type="radio" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        <label class="form-check-label" for="inputProfissional2">
                                        Agendar com este profissional
                                        </label>
                                    </div>
                                    <strong>R$ 173,00</strong>
                                </div>
                                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                                    <div class="area-escolher-data">
                                        <div class="titulo-escolhe-data">
                                            Escolha data e horário
                                        </div>
                                        <div class="escolher-data">                                    
                                            <input id="selecionaData2" class="selecionaData" type="text" placeholder="Data">
                                            <label for="selecionaData2"><i class="far fa-calendar-alt"></i></label>
                                        </div>
                                        <div class="escolher-hora">                                    
                                            <input id="selecionaHora2" class="selecionaData" type="text" placeholder="Horário">
                                            <label for="selecionaHora2"><i class="far fa-clock"></i></label>
                                        </div>
                                        <div class="confirma-data">
                                            <span>26/03/2018 - Segunda-feira - 10h30min</span>
                                        </div>
                                        <div class="valor-total">
                                            <span><strong>Total a pagar:</strong> R$ 173,00</span>
                                        </div>
                                        <button type="button" class="btn btn-primary btn-vermelho">Prosseguir para pagamento</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-mapa col-lg-7">
                        <div class="mapa-resultado">
                            <div id="map"></div>    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@push('scripts')
	<script type="text/javascript">
		var laravel_token = '{{ csrf_token() }}';
		var resizefunc = [];

		/*********************************
        *
        * GOOGLE MAPS
        * 
        *********************************/

        function initMap() {

            var clinicaUm = {
                info: '<strong>Check Up Centro Médico</strong><br>\
                            SDS Bloco O Ed. Venâncio VI 221 a 227<br> Brasília, DF, 70393-905<br>\
                            <a href="https://goo.gl/Y9UUWt">Obter direção</a>',
                lat: -15.7987496,
                long: -47.8949315
            };

            var clinicaDois = {
                info: '<strong>Actual Clínica Médica e Psicologia</strong><br>\
                            SCS Quadra 6 Bloco A Lote 150/170 - Edifício<br> Carioca 5 andar Sala 514/15,<br> Q. 6 - Asa Sul, Brasília - DF, 70325-900<br>\
                            <a href="https://goo.gl/JWt3Tp">Obter direção</a>',
                lat: -15.7960663,
                long: -47.8927361
            };

            var clinicaTres = {
                info: '<strong>Clínica Devas</strong><br>\r\
                            SDN CNB Etapa III - S 4104, Setor de<br> Diversões Norte - Brasília, DF, 70077-000<br>\
                            <a href="https://goo.gl/2JdPbn">Obter direção</a>',
                lat: -15.7920841,
                long: -47.8859702
            };

            var locations = [
            [clinicaUm.info, clinicaUm.lat, clinicaUm.long, 0],
            [clinicaDois.info, clinicaDois.lat, clinicaDois.long, 1],
            [clinicaTres.info, clinicaTres.lat, clinicaTres.long, 2],
            ];

            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 13,
                center: new google.maps.LatLng(-15.7987496, -47.8949315),
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });

            var infowindow = new google.maps.InfoWindow({});

            var marker, i;

            for (i = 0; i < locations.length; i++) {
                marker = new google.maps.Marker({
                    position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                    map: map
                });

                google.maps.event.addListener(marker, 'click', (function (marker, i) {
                    return function () {
                        infowindow.setContent(locations[i][0]);
                        infowindow.open(map, marker);
                    }
                })(marker, i));
            }
        }
	</script>
	
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCkovLYQa6lqh1suWtV_ZFJ0i9ChWc9hqI&callback=initMap" type="text/javascript"></script>
@endpush

@endsection
