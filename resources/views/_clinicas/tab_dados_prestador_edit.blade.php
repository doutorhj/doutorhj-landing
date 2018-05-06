<style>
    .ui-autocomplete {
        max-height: 200px;
        overflow-y: auto;
        overflow-x: hidden;
    }
    * html .ui-autocomplete {
        height: 200px;
    }
</style>

@if($errors->any())
    <div class="col-12 alert alert-danger">
        @foreach ($errors->all() as $error)<div class="col-5">{{ $error }}</div>@endforeach
    </div>
@endif

{!! csrf_field() !!}
<div class="row">
	<div class="col-md-6">
	
		<h4>Dados Cadastrais da Clínica</h4>
		
		<div class="form-group{{ $errors->has('nm_razao_social') ? ' has-error' : '' }}">
            <label for="nm_razao_social" class="col-3 control-label">Razão Social<span class="text-danger">*</span></label>
            <div class="col-8">
                <input id="nm_razao_social" type="text" class="form-control" name="nm_razao_social" value="{{ $prestador->nm_razao_social}}" required  maxlength="100" autofocus>
            </div>	
        </div>
        
        <div class="form-group{{ $errors->has('nm_fantasia') ? ' has-error' : '' }}">
            <label for="nm_fantasia" class="col-3 control-label">Nome Fantasia<span class="text-danger">*</span></label>
            <div class="col-8">
                <input id="nm_fantasia" type="text" class="form-control" name="nm_fantasia" value="{{$prestador->nm_fantasia}}" required  maxlength="100">
            </div>	
        </div>
        
        <div class="form-group{{ $errors->has('nr_cnpj') ? ' has-error' : '' }}">
        	 @foreach( $documentosclinica as $documento )
                <label for="nr_cnpj" class="col-12 control-label">CNPJ / Inscrição Estadual<span class="text-danger">*</span></label>
                <div class="col-8">
                	<input type="hidden" name="tp_documento_{{$documento->id}}" value="CNPJ">
                    <input id="te_documento_{{$documento->id}}" type="text" class="form-control mascaraCNPJ" name="te_documento_{{$documento->id}}" value="{{ $prestador->documentos->first()->te_documento }}" required readonly >
                    <input type="hidden" id="cnpj_id" name="cnpj_id" value="{{ $documento->id }}">
                </div>
            @endforeach
        </div>
        
         <div class="form-group{{ $errors->has('tp_contato') ? ' has-error' : '' }}">
        	@foreach ( $prestador->contatos as $obContato )                
            <label for="tp_contato" class="col-12 control-label">Telefone<span class="text-danger">*</span></label>
            <div class="row">
            	<div class="col-md-4">
    				<select id="tp_contato_{{$obContato->id}}" name="tp_contato_{{$obContato->id}}" class="form-control">
    					<option value="FC" @if( $obContato->tp_contato == 'FC' ) selected='selected' @endif >Telefone Comercial</option>
    					<option value="CC" @if( $obContato->tp_contato == 'CC' ) selected='selected' @endif >Celular Comercial</option>
    					<option value="FX" @if( $obContato->tp_contato == 'FX' ) selected='selected' @endif >FAX</option>
    				</select>
                </div>
    	        <div class="col-md-4">
    				<input id="ds_contato_{{ $obContato->id }}" type="text" placeholder="" class="form-control mascaraTelefone" name="ds_contato_{{ $obContato->id }}" value="{{ $obContato->ds_contato }}" required >
    				<input type="hidden" id="contato_id" name="contato_id" value="{{ $obContato->id }}" >
                </div>
            </div>
            @endforeach
        </div>
        
	</div>
	<div class="col-md-6">
	
		<h4>Dados Pessoais do Responsável</h4>
		
        <div class="form-group{{ $errors->has('name_responsavel') ? ' has-error' : '' }}">
        	<div class="row">
                <label for="name_responsavel" class="col-12 control-label">Nome<span class="text-danger">*</span></label>
                <div class="col-8">
                    <input type="text"  id="name_responsavel" class="form-control" name="name_responsavel" value="{{$prestador->responsavel->user->name}}" required  maxlength="50">
                    <input type="hidden" id="responsavel_id" name="responsavel_id" value="{{$prestador->responsavel->id}}">
                </div>
            </div>
        </div>
        
        <div class="form-group{{ $errors->has('cpf_responsavel') ? ' has-error' : '' }}">
            <div class="row">
                <label for="cpf_responsavel" class="col-12 control-label">CPF<span class="text-danger">*</span></label>
                <div class="col-8">
                    <input id="cpf_responsavel" type="text" class="form-control mascaraCPF" name="cpf_responsavel" value="{{$prestador->responsavel->cpf}}" required readonly  maxlength="14">
                </div>
            </div>
        </div>
        
        <div class="form-group{{ $errors->has('telefone_responsavel') ? ' has-error' : '' }}">
            <div class="row">
                <label for="telefone_responsavel" class="col-12 control-label">Telefone<span class="text-danger">*</span></label>
                <div class="col-8">
                    <input id="telefone_responsavel" type="text" class="form-control mascaraTelefone" name="telefone_responsavel" value="{{$prestador->responsavel->telefone}}" required  maxlength="20">
                </div>
            </div>
        </div>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
    	<hr>
    </div>
</div>

<div class="col-10">
	<h4>Endereço Comercial</h4>
</div>

<div class="row">
	<div class="col-md-1">
		<div class="form-group{{ $errors->has('nr_cep') ? ' has-error' : '' }}">
            <label for="nr_cep" class="col-3 control-label">CEP<span class="text-danger">*</span></label>
            <input id="nr_cep" type="text" class="form-control mascaraCEP consultaCep" name="nr_cep" value="{{$prestador->enderecos->first()->nr_cep}}" required  maxlength="10">
            <input type="hidden" id="nr_latitude_gps" name="nr_latitude_gps" value="{{ $prestador->enderecos->first()->nr_latitude_gps }}" >
            <input type="hidden" id="nr_longitute_gps" name="nr_longitute_gps" value="{{ $prestador->enderecos->first()->nr_longitute_gps }}" >
            <input type="hidden" id="endereco_id" name="endereco_id" value="{{$prestador->enderecos->first()->id}}">
        </div>
	</div>
	
	<div class="col-md-2">
		<div class="form-group{{ $errors->has('sg_logradouro') ? ' has-error' : '' }}">
            <label for="sg_logradouro" class="col-3 control-label">Logradouro<span class="text-danger">*</span></label>
    		<select id="sg_logradouro" name="sg_logradouro" class="form-control">
    			<option value="" selected="selected"></option>
                <option value="Aeroporto" {{($prestador->enderecos->first()->sg_logradouro == 'Aeroporto' ? 'selected' : '')}}>Aeroporto</option>
                <option value="Alameda" {{($prestador->enderecos->first()->sg_logradouro == 'Alameda' ? 'selected' : '')}}>Alameda</option>
                <option value="Área" {{($prestador->enderecos->first()->sg_logradouro == 'Área' ? 'selected' : '')}}>Área</option>
                <option value="Avenida" {{($prestador->enderecos->first()->sg_logradouro == 'Avenida' ? 'selected' : '')}}>Avenida</option>
                <option value="Campo" {{($prestador->enderecos->first()->sg_logradouro == 'Campo' ? 'selected' : '')}}>Campo</option>
                <option value="Chácara" {{($prestador->enderecos->first()->sg_logradouro == 'Chácara' ? 'selected' : '')}}>Chácara</option>
                <option value="Colônia" {{($prestador->enderecos->first()->sg_logradouro == 'Colônia' ? 'selected' : '')}}>Colônia</option>
                <option value="Condomínio" {{($prestador->enderecos->first()->sg_logradouro == 'Condomínio' ? 'selected' : '')}}>Condomínio</option>
                <option value="Conjunto" {{($prestador->enderecos->first()->sg_logradouro == 'Conjunto' ? 'selected' : '')}}>Conjunto</option>
                <option value="Distrito" {{($prestador->enderecos->first()->sg_logradouro == 'Distrito' ? 'selected' : '')}}>Distrito</option>
                <option value="Esplanada" {{($prestador->enderecos->first()->sg_logradouro == 'Esplanada' ? 'selected' : '')}}>Esplanada</option>
                <option value="Estação" {{($prestador->enderecos->first()->sg_logradouro == 'Estação' ? 'selected' : '')}}>Estação</option>
                <option value="Estrada" {{($prestador->enderecos->first()->sg_logradouro == 'Estrada' ? 'selected' : '')}}>Estrada</option>
                <option value="Favela" {{($prestador->enderecos->first()->sg_logradouro == 'Favela' ? 'selected' : '')}}>Favela</option>
                <option value="Feira" {{($prestador->enderecos->first()->sg_logradouro == 'Feira' ? 'selected' : '')}}>Feira</option>
                <option value="Jardim" {{($prestador->enderecos->first()->sg_logradouro == 'Jardim' ? 'selected' : '')}}>Jardim</option>
                <option value="Ladeira" {{($prestador->enderecos->first()->sg_logradouro == 'Ladeira' ? 'selected' : '')}}>Ladeira</option>
                <option value="Lago" {{($prestador->enderecos->first()->sg_logradouro == 'Lago' ? 'selected' : '')}}>Lago</option>
                <option value="Lagoa" {{($prestador->enderecos->first()->sg_logradouro == 'Lagoa' ? 'selected' : '')}}>Lagoa</option>
                <option value="Largo" {{($prestador->enderecos->first()->sg_logradouro == 'Largo' ? 'selected' : '')}}>Largo</option>
                <option value="Loteamento" {{($prestador->enderecos->first()->sg_logradouro == 'Loteamento' ? 'selected' : '')}}>Loteamento</option>
                <option value="Morro" {{($prestador->enderecos->first()->sg_logradouro == 'Morro' ? 'selected' : '')}}>Morro</option>
                <option value="Núcleo" {{($prestador->enderecos->first()->sg_logradouro == 'Núcleo' ? 'selected' : '')}}>Núcleo</option>
                <option value="Parque" {{($prestador->enderecos->first()->sg_logradouro == 'Parque' ? 'selected' : '')}}>Parque</option>
                <option value="Passarela" {{($prestador->enderecos->first()->sg_logradouro == 'P -->assarela' ? 'selected' : '')}}>Passarela</option>
                <option value="Pátio" {{($prestador->enderecos->first()->sg_logradouro == 'Pátio' ? 'selected' : '')}}>Pátio</option>
                <option value="Praça" {{($prestador->enderecos->first()->sg_logradouro == 'Praça' ? 'selected' : '')}}>Praça</option>
                <option value="Quadra" {{($prestador->enderecos->first()->sg_logradouro == 'Quadra' ? 'selected' : '')}}>Quadra</option>
                <option value="Recanto" {{($prestador->enderecos->first()->sg_logradouro == 'Recanto' ? 'selected' : '')}}>Recanto</option>
                <option value="Residencial" {{($prestador->enderecos->first()->sg_logradouro == 'Residencial' ? 'selected' : '')}}>Residencial</option>
                <option value="Rodovia" {{($prestador->enderecos->first()->sg_logradouro == 'Rodovia' ? 'selected' : '')}}>Rodovia</option>
                <option value="Rua" {{($prestador->enderecos->first()->sg_logradouro == 'Rua' ? 'selected' : '')}}>Rua</option>
                <option value="Setor" {{($prestador->enderecos->first()->sg_logradouro == 'Setor' ? 'selected' : '')}}>Setor</option>
                <option value="Sítio" {{($prestador->enderecos->first()->sg_logradouro == 'Sítio' ? 'selected' : '')}}>Sítio</option>
                <option value="Travessa" {{($prestador->enderecos->first()->sg_logradouro == 'Travessa' ? 'selected' : '')}}>Travessa</option>
                <option value="Trecho" {{($prestador->enderecos->first()->sg_logradouro == 'Trecho' ? 'selected' : '')}}>Trecho</option>
                <option value="Trevo" {{($prestador->enderecos->first()->sg_logradouro == 'Trevo' ? 'selected' : '')}}>Trevo</option>
                <option value="Vale" {{($prestador->enderecos->first()->sg_logradouro == 'Vale' ? 'selected' : '')}}>Vale</option>
                <option value="Vereda" {{($prestador->enderecos->first()->sg_logradouro == 'Vereda' ? 'selected' : '')}}>Vereda</option>
                <option value="Via" {{($prestador->enderecos->first()->sg_logradouro == 'Via' ? 'selected' : '')}}>Via</option>
                <option value="Viaduto" {{($prestador->enderecos->first()->sg_logradouro == 'Viaduto' ? 'selected' : '')}}>Viaduto</option>
                <option value="Viela" {{($prestador->enderecos->first()->sg_logradouro == 'Viela' ? 'selected' : '')}}>Viela</option>
                <option value="Vila" {{($prestador->enderecos->first()->sg_logradouro == 'Vila' ? 'selected' : '')}}>Vila</option>
    		</select>
        </div>
	</div>
	<div class="col-md-8">
		<div class="form-group{{ $errors->has('te_endereco') ? ' has-error' : '' }}">
    		<label for="te_endereco" class="col-3 control-label">Endereço<span class="text-danger">*</span></label>
    		<input id="te_endereco" type="text" class="form-control" name="te_endereco" value="{{$prestador->enderecos->first()->te_endereco}}" required  maxlength="200">
    	</div>
	</div>
	<div class="col-md-1">
		<div class="form-group{{ $errors->has('nr_logradouro') ? ' has-error' : '' }}">
    		<label for="nr_logradouro" class="col-3 control-label">Número<span class="text-danger">*</span></label>
    		<input id="nr_logradouro" type="text" class="form-control" name="nr_logradouro" value="{{$prestador->enderecos->first()->nr_logradouro}}" required  maxlength="50">
		</div>
	</div>
