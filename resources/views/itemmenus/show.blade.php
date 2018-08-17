@extends('layouts.master')

@section('title', 'Item de Menu')

@section('container')
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="page-title-box">
				<h4 class="page-title">Doutor HJ</h4>
				<ol class="breadcrumb float-right">
					<li class="breadcrumb-item"><a href="/">Home</a></li>
					<li class="breadcrumb-item"><a href="{{ route('itemmenus.index') }}">Itens de Menu</a></li>
					<li class="breadcrumb-item active">Detalhes do Item</li>
				</ol>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-12">
			<div class="card-box">
				<h4 class="header-title m-t-0 m-b-20">Detalhes do Item</h4>
				
				<table class="table table-bordered table-striped view-doutorhj">
					<tbody>
						<tr>
							<td width="25%">Código (id):</td>
							<td width="75%">{{ $itemmenu->id }}</td>
						</tr>
						<tr>
							<td>Título:</td>
							<td>{{ $itemmenu->titulo }}</td>
						</tr>
						<tr>
							<td>URL:</td>
							<td>/{{ $itemmenu->url }}</td>
						</tr>
						<tr>
							<td>Ícone:</td>
							<td>@if( $itemmenu->ic_item_class != '') "<i class='$itemmenu->ic_item_class'></i>" @else ---- @endif</td>
						</tr>
						<tr>
							<td>Ordem de Exibição:</td>
							<td>{{ $itemmenu->ordemexibicao }}ª</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="form-group text-right m-b-0">
				<a href="{{ route('itemmenus.edit', $itemmenu->id) }}" class="btn btn-primary waves-effect waves-light" ><i class="mdi mdi-lead-pencil"></i> Editar</a>
				<a href="{{ route('itemmenus.index') }}" class="btn btn-secondary waves-effect m-l-5"><i class="mdi mdi-cancel"></i> Cancelar</a>
			</div>
		</div>
	</div>
</div>
@endsection