@extends('layouts.base')

@section('title', 'Pagamento - DoutorHj')

@push('scripts')

@endpush

@section('content')
    <section class="pagamento">
        <div class="container">
            <div class="area-container">
                <div class="titulo">
                    <strong>Pagamento</strong>
                    <p>Selecione ou adicione seu cartão, escolha o beneficiário da compra e, caso houver, digite seu
                        cupom de desconto.</p>
                </div>
                <div class="row">
                    <div class="col-md-12 col-lg-6">
                        <div class="card card-formulario">
                            <div class="card-header">
                                Cupom de Desconto
                            </div>
                            <div class="card-body">
                                <span class="card-span">Digite aqui o número do seu cupom</span>
                                <form>
                                    <div class="form-group row">
                                        <div class="col col-lg-7 col-xl-8">
                                            <input type="text" class="form-control" id="inputCupom" name="inputCupom" placeholder="Cupom de desconto">
                                            <i class="cvx-check-cupom-desconto cvx-no-loading fa fa-check-circle text-success"></i>
                                        </div>
                                        <div class="col col-lg-5 col-xl-4">
                                            <button type="button" id="btn-validar-cupom" class="btn btn-vermelho">
                                                Validar
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card card-formulario">
                            <div class="card-header">
                                Forma de pagamento
                            </div>
                            <div class="card-body">
                                <form id="form-finalizar-pedido" method="post">
                                    <div class="form-group row area-label">
                                        <label for="selectFormaPagamento" class="col-sm-12">Escolha a Forma de
                                            pagamento</label>
                                    </div>
                                    <!-- client data order -->
                                    <input type="hidden" id="titulo_pedido" name="titulo_pedido" value="sdfsdf">
                                    <input type="hidden" id="paciente_id" name="paciente_id" value="e3rt">

                                    <input type="hidden" name="atendimento_id">

                                    <!-- Formulário cartão de Crédito -->
                                    <div class="form-group row">
                                        <div class="col col-md-6">
                                            <div class="button dropdown">
                                                <select id="selectFormaPagamento" class="form-control" name="tipo_pagamento">
                                                    <option value="">Selecione</option>
                                                    <option value="credito">Crédito</option>
                                                    <!-- <option value="debito">Débito</option> -->
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="output">
                                        <div id="selecionaFormaPagamento" class="formas-pagamento"></div>
                                        <!-- Formulário cartão de Crédito -->
                                        <div id="credito" class="formas-pagamento">
                                            <div class="form-group row area-label">
                                                <label for="selectCartaoCredito" class="col-sm-12">Selecione um cartão
                                                    cadastrado</label>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col col-md-6">
                                                    <div class="button dropdown">
                                                        <select class="form-control" id="selectCartaoCredito" name="selectCartaoCredito">
                                                            <option value="">Selecione</option>
                                                            <option>Cartão Visa - final 0889</option>
                                                            <option>Cartão MasterCard - final 0685</option>
                                                            <option>Cartão Cielo - final 6854</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row row-payment-card">
                                                <div class="col col-12 col-sm-6">
                                                    <label for="inputNumeroCartaoCredito">Número do cartão</label>
                                                    <input type="text" id="inputNumeroCartaoCredito" class="form-control input-numero-cartao cvx-checkout_card_number" name="num_cartao_credito" placeholder="Número do cartão" onkeypress="onlyNumbers(event)" maxlength="16">
                                                    <input type="hidden" id="inputBandeiraCartaoCredito" class="inputBandeiraCartaoCredito" name="bandeira_cartao_credito">
                                                </div>
                                                <div class="col col-12 col-sm-6">
                                                    <label for="inputNomeCartaoCredito">Nome impresso no cartão</label>
                                                    <input type="text" id="inputNomeCartaoCredito" class="form-control" name="nome_impresso_cartao_credito" placeholder="Nome impresso no cartão">
                                                </div>
                                            </div>
                                            <div class="form-group row row-payment-card">
                                                <div class="col col-6 col-sm-6 col-md-3">
                                                    <label for="selectValidadeMesCredito">Validade</label>
                                                    <div class="button dropdown">
                                                        <select id="selectValidadeMesCredito" class="form-control select-validade-mes" name="mes_cartao_credito">
                                                            <option>Mês</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col col-6 col-sm-6 col-md-3">
                                                    <label class="label-validade-ano" for="selectValidadeAnoCredito">&nbsp;</label>
                                                    <div class="button dropdown">
                                                        <select id="selectValidadeAnoCredito" class="form-control" name="ano_cartao_credito">
                                                            <option>Ano</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col col-12 col-sm-12 col-md-6 codigo-seguranca">
                                                    <label for="inputCodigoCredito" class="label-codigo-seguranca">Código
                                                        de segurança</label>
                                                    <div class="area-codigo-seguranca">
                                                        <input type="text" id="inputCodigoCredito" class="form-control inputCodigoCredito" name="cod_seg_cartao_credito" placeholder="0000" onkeypress="onlyNumbers(event)" maxlength="3">
                                                        <i class="fa fa-credit-card" data-toggle="tooltip" data-placement="top" title="Cógido de segurança ou CVV são os 3 dígitos eu ficam no verso do seu cartão."></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row row-payment-card">
                                                <div class="col col-12 col-sm-6">
                                                    <label for="inputCPFCredito">CPF do titular do cartão</label>
                                                    <input type="text" id="inputCPFCredito" class="form-control input-cpf-titular mascaraCPF" name="cpf-titular-cartao-credito" value="000.000.000-00" placeholder="CPF do titular do cartão">
                                                </div>
                                                <div class="col col-12 col-sm-6 codigo-seguranca">
                                                    <label for="selectParcelamentoCredito">Parcelamento</label>
                                                    <div class="button dropdown">
                                                        <select id="selectParcelamentoCredito" class="form-control" name="parcelamento-cartao-credito">
                                                                <option value="1X"</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-check fc-checkbox row-payment-card">
                                                <input type="checkbox" id="checkGravarCartaoCredito" class="form-check-input" name="gravar_cartao_credito">
                                                <label class="form-check-label" for="checkGravarCartaoCredito">Gravar
                                                    dados para futuras compras</label>
                                            </div>

                                            <div class="form-group row row-card-token">
                                                <div class="col col-12 col-sm-12">
                                                    <br>
                                                    <h6 style="color: rgb(70, 117, 184); text-transform: uppercase; font-weight: 700;">
                                                        PAGAMENTO DINÂMICO</h6>
                                                    <label>Informe apenas o código de segurança do seu cartão</label>
                                                    <input type="hidden" id="inputSaveCardId" name="save_card_id">
                                                </div>
                                            </div>
                                            <div class="form-group row row-card-token">
                                                <div class="col col-12 col-sm-8">
                                                    <label for="inputNomeSaveCard">Nome Impresso</label>
                                                    <input type="text" id="inputNomeSaveCard" class="form-control" name="nome_impresso_save_card" placeholder="Nome impresso" readonly="readonly">
                                                </div>
                                                <div class="col col-12 col-sm-4">
                                                    <label for="inputNumFinalSaveCard">Final do Cartão</label>
                                                    <input type="text" id="inputNumFinalSaveCard" class="form-control" name="num_final_save_card" placeholder="Final do cartão" readonly="readonly">
                                                </div>
                                            </div>
                                            <div class="form-group row row-card-token">
                                                <div class="col col-12 col-sm-4">
                                                    <label for="inputExpirationDateSaveCard">Validade</label>
                                                    <input type="text" id="inputExpirationDateSaveCard" class="form-control" name="expiration_date_save_card" placeholder="Validade" readonly="readonly">
                                                </div>
                                                <div class="col col-12 col-sm-4">
                                                    <label for="inputBrandSaveCard">Bandeira</label>
                                                    <input type="text" id="inputBrandSaveCard" class="form-control" name="brand_save_card" placeholder="Bandeira" readonly="readonly">
                                                </div>
                                                <div class="col col-12 col-sm-4">
                                                    <label for="inputCodigoSegSaveCard" class="label-codigo-seguranca">Código
                                                        seg.</label>
                                                    <div class="area-codigo-seguranca">
                                                        <input type="text" id="inputCodigoSegSaveCard" class="form-control" name="cod_seg_save_card" placeholder="000" maxlength="3">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Formulário cartão de Débito -->
                                        <div id="debito" class="formas-pagamento">
                                            <div class="form-group row area-label">
                                                <label for="selectCartaoDebito" class="col-sm-12">Selecione um cartão
                                                    cadastrado</label>
                                            </div>
                                            <!-- <div class="form-group row">
                                                <div class="col col-md-6">
                                                    <div class="button dropdown">
                                                        <select id="selectCartaoDebito" class="form-control">
                                                            <option>Selecione</option>
                                                            <option>Cartão Visa - final 0889</option>
                                                            <option>Cartão MasterCard - final 0685</option>
                                                            <option>Cartão Cielo - final 6854</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div> -->
                                            <div class="form-group row">
                                                <div class="col col-12 col-sm-6">
                                                    <label for="inputNumeroCartaoDebito">Número do cartão</label>
                                                    <input type="text" id="inputNumeroCartaoDebito" class="form-control input-numero-cartao cvx-checkout_card_number" name="num_cartao_credito" placeholder="Número do cartão" onkeypress="onlyNumbers(event)" maxlength="16">
                                                    <input type="hidden" id="inputBandeiraCartaoDebito" class="inputBandeiraCartaoDebito" name="bandeira_cartao_debito">
                                                </div>
                                                <div class="col col-12 col-sm-6">
                                                    <label for="inputNomeCartaoDebito">Nome impresso no cartão</label>
                                                    <input type="text" class="form-control" id="inputNomeCartaoDebito" placeholder="Nome impresso no cartão">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col col-6 col-sm-6 col-md-3">
                                                    <label for="selectValidadeMesDebito">Validade</label>
                                                    <div class="button dropdown">
                                                        <select id="selectValidadeMesDebito" class="form-control select-validade-mes" name="mes_cartao_debito">
                                                            <option>Mês</option>

                                                                <option value="Janeiro</option>

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col col-6 col-sm-6 col-md-3">
                                                    <label class="label-validade-ano" for="selectValidadeAnoDebito">&nbsp;</label>
                                                    <div class="button dropdown">
                                                        <select id="selectValidadeAnoDebito" class="form-control" name="ano_cartao_debito">
                                                            <option>Ano</option>
                                                                <option value="2018"</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col col-12 col-sm-12 col-md-6 codigo-seguranca">
                                                    <label for="inputCodigoDebito" class="label-codigo-seguranca">Código
                                                        de segurança</label>
                                                    <div class="area-codigo-seguranca">
                                                        <input type="text" id="inputCodigoDebito" class="form-control" name="cod_seg_cartao_debito" placeholder="000" maxlength="3">
                                                        <i class="fa fa-credit-card" data-toggle="tooltip" data-placement="top" title="Cógido de segurança ou CVV são os 3 dígitos eu ficam no verso do seu cartão."></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col col-sm-6">
                                                    <label for="inputCPFDebito">CPF do titular do cartão</label>
                                                    <input type="text" id="inputCPFDebito" class="form-control input-cpf-titular mascaraCPF" name="cpf-titular-cartao-debito" value="" placeholder="CPF do titular do cartão">
                                                </div>
                                            </div>
                                            <!-- 
                                            <div class="form-check fc-checkbox">
                                                <input type="checkbox" class="form-check-input" id="checkGravarCartaoDebito">
                                                <label class="form-check-label" for="checkGravarCartaoDebito">Gravar dados para futuras compras</label>
                                            </div>
                                             -->
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-6">
                        <div class="card card-formulario">
                            <div class="card-header">
                                Resumo da compra
                            </div>
                            <div class="card-body">
                                <div id="accordion">

                                        <div class="card card-resumo-compra">
                                            <div class="card-header" id="heading_">
                                                <div class="row">
                                                    <div class="nome-produto col-12 col-sm-9" data-toggle="collapse" data-target="#collapse_" aria-expanded="false" aria-controls="collapse_">
                                                        <span>Checkup Completo Acima de 60 anos - Masculino</span>
                                                    </div>
                                                    <!--<div class="excluir-produto col-2 col-sm-1">
                                                        <a class="close-div" href="#"><i class="fa fa-trash"></i></a>
                                                    </div>-->
                                                    <div class="valor-produto col-12 col-sm-3">
                                                        <span>R$ 1.161,00</span>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="valor-produto col-12 col-sm-12">
                                                        <select id="selectPacienteAgendamento_" class="form-control select-paciente-agendamento" name="selectCartaoCredito">
                                                            <option>Selecione o Paciente deste Atendimento</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="collapse_" class="collapse" aria-labelledby="heading_" data-parent="#accordion">
                                                <div class="card-body">
                                                    <div class="linha-resumo">
                                                        <div class="titulo-resumo">
                                                            <span>Pessoa que utilizará o serviço:</span>
                                                        </div>
                                                        <div class="dados-resumo dados-resumo-paciente">
                                                           <p>Paulo Kenobi</p>
                                                        </div>
                                                    </div>

                                                    <div class="linha-resumo">
                                                        <div class="titulo-resumo">
                                                            <span>Incluso nesse pacote</span>
                                                        </div>
                                                        <div class="dados-resumo">

                                                            {{--CONSULTAS--}}

                                                            <div class="procedimentos consultas">
                                                                <p><span>4 </span>Consultas</p>
                                                                <ul>
                                                                    <li>
                                                                        <div class="clinica">
                                                                            Consulta Clínico-cardiológica com smokerlyser para
                                                                            tabagistas, no dia <span>29/02/2018</span>, às <span>14:30</span>
                                                                        </div>
                                                                        <div class="endereco">
                                                                            <span>Local: </span>Biocárdios - SEPS Q 709/909 BL F -
                                                                            Asa Sul, Brasília - DF
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <div class="clinica">
                                                                            Consulta Clínico-cardiológica com smokerlyser para
                                                                            tabagistas, no dia <span>29/02/2018</span>, às <span>14:30</span>
                                                                        </div>
                                                                        <div class="endereco">
                                                                            <span>Local: </span>Biocárdios - SEPS Q 709/909 BL F -
                                                                            Asa Sul, Brasília - DF
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <div class="clinica">
                                                                            Consulta Clínico-cardiológica com smokerlyser para
                                                                            tabagistas, no dia <span>29/02/2018</span>, às <span>14:30</span>
                                                                        </div>
                                                                        <div class="endereco">
                                                                            <span>Local: </span>Biocárdios - SEPS Q 709/909 BL F -
                                                                            Asa Sul, Brasília - DF
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <div class="clinica">
                                                                            Consulta Clínico-cardiológica com smokerlyser para
                                                                            tabagistas, no dia <span>29/02/2018</span>, às <span>14:30</span>
                                                                        </div>
                                                                        <div class="endereco">
                                                                            <span>Local: </span>Biocárdios - SEPS Q 709/909 BL F -
                                                                            Asa Sul, Brasília - DF
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </div>

                                                            {{--EXAMES--}}

                                                            <div class="procedimentos exames">
                                                                <p><span>6 </span>Exames</p>
                                                                <ul>
                                                                    <li>
                                                                        <div class="clinica">
                                                                            Consulta Clínico-cardiológica com smokerlyser para
                                                                            tabagistas, no dia <span>29/02/2018</span>, às <span>14:30</span>
                                                                        </div>
                                                                        <div class="endereco">
                                                                            <span>Local: </span>Biocárdios - SEPS Q 709/909 BL F -
                                                                            Asa Sul, Brasília - DF
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <div class="clinica">
                                                                            Consulta Clínico-cardiológica com smokerlyser para
                                                                            tabagistas, no dia <span>29/02/2018</span>, às <span>14:30</span>
                                                                        </div>
                                                                        <div class="endereco">
                                                                            <span>Local: </span>Biocárdios - SEPS Q 709/909 BL F -
                                                                            Asa Sul, Brasília - DF
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <div class="clinica">
                                                                            Consulta Clínico-cardiológica com smokerlyser para
                                                                            tabagistas, no dia <span>29/02/2018</span>, às <span>14:30</span>
                                                                        </div>
                                                                        <div class="endereco">
                                                                            <span>Local: </span>Biocárdios - SEPS Q 709/909 BL F -
                                                                            Asa Sul, Brasília - DF
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <div class="clinica">
                                                                            Consulta Clínico-cardiológica com smokerlyser para
                                                                            tabagistas, no dia <span>29/02/2018</span>, às <span>14:30</span>
                                                                        </div>
                                                                        <div class="endereco">
                                                                            <span>Local: </span>Biocárdios - SEPS Q 709/909 BL F -
                                                                            Asa Sul, Brasília - DF
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <div class="clinica">
                                                                            Consulta Clínico-cardiológica com smokerlyser para
                                                                            tabagistas, no dia <span>29/02/2018</span>, às <span>14:30</span>
                                                                        </div>
                                                                        <div class="endereco">
                                                                            <span>Local: </span>Biocárdios - SEPS Q 709/909 BL F -
                                                                            Asa Sul, Brasília - DF
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <div class="clinica">
                                                                            Consulta Clínico-cardiológica com smokerlyser para
                                                                            tabagistas, no dia <span>29/02/2018</span>, às <span>14:30</span>
                                                                        </div>
                                                                        <div class="endereco">
                                                                            <span>Local: </span>Biocárdios - SEPS Q 709/909 BL F -
                                                                            Asa Sul, Brasília - DF
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </div>

                                                            {{--IMAGENS--}}

                                                            <div class="procedimentos imagem">
                                                                <p><span>3 </span>Imagens</p>
                                                                <ul>
                                                                    <li>
                                                                        <div class="clinica">
                                                                            Consulta Clínico-cardiológica com smokerlyser para
                                                                            tabagistas, no dia <span>29/02/2018</span>, às <span>14:30</span>
                                                                        </div>
                                                                        <div class="endereco">
                                                                            <span>Local: </span>Biocárdios - SEPS Q 709/909 BL F -
                                                                            Asa Sul, Brasília - DF
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <div class="clinica">
                                                                            Consulta Clínico-cardiológica com smokerlyser para
                                                                            tabagistas, no dia <span>29/02/2018</span>, às <span>14:30</span>
                                                                        </div>
                                                                        <div class="endereco">
                                                                            <span>Local: </span>Biocárdios - SEPS Q 709/909 BL F -
                                                                            Asa Sul, Brasília - DF
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <div class="clinica">
                                                                            Consulta Clínico-cardiológica com smokerlyser para
                                                                            tabagistas, no dia <span>29/02/2018</span>, às <span>14:30</span>
                                                                        </div>
                                                                        <div class="endereco">
                                                                            <span>Local: </span>Biocárdios - SEPS Q 709/909 BL F -
                                                                            Asa Sul, Brasília - DF
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </div>

                                                            {{--AVALIAÇÕES--}}

                                                            <div class="procedimentos avaliacoes">
                                                                <p><span>1 </span>Avaliação</p>
                                                                <ul>
                                                                    <li>
                                                                        <div class="clinica">
                                                                            Consulta Clínico-cardiológica com smokerlyser para
                                                                            tabagistas, no dia <span>29/02/2018</span>, às <span>14:30</span>
                                                                        </div>
                                                                        <div class="endereco">
                                                                            <span>Local: </span>Biocárdios - SEPS Q 709/909 BL F -
                                                                            Asa Sul, Brasília - DF
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    <div class="resumo-compra">
                                        <div class="row">
                                            <div class="col-sm-8 linha-forma-pagamento linha-resumo">
                                                <div class="titulo-resumo">
                                                    <span>Forma de pagamento:</span>
                                                </div>
                                                <div class="dados-resumo">
                                                    <p><span id="resumo_compra_tipo_cartao"></span> - Final
                                                        <span id="resumo_compra_final_cartao">XXXX</span></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-5">
                                                <div class="titulo-resumo">
                                                    <span>Valor do(s) serviço(s):</span>
                                                </div>
                                            </div>
                                            <div class="col-md-7">
                                                <div class="dados-resumo">
                                                    <p>R$ 1.161,00</p>
                                                    <input type="hidden" id="valor_servicos" value="1.161,00">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-5">
                                                <div class="titulo-resumo">
                                                    <span>Desconto aplicado:</span>
                                                </div>
                                            </div>
                                            <div class="col-md-7">
                                                <div class="dados-resumo">
                                                    <p>- R$ 00</p>
                                                    <input type="hidden" id="valor_desconto" value="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-5">
                                                <div class="titulo-resumo">
                                                    <span>Anuidade:</span>
                                                </div>
                                            </div>
                                            <div class="col-md-7">
                                                <div class="dados-resumo">
                                                    <p>Isento</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="parcelamento-content" class="row">
                                            <div class="col-md-5">
                                                <div class="titulo-resumo">
                                                    <span>Parcelamento:</span>
                                                </div>
                                            </div>
                                            <div class="col-md-7">
                                                <div id="resumo_parcelamento" class="dados-resumo">
                                                    <!-- <p>10x com juros (5% a.m.) de R$ 48,50</p> -->
                                                    <p>3X R$ 387,00</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-5">
                                                <div class="titulo-resumo">
                                                    <span>Total a pagar: </span>
                                                </div>
                                            </div>
                                            <div class="col-md-7">
                                                <div class="dados-resumo valor-total-produtos">
                                                    <p>
                                                        R$ 1.161,00</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 col-md-6 area-btn-finalizar">
                                                <a href="" class="btn btn-link btn-continuar-comprando">Continuar
                                                    comprando</a>
                                            </div>
                                            <div class="col-12 col-md-6 area-btn-finalizar">

                                                    <button type="button" id="btn-finalizar-pedido" class="btn btn-vermelho btn-finalizar">
                                                        <span id="lbl-finalizar-pedido">Finalizar Pagamento <i class="fa fa-spin fa-spinner" style="display: none; float: right; font-size: 16px;"></i></span>
                                                    </button>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                localStorage.setItem('activeCupom', 'f');

                /*********************************
                 *
                 * ALTERA FORMULARIO PAGAMENTO
                 *
                 *********************************/

                $(function () {
                    $('#selectFormaPagamento').change(function () {
                        $('.row-payment-card').css('display', 'flex');
                        $('.row-card-token').css('display', 'none');
                        $('#selectCartaoCredito').prop('selectedIndex', 0);
                        if ($(this).val() == 'debito') {
                            $('#parcelamento-content').hide();
                            $('#resumo_compra_tipo_cartao').html('Cartão de débito')
                        } else if ($(this).val() == 'credito') {
                            $('#parcelamento-content').show();
                            $('#resumo_compra_tipo_cartao').html('Cartão de crédito')
                        }
                        $('.formas-pagamento').hide();
                        $('#' + $(this).val()).show();
                    });


                });

                $('.select-paciente-agendamento').change(function () {

                    var element_id = $(this).attr('id');
                    if ($(this).val() == 0) {
                        return false;
                    }

                    var paciente_id = $('#' + element_id + " :selected").val();
                    var nome_paciente = $('#' + element_id + " :selected").text();
                    nome_paciente = nome_paciente.replace("(T)", "");
                    nome_paciente = nome_paciente.replace("(D)", "");

                    $(this).parent().parent().parent().parent().find('div.collapse').find('div.dados-resumo-paciente').find('input.paciente_agendamento_id').val(paciente_id);
                    $(this).parent().parent().parent().parent().find('div.collapse').find('div.dados-resumo-paciente').find('p').html(nome_paciente).removeClass('text-danger').addClass('text-primary');
                });

                $('#inputNumeroCartaoCredito').keydown(function () {
                    if ($(this).val().length >= 15) {
                        //alert($(this).val());
                        var num_cartao = $('#inputNumeroCartaoCredito').val();
                        $('#resumo_compra_final_cartao').html(num_cartao.substr(-4));
                    } else {
                        $('#resumo_compra_final_cartao').html('XXXX');
                    }
                });

                $('#inputNumeroCartaoCredito').blur(function () {
                    var num_cartao = $(this).val();
                    $('#resumo_compra_final_cartao').html(num_cartao.substr(-4));
                });

                $('#btn-validar-cupom').click(function () {

                    var codigo = $('#inputCupom').val();
                    var activeCupom = localStorage.getItem('activeCupom');
                    var valor_total = $('#valor_servicos').val();

                    if (codigo.length == 0 | activeCupom == 't') {
                        return false;
                    }

                    $.ajax({
                        type: 'post',
                        dataType: 'json',
                        url: '/validar-cupom-desconto',
                        data: {
                            'codigo': codigo,
                            'valor_parcelamento': valor_total,
                            '_token': laravel_token
                        },
                        timeout: 15000,
                        success: function (result) {

                            if (result.status) {

                                var desconto = valor_total * result.percentual;
                                var valor_com_desconto = valor_total - desconto;
                                var parcelamentos = result.parcelamentos;

                                //localStorage.setItem('activeCupom', 'f');
                                $('.cvx-check-cupom-desconto').removeClass('cvx-no-loading');
                                $('#valor_desconto').parent().find('p').html('- R$ ' + numberToReal(desconto));
                                $('.valor-total-produtos').find('p').html('R$ ' + numberToReal(valor_com_desconto));

                                $('#selectParcelamentoCredito').empty();
                                for (var i = 0; i < parcelamentos.length; i++) {
                                    var option = '<option value="' + (i + 1) + '">' + parcelamentos[i] + '</option>';
                                    $('#selectParcelamentoCredito').append($(option));
                                }

                                $('#resumo_parcelamento p').html(result.resumo_parcelamento);

                            } else {

                                swal(
                                    {
                                        title: '<div class="tit-sweet tit-error"><i class="fa fa-times-circle" aria-hidden="true"></i> Ocorreu um erro</div>',
                                        text: result.mensagem
                                    }
                                );

                            }
                        },
                        error: function (result) {
                            $.Notification.notify('error', 'top right', 'DrHoje', 'Falha na operação!');
                        }
                    });
                });

                /*********************************
                 *
                 * ATIVA TOOLTIP
                 *
                 *********************************/

                $(function () {
                    $('[data-toggle="tooltip"]').tooltip()
                })

                /*********************************
                 *
                 * REMOVE PRODUTO
                 *
                 *********************************/

                $(".close-div").on("click", function (event) {
                    event.preventDefault();
                    if (confirm("Tem certeza que deseja excluir esse produto?")) {
                        $(this).parent().parent().parent().parent().remove();
                    }
                });

            });
        </script>

    @endpush

@endsection