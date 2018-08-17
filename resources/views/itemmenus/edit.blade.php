@extends('layouts.master')

@section('title', 'Itens de Menu')

@section('container')
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="page-title-box">
				<h4 class="page-title">Doutor HJ</h4>
				<ol class="breadcrumb float-right">
					<li class="breadcrumb-item"><a href="/">Home</a></li>
					<li class="breadcrumb-item"><a href="{{ route('itemmenus.index') }}">Itens de Menus</a></li>
					<li class="breadcrumb-item active">Editar Menu</li>
				</ol>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-6 offset-md-3">
			<div class="card-box">
				<h4 class="header-title m-t-0">Editar Item de Menu</h4>
				
				<form action="{{ route('itemmenus.update', $itemmenu->id) }}" method="post">
					<input type="hidden" name="_method" value="PUT">
					{!! csrf_field() !!}
					
					<div class="form-group">
						<label for="titulo">Título<span class="text-danger">*</span></label>
						<input type="text" id="titulo" class="form-control" name="titulo" value="{{ $itemmenu->titulo }}" placeholder="Título do Menu" maxlength="150" required  >
					</div>
					
					<div class="form-group">
						<label for="url">URL<span class="text-danger">*</span></label>
						<input type="text" id="url" class="form-control" name="url" value="{{ $itemmenu->url }}" placeholder="URL" required  >
					</div>
					
					<div class="form-group">
						<label for="ic_menu_class">Icon Class</label>
						<input type="text" id="ic_item_class" class="form-control" name="ic_item_class" value="{{ $itemmenu->ic_item_class }}" placeholder="Classe do Ícone" maxlength="250"  >
					</div>
					
					<div class="form-group">
						<label for="ordemexibicao">Ordem de Exibição<span class="text-danger">*</span></label>
						<input type="number" id="ordemexibicao" class="form-control" name="ordemexibicao" value="{{ $itemmenu->ordemexibicao }}" placeholder="Ordem de Exibição" min="0" required  >
					</div>
					
					<div class="form-group">
						<label for="menu_id">Menu<span class="text-danger">*</span></label>
						<select id="menu_id" class="form-control" name="menu_id" placeholder="Selecione o Menu" required>
						@foreach($menus as $id => $titulo)
							<option value="{{ $id }}" @if( $id == $itemmenu->menu_id ) selected='selected' @endif >{{ $titulo }}</option>
						@endforeach
						</select>
					</div>
					
					<div class="form-group text-right m-b-0">
						<button type="submit" class="btn btn-primary waves-effect waves-light" ><i class="mdi mdi-content-save"></i> Salvar</button>
						<a href="{{ route('itemmenus.index') }}" class="btn btn-secondary waves-effect m-l-5"><i class="mdi mdi-cancel"></i> Cancelar</a>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection