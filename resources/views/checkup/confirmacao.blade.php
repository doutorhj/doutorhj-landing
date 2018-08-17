@extends('layouts.base')
@section('title', 'Pagamento - DoutorHj')
@push('scripts')
@endpush
@section('content')
    <section class="pagamento">
        <div class="container">
            <div class="area-container">
                <div class="titulo">
                    <strong>Pré-agendamento realizado com sucesso!</strong>
                    <p>Sua <b>solicitação</b> foi realizada com sucesso. Em até 48 horas você receberá a
                        confirmação do agendamento escolhido.
                    </p>
                </div>
                <div class="row">
                    <div class="col-md-12 col-lg-12">
                        <div class="card card-formulario card-concluir">
                            <div class="card-header">
                                NÚMERO DO PEDIDO: 00884499
                            </div>
                            <div class="card-body">
                                <div id="accordion">

                                    <div class="card card-resumo-compra">
                                        <div class="card-header" id="heading">
                                            <div class="row">
                                                <div class="nome-produto col-12 col-sm-7" data-toggle="collapse"
                                                     data-target="#collapse"
                                                     aria-expanded="true"
                                                     aria-controls="collapse">
                                                    <div>
                                                        <span>Checkup Completo Acima de 60 anos - Masculino</span>
                                                    </div>
                                                </div>
                                                <div class="numero-token col-12 col-sm-5">
                                                    <div>
                                                        <?php /* <span>TOKEN DE ATENDIMENTO: {{ $agendamento->te_ticket }}</span> */ ?>
                                                        <span>●●●●●●</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="collapse" class="collapse show"
                                             aria-labelledby="heading"
                                             data-parent="#accordion">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12 col-sm-9">
                                                        <div class="linha-resumo">
                                                            <div class="titulo-resumo">
                                                                <span>Pessoa que utilizará o serviço:</span>
                                                            </div>
                                                            <div class="dados-resumo">
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

                                                    <div class="col-12 col-sm-3">
                                                        <div class="linha-resumo">
                                                            <div class="titulo-resumo text-right">
                                                                <span>VALOR DO CHECKUP:</span>
                                                            </div>
                                                            <div class="dados-resumo text-right">
                                                                <h3>
                                                                    <strong>R$ 1.161,00</strong>
                                                                </h3>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="concluir-total">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <h4>VALOR TOTAL DO PEDIDO</h4>
                                            </div>
                                            <div class="col-md-2">
                                                <h3>
                                                    <strong>R$ 1.161,00</strong>
                                                </h3>
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

                /*********************************
                 *
                 * ALTERA FORMULARIO PAGAMENTO
                 *
                 *********************************/

                $(function () {
                    $('#selectFormaPagamento').change(function () {
                        if ($(this).val() == 'debito') {
                            $('#parcelamento-content').hide();
                        } else if ($(this).val() == 'credito') {
                            $('#parcelamento-content').show();
                        }
                        $('.formas-pagamento').hide();
                        $('#' + $(this).val()).show();
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