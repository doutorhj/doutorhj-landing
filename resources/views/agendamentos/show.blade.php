@extends('layouts.master')

@section('title', 'Locais de Atendimento')

@section('container')
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="page-title-box">
				<h4 class="page-title">Doutor HJ</h4>
				<ol class="breadcrumb float-right">
					<li class="breadcrumb-item"><a href="/">Home</a></li>
					<li class="breadcrumb-item"><a href="{{ route('prestadores.index') }}">Lista de Prestadores</a></li>
					<li class="breadcrumb-item active">Cadastrar Prestador</li>
				</ol>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
	
	<input type="hidden" name="_method" value="PUT">
	{!! csrf_field() !!}
	
	<div class="row">
        <div class="col-12">
            <div class="card-box col-12">
                <h4 class="header-title m-t-0 m-b-30">Locais de Atendimento</h4>

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
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade active show" id="prestador">
                    	@include('prestadores/tab_dados_prestador_show')
                    </div>
                    <div class="tab-pane fade" id="precificacaoProcedimento">
                        @include('prestadores/precificacaoProcedimentoShow')
                    </div>
                    <div class="tab-pane fade" id="precificacaoConsulta">
                     	@include('prestadores/precificacaoConsultaShow')
                    </div>
                </div>
            </div>
   		</div>
	</div>
</div>
@endsection