</div>	

<div class="row">
	<div class="col-md-4">
		<div class="form-group{{ $errors->has('te_complemento') ? ' has-error' : '' }}">
            <label for="te_complemento" class="col-3 control-label">Complemento</label>
            <textarea rows="1" cols="10" id="te_complemento" class="form-control" name="te_complemento"  maxlength="1000">{{$prestador->enderecos->first()->te_complemento}}</textarea>
        </div>
	</div>
	<div class="col-md-2">
		<div class="form-group{{ $errors->has('te_bairro') ? ' has-error' : '' }}">
    		<label for="te_bairro" class="col-3 control-label">Bairro<span class="text-danger">*</span></label>
    		<input id="te_bairro" type="text" class="form-control" name="te_bairro" value="{{$prestador->enderecos->first()->te_bairro}}" required  maxlength="50">
		</div>
	</div>
	<div class="col-md-4">
		<div class="form-group{{ $errors->has('nm_cidade') ? ' has-error' : '' }}">
			<label for="nm_cidade" class="col-3 control-label">Cidade<span class="text-danger">*</span></label>

            <input id="nm_cidade" type="text" class="form-control" name="nm_cidade" value="{{ $prestador->enderecos->first()->cidade->nm_cidade }}" required  maxlength="50" readonly>
    		<input id="cd_cidade_ibge" type="hidden" name="cd_cidade_ibge" value="{{ $prestador->enderecos->first()->cidade->cd_ibge }}">
    	</div>
    </div>
	<div class="col-md-2">
		<div class="form-group{{ $errors->has('sg_estado') ? ' has-error' : '' }}">
            <label for="sg_estado" class="col-3 control-label">Estado<span class="text-danger">*</span></label>
            <select id="sg_estado" name="sg_estado" class="form-control" disabled>
    			<option></option>
                @foreach ($estados as $uf)
    				<option value="{{ $uf->sg_estado }}" {{ ($prestador->enderecos->first()->cidade->sg_estado == $uf->sg_estado ? 'selected' : '')}}>{{ $uf->ds_estado }}</option>
                @endforeach
    		</select>
        </div>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
    	<hr>
    </div>
