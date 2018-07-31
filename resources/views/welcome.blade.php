@extends('layouts.base')

@section('title', 'DoctorHoje: Home')

@push('scripts')
@endpush

@section('content')
    @if (Auth::check())
        <section class="area-busca-interna home-logado">
            <div class="container">
                <div id="busca-home" class="busca-home">
                    <div class="titulo">
                        <span>Quero agendar</span>
                    </div>
                    <form action="/resultado" class="form-busca-home form-busca" method="get" onsubmit="return validaBuscaAtendimento()">
                        <div class="row">
                            <div class="form-group col-md-12 col-lg-3">
                                <label for="tipo">Tipo de atendimento</label>
                                <select id="tipo_atendimento" class="form-control" name="tipo_atendimento">
                                    <option value="" disabled selected hidden>Ex.: Consulta</option>
                                    <option value="saude">Consulta Médica</option>
                                    <option value="odonto">Consulta Odontológica</option>
                                    <option value="exame">Exames</option>
                                    <!-- <option value="checkup">Check-ups</option> -->
                                </select>
                            </div>
                            <div class="form-group col-md-12 col-lg-3">
                                <label for="especialidade">Especialidade ou exame</label>
                                <select id="tipo_especialidade" class="form-control select2" name="tipo_especialidade">
                                    <option value="" disabled selected hidden>Ex.: Clínica Médica</option>
                                </select>
                            </div>
                            <div class="form-group col-md-12 col-lg-3" id="dvLocalAtendimento">
                                <label for="local">Local de antedimento</label>
                                <select id="local_atendimento" class="form-control select2" name="local_atendimento">
                                    <option value="" disabled selected hidden>Ex.: Asa Sul</option>
                                </select>
                                <i class="cvx-no-loading fa fa-spin fa-spinner"></i>
                                <input type="hidden" id="endereco_id" name="endereco_id">
                            </div>
                            <div class="form-group col-md-12 col-lg-3">
                                <button type="submit" class="btn btn-primary btn-vermelho">Pesquisar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="box-resumo-home">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="link-agendamentos">
                                <p>Não se esqueça da sua<br> próxima consulta e/ou exame</p>
                                <button type="button" class="btn btn-light" onclick="window.location.href='{{ route("meus-agendamentos") }}'">
                                    Ver meus
                                    agendamentos
                                </button>
                            </div>
                        </div>
                        <div class="col-12">
                            <p class="tit-box-pc">Resumo dos próximos agendamentos</p>
                            <div class="row">
                                @foreach($agendamentos_home as $agendamento)
									@if(!is_null($agendamento->atendimento_id))
										<div class="proxima-consulta col-sm-12 col-md-6">
											<div class="area-pc">
												<div class="tit-pc">
													{{--<p>Sua próxima consulta é</p>--}}
													<p class="data-consulta">
														Dia {{ date('d', strtotime($agendamento->getRawDtAtendimentoAttribute()))}}
														de {{ strftime('%B', strtotime($agendamento->getRawDtAtendimentoAttribute())) }}
														às <span>{{ date('H', strtotime($agendamento->getRawDtAtendimentoAttribute())) }}h
															@if(date('i', strtotime($agendamento->getRawDtAtendimentoAttribute())) != '00')
																e {{ date('i', strtotime($agendamento->getRawDtAtendimentoAttribute())) }}min. @endif</span></p>
												</div>
												<div class="resumo">
													<div class="nome-status">
														<div class="row">
															<div class="col-6">
																<p class="beneficiario">{{ $agendamento->paciente->nm_primario }}</p>
															</div>
															<div class="col-6">
																@if($agendamento->getRawCsStatusAttribute() == 10)
																	<span class="status pre-agendado">Pré-Agendado</span>
																@elseif($agendamento->getRawCsStatusAttribute() == 20)
																	<span class="status confirmado">Confirmado</span>
																@elseif($agendamento->getRawCsStatusAttribute() == 30)
																	<span class="status nao-confirmado">Não Confirmado</span>
																@elseif($agendamento->getRawCsStatusAttribute() == 40)
																	<span class="status finalizado">Finalizado</span>
																@elseif($agendamento->getRawCsStatusAttribute() == 50)
																	<span class="status ausente">Não compareceu</span>
																@elseif($agendamento->getRawCsStatusAttribute() == 60)
																	<span class="status cancelado">Cancelado</span>
																@elseif($agendamento->getRawCsStatusAttribute() == 70)
																	<span class="status agendado">Agendado</span>
																@elseif($agendamento->getRawCsStatusAttribute() == 80)
																	<span class="status retorno">Retorno de Consulta</span>
																@endif
															</div>
														</div>
													</div>
													<p class="tipo">
														<span><strong>Procedimento:</strong> {{ $agendamento->atendimento->ds_preco }}</span>
														<br>
														<span><strong>Prestador:</strong> {{ $agendamento->clinica->nm_fantasia }}</span>
													</p>
													{{--<p class="profissional">
														Dr. {{ $agendamento->profissional->nm_primario.' '.$agendamento->profissional->nm_secundario }}</p>
													<p class="valor">R$ <span>{{ $agendamento->valor_total }}</span></p>--}}
													<p class="endereco">
														<strong>Endereço:</strong> {{ $agendamento->endereco_completo }}</p>
												</div>
											</div>
										</div>
									@elseif(!is_null($agendamento->checkup_id))
										<div class="proxima-consulta col-sm-12 col-md-6">
											<div class="area-pc">
												<div class="tit-pc">
													<p class="data-consulta">Checkup</p>
												</div>
												<div class="resumo">
													<div class="nome-status">
														<div class="row">
															<div class="col-12">
																<p class="beneficiario">{{$agendamento->paciente->nm_primario}}</p>
															</div>
														</div>
													</div>
													<p class="tipo">
														<span>Checkup {{$agendamento->checkup->titulo}} - {{ucfirst($agendamento->checkup->tipo)}}</span>
													</p>
													{{--<p class="profissional">
														Dr. {{ $agendamento->profissional->nm_primario.' '.$agendamento->profissional->nm_secundario }}</p>
													<p class="valor">R$ <span>{{ $agendamento->valor_total }}</span></p>--}}
													<a href="{{url('meus-agendamentos#checkup')}}" class="link-detalhes-checkup">Clique aqui para ver todos os detalhes do Checkup</a>
												</div>
											</div>
										</div>
									@endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-noticias-home">
                <div class="container">
                    <div class="titulo">Saúde Hoje</div>
                    <div class="row">
                        <div class="col-sm-12 col-md-4 card-post">
                            <div class="card">
                                <img class="card-img-top" src="/libs/home-template/img/cancer-de-mama.png" alt="">
                                <div class="card-body">
                                    <h5 class="card-title">A doença que mais afeta as mulheres</h5>
                                    <p class="card-text">Câncer de Mama - Esse tipo de câncer é o que mais comum entre
                                        mulheres no Brasil e no Mundo, correspondendo a 25% de novos casos de câncer
                                        todos os anos. Segundo o Instituto Nacional do Câncer (INCA), no ano passado, os
                                        casos de câncer de Mama ultrapassaram a casa de 57mil no Brasil. Existem vários
                                        tipo desse câncer, na maioria deles, quando diagnosticados em fases iniciais, é
                                        passível de tratamento, com boas perspectivas de cura. Atente-se. </p>
                                    {{--<a href="#" class="btn btn-link">Leia mais</a>--}}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4 card-post">
                            <div class="card">
                                <img class="card-img-top" src="/libs/home-template/img/gripe.png" alt="">
                                <div class="card-body">
                                    <h5 class="card-title">Proteja-se das doenças causadas pelo frio</h5>
                                    <p class="card-text">Vale lembrar que em 95% dos casos a gripe é causada por vírus,
                                        e apenas 5% por bactéria. Em determinado casos, a infecção por vírus pode acabar
                                        facilitando a infecção por bactéria, já que por conta da infecção há uma redução
                                        das defesas. A vacina não causa gripe nos pacientes imunizados, mas leva de
                                        quatro a oito semanas para ter eficácia plena, por isso a pessoa que tomou a
                                        vacina pode chegar a ficar doente nesse período. Fique esperto. </p>
                                    {{--<a href="#" class="btn btn-link">Leia mais</a>--}}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4 card-post">
                            <div class="card">
                                <img class="card-img-top" src="/libs/home-template/img/beba-agua.png" alt="">
                                <div class="card-body">
                                    <h5 class="card-title">Os benefícios de beber água diariamente</h5>
                                    <p class="card-text">Apesar de não haver contra-indicação, é importante estar atento
                                        à quantidade correta de água que deve ser ingerida. Para isso, existe um cálculo
                                        simples e rápido: basta multiplicar seu peso por 35 para obter o resultado em
                                        ml. Mas por que 35? A resposta é fácil: a conta matemática considera que são
                                        necessários 35 mililitros de água para cada quilograma de peso corporal .Uma
                                        pessoa que pesa 70kg deve ingerir aproximadamente 2,4 litros de água.</p>
                                    {{--<a href="#" class="btn btn-link">Leia mais</a>--}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="area-parceiros">
                <div class="container">
                    <div class="titulo-home">
                        <h3>Alguns de nossos parceiros</h3>
                    </div>
                    <div class="owl-carousel owl-theme">
                        <div class="item"><img src="/libs/home-template/img/parceiros/alianca.png" alt=""></div>
                        <div class="item"><img src="/libs/home-template/img/parceiros/bela-vista.png" alt=""></div>
                        <div class="item"><img src="/libs/home-template/img/parceiros/biocardios.png" alt=""></div>
                        <div class="item"><img src="/libs/home-template/img/parceiros/biovida.png" alt=""></div>
                        <div class="item"><img src="/libs/home-template/img/parceiros/cardio-fitness.png" alt=""></div>
                        <div class="item"><img src="/libs/home-template/img/parceiros/centro-clinico-lorena-de-fatima.png" alt=""></div>
                        <div class="item"><img src="/libs/home-template/img/parceiros/cimed.png" alt=""></div>
                        <div class="item"><img src="/libs/home-template/img/parceiros/cinthia-rojas.png" alt=""></div>
                        <div class="item"><img src="/libs/home-template/img/parceiros/clinica-fisio-evidence.png" alt=""></div>
                        <div class="item"><img src="/libs/home-template/img/parceiros/clinica-vila-rica.png" alt=""></div>
                        <div class="item"><img src="/libs/home-template/img/parceiros/cliped.png" alt=""></div>
                        <div class="item"><img src="/libs/home-template/img/parceiros/crb.png" alt=""></div>
                        <div class="item"><img src="/libs/home-template/img/parceiros/digidoc.png" alt=""></div>
                        <div class="item"><img src="/libs/home-template/img/parceiros/exame.png" alt=""></div>
                        <div class="item"><img src="/libs/home-template/img/parceiros/fenelon.png" alt=""></div>
                        <div class="item"><img src="/libs/home-template/img/parceiros/fisiolabor.png" alt=""></div>
                        <div class="item"><img src="/libs/home-template/img/parceiros/equilibrio.png" alt=""></div>
                        <div class="item"><img src="/libs/home-template/img/parceiros/hospital-pacini.png" alt=""></div>
                        <div class="item"><img src="/libs/home-template/img/parceiros/ibramer.png" alt=""></div>
                        <div class="item"><img src="/libs/home-template/img/parceiros/ila.png" alt=""></div>
                        <div class="item"><img src="/libs/home-template/img/parceiros/implante-vida.png" alt=""></div>
                        <div class="item"><img src="/libs/home-template/img/parceiros/infinita.png" alt=""></div>
                        <div class="item"><img src="/libs/home-template/img/parceiros/instituto-mulher.png" alt=""></div>
                        <div class="item"><img src="/libs/home-template/img/parceiros/icb.png" alt=""></div>
                        <div class="item"><img src="/libs/home-template/img/parceiros/dermaline.png" alt=""></div>
                        <div class="item"><img src="/libs/home-template/img/parceiros/sabin.png" alt=""></div>
                        <div class="item"><img src="/libs/home-template/img/parceiros/micra.png" alt=""></div>
                        <div class="item"><img src="/libs/home-template/img/parceiros/policlinica-mais.png" alt=""></div>
                        <div class="item"><img src="/libs/home-template/img/parceiros/santa-paula.png" alt=""></div>
                        <div class="item"><img src="/libs/home-template/img/parceiros/visao.png" alt=""></div>
                    </div>
                </div>
            </div>
        </section>
    @else
        <section class="banner-slider-home">

            <header>
                <div id="carouselBannerHome" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators">
                        <li data-target="#carouselBannerHome" data-slide-to="0" class="active"></li>
                        <li data-target="#carouselBannerHome" data-slide-to="1"></li>
                        <li data-target="#carouselBannerHome" data-slide-to="2"></li>
                    </ol>
                    <div class="carousel-inner" role="listbox">

                        <div class="carousel-item active" style="background-image: url('/libs/home-template/img/banner1.jpg')">
                            <div class="carousel-caption">
                                <div class="area-texto">
                                    <div class="texto">
                                        <h3>Conheça o novo jeito de cuidar da sua saúde</h3>
                                        <p>Agende consultas e exames.<br> É simples, é rápido, é online!</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="carousel-item" style="background-image: url('/libs/home-template/img/banner_home_cupom.jpg')">
                            <div class="carousel-caption">
                                <div class="area-cupom">
                                    <img src="/libs/home-template/img/cupom.png" alt="">
                                </div>
                                <div class="area-texto area-texto-cupom">
                                    <div class="texto texto-cupom">
                                        <h3>Use o código: <span><br>DOCTOR10<br></span>e ganhe 10% na<br>primeira
                                            consulta.</h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="carousel-item" style="background-image: url('/libs/home-template/img/banner_home_precos.jpg')">
                            <div class="carousel-caption">
                                <div class="area-texto">
                                    <div class="texto">
                                        <h3>Preços justos<br>que cabem no<br>seu bolso.</h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="busca-home" class="busca-welcome">
                            <div class="titulo">
                                <span>Quero agendar</span>
                            </div>
                            <form action="/resultado" class="form-busca-home form-busca" method="get" onsubmit="return validaBuscaAtendimento()">
                                <div class="row">
                                    <div class="form-group col-md-12 col-lg-3">
                                        <label for="tipo">Tipo de atendimento</label>
                                        <select id="tipo_atendimento" class="form-control" name="tipo_atendimento">
                                            <option value="" disabled selected hidden>Ex.: Consulta</option>
                                            <option value="saude">Consulta Médica</option>
                                            <option value="odonto">Consulta Odontológica</option>
                                            <option value="exame">Exames</option>
                                            <!-- <option value="checkup">Check-up</option> -->
                                        </select>
                                    </div>
                                    <div class="form-group col-md-12 col-lg-3">
                                        <label for="especialidade">Especialidade ou exame</label>
                                        <select id="tipo_especialidade" class="form-control select2" name="tipo_especialidade">
                                            <option value="">Ex.: Clínica Médica</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-12 col-lg-3" id="localAtendimento">
                                        <label for="local">Local de atendimento</label>
                                        <select id="local_atendimento" class="form-control select2" name="local_atendimento">
                                            <option value="">Ex.: Asa Sul</option>
                                        </select>
                                        <i class="cvx-no-loading fa fa-spin fa-spinner"></i>
                                        <input type="hidden" id="endereco_id" name="endereco_id">
                                    </div>
                                    <div class="form-group col-md-12 col-lg-3">
                                        <button type="submit" class="btn btn-primary btn-vermelho">Pesquisar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <a class="carousel-control-prev" href="#carouselBannerHome" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselBannerHome" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </header>


            {{--<div class="area-banner">
                <div class="container">
                    <div class="frase">
                        <p>Conheça o novo jeito de cuidar da sua saúde</p>
                        <p>Agende consultas e exames.<br>
                            É simples, é rápido, é online! </p>
                    </div>

                </div>
            </div>--}}
            <div id="area-sobre" class="area-sobre">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12 col-md-6 col1-sobre">
                            <p>O que é o Doctor hoje?</p>
                            <p>O Doctor Hoje é uma<br>plataforma digital que facilita o <strong>acesso a consultas e
                                    exames</strong> com <strong>preços acessíveis</strong> que cabem no seu orçamento.
                            </p>
                        </div>
                        <div class="col-md-1"></div>
                        <div class="col-sm-12 col-md-5 col2-sobre">
                            <p>O Doctor Hoje reúne uma ampla rede de profissionais de saúde em Brasília que, por meio da
                                plataforma, oferecem o acesso a consultas e exames com preços acessíveis. (NÃO somos um
                                plano ou seguro saúde).</p>
                            <a href="https://doctorhoje.com.br/login">Acesse e comece a usar agora mesmo
                                <i class="fa fa-angle-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div id="como-funciona" class="area-passos">
                <div class="container">
                    <div class="titulo-home">
                        <span>É tão fácil que você não vai acreditar</span>
                        <h3>Veja como é fácil levar saúde para sua família</h3>
                        <p>São apenas 5 passos para você cuidar da sua saúde.<br>
                            Chega de mensalidades caras para serviços que nem sempre você usa. </p>
                    </div>
                    <div class="steps">
                        <div class="step step1">
                            <div class="icone">
                                <img src="/libs/home-template/img/icone-step1.png" alt="">
                            </div>
                            <div class="traco"></div>
                            <div class="numero">
                                <span>1</span>
                            </div>
                            <div class="texto">
                                <p>Selecione o tipo<br>de atendimento</p>
                            </div>
                        </div>
                        <div class="step step2">
                            <div class="icone">
                                <img src="/libs/home-template/img/icone-step2.png" alt="">
                            </div>
                            <div class="traco"></div>
                            <div class="numero">
                                <span>2</span>
                            </div>
                            <div class="texto">
                                <p>Escolha<br>o local</p>
                            </div>
                        </div>
                        <div class="step step3">
                            <div class="icone">
                                <img src="/libs/home-template/img/icone-step3.png" alt="">
                            </div>
                            <div class="traco"></div>
                            <div class="numero">
                                <span>3</span>
                            </div>
                            <div class="texto">
                                <p>Faça<br>a sugestão<br>do dia e horário</p>
                            </div>
                        </div>
                        <div class="step step4">
                            <div class="icone">
                                <img src="/libs/home-template/img/icone-step4.png" alt="">
                            </div>
                            <div class="traco"></div>
                            <div class="numero">
                                <span>4</span>
                            </div>
                            <div class="texto">
                                <p>Realize<br>o pagamento</p>
                            </div>
                        </div>
                        <div class="step step5">
                            <div class="icone">
                                <img src="/libs/home-template/img/icone-step5.png" alt="">
                            </div>
                            <div class="traco"></div>
                            <div class="numero">
                                <span>5</span>
                            </div>
                            <div class="texto">
                                <p>Pronto!<br>Agora é só ir ao médico<br>e cuidar da sua saúde</p>
                            </div>
                        </div>
                    </div>
                    <div class="steps-mobile">
                        <div class="step step1">
                            <div class="numero">
                                <span>1</span>
                            </div>
                            <div class="traco"></div>
                            <div class="icone">
                                <img src="/libs/home-template/img/icone-step1.png" alt="">
                            </div>
                            <div class="texto">
                                <p>Selecione o tipo<br>de atendimento</p>
                            </div>
                        </div>
                        <div class="step step2">
                            <div class="numero">
                                <span>2</span>
                            </div>
                            <div class="traco"></div>
                            <div class="icone">
                                <img src="/libs/home-template/img/icone-step2.png" alt="">
                            </div>
                            <div class="texto">
                                <p>Escolha<br>o local</p>
                            </div>
                        </div>
                        <div class="step step3">
                            <div class="numero">
                                <span>3</span>
                            </div>
                            <div class="traco"></div>
                            <div class="icone">
                                <img src="/libs/home-template/img/icone-step3.png" alt="">
                            </div>
                            <div class="texto">
                                <p>Faça a sugestão<br>do dia e horário</p>
                            </div>
                        </div>
                        <div class="step step4">
                            <div class="numero">
                                <span>4</span>
                            </div>
                            <div class="traco"></div>
                            <div class="icone">
                                <img src="/libs/home-template/img/icone-step4.png" alt="">
                            </div>
                            <div class="texto">
                                <p>Realize<br>o pagamento</p>
                            </div>
                        </div>
                        <div class="step step5">
                            <div class="numero">
                                <span>5</span>
                            </div>
                            <div class="traco"></div>
                            <div class="icone">
                                <img src="/libs/home-template/img/icone-step5.png" alt="">
                            </div>
                            <div class="texto">
                                <p>Pronto!<br>Agora é só ir ao médico<br>e cuidar da sua saúde</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="checkup" class="area-checkup">
                <div class="container">
                    <div class="selo">
                        <img src="/libs/home-template/img/ckp-selo.png" alt="">
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-lg-6">
                            <div class="pacote-checkup">
                                <div class="resumo">
                                    <h3>CheckUp Masculino</h3>
                                    <p class="qtde-procedimentos">9 Procedimentos</p>
                                    <ul>
                                        <li>
                                            <p><strong>1 Consulta:</strong> Consulta urológica</p>
                                        </li>
                                        <li>
                                            <p><strong>7 Exames:</strong> Creatina; Lipidograma; Glicemia de Jejum; Hemograma Completo; TGO; TGP; Urina I</p>
                                        </li>
                                        <li>
                                            <p><strong>1 Imagem:</strong> Ultrassom da prostata</p>
                                        </li>
                                    </ul>
                                    <div class="valor">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <p class="vlr-mercado"><strike>R$ 610,00</strike></p>
                                                <p>valor de mercado</p>
                                            </div>
                                            <div class="col-md-3">
                                                <p class="txt-auxiliar">a partir de</p>
                                            </div>
                                            <div class="col-md-5">
                                                <p class="vlr-drhj">R$ 350,00</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="area-btn">
                                    <button type="button" class="btn btn-agendar-plano-1">Agendar</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-6">
                            <div class="pacote-checkup">
                                <div class="resumo">
                                    <h3>CheckUp Feminino</h3>
                                    <p class="qtde-procedimentos">12 Procedimentos</p>
                                    <ul>
                                        <li>
                                            <p><strong>1 Consulta:</strong> Consulta ginecológica</p>
                                        </li>
                                        <li>
                                            <p><strong>8 Exames:</strong> Creatina; Lipidograma; Glicemia de Jejum; Hemograma Completo; TGO; TGP; TSH; Urina I</p>
                                        </li>
                                        <li>
                                            <p><strong>2 Imagens:</strong> Ultrassom das mamas; Ultrasom transvaginal</p>
                                        </li>
                                        <li>
                                            <p><strong>Exame clínico laboratorial:</strong> Papanicolau</p>
                                        </li>
                                    </ul>
                                    <div class="valor">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <p class="vlr-mercado"><strike>R$ 638,00</strike></p>
                                                <p>valor de mercado</p>
                                            </div>
                                            <div class="col-md-3">
                                                <p class="txt-auxiliar">a partir de</p>
                                            </div>
                                            <div class="col-md-5">
                                                <p class="vlr-drhj">R$ 438,00</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="area-btn">
                                    <button type="button" class="btn btn-agendar-plano-2">Agendar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="vantagens" class="area-vantagens">
                <div class="container">
                    <div class="titulo-home">
                        <span>Você só tem a ganhar</span>
                        <h3>Vantagens em escolher o Doctor Hoje</h3>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="icone-vantagem">
                                <img src="/libs/home-template/img/icone-vantagem1.png" alt="">
                            </div>
                            <div class="texto-vantagem">
                                <h4>Mais barato</h4>
                                <p>Consultas e exames com preços acessíveis, sem burocracia ou carências.</p>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="icone-vantagem">
                                <img src="/libs/home-template/img/icone-vantagem2.png" alt="">
                            </div>
                            <div class="texto-vantagem">
                                <h4>Controle total</h4>
                                <p>Pague somente ao utilizar e acompanhe todo o histórico de suas consultas e
                                    exames. </p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="icone-vantagem">
                                <img src="/libs/home-template/img/icone-vantagem3.png" alt="">
                            </div>
                            <div class="texto-vantagem">
                                <h4>Não fique esperando</h4>
                                <p>Realize suas consultas ou exames o quanto antes. No máximo em 10 dias você será
                                    atendido
                                    com o médico de sua preferência. </p>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="icone-vantagem">
                                <img src="/libs/home-template/img/icone-vantagem4.png" alt="">
                            </div>
                            <div class="texto-vantagem">
                                <h4>Moderno</h4>
                                <p>A agendamento online oferece a comodidade do agendamento da consulta e exame no seu
                                    computador ou celular.</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="icone-vantagem">
                                <img src="/libs/home-template/img/icone-vantagem5.png" alt="">
                            </div>
                            <div class="texto-vantagem">
                                <h4>Onde você precisar</h4>
                                <p>Mais de 700 prestadores em Brasília, 56 especialidades e 1200 exames. </p>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="icone-vantagem">
                                <img src="/libs/home-template/img/icone-vantagem6.png" alt="">
                            </div>
                            <div class="texto-vantagem">
                                <h4>Seja avisado</h4>
                                <p>O sistema envia lembretes do dia e horário da consulta.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="area-participe">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 col-lg-6 col-xl-6 order-lg-2 box-banner-conheca">
                            <div class="area-imagem">
                                <img src="/libs/home-template/img/banner-participe.jpg" alt="Venha fazer parte você também">
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-6 col-xl-6 order-lg-1">
                            <div class="titulo-home">
                                <span>Venha fazer parte você também</span>
                                <h3>Você é profissional de saúde?</h3>
                            </div>
                            <p>Entre em contato e venha participar da nossa plataforma. O Doctor Hoje conecta você a
                                milhares de clientes. Oferecemos sistema para o gerenciamento da sua agenda e
                                pagamentos. </p>
                            <form action="https://prestador.doctorhoje.com.br/">
                                <button type="submit" class="btn btn-primary btn-vermelho">Saiba mais</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="area-aplicativo">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12 col-md-5 area-imagem-aplicativo">
                            <img src="/libs/home-template/img/aplicativo-smartphone.png" alt="Aplicativo Doctor Hoje">
                        </div>
                        <div class="col-sm-12 col-md-7 area-texto-aplicativo">
                            <div class="titulo-home">
                                <span>a comodidade anda junto com você </span>
                                <h3>Aplicativo Doctor Hoje</h3>
                            </div>
                            <p>Com o aplicativo Doctor Hoje, você poderá ter acesso a todas as funcionalidades na palma
                                da sua mão. Agende, pague, veja suas marcações, altere horários e ainda fique por dentro
                                das novidades. </p>
                            <p><strong>Em breve disponível:</strong></p>
                            <ul class="link-aplicativos">
                                <li><a>Google Play</a></li>
                                <li><a>App Store</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="area-parceiros">
                <div class="container">
                    <div class="titulo-home">
                        <h3>Alguns de nossos parceiros</h3>
                    </div>
                    <div class="owl-carousel owl-theme">
                        <div class="item"><img src="/libs/home-template/img/parceiros/bela-vista.png" alt=""></div>
                        <div class="item"><img src="/libs/home-template/img/parceiros/biovida.png" alt=""></div>
                        <div class="item"><img src="/libs/home-template/img/parceiros/cardio-fitness.png" alt=""></div>
                        <div class="item"><img src="/libs/home-template/img/parceiros/centro-clinico-lorena-de-fatima.png" alt=""></div>
                        <div class="item"><img src="/libs/home-template/img/parceiros/cimed.png" alt=""></div>
                        <div class="item"><img src="/libs/home-template/img/parceiros/cinthia-rojas.png" alt=""></div>
                        <div class="item"><img src="/libs/home-template/img/parceiros/clinica-fisio-evidence.png" alt=""></div>
                        <div class="item"><img src="/libs/home-template/img/parceiros/clinica-vila-rica.png" alt=""></div>
                        <div class="item"><img src="/libs/home-template/img/parceiros/cliped.png" alt=""></div>
                        <div class="item"><img src="/libs/home-template/img/parceiros/crb.png" alt=""></div>
                        <div class="item"><img src="/libs/home-template/img/parceiros/digidoc.png" alt=""></div>
                        <div class="item"><img src="/libs/home-template/img/parceiros/fenelon.png" alt=""></div>
                        <div class="item"><img src="/libs/home-template/img/parceiros/fisiolabor.png" alt=""></div>
                        <div class="item"><img src="/libs/home-template/img/parceiros/equilibrio.png" alt=""></div>
                        <div class="item"><img src="/libs/home-template/img/parceiros/hospital-pacini.png" alt=""></div>
                        <div class="item"><img src="/libs/home-template/img/parceiros/ibramer.png" alt=""></div>
                        <div class="item"><img src="/libs/home-template/img/parceiros/ila.png" alt=""></div>
                        <div class="item"><img src="/libs/home-template/img/parceiros/implante-vida.png" alt=""></div>
                        <div class="item"><img src="/libs/home-template/img/parceiros/infinita.png" alt=""></div>
                        <div class="item"><img src="/libs/home-template/img/parceiros/instituto-mulher.png" alt=""></div>
                        <div class="item"><img src="/libs/home-template/img/parceiros/icb.png" alt=""></div>
                        <div class="item"><img src="/libs/home-template/img/parceiros/dermaline.png" alt=""></div>
                        <div class="item"><img src="/libs/home-template/img/parceiros/micra.png" alt=""></div>
                        <div class="item"><img src="/libs/home-template/img/parceiros/policlinica-mais.png" alt=""></div>
                        <div class="item"><img src="/libs/home-template/img/parceiros/santa-paula.png" alt=""></div>
                        <div class="item"><img src="/libs/home-template/img/parceiros/visao.png" alt=""></div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    @push('scripts')
        <script type="text/javascript">
            $(document).ready(function () {
                var laravel_token = '{{ csrf_token() }}';
                var resizefunc = [];

                $('#tipo_especialidade option:first').prop("selected", false);
                $('#local_atendimento option:first').prop("selected", false);
            });

            /*********************************
             *
             * VELOCIDADE CARROSSEL
             *
             *********************************/

            $('.carousel').carousel({
                interval: 7000
            });

            /*********************************
             *
             * CARROSSEL PARCEIROS
             *
             *********************************/

            $(document).ready(function(){
                $('.owl-carousel').owlCarousel({
                    loop:true,
                    margin:10,
                    nav:true,
                    responsive:{
                        0:{
                            items:3
                        },
                        600:{
                            items:4
                        },
                        1000:{
                            items:8
                        }
                    }
                })
            });

            /*********************************
             *
             * COPIA TOKEN
             *
             *********************************/

            jQuery(function ($) {

                $('#copyButton').click(function () {
                    var pElement = $('#token')[0];
                    copyToClipboard(pElement, showSuccessMsg);
                });

                function showSuccessMsg() {
                    $('#successMsg').finish().fadeIn(300).fadeOut(1000);
                }

                function copyToClipboard(element, successCallback) {
                    selectText(element);

                    var succeed;
                    try {
                        succeed = document.execCommand('copy');
                    } catch (e) {
                        succeed = false;
                    }

                    if (succeed && typeof(successCallback) === 'function') {
                        successCallback();
                    }
                }

                function selectText(element) {
                    if (/INPUT|TEXTAREA/i.test(element.tagName)) {
                        element.focus();
                        if (element.setSelectionRange) {
                            element.setSelectionRange(0, element.value.length);
                        } else {
                            element.select();
                        }
                        return;
                    }

                    var rangeObj, selection;
                    if (document.createRange) { // IE 9+ and all other browsers
                        rangeObj = document.createRange();
                        rangeObj.selectNodeContents(element);
                        selection = window.getSelection();
                        selection.removeAllRanges();
                        selection.addRange(rangeObj);
                    } else if (document.body.createTextRange) { // IE <=8
                        rangeObj = document.body.createTextRange();
                        rangeObj.moveToElementText(element);
                        rangeObj.select();
                    }
                }

            });

            /*********************************
             *
             * Smooth Scroll
             *
             *********************************/

            $('a[href*="#"]')
                .not('[href="#"]')
                .not('[href="#0"]')
                .click(function (event) {
                    if (
                        location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '')
                        &&
                        location.hostname == this.hostname
                    ) {
                        var target = $(this.hash);
                        target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                        if (target.length) {
                            event.preventDefault();
                            $('html, body').animate({
                                scrollTop: target.offset().top
                            }, 1000, function () {
                                var $target = $(target);
                                $target.focus();
                                if ($target.is(":focus")) {
                                    return false;
                                } else {
                                    $target.attr('tabindex', '-1');
                                    $target.focus();
                                }
                                ;
                            });
                        }
                    }
                });


            /*********************************
             *
             * Alerta
             *
             *********************************/

            /* Success

            $(document).ready(function () {
                swal(
                    {
                        title: '<div class="tit-sweet tit-success"><i class="fa fa-check-circle" aria-hidden="true"></i> Sucesso!</div>',
                        text: 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit.'
                    }
                );
            });

            */

            /* Warning

            $(document).ready(function () {
                swal(
                    {
                        title: '<div class="tit-sweet tit-warning"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Atenção!</div>',
                        text: 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit.'
                    }
                );
            });

            */

            /* Info

            $(document).ready(function () {
                swal(
                    {
                        title: '<div class="tit-sweet tit-info"><i class="fa fa-info-circle" aria-hidden="true"></i> Informação</div>',
                        text: 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit.'
                    }
                );
            });

            */

            /* Question

            $(document).ready(function () {
                swal(
                    {
                        title: '<div class="tit-sweet tit-question"><i class="fa fa-question-circle" aria-hidden="true"></i> Tem certeza?</div>',
                        text: 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit.'
                    }
                );
            });

            */

            /* Error

            $(document).ready(function () {
                swal(
                    {
                        title: '<div class="tit-sweet tit-error"><i class="fa fa-times-circle" aria-hidden="true"></i> Ocorreu um erro</div>',
                        text: 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit.'
                    }
                );
            });

            */

        </script>

    @endpush
@endsection