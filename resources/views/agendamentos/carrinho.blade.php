@extends('layouts.base')

@section('title', 'Meus Pedidos - DoutorHj')

@push('scripts')

@endpush

@section('content')
	<section class="carrinho">
		<div class="container">
			<div class="area-container">
				<div class="titulo">
					<strong>Meus Pedidos</strong>
				</div>
				<div class="produtos-carrinho">

					<div class="card-body">
						<input type="hidden" name="current_url" value="{{ $url }}">

						<div id="accordion">
							@if(sizeof($carrinho) == 0)
								<div class="card card-resumo-compra">
									<div class="card-header" id="heading_empty_cart }}">
										<div class="row">
											<div class="nome-produto col-10 col-sm-7" data-toggle="collapse" data-target="#collapse_empty_cart" aria-expanded="false" aria-controls="collapse_empty_cart">
												<span>SUA LISTA DE PEDIDOS ESTÁ VAZIA</span>
											</div>
											<div class="detalhes-produto col-12 col-sm-2" data-toggle="collapse" data-target="#collapse_empty_cart" aria-expanded="false" aria-controls="collapse_empty_cart">
												<span></span>
											</div>
											<div class="excluir-produto col-2 col-sm-1">

											</div>
											<div class="valor-produto col-12 col-sm-2">
												<span>R$ 0,00</span>
											</div>
										</div>
									</div>
								</div>
							@endif
							@foreach($carrinho as $item)
								@if(!empty($item['atendimento']))
									<div class="card card-resumo-compra">
										<div class="card-header" id="heading_{{ $item['item_id'] }}">
											<div class="row">
												<div class="nome-produto col-10 col-sm-7" data-toggle="collapse" data-target="#collapse_{{ $item['item_id'] }}" aria-expanded="false" aria-controls="#collapse_{{ $item['item_id'] }}">
													<span>{{ $item['atendimento']->ds_atendimento }}</span>
												</div>
												<div class="detalhes-produto col-12 col-sm-2" data-toggle="collapse" data-target="#collapse_{{ $item['item_id'] }}" aria-expanded="false" aria-controls="collapse_{{ $item['item_id'] }}">
													<span>+ detalhes</span>
												</div>
												<div class="excluir-produto col-2 col-sm-1">
													<a class="close-div"><i class="fa fa-trash"></i></a>
													<input type="hidden" class="cart_id" value="{{ $item['item_id'] }}">
												</div>
												<div class="valor-produto col-12 col-sm-2">
													<span>R$ {{ $item['atendimento']->getVlComercialAtendimento() }}</span>
												</div>
											</div>
										</div>
										<div id="collapse_{{ $item['item_id'] }}" class="collapse" aria-labelledby="heading_{{ $item['item_id'] }}" data-parent="#accordion">
											<div class="card-body">
												<div class="linha-resumo">
													<div class="titulo-resumo">
														<span>Pessoa que utilizará o serviço:</span>
													</div>
													<div class="dados-resumo">
														<p>{{ isset($item['paciente']) ? $item['paciente']->nm_primario.' '.$item['paciente']->nm_secundario : '--------' }}</p>
													</div>
												</div>
												@if($item['profissional'] != null && $item['atendimento']->consulta_id != null)
													<div class="linha-resumo">
														<div class="titulo-resumo">
															<span>Médico:</span>
														</div>
														<div class="dados-resumo">
															<p>
																Dr. {{ $item['profissional']->nm_primario.' '.$item['profissional']->nm_secundario }}</p>
														</div>
													</div>
													<div class="linha-resumo">
														<div class="titulo-resumo">
															<span>Especialidade:</span>
														</div>
														<div class="dados-resumo">
															<p>{{  $item['atendimento']->nome_especialidade }}</p>
														</div>
													</div>
												@else
													<div class="linha-resumo">
														<div class="titulo-resumo">
															<span>Descrição do Atendimento:</span>
														</div>
														<div class="dados-resumo">
															<p>{{ $item['atendimento']->ds_atendimento }}<br><em>({{  $item['atendimento']->nome_especialidade }})</em></p>
														</div>
													</div>
												@endif
												<div class="linha-resumo">
													<div class="titulo-resumo">
														<span>Prestador:</span>
													</div>
													<div class="dados-resumo">
														<p>{{ $item['clinica']->nm_fantasia }} - {{ ($item['filial']->eh_matriz == 'S') ? 'Matriz: ' : 'Filial: ' }} {{ $item['filial']->nm_nome_fantasia }}</p>
													</div>
												</div>
												<div class="linha-resumo">
													<div class="titulo-resumo">
														<span>Endereço:</span>
													</div>
													<div class="dados-resumo">
														<p>{{ $item['filial']->endereco->te_endereco.', '.$item['filial']->endereco->nr_logradouro.', '.$item['filial']->endereco->te_bairro }}</p>
													</div>
												</div>
												@if($item['data_agendamento'] != null && $item['data_agendamento'] != 'null')
													<div class="linha-resumo">
														<div class="titulo-resumo">
															<span>Data pré-agendada:</span>
														</div>
														<div class="dados-resumo">
															<p>{{ date('d/m/Y', strtotime($item['data_agendamento'])) }} - {{ strftime('%A', strtotime($item['data_agendamento'])) }}</p>
														</div>
													</div>
													<div class="linha-resumo">
														<div class="titulo-resumo">
															<span>Horário pré-agendado: </span>
														</div>
														<div class="dados-resumo">
															<p>{{ date('H', strtotime($item['hora_agendamento'])).'h'.date('i', strtotime($item['hora_agendamento'])).'min' }}</p>
														</div>
													</div>
												@else
													<div class="linha-resumo">
														<div class="titulo-resumo">
															<span>Observação do Atendimento: </span>
														</div>
														<div class="dados-resumo">
															<p>{{ $item['clinica']->obs_procedimento }}</p>
														</div>
													</div>
												@endif
											</div>
										</div>
									</div>
								@elseif(!empty($item['checkup']))
									<div class="card card-resumo-compra">
										<div class="card-header" id="heading_{{$item['item_id']}}">
											<div class="row">
												<div class="nome-produto col-10 col-sm-7" data-toggle="collapse" data-target="#collapse_{{$item['item_id']}}" aria-expanded="false" aria-controls="#collapse">
                                                    <span>Checkup {{$item['checkup']->titulo}} - {{ucfirst($item['checkup']->tipo)}}</span>
												</div>
												<div class="detalhes-produto col-12 col-sm-2" data-toggle="collapse" data-target="#collapse_{{$item['item_id']}}" aria-expanded="false" aria-controls="collapse">
													<span>+ detalhes</span>
												</div>
												<div class="excluir-produto col-2 col-sm-1">
													<a class="close-div"><i class="fa fa-trash"></i></a>
													<input type="hidden" class="cart_id" value="{{$item['item_id']}}">
												</div>
												<div class="valor-produto col-12 col-sm-2">
													<span>R$ {{number_format($item['valor'], 2, ',', '.')}}</span>
												</div>
											</div>
										</div>
										<div id="collapse_{{$item['item_id']}}" class="collapse" aria-labelledby="heading_{{$item['item_id']}}" data-parent="#accordion">
											<div class="card-body">
												<div class="linha-resumo">
													<div class="titulo-resumo">
														<span>Pessoa que utilizará o serviço:</span>
													</div>
													<div class="dados-resumo">
														<p>{{$item['paciente']->nm_primario.' '.$item['paciente']->nm_secundario}}</p>
													</div>
												</div>
												<div class="linha-resumo">
													<div class="titulo-resumo">
														<span>Incluso nesse pacote</span>
													</div>
													<div class="dados-resumo">
														{{--CONSULTAS--}}
														<div class="procedimentos consultas">
															<p>Consultas</p>
															<ul>
																@foreach($item['itens_checkup'] as $itemCheckup)
																	@if(!is_null($itemCheckup->atendimento->consulta_id))
																		<?php //dd($item)?>
																		<li>
																			<div class="clinica">
																				{{$itemCheckup->atendimento->consulta->ds_consulta}}, no dia <span>{{$itemCheckup->dataHoraAgendamento}}</span><br>
																				<span>Profissional: </span>{{$itemCheckup->atendimento->profissional->nm_primario.' '.$itemCheckup->atendimento->profissional->nm_secundario}}
																			</div>
																			<div class="endereco">
																				<span>Clinica: </span> {{$itemCheckup->atendimento->clinica->nm_fantasia}}<br>
																				<span>Local: </span>{{$itemCheckup->atendimento->clinica->enderecos[0]->te_endereco.', '.$itemCheckup->atendimento->clinica->enderecos[0]->nr_logradouro.', '.$itemCheckup->atendimento->clinica->enderecos[0]->te_bairro}}
																			</div>
																		</li>
																	@endif
																@endforeach
															</ul>
														</div>

														{{--EXAMES--}}
														<div class="procedimentos exames">
															<p>Exames</p>
															<ul>
																@foreach($item['itens_checkup'] as $itemCheckup)
																	@if(!is_null($itemCheckup->atendimento->procedimento_id))
																		<?php //dd($itemCheckup->atendimento->clinica)?>
																		<li>
																			<div class="clinica">
																				{{$itemCheckup->atendimento->procedimento->ds_procedimento}}
																				@if(!is_null($itemCheckup->dataHoraAgendamento))
																					, no dia <span>{{ $itemCheckup->dataHoraAgendamento}}</span>
																				@endif
																				<br>
																			</div>
																			<div class="endereco">
																				<span>Clinica: </span> {{$itemCheckup->atendimento->clinica->nm_fantasia}}<br>
																				<span>Local: </span>{{$itemCheckup->atendimento->clinica->enderecos[0]->te_endereco.', '.$itemCheckup->atendimento->clinica->enderecos[0]->nr_logradouro.', '.$itemCheckup->atendimento->clinica->enderecos[0]->te_bairro}}
																			</div>
																		</li>
																	@endif
																@endforeach
															</ul>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								@endif
							@endforeach

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
											<p><strong>R$ <span id="valor_total_carrinho">{{$valor_total}}</span></strong>
											</p>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-12 col-md-6 area-btn-finalizar">
										<a href="{{ $url }}" class="btn btn-link btn-continuar-comprando">Continuar comprando</a>
									</div>
									<div class="col-12 col-md-6 area-btn-finalizar">
										@if(sizeof($carrinho) > 0)
											<a href="{{ route('pagamento')}}" id="btn-finaliza-pedido" class="btn btn-vermelho btn-finalizar" style="padding-top: 16px;">Finalizar Pagamento </a>
										@endif
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