</div>

<div class="col-10">
	<h4>Dados de Acesso</h4>
</div>

<div class="row">
	<div class="col-md-4">
		<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <label for="email" class="col-3 control-label">E-mail<span class="text-danger">*</span></label>
            <input id="email" type="email" class="form-control semDefinicaoLetrasMaiusculasMinusculas" name="email" value="{{ $user->email }}" required readonly  maxlength="50">
            <input type="hidden" id="responsavel_user_id" name="responsavel_user_id" value="{{ $user->id }}" >
        </div>
	</div>
	<div class="col-md-2">
		<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
    		<label for="password" class="col-3 control-label">Senha<span class="text-danger">*</span></label>
    		<input id="password" type="password" class="form-control semDefinicaoLetrasMaiusculasMinusculas" name="password" value="{{ $user->password }}" required  maxlength="50">
		</div>
	</div>
	<div class="col-md-2">
		<div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
    		<label for="password_confirmation" class="control-label">Repita a Senha<span class="text-danger">*</span></label>
    		<input id="password_confirmation" type="password" class="form-control semDefinicaoLetrasMaiusculasMinusculas" name="password_confirmation" value="{{ $user->password }}" required  maxlength="50">
		</div>
	</div>
</div>

<div class="form-group">
    <div class="col-12 col-offset-3">
        
    </div>
				
	<div class="form-group text-right m-b-0">
		<button type="submit" class="btn btn-primary waves-effect waves-light" ><i class="mdi mdi-content-save"></i> Alterar</button>
		<a href="{{ route('clinicas.index') }}" class="btn btn-secondary waves-effect m-l-5"><i class="mdi mdi-cancel"></i> Cancelar</a>
	</div>
