@extends('layouts.master')

@section('title', 'Clínicas')

@section('container')
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="page-title-box">
				<h4 class="page-title">Doutor HJ</h4>
				<ol class="breadcrumb float-right">
					<li class="breadcrumb-item"><a href="/">Home</a></li>
					<li class="breadcrumb-item"><a href="{{ route('clinicas.index') }}">Lista de Clínicas</a></li>
					<li class="breadcrumb-item active">Cadastrar Clínicas</li>
				</ol>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
	
	<form action="{{ route('clinicas.store') }}" method="post">
    	<div class="row">
	        <div class="col-12">
                <div class="card-box col-12">
                    <h4 class="header-title m-t-0 m-b-30">Clínicas</h4>
    
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a href="#prestador" data-toggle="tab" aria-expanded="true" class="nav-link active">
                                Dados do Prestador
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#precificacaoProcedimento" data-toggle="tab" aria-expanded="false" class="nav-link">
                                Precificação de Procedimentos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#precificacaoConsulta" data-toggle="tab" aria-expanded="false" class="nav-link">
                                Precificação de Consultas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#corpoClinico" data-toggle="tab" aria-expanded="false" class="nav-link">
                                Corpo Clínico
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="prestador">
                        	@include('clinicas/tab_dados_prestador_create')
                        </div>
                        <?php /*
                        <div class="tab-pane fade" id="precificacaoProcedimento">
                         	@include('clinicas/precificacaoProcedimento')
                        </div>
                        <div class="tab-pane fade" id="precificacaoConsulta">
                         	@include('clinicas/precificacaoConsulta')
                        </div>
                        <div class="tab-pane fade" id="corpoClinico">
                         	@include('clinicas/tab_corpo_clinico')
                        </div>
                        */?>
                    </div>
                </div>
       		</div>
    	</div>
   </form>
</div>
@push('scripts')
	<script type="text/javascript">
	jQuery(document).ready(function($) {

	    $( "#nr_cep" ).blur(function() {
	    	jQuery.ajax({
        		type: 'GET',
        	  	url: '/consulta-cep/cep/'+this.value,
        	  	data: {
					'nr_cep': this.value,
					'_token': laravel_token
				},
				success: function (result) {
					$( this ).addClass( "done" );

					if( result != null) {
						var json = JSON.parse(result.endereco);

						$('#te_endereco').val(json.logradouro);
						$('#te_bairro').val(json.bairro);
						$('#nm_cidade').val(json.cidade);
						$('#sg_estado').val(json.estado);
						$('#cd_cidade_ibge').val(json.ibge);
						$('#nr_latitude_gps').val(json.latitude);
						$('#nr_longitute_gps').val(json.longitude);
						
					} else {

						$('#te_endereco').val('');
						$('#te_bairro').val('');
						$('#nm_cidade').val('');
						$('#sg_estado').val('');
						$('#cd_cidade_ibge').val('');
						$('#sg_logradouro').prop('selectedIndex',0);
						$('#nr_latitude_gps').val('');
						$('#nr_longitute_gps').val('');
					}
	            },
	            error: function (result) {
	            	$.Notification.notify('error','top right', 'DrHoje', 'Falha na operação!');
	            }
        	});
		});
	});
	</script>
    @endpush
@endsection