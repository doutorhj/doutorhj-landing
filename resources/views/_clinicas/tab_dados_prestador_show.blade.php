<div class="container-fluid">
	<div class="row">
		<div class="col-12">
			<div class="card-box">
				<table class="table table-bordered table-striped view-doutorhj">
					<tbody>
						<tr>
							<td width="25%">Razão Social</td>
							<td width="75%">{{ $prestador->nm_razao_social}}</td>
						</tr>
						<tr>
							<td width="25%">Nome Fantasia</td>
							<td width="75%">{{$prestador->nm_fantasia}}</td>
						</tr>
						<tr>
							<td width="25%"><h4>Dados do Responsável</h4></td>
							<td width="75%"></td>
						</tr>
						<tr>
							<td width="25%">Nome</td>
							<td width="75%">{{$prestador->responsavel->user->name}}</td>
						</tr>
						<tr>
							<td width="25%">Cargo</td>
							<td width="75%">Responsável</td>
						</tr>
						<tr>
							<td width="25%">E-mail</td>
							<td width="75%">{{ $user->email }}</td>
						</tr>
						<tr>
							<td width="25%"><h4>Documentação</h4></td>
							<td width="75%"></td>
						</tr>
						<tr>
							<td width="25%">CNPJ</td>
							<td width="75%">{{$prestador->documentos->first()->te_documento}}</td>
						</tr>
						<tr>
							<td width="25%"><h4>Contato</h4></td>
							<td width="75%"></td>
						</tr>
						@foreach ( $prestador->contatos as $obContato )
						<tr>
							<td width="25%">
                                @switch($obContato->tp_contato)
                                    @case("FC")
                                        Telefone Fixo
                                        @break                                
                                    @case("CC")
                                        Celular Comercial
                                        @break
                                    @case("FX")
                                        FAX
                                        @break
                                @endswitch
							</td>
							<td width="75%">{{ $obContato->ds_contato }}</td>
						</tr>
                         @endforeach
						<tr>
							<td width="25%"><h4>Endereço Comercial</h4></td>
							<td width="75%"></td>
						</tr>
						<tr>
							<td width="25%">CEP</td>
							<td width="75%">{{$prestador->enderecos->first()->nr_cep}}</td>
						</tr>
						<tr>
							<td width="25%">Logradouro</td>
							<td width="75%">{{$prestador->enderecos->first()->sg_logradouro}}</td>
						</tr>
						<tr>
							<td width="25%">Endereço</td>
							<td width="75%">{{$prestador->enderecos->first()->te_endereco}}</td>
						</tr>
						<tr>
							<td width="25%">Número</td>
							<td width="75%">{{$prestador->enderecos->first()->nr_logradouro}}</td>
						</tr>
						<tr>
							<td width="25%">Complemento</td>
							<td width="75%">{{$prestador->enderecos->first()->te_complemento}}</td>
						</tr>
						<tr>
							<td width="25%">Bairro</td>
							<td width="75%">{{$prestador->enderecos->first()->te_bairro}}</td>
						</tr>
						<tr>
							<td width="25%">Cidade</td>
							<td width="75%">{{ $cidade->nm_cidade }}</td>
						</tr>
						<tr>
							<td width="25%">Estado</td>
							<td width="75%">{{$cidade->sg_estado}}</td>
						</tr>
					</tbody>
				</table>

    			<div class="form-group text-right m-b-0">
    				<a href="{{ route('clinicas.edit', $prestador->id) }}" class="btn btn-primary waves-effect waves-light" ><i class="mdi mdi-lead-pencil"></i> Editar</a>
    				<a href="{{ route('clinicas.index') }}" class="btn btn-secondary waves-effect m-l-5"><i class="mdi mdi-cancel"></i> Cancelar</a>
    			</div>
        	
			</div>
		</div>
	</div>
</div>
    