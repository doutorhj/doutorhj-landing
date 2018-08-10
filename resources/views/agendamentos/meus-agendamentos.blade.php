@extends('layouts.base')

@section('title', 'DoctorHj: Meus Agendamentos')

@push('scripts')

@endpush

@section('content')
    <section class="area-busca-interna minha-conta">
        <div class="container">

            @include('includes/main-search', ['class' => 'busca-home'] );

            <div class="box-meus-agendamentos">
                <div class="titulo-box">
                    Meus Agendamentos
                </div>
                <div class="lista-agendamentos">
                    <ul class="nav nav-tabs" id="tabAgendamentos" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#consultas" role="tab" aria-controls="home" aria-selected="true">Consultas
                                e Exames</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#checkup" role="tab" aria-controls="profile" aria-selected="false">Checkup</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="contentAgendamentos">

                        {{-- EXAMES/CONSULTAS --}}

                        <div class="tab-pane fade show active" id="consultas" role="tabpanel" aria-labelledby="home-tab">
                            @foreach($agendamentos_home as $agendamento)
                                @if(!is_null($agendamento->atendimento_id))
                                    <div class="agendamentos">
                                        <div class="row">
                                            <div class="col col-lg-2 area-data">
                                                @if(!empty($agendamento->dt_atendimento))
                                                    <p id="dia_{{ $agendamento->id }}" class="dia">@if($agendamento->getRawCsStatusAttribute() != 60) {{ date('d', strtotime($agendamento->getRawDtAtendimentoAttribute()))}} @else
                                                            -- @endif</p>
                                                    <p id="mes_{{ $agendamento->id }}" class="mes text-center">@if($agendamento->getRawCsStatusAttribute() != 60) {{ substr(strftime('%B', strtotime($agendamento->getRawDtAtendimentoAttribute())), 0, 3) }} @else
                                                            --- @endif</p>
                                                    <p id="hora_{{ $agendamento->id }}" class="mes" style="font-size: 28px;">@if($agendamento->getRawCsStatusAttribute() != 60) {{ date('H:i', strtotime($agendamento->getRawDtAtendimentoAttribute())) }} @else
                                                            ---- @endif</p>
                                                @else
                                                    <p id="dia_{{ $agendamento->id }}" class="dia"> -- </p>
                                                    <p id="mes_{{ $agendamento->id }}" class="mes text-center"> --- </p>
                                                    <p id="hora_{{ $agendamento->id }}" class="mes" style="font-size: 28px;">
                                                        ---- </p>
                                                @endif
                                            </div>
                                            <div class="col col-lg-5 area-informacoes">
                                                <div class="nome-status">
                                                    <div class="row">
                                                        <div class="col-lg-7">
                                                            <p class="beneficiario">{{ $agendamento->paciente->nm_primario }}</p>
                                                        </div>
                                                        <div class="col-lg-5">
                                                            @if($agendamento->getRawCsStatusAttribute() == 10)
                                                                <span id="status_agendamento_{{ $agendamento->id }}" class="status pre-agendado">Pré-Agendado</span>
                                                            @elseif($agendamento->getRawCsStatusAttribute() == 20)
                                                                <span id="status_agendamento_{{ $agendamento->id }}" class="status confirmado">Confirmado</span>
                                                            @elseif($agendamento->getRawCsStatusAttribute() == 30)
                                                                <span id="status_agendamento_{{ $agendamento->id }}" class="status nao-confirmado">Não Confirmado</span>
                                                            @elseif($agendamento->getRawCsStatusAttribute() == 40)
                                                                <span id="status_agendamento_{{ $agendamento->id }}" class="status finalizado">Finalizado</span>
                                                            @elseif($agendamento->getRawCsStatusAttribute() == 50)
                                                                <span id="status_agendamento_{{ $agendamento->id }}" class="status ausente">Não compareceu</span>
                                                            @elseif($agendamento->getRawCsStatusAttribute() == 60)
                                                                <span id="status_agendamento_{{ $agendamento->id }}" class="status cancelado">Cancelado</span>
                                                            @elseif($agendamento->getRawCsStatusAttribute() == 70)
                                                                <span id="status_agendamento_{{ $agendamento->id }}" class="status agendado">Agendado</span>
                                                            @elseif($agendamento->getRawCsStatusAttribute() == 80)
                                                                <span id="status_agendamento_{{ $agendamento->id }}" class="status retorno">Retorno de Consulta</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <p class="tipo">{{ $agendamento->atendimento->ds_preco }}
                                                    <strong>{{ $agendamento->clinica->nm_fantasia }}</strong></p>
                                                @if(!is_null($agendamento->profissional_id))
                                                    <p class="profissional">
                                                        Dr. {{ $agendamento->profissional->nm_primario.' '.$agendamento->profissional->nm_secundario }}</p>
                                                @endif
                                                <p class="valor">R$ <span>{{ $agendamento->valor_total }}</span>
                                                    <span class="dt-pagamento">pago em {{ $agendamento->data_pagamento }}</span>
                                                </p>
                                                <p class="endereco">{{ $agendamento->endereco_completo }}</p>
                                            </div>
                                            <div class="col col-lg-4">
                                                <p class="tit-token">Token</p>
                                                <p class="txt-token">Apresente este código no momento da consulta</p>
                                                <div class="area-token">
                                                    <p id="token_{{ $agendamento->id }}" class="token">@if($agendamento->getRawCsStatusAttribute() == 20 | $agendamento->getRawCsStatusAttribute() == 70) {{ $agendamento->te_ticket }} @else
                                                            ●●●●●● @endif</p>
                                                    <p>
                                                        <button type="button" id="copyButton_{{ $agendamento->id }}" class="btn btn-azul copyButton" @if($agendamento->getRawCsStatusAttribute() != 20 & $agendamento->getRawCsStatusAttribute() != 70) disabled="disabled" @endif >
                                                            Copiar
                                                        </button>
                                                        <br>
                                                        <span id="successMsg_{{ $agendamento->id }}" class="successMsg" style="display:none;">Copiado!</span>

                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-lg-1">
                                                @if($agendamento->getRawCsStatusAttribute() != 60)
                                                    <div id="btn_remarcar_agendamento_{{ $agendamento->id }}" class="btn-group dropleft">
                                                        <button type="button" class="btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="fa fa-ellipsis-v"></i>
                                                        </button>
                                                        <div class="dropdown-menu ddm-opcoes">
                                                            @if(!is_null($agendamento->profissional_id))
                                                                <p>
                                                                    <a href="javascript:void(0);" onclick="remarcarAgendamento(this, '{{ $agendamento->clinica->id }}', '{{ $agendamento->profissional->id }}', '{{ $agendamento->id }}')">Remarcar</a>
                                                                </p>
                                                            @endif
                                                            <p>
                                                                <a href="javascript:void(0)" onclick="cancelarAgendamento(this, '{{ $agendamento->id }}')">Cancelar</a>
                                                            </p>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        {{-- CHECKUP --}}
                        <div class="tab-pane fade" id="checkup" role="tabpanel" aria-labelledby="profile-tab">
                            @foreach($agendamentos_home as $agendamento)
                                @if(!is_null($agendamento->checkup_id))
                                    <div class="agendamentos">
                                        <div class="row">
                                            <div class="col col-lg-8 area-informacoes">
												<div class="nome-ckp">
                                                    <p>Check-Up Masculino - Completo (ñ dinâmico)</p>
                                                </div>
                                                <div class="linha-resumo">
                                                    <div class="titulo-resumo">
                                                        <span>Pessoa que utilizará o serviço:</span>
                                                    </div>
                                                    <div class="dados-resumo">
                                                        <p>{{$agendamento->paciente->nm_primario.' '.$agendamento->paciente->nm_secundario}}</p>
                                                    </div>
                                                </div>

                                                <div class="titulo-resumo">
                                                    <span>Procedimentos inclusos no Checkup</span>
                                                </div>

                                                <div class="dados-resumo">

                                                    {{--CONSULTAS--}}

                                                    <div class="procedimentos">
                                                        <p>Consultas</p>
                                                        <ul>
                                                            @foreach($agendamento->datahoracheckups as $datahoracheckup)
                                                                @if(!is_null($datahoracheckup->itemcheckup->atendimento->consulta_id))
                                                                    <li>
                                                                        <div class="row">
                                                                            <div class="col-md-9">
                                                                                <div class="clinica">
                                                                                    Consulta {{$datahoracheckup->itemcheckup->atendimento->consulta->ds_consulta}}
                                                                                    , no dia
                                                                                    <span>{{$datahoracheckup->dt_atendimento}}</span><br>
                                                                                    <span>Profissional: </span>{{$datahoracheckup->itemcheckup->atendimento->profissional->nm_primario.' '.$datahoracheckup->itemcheckup->atendimento->profissional->nm_secundario}}
                                                                                </div>
                                                                                <div class="endereco">
                                                                                    <span>Clinica: </span> {{$datahoracheckup->itemcheckup->atendimento->clinica->nm_fantasia}}
                                                                                    <br>
                                                                                    <span>Local: </span>{{$datahoracheckup->itemcheckup->atendimento->clinica->enderecos[0]->te_endereco.', '.$datahoracheckup->itemcheckup->atendimento->clinica->enderecos[0]->nr_logradouro.', '.$datahoracheckup->itemcheckup->atendimento->clinica->enderecos[0]->te_bairro}}
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                @if($agendamento->getRawCsStatusAttribute() == 10)
                                                                                    <span id="status_agendamento_{{ $agendamento->id }}" class="status pre-agendado">Pré-Agendado</span>
                                                                                @elseif($agendamento->getRawCsStatusAttribute() == 20)
                                                                                    <span id="status_agendamento_{{ $agendamento->id }}" class="status confirmado">Confirmado</span>
                                                                                @elseif($agendamento->getRawCsStatusAttribute() == 30)
                                                                                    <span id="status_agendamento_{{ $agendamento->id }}" class="status nao-confirmado">Não Confirmado</span>
                                                                                @elseif($agendamento->getRawCsStatusAttribute() == 40)
                                                                                    <span id="status_agendamento_{{ $agendamento->id }}" class="status finalizado">Finalizado</span>
                                                                                @elseif($agendamento->getRawCsStatusAttribute() == 50)
                                                                                    <span id="status_agendamento_{{ $agendamento->id }}" class="status ausente">Não compareceu</span>
                                                                                @elseif($agendamento->getRawCsStatusAttribute() == 60)
                                                                                    <span id="status_agendamento_{{ $agendamento->id }}" class="status cancelado">Cancelado</span>
                                                                                @elseif($agendamento->getRawCsStatusAttribute() == 70)
                                                                                    <span id="status_agendamento_{{ $agendamento->id }}" class="status agendado">Agendado</span>
                                                                                @elseif($agendamento->getRawCsStatusAttribute() == 80)
                                                                                    <span id="status_agendamento_{{ $agendamento->id }}" class="status retorno">Retorno de Consulta</span>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                @endif
                                                            @endforeach
                                                        </ul>
                                                    </div>

                                                    {{--EXAMES--}}

                                                    <div class="procedimentos">
                                                        <p>Exames</p>
                                                        <ul>
                                                            @foreach($agendamento->datahoracheckups as $datahoracheckup)
                                                                @if(!is_null($datahoracheckup->itemcheckup->atendimento->procedimento_id))
                                                                    <li>
                                                                        <div class="row">
                                                                            <div class="col-md-9">
                                                                                <div class="clinica">
                                                                                    {{$datahoracheckup->itemcheckup->atendimento->procedimento->ds_procedimento}}
                                                                                    @if(!is_null($datahoracheckup->dt_atendimento))
                                                                                        , no dia
                                                                                        <span>{{ $datahoracheckup->dt_atendimento}}</span>
                                                                                    @endif
                                                                                </div>
                                                                                <div class="endereco">
                                                                                    <span>Clinica: </span> {{$datahoracheckup->itemcheckup->atendimento->clinica->nm_fantasia}}
                                                                                    <br>
                                                                                    <span>Local: </span>{{$datahoracheckup->itemcheckup->atendimento->clinica->enderecos[0]->te_endereco.', '.$datahoracheckup->itemcheckup->atendimento->clinica->enderecos[0]->nr_logradouro.', '.$datahoracheckup->itemcheckup->atendimento->clinica->enderecos[0]->te_bairro}}
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                @if($agendamento->getRawCsStatusAttribute() == 10)
                                                                                    <span id="status_agendamento_{{ $agendamento->id }}" class="status pre-agendado">Pré-Agendado</span>
                                                                                @elseif($agendamento->getRawCsStatusAttribute() == 20)
                                                                                    <span id="status_agendamento_{{ $agendamento->id }}" class="status confirmado">Confirmado</span>
                                                                                @elseif($agendamento->getRawCsStatusAttribute() == 30)
                                                                                    <span id="status_agendamento_{{ $agendamento->id }}" class="status nao-confirmado">Não Confirmado</span>
                                                                                @elseif($agendamento->getRawCsStatusAttribute() == 40)
                                                                                    <span id="status_agendamento_{{ $agendamento->id }}" class="status finalizado">Finalizado</span>
                                                                                @elseif($agendamento->getRawCsStatusAttribute() == 50)
                                                                                    <span id="status_agendamento_{{ $agendamento->id }}" class="status ausente">Não compareceu</span>
                                                                                @elseif($agendamento->getRawCsStatusAttribute() == 60)
                                                                                    <span id="status_agendamento_{{ $agendamento->id }}" class="status cancelado">Cancelado</span>
                                                                                @elseif($agendamento->getRawCsStatusAttribute() == 70)
                                                                                    <span id="status_agendamento_{{ $agendamento->id }}" class="status agendado">Agendado</span>
                                                                                @elseif($agendamento->getRawCsStatusAttribute() == 80)
                                                                                    <span id="status_agendamento_{{ $agendamento->id }}" class="status retorno">Retorno de Consulta</span>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                @endif
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col col-lg-3">
                                                <p class="tit-token">Token</p>
                                                <p class="txt-token">Apresente este código no momento da consulta</p>
                                                <div class="area-token">
                                                    <p id="token_{{ $agendamento->id }}" class="token">@if($agendamento->getRawCsStatusAttribute() == 20 | $agendamento->getRawCsStatusAttribute() == 70) {{ $agendamento->te_ticket }} @else
                                                            ●●●●●● @endif</p>
                                                    <p>
                                                        <button type="button" id="copyButton_{{ $agendamento->id }}" class="btn btn-azul copyButton" @if($agendamento->getRawCsStatusAttribute() != 20 & $agendamento->getRawCsStatusAttribute() != 70) disabled="disabled" @endif >
                                                            Copiar
                                                        </button>
                                                        <br>
                                                        <span id="successMsg_{{ $agendamento->id }}" class="successMsg" style="display:none;">Copiado!</span>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-lg-1">
                                                @if($agendamento->getRawCsStatusAttribute() != 60)
                                                    <div id="btn_remarcar_agendamento_{{ $agendamento->id }}" class="btn-group dropleft">
                                                        <button type="button" class="btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="fa fa-ellipsis-v"></i>
                                                        </button>
                                                        <div class="dropdown-menu ddm-opcoes">
                                                            @if(!is_null($agendamento->profissional_id))
                                                                <p>
                                                                    <a href="javascript:void(0);" onclick="remarcarAgendamento(this, '{{ $agendamento->clinica->id }}', '{{ $agendamento->profissional->id }}', '{{ $agendamento->id }}')">Remarcar</a>
                                                                </p>
                                                            @endif
                                                            <p>
                                                                <a href="javascript:void(0)" onclick="cancelarAgendamento(this, '{{ $agendamento->id }}')">Cancelar</a>
                                                            </p>
                                                        </div>
                                                    </div>
                                                @endif
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
    </section>
    @push('scripts')
        <script type="text/javascript">
            $(document).ready(function () {
                var laravel_token = '{{ csrf_token() }}';
                var resizefunc = [];

                // Javascript to enable link to tab
                var url = document.location.toString();
                if (url.match('#')) {
                    $('.nav-tabs a[href="#' + url.split('#')[1] + '"]').tab('show');
                }

                // Change hash for page-reload
                $('.nav-tabs a').on('shown.bs.tab', function (e) {
                    window.location.hash = e.target.hash;
                })
            });

            /*********************************
             *
             * COPIA TOKEN
             *
             *********************************/

            jQuery(function ($) {

                $('.copyButton').click(function () {
                    var pElement = $(this).parent().parent().find('.token')[0];
                    var element = $(this).parent().parent().find('.successMsg');
                    copyToClipboard(pElement, showSuccessMsg(element));
                });

                function showSuccessMsg(element) {
                    element.finish().fadeIn(300).fadeOut(1000);
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

            function remarcarAgendamento(input, clinica_id, profissional_id, agendamento_id) {

                swal({
                    title: '<div class="tit-sweet tit-info"><i class="fa fa-info-circle" aria-hidden="true"></i> Atenção</div>',
                    html: '<span style="margin-bottom: 10px;">Informe a Data/hora para solicitar a Remarcação: </span>' +
                        '<div style="margin-top: 10px;">' +
                        '<div class="escolher-data">' +
                        '<input type="text" id="selecionaDataRemarcar" class="selecionaDataRemarcar mascaraDataAgendamento" name="data_atendimento" placeholder="Data">' +
                        '<label for="selecionaData1623"><i class="fa fa-calendar"></i></label>' +
                        '</div>' +
                        '<div class="escolher-hora">' +
                        '<input type="text" id="selecionaHoraRemarcar" class="selecionaHoraRemarcar mascaraHoraAgendamento" name="hora_atendimento" placeholder="Horário">' +
                        '<label for="selecionaHora1623"><i class="fa fa-clock-o"></i></label>' +
                        '<input type="hidden" id="resultAgendamento" value="false">' +
                        '<input type="hidden" id="messageAgendamento" value="">' +
                        '</div> </div>',
                    type: 'info',
                    showCancelButton: true,
                    confirmButtonClass: 'btn btn-confirm mt-2',
                    cancelButtonClass: 'btn btn-cancel ml-2 mt-2',
                    confirmButtonText: 'Solicitar',
                    cancelButtonText: 'Cancelar',
                    showLoaderOnConfirm: true,
                    allowOutsideClick: false,
                    preConfirm: function (input) {
                        return new Promise(function (resolve, reject) {

                            var data_agendamento = $('#selecionaDataRemarcar').val()
                            var hora_agendamento = $('#selecionaHoraRemarcar').val()

                            if (clinica_id.length == 0 | profissional_id.length == 0 | agendamento_id.length == 0) {

                                reject('Agendamento não disponível');
                                return false;
                            }

                            if (data_agendamento.length == 0 | hora_agendamento.length == 0) {

                                reject('A Data/hora informada não é válida. Por favor, tente novamente.');
                                return false;
                            }

                            $.ajax({
                                type: 'post',
                                dataType: 'json',
                                url: '/remarcar-agendamento',
                                data: {
                                    'clinica_id': clinica_id,
                                    'profissional_id': profissional_id,
                                    'agendamento_id': agendamento_id,
                                    'data_agendamento': data_agendamento,
                                    'hora_agendamento': hora_agendamento,
                                    '_token': laravel_token
                                },
                                timeout: 15000,
                                success: function (result) {

                                    if (result.status) {

                                        var agendamento = JSON.parse(result.agendamento);

                                        $('#resultAgendamento').val('true');
                                        $('#messageAgendamento').val(result.mensagem);
                                        $('#status_agendamento_' + agendamento.id).attr('class', 'status pre-agendado').html('Pré-Agendado');

                                        $('#dia_' + agendamento.id).html(agendamento.dia_agendamento);
                                        $('#mes_' + agendamento.id).html(agendamento.mes_agendamento);
                                        $('#hora_' + agendamento.id).html(agendamento.hora_agendamento);

                                    } else {
                                        $('#resultAgendamento').val('false');
                                        $('#messageAgendamento').val(result.mensagem);
                                    }

                                    resolve();
                                },
                                error: function (result) {
                                    $('#resultAgendamento').val('false');
                                    $('#messageAgendamento').val('Falha na operação!');
                                    resolve();
                                }
                            });
                        })
                    },
                }).then(function (e) {
                    var element = e.target;
                    var result = $('#resultAgendamento').val();
                    var data = $('#selecionaDataRemarcar').val();
                    var hora = $('#selecionaHoraRemarcar').val();
                    var mensagem = $('#messageAgendamento').val();
                    //alert('teste: '+data);

                    if (result == 'true') {
                        swal(
                            {
                                title: '<div class="tit-sweet tit-success"><i class="fa fa-check-circle" aria-hidden="true"></i> Sucesso!</div>',
                                text: mensagem
                            }
                        );
                    } else if (result == 'false') {
                        swal(
                            {
                                title: '<div class="tit-sweet tit-error"><i class="fa fa-times-circle" aria-hidden="true"></i> Ocorreu um erro</div>',
                                text: mensagem
                            }
                        );
                    }
                });

                var today_date = new Date();
                var today_date_range = new Date();
                var min_date = today_date.setDate(today_date.getDate() + 2);
                var max_date = today_date_range.setMonth(today_date_range.getMonth() + 2);

                jQuery.datetimepicker.setLocale('pt-BR');

                jQuery('.selecionaDataRemarcar').datetimepicker({
                    timepicker: false,
                    format: 'd/m/Y',
                    minDate: min_date,
                    maxDate: max_date,
                    beforeShowDay: function (date) {
                        return [date.getDay() == 1 || date.getDay() == 2 || date.getDay() == 3 || date.getDay() == 4 || date.getDay() == 5, ""]
                    },
                }).on("input change", function (e) {
                    //console.log("Date changed: ", e.target.value);
                    if (e.target.value != '') {
                        var ct_date_temp = ((e.target.value).replace('/', '-').replace('/', '-')).split('-');
                        var ct_date = new Date(ct_date_temp[2], ct_date_temp[1] - 1, ct_date_temp[0]);
                        var days = ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'];

                        if (ct_date.getDay() == 0 || ct_date.getDay() == 6) {
                            $(this).val('');
                            swal(
                                {
                                    title: '<div class="tit-sweet tit-warning"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Atenção!</div>',
                                    text: 'Atendimento não disponível para ser Realizado aos Sábados e Domingos'
                                }
                            );
                        }

                        jQuery(this).parent().parent().find('.confirma-data span.span-data').html((e.target.value).replace('.', '/').replace('.', '/') + "- " + days[ct_date.getDay()] + " - ");

                        if (ct_date <= today_date) {
                            $(this).val('');
                            //alert('A Data informada não está disponível para a Agendamento');
                            swal(
                                {
                                    title: '<div class="tit-sweet tit-warning"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Atenção!</div>',
                                    text: 'A Data informada não está disponível para a Agendamento'
                                }
                            );

                        }
                    }
                }).on("input blur", function (e) {
                    //console.log("Date changed: ", e.target.value);
                    if (e.target.value != '') {
                        var ct_date_input = ((e.target.value).replace('/', '-').replace('/', '-')).split('-');
                        var ct_date = new Date(ct_date_input[2], ct_date_input[1] - 1, ct_date_input[0]);

                        if (ct_date.getDay() == 0 || ct_date.getDay() == 6) {
                            $(this).val('');
                            swal(
                                {
                                    title: '<div class="tit-sweet tit-warning"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Atenção!</div>',
                                    text: 'Atendimento não disponível para ser Realizado aos Sábados e Domingos'
                                }
                            );
                        }

                        if (ct_date <= today_date) {
                            ct_date.setDate(today_date.getDate());
                            var ct_mes = pad((ct_date.getMonth() + 1));
                            $(this).val('');
                            swal(
                                {
                                    title: '<div class="tit-sweet tit-warning"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Atenção!</div>',
                                    text: 'A Data informada não está disponível para a Agendamento'
                                }
                            );
                        }

                        var ct_hora = jQuery(this).parent().parent().find('.selecionaHora').val();
                        if (ct_hora != '') {

                            var clinica_id = jQuery(this).parent().parent().parent().find('#clinica_id').val();
                            var profissional_id = jQuery(this).parent().parent().parent().find('#profissional_id').val();
                            var dt_agendamento = ct_date_input[2] + '-' + ct_date_input[1] + '-' + ct_date_input[0];

                            if (clinica_id == '') {
                                return false;
                            }
                            if (profissional_id == '') {
                                return false;
                            }

                            jQuery.ajax({
                                type: 'POST',
                                url: '/consulta-agendamento-disponivel',
                                data: {
                                    'clinica_id': clinica_id,
                                    'profissional_id': profissional_id,
                                    'data_agendamento': dt_agendamento,
                                    'hora_agendamento': ct_hora,
                                    '_token': laravel_token
                                },
                                success: function (result) {

                                    if (!result.status) {
                                        swal(
                                            {
                                                title: '<div class="tit-sweet tit-warning"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Atenção!</div>',
                                                text: result.mensagem
                                            }
                                        );
                                    }
                                },
                                error: function (result) {
                                    swal(
                                        {
                                            title: '<div class="tit-sweet tit-error"><i class="fa fa-times-circle" aria-hidden="true"></i> Ocorreu um erro</div>',
                                            text: 'Falha na operação!'
                                        }
                                    );
                                }
                            });
                        }
                    }
                });

                jQuery('.selecionaHoraRemarcar').datetimepicker({
                    datepicker: false,
                    format: 'H:i',
                    step: 30,
                });

                $(".mascaraDataAgendamento").inputmask({
                    mask: ["99/99/9999"],
                    keepStatic: true
                });

                $(".mascaraHoraAgendamento").inputmask({
                    mask: ["99:99"],
                    keepStatic: true
                });
            }

            function cancelarAgendamento(input, agendamento_id) {
                var cancelamento_result = false;
                var cancelamento_mensagem = '';
                swal({
                    title: '<div class="tit-sweet tit-question"><i class="fa fa-question-circle" aria-hidden="true"></i> Atenção</div>',
                    html: '<span style="margin-bottom: 10px;">Tem certeza que deseja Solicitar o Cancelamento deste Agendamento?</span>',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonClass: 'btn btn-confirm mt-2',
                    cancelButtonClass: 'btn btn-cancel ml-2 mt-2',
                    confirmButtonText: 'Solicitar',
                    cancelButtonText: 'Cancelar',
                    showLoaderOnConfirm: true,
                    allowOutsideClick: false,
                    preConfirm: function (input) {
                        return new Promise(function (resolve, reject) {

                            if (agendamento_id.length == 0) {

                                reject('Agendamento não disponível');
                                return false;
                            }

                            $.ajax({
                                type: 'post',
                                dataType: 'json',
                                url: '/cancelar-agendamento',
                                data: {
                                    'agendamento_id': agendamento_id,
                                    '_token': laravel_token
                                },
                                timeout: 15000,
                                success: function (result) {

                                    if (result.status) {

                                        var agendamento = JSON.parse(result.agendamento);

                                        $('#resultAgendamento').val('true');
                                        $('#messageAgendamento').val(result.mensagem);
//                                       	  $('#status_agendamento_'+agendamento.id).attr('class', 'status cancelado').html('Cancelado');
//                                       	  $('#btn_remarcar_agendamento_'+agendamento.id).remove();

//                                           $('#dia_'+agendamento.id).html(agendamento.dia_agendamento);
//                                           $('#mes_'+agendamento.id).html(agendamento.mes_agendamento);
//                                           $('#hora_'+agendamento.id).html(agendamento.hora_agendamento);
                                        cancelamento_result = true;
                                    } else {
                                        $('#resultAgendamento').val('false');
                                        $('#messageAgendamento').val(result.mensagem);
                                    }
                                    cancelamento_mensagem = result.mensagem;
                                    resolve();
                                },
                                error: function (result) {
                                    $('#resultAgendamento').val('false');
                                    $('#messageAgendamento').val('Falha na operação!');
                                    resolve();
                                }
                            });
                        })
                    },
                }).then(function (e) {
                    var mensagem = $('#messageAgendamento').val();


                    if (cancelamento_result) {
                        swal(
                            {
                                title: '<div class="tit-sweet tit-success"><i class="fa fa-check-circle" aria-hidden="true"></i> Sucesso!</div>',
                                text: cancelamento_mensagem
                            }
                        );
                    } else if (!cancelamento_result) {
                        swal(
                            {
                                title: '<div class="tit-sweet tit-error"><i class="fa fa-times-circle" aria-hidden="true"></i> Ocorreu um erro</div>',
                                text: 'Sua Solicitação falhou. Por favor, tente novamente.'
                            }
                        );
                    }
                });
            }
        </script>
    @endpush

@endsection