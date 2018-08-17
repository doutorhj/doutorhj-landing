@extends('layouts.base')
@section('title', 'DoutorHoje: Resultado')

@push('scripts')
	<script>
        $(document).ready(function () {
        	$('#tipo_especialidade').change();
        });
	</script>
@endpush

@section('content')
    <section class="resultado resultado-checkup">
        <div class="container">
            <div class="area-container">
                <div class="area-alt-busca">
                    <a class="btn btn-primary btn-alt-busca" data-toggle="collapse" href="#collapseFormulario" role="button" aria-expanded="false" aria-controls="collapseFormulario">Alterar Busca <i class="fa fa-edit"></i></a>
                </div>
                <div class="collapseFormulario collapse show" id="collapseFormulario">
                    <form action="/resultado-checkup" class="form-busca-resultado" method="get">
                        <div class="row">
                            <div class="form-group col-md-12 col-lg-3">
                                <select id="tipo_atendimento" class="form-control select2" name="tipo_atendimento">
                                    <option value="" disabled selected hidden>Tipo de atendimento</option>

                                    @foreach($tipoAtendimentos as $tipoAtendimento)
                                        <option value="{{ $tipoAtendimento->tag_value }}" @if( isset($_GET['tipo_atendimento']) && $_GET['tipo_atendimento'] == $tipoAtendimento->tag_value  ) selected='selected' @endif>{{ $tipoAtendimento->ds_atendimento }}</option>
                                    @endforeach

                                    @if( $hasActiveCheckup )
                                        <option value="checkup" @if( isset($_GET['tipo_atendimento']) && $_GET['tipo_atendimento'] == 'checkup' ) selected='selected' @endif>Checkups</option>
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-md-12 col-lg-3">
                                <select id="tipo_especialidade" class="form-control select2" name="tipo_especialidade">
                                    <option value="" disabled selected hidden>Tipo de checkup</option>
                                    @foreach($checkups as $obj)
                                    	<option value="{{$obj->id}}" @if( isset($_GET['tipo_especialidade']) && $_GET['tipo_especialidade'] == $obj->id ) selected='selected' @endif>{{ strtoupper($obj->titulo) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-12 col-lg-3">
                                <select id="local_atendimento" class="form-control select2" name="local_atendimento">
                                </select>
                            </div>
                            <div class="form-group col-md-12 col-lg-3">
                                <button type="submit" class="btn btn-primary btn-vermelho"><i class="fa fa-search"></i>
                                    Alterar Busca
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="titulo">
                    <strong>{{ count($consulta) > 1 ? count($consulta) . " resultados para checkup" : count($consulta) . " resultado para checkup"}}</strong>
                    <p>Escolha abaixo um dos pacotes de exames para seu checkup</p>
                </div>
				<div class="lista">
                  <div class="accordion" id="accordionResultado">
      	  			  @foreach( $consulta as $checkup )
                  @php $qty = 0; @endphp

      	  				<form action="/agendar-atendimento" method="post" >
      	  				
      	  					<input type="hidden" id="tipo_atendimento" name="tipo_atendimento" value="checkup">
      	  					<input type="hidden" id="checkup_id" name="checkup_id" value="{{ $checkup['id'] }}">
                    <input type="hidden" name="current_url" value="{{ Request::fullUrl() }}">
      	  					{!! csrf_field() !!}
                              <div class="card">
                                  <div class="card-header" id="headingTres">
                                      <div class="resumo">
                                          <div class="row">
                                              <div class="col-md-6 col-lg-8 col-xl-9">
                                                  <div class="resumo-pacote">
                                                      <h4>{{ $checkup['titulo'] }}</h4>
                                                      <span class="incluso">Incluso nesse pacote: {{ $checkupObj->itemcheckups->count() }} ítens</span>

                                                      <ul class="quantidade">
                                                        @foreach( $checkup['summary'] as $summary )
                                                          <li><span>{{$summary->qty}} {{$summary->tipo}}</span> </li>

                                                          @php $qty += $summary->qty; @endphp
                                                        @endforeach
                                                      </ul>

                                                      <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse{{$checkup['titulo']}}{{$checkup['tipo']}}" aria-expanded="true" aria-controls="collapseOne">
                                                          Ver detalhes
                                                      </button>
                                                  </div>
                                              </div>
                                              <div class="col-md-6 col-lg-4 col-xl-3">
                                                  <div class="valores">
                                                      <div class="mercado">
                                                          <p>Valor médio de mercado</p>
                                                          <span>R$ {{$checkup['total_vl_mercado']}}</span>
                                                      </div>
                                                      <div class="drhj">
                                                          <p>Procedimentos individuais no Doutor Hoje</p>
                                                          <span>R$ {{$checkup['total_vl_individual']}}</span>
                                                      </div>
                                                      <div class="checkup">
                                                          <p>Valor do Checkup DoutorHJ</p>
                                                          <span>R$ {{$checkup['total_com_checkup']}}</span>
                                                          
                                                          <input type="hidden" id="vl_total_checkup" name="vl_total_checkup" value="{{ $checkup['total_com_checkup'] }}">
                                                          
                                                          <button class="btn btn-vermelho" type="button" data-toggle="collapse" data-target="#collapse{{$checkup['titulo']}}{{$checkup['tipo']}}" aria-expanded="true" aria-controls="collapseOne">
                                                              Agendar este Checkup
                                                          </button>
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
              
                                  <div id="collapse{{$checkup['titulo']}}{{$checkup['tipo']}}" class="collapse" aria-labelledby="headingOne" data-parent="#accordionResultado">
                                      <div class="card-body">
              	  						@foreach($checkup['camadas'] as $titulo => $procedimento)
              	  							<div id="consultas" class="pacote-procedimentos">
                                                      <div class="titulo">
                                                          <div class="row">
                                                              <div class="col-xl-8">
                                                                  {{$titulo}}
                                                              </div>
                                                              <div class="col-xl-4">
                                                                  Escolha data e horário
                                                              </div>
                                                               </div>
                                                      </div>
                                                      @foreach($procedimento as $codigo => $descricao)
                                                          <div class="procedimento">
                                                              <div class="row">
                                                                  <div class="col-xl-8">
                                                                      <div class="nome">
                                                                          @if ( !empty($descricao['obs_procedimento']) ) 
                                                                            <button type="button" class="btn btn-tooltip text-left" data-toggle="tooltip" data-html="true" title="{{ $descricao['obs_procedimento'] }}">
                                                                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                                            </button>
                                                                          @endif
                                                                          <span class="text-primary" style="white-space: normal;">{{@$descricao['descricao']}} @if($descricao['nm_profissional'] != null)- Dr. {{ @$descricao['nm_profissional'] }} @endif</span>
                                                                      </div>
                                                                      <div class="clinicas">
                                                                          <div class="form-check">
                                                                              <label class="form-check-label" for="clinicaProcedimento027">
                                                                                  {{@$descricao['prestador']}} - {{@$descricao['endereco']}}
                                                                              </label>
                                                                          </div>
                                                                      </div>
                                                                  </div>
                                                                  <div class="col-xl-4">
                                                                      @if($descricao['tp_prestador'] == 'CLI')
                                                                      <div class="escolher-data">
                                                                          <label for="selecionaData_{{ $descricao['idatendimento'] }}"><i class="fa fa-calendar"></i></label>
                                                                          <input type="text" id="selecionaData_{{ $descricao['idatendimento'] }}" class="selecionaData mascaraDataAgendamento" name="selecionaData_{{ $descricao['idatendimento'] }}" placeholder="Data" required>
                                                                      </div>
                                                                      <div class="escolher-hora">
                                                                      	  <label for="selecionaHora_{{ $descricao['idatendimento'] }}"><i class="fa fa-clock-o"></i></label>
                                                                          <input type="text" id="selecionaHora_{{ $descricao['idatendimento'] }}" class="selecionaHora mascaraHoraAgendamento" name="selecionaHora_{{ $descricao['idatendimento'] }}" placeholder="Horário" required>
                                                                      </div>
                                                                      @endif

                                                                      @if ( !empty($descricao['ds_item']) )
                                                                        <div class="checkup-warning">
                                                                          <span>{{  $descricao['ds_item'] }}</span>
                                                                        </div>
                                                                      @endif
                                                                  </div>
                                                              </div>
                                                          </div>
              	  								@endforeach
                                                  </div>																			
              	  						@endforeach
        							                   <button type="submit" class="btn btn-primary btn-vermelho">Prosseguir para pagamento</button>
                                      </div>
                                  </div>
                              </div>
                          </form>
      	  		      @endforeach
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
             * COLLAPSE FORM BUSCA MOBILE
             *
             *********************************/
            jQuery(document).ready(function($) {
                var alterClass = function() {
                    var ww = document.body.clientWidth;
                    if (ww < 975) {
                        $('.collapseFormulario').removeClass('show');
                    } else if (ww >= 975) {
                        $('.collapseFormulario').addClass('show');
                    };
                };
                $(window).resize(function(){
                    alterClass();
                });
                //Fire it when the page first loads:
                alterClass();
            });

            /*********************************
             *
             * TROCA COR CARD AO CLICAR
             *
             *********************************/
            $('.card').on('show.bs.collapse hide.bs.collapse', function (e) {
                if (e.type == 'show') {
                    $(this).addClass("card-active");
                } else {
                    $(this).removeClass("card-active");
                }
            });

            /*********************************
             *
             * DATA E HORA
             *
             *********************************/
            jQuery.datetimepicker.setLocale('pt-BR');

            /* DATA */
            var today_date = new Date();
            var today_date_temp = new Date();
            var today_date_range = new Date();
            var min_date = today_date_temp.setDate(today_date.getDate() + 2);
            var max_date = today_date_range.setMonth(today_date_range.getMonth() + 2);            
                
            jQuery('.selecionaData').datetimepicker({                
                timepicker:false,
                format:'d.m.Y',
                minDate: min_date,
                maxDate: max_date,
                beforeShowDay: function(date){ return [date.getDay() == 1 || date.getDay() == 2 || date.getDay() == 3 || date.getDay() == 4 || date.getDay() == 5,""]},
            }).on("input change", function(e){
            	//console.log("Date changed: ", e.target.value);
            	if(e.target.value != '') {
            		var ct_date_temp = ((e.target.value).replace('.', '-').replace('.', '-')).split('-');
                	var ct_date = new Date(ct_date_temp[2], ct_date_temp[1] - 1, ct_date_temp[0]);
                	var days = ['Domingo','Segunda-feira','Terça-feira','Quarta-feira','Quinta-feira','Sexta-feira','Sábado'];

                	if(ct_date.getDay() == 0 || ct_date.getDay() == 6) {
                		$(this).val('');
                		swal(
					        {
					            title: '<div class="tit-sweet tit-warning"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Atenção!</div>',
					            text: 'Atendimento não disponível para ser Realizado aos Sábados e Domingos'
					        }
					    );
                	}
                	
                	jQuery(this).parent().parent().find('.confirma-data span.span-data').html((e.target.value).replace('.', '/').replace('.', '/')+"- "+days[ ct_date.getDay() ]+" - ");

                	if(ct_date <= today_date) {
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
            }).on("input blur", function(e){
            	//console.log("Date changed: ", e.target.value);
            	if(e.target.value != '') {
            		var ct_date_input = ((e.target.value).replace('.', '-').replace('.', '-')).split('-');
                	var ct_date = new Date(ct_date_input[2], ct_date_input[1] - 1, ct_date_input[0]);
                	
            		if(ct_date <= today_date) {
            			ct_date.setDate(today_date.getDate());
            			var ct_mes = pad((ct_date.getMonth()+1));
            			$(this).val('');
            			swal(
					        {
					            title: '<div class="tit-sweet tit-warning"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Atenção!</div>',
					            text: 'A Data informada não está disponível para a Agendamento'
					        }
					    );
            		}

            		var ct_hora = jQuery(this).parent().parent().find('.selecionaHora').val();
            		if(ct_hora != '') {

                		var clinica_id 		= jQuery(this).parent().parent().parent().find('#clinica_id').val();
                		var profissional_id = jQuery(this).parent().parent().parent().find('#profissional_id').val();
                		var dt_agendamento = ct_date_input[2]+'-'+ct_date_input[1]+'-'+ct_date_input[0];
                		
                		if(clinica_id == '') { return false; }
                		if(profissional_id == '') { return false; }
                		
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

                				if( !result.status) {
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

            /* HORA */
            jQuery('.selecionaHora').datetimepicker({ 
                datepicker:false,
                format:'H:i',
                step: 30,
                minTime:'08:00',
                maxTime:'18:10',
            }).on("input change", function(e){
            	//console.log("Time changed: ", e.target.value);
            	if(e.target.value != '') {
	            	var ct_hora_temp = (e.target.value).split(':');
	            	var ct_date = jQuery('.selecionaData').parent().parent().find('.confirma-data span.span-data').html();
	            	var ct_date_input = (jQuery(this).parent().parent().find('.selecionaData').val()).split('.');
	            	jQuery(this).parent().parent().find('.confirma-data span.span-hora').html(ct_hora_temp[0]+"H"+ct_hora_temp[1]+"MIN");

	            	var ct_hora = ct_hora_temp[0]+':'+ct_hora_temp[1];
            		if(ct_hora != '') {

                		var clinica_id 		= jQuery(this).parent().parent().parent().find('#clinica_id').val();
                		var profissional_id = jQuery(this).parent().parent().parent().find('#profissional_id').val();
                		var dt_agendamento = ct_date_input[2]+'-'+ct_date_input[1]+'-'+ct_date_input[0];
                		
                		if(clinica_id == '') { return false; }
                		if(profissional_id == '') { return false; }
                		
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

                				if( !result.status) {
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
        </script>
    @endpush
@endsection