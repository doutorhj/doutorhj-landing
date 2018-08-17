@extends('layouts.base')

@section('title', 'Carrinho de Compras - DoutorHj')

@push('scripts')

@endpush

@section('content')
    <section class="carrinho">
        <div class="container">
            <div class="area-container">
                <div class="titulo">
                    <strong>Carrinho de compras</strong>
                </div>
                <div class="produtos-carrinho">

                    <div class="card-body">
                        <input type="hidden" name="current_url" value="Valor 1">
                        <div id="accordion">

                            {{--<div class="card card-resumo-compra">
                                <div class="card-header" id="heading_empty_cart }}">
                                    <div class="row">
                                        <div class="nome-produto col-10 col-sm-7" data-toggle="collapse"  data-target="#collapse_empty_cart" aria-expanded="false" aria-controls="collapse_empty_cart">
                                            <span>SEU CARRINHO ESTÁ VAZIO</span>
                                        </div>
                                        <div class="detalhes-produto col-12 col-sm-2" data-toggle="collapse"
                                             data-target="#collapse_empty_cart" aria-expanded="false" aria-controls="collapse_empty_cart">
                                            <span></span>
                                        </div>
                                        <div class="excluir-produto col-2 col-sm-1">

                                        </div>
                                        <div class="valor-produto col-12 col-sm-2">
                                            <span>R$ 0,00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>--}}


                            <div class="card card-resumo-compra">
                                <div class="card-header" id="">
                                    <div class="row">
                                        <div class="nome-produto col-10 col-sm-7" data-toggle="collapse" data-target="#collapse" aria-expanded="false" aria-controls="#collapse">
                                            <span>Checkup Completo Acima de 60 anos - Masculino</span>
                                        </div>
                                        <div class="detalhes-produto col-12 col-sm-2" data-toggle="collapse" data-target="#collapse" aria-expanded="false" aria-controls="collapse">
                                            <span>+ detalhes</span>
                                        </div>
                                        <div class="excluir-produto col-2 col-sm-1">
                                            <a class="close-div"><i class="fa fa-trash"></i></a>
                                            <input type="hidden" class="cart_id" value="value 2">
                                        </div>
                                        <div class="valor-produto col-12 col-sm-2">
                                            <span>R$ 1.161,00</span>
                                        </div>
                                    </div>
                                </div>
                                <div id="collapse" class="collapse" aria-labelledby="heading" data-parent="#accordion">
                                    <div class="card-body">
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
                                </div>
                            </div>


                            <div class="resumo-compra">
                                <div class="row">
                                    <div class="col-md-7"></div>
                                    <div class="col-md-3">
                                        <div class="titulo-resumo">
                                            <span>Valor do(s) serviço(s):</span>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="dados-resumo">
                                            <p><strong>R$ <span id="valor_total_carrinho">1.161,00</span></strong></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 col-md-6 area-btn-finalizar">
                                        <a href="#" class="btn btn-link btn-continuar-comprando">Continuar comprando</a>
                                    </div>
                                    <div class="col-12 col-md-6 area-btn-finalizar">

                                        <a href="#" id="btn-finaliza-pedido" class="btn btn-vermelho btn-finalizar" style="padding-top: 16px;">Finalizar
                                            Pagamento </a>

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
            /*********************************
             *
             * REMOVE PRODUTO
             *
             *********************************/

            $(".close-div").on("click", function (event) {
                event.preventDefault();

                if (confirm("Tem certeza que deseja excluir esse produto?")) {
                    var cart_id = $(this).parent().find('input.cart_id').val();

                    var item_carrinho = $(this).parent().parent().parent().parent();
                    var num_itens = $('.card-resumo-compra').length;

                    jQuery.ajax({
                        type: 'POST',
                        url: '/remover-item_carrinho',
                        data: {
                            'cart_id': cart_id,
                            '_token': laravel_token
                        },
                        success: function (result) {
                            if (result.status) {
                                atualizaValorTotal(result.valor_item);

                                $.Notification.notify('success', 'top right', 'Solicitação Concluída!', result.mensagem);
                                item_carrinho.remove();

                                if (num_itens == 1) {
                                    $('#btn-finaliza-pedido').css('display', 'none');
                                }
                            } else {
                                $.Notification.notify('error', 'top right', 'Solicitação Falhou!', result.mensagem);
                            }
                        },
                        error: function (result) {
                            $.Notification.notify('error', 'top right', 'Solicitação Falhou!', 'Falha na operação!');
                        }
                    });
                }
            });

            function atualizaValorTotal(valor_item) {

                var valor_total = moedaParaNumero($('#valor_total_carrinho').html());
                valor_total = valor_total - valor_item;

                $('#valor_total_carrinho').html(numberToReal(valor_total))


            }
        </script>
    @endpush

@endsection