</div>

<script>
    $( function() {
        $( function() {
            var availableTags = [
                @foreach ($cargos as $cargo)
                    '{{ $cargo->id ." | ". $cargo->ds_cargo }}',
                @endforeach
            ];

            $( "#ds_cargo" ).autocomplete({
              source: availableTags,
              select: function (event, ui) {
                  var id_cargo = ui.item.value.split(' | ');
                  $("#cargo_id").val(id_cargo[0]); 	
              },
              delay: 500,
              minLength: 4 
            });
        });

        /* $( "#nr_cep" ).blur(function() {
        	$.ajax({
        	  url: "/consulta-cep/cep/"+this.value,
        	  context: document.body
        	}).done(function(resposta) {
        	  $( this ).addClass( "done" );

        	  if( resposta != null){
            	  var json = JSON.parse(resposta);
    
      			  $('#te_endereco').val(json.logradouro);
      			  $('#te_bairro').val(json.bairro);
      			  $('#nm_cidade').val(json.cidade);
      			  $('#sg_estado').val(json.estado);
      			  $('#cd_cidade_ibge').val(json.ibge);
      			  
        	  }else{
      			  $('#te_endereco').val(null);
      			  $('#te_bairro').val(null);
      			  $('#nm_cidade').val(null);
      			  $('#sg_estado').val(null);
      			  $('#cd_cidade_ibge').val(null);
              }
        	});
        }); */
    });
</script>