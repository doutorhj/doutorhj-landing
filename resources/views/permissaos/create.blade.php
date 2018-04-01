@extends('layouts.master')

@section('title', 'Permissões')

@section('container')
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="page-title-box">
				<h4 class="page-title">Doutor HJ</h4>
				<ol class="breadcrumb float-right">
					<li class="breadcrumb-item"><a href="/">Home</a></li>
					<li class="breadcrumb-item"><a href="{{ route('permissaos.index') }}">Lista de Permissões</a></li>
					<li class="breadcrumb-item active">Adicionar Permissão</li>
				</ol>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-6 offset-md-3">
			<div class="card-box">
				<h4 class="header-title m-t-0">Adicionar Permissão</h4>
				
				<form action="{{ route('permissaos.store') }}" method="post">
				
					{!! csrf_field() !!}
					
					<div class="form-group">
						<label for="titulo">Título<span class="text-danger">*</span></label>
						<input type="text" id="titulo" class="form-control" name="titulo" placeholder="Título do Menu" maxlength="150" required  >
					</div>
					
					<div class="form-group">
						<label for="codigo_permissao">Código Permissão<span class="text-danger">*</span></label>
						<input type="text" id="codigo_permissao" class="form-control" name="codigo_permissao" value="{{ $code_permission }}" placeholder="Código da Permissão" maxlength="32" readonly="readonly" required   >
					</div>
					
					<div class="form-group">
						<label for="url_model">Nome do Model<span class="text-danger">*</span></label>
						<input type="text" id="url_model" class="form-control" name="url_model" placeholder="Model Name" maxlength="250" required  >
					</div>
					
					<div class="form-group">
						<label for="url_action">Nome do Action<span class="text-danger">*</span></label>
						<input type="text" id="url_action" class="form-control" name="url_action" placeholder="Action Name" maxlength="250" required  >
					</div>
					
					<div class="form-group">
						<label for="descricao">Descrição<span class="text-danger">*</span></label>
						<textarea id="descricao" class="form-control" name="descricao" placeholder="Descrição do Menu" required ></textarea>
					</div>
					
					<div class="form-group text-right m-b-0">
						<button type="submit" class="btn btn-primary waves-effect waves-light" ><i class="mdi mdi-content-save"></i> Salvar</button>
						<a href="{{ route('permissaos.index') }}" class="btn btn-secondary waves-effect m-l-5"><i class="mdi mdi-cancel"></i> Cancelar</a>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection