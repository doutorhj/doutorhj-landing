@extends('layouts.base')

@section('title', 'DoutorHj: Minha Conta')

@push('scripts')

@endpush

@section('content')
    <section class="area-busca-interna minha-conta">
        <div class="container">
            
            <div class="box-minha-conta">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-3 menu">
                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-cadastro" role="tab" aria-controls="v-pills-home" aria-selected="true"><i class="fa fa-caret-right"></i>
                                Cadastro</a>
                            <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-pagamento" role="tab" aria-controls="v-pills-profile" aria-selected="false"><i class="fa fa-caret-right"></i>
                                Pagamento</a>
                            <!-- <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-anuidade" role="tab" aria-controls="v-pills-profile" aria-selected="false"><i class="fa fa-caret-right"></i>
                                Anuidade</a> -->
                            <!-- <a class="nav-link" id="v-pills-messages-tab" data-toggle="pill" href="#v-pills-mensagens" role="tab" aria-controls="v-pills-messages" aria-selected="false"><i class="fa fa-caret-right"></i>
                                Mensagens</a> -->
                            <!-- <a class="nav-link" id="v-pills-notifications-tab" data-toggle="pill" href="#v-pills-notificacoes" role="tab" aria-controls="v-pills-notifications" aria-selected="false"><i class="fa fa-caret-right"></i>
                                Notificações</a> -->
                            <!-- <a class="nav-link" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-sugestoes" role="tab" aria-controls="v-pills-settings" aria-selected="false"><i class="fa fa-caret-right"></i>
                                Deixe sua Sugestão</a> -->
                            <a class="nav-link" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-sair" role="tab" aria-controls="v-pills-settings" aria-selected="false"><i class="fa fa-caret-right"></i>
                                Sair</a>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-9 conteudo">
                        <div class="tab-content" id="v-pills-tabContent">

                            <!-- Cadastro -->

                            <div class="tab-pane fade show active" id="v-pills-cadastro" role="tabpanel" aria-labelledby="v-pills-home-tab">
                                <div class="row">
                                    <div class="col-md-12 col-lg-6 col-dados-cadastro">
                                        <span class="label-titulo">Cadastro</span>
                                        <form>
                                            <fieldset disabled>
                                                <div class="form-group">
                                                    <label for="inputNome">Nome</label>
                                                    <input type="text" id="inputNome" class="form-control" value="{{ $user_paciente->paciente->nm_primario.' '.$user_paciente->paciente->nm_secundario }}" placeholder="Nome completo">
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputEmail">Email</label>
                                                    <input type="email" id="inputEmail" class="form-control" value="{{ $user_paciente->email }}" placeholder="Email">
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputTelefone">Telefone</label>
                                                    <input type="text" id="inputTelefone" class="form-control" value="{{ $user_paciente->paciente->contatos->first()->ds_contato }}" placeholder="Telefone">
                                                </div>
                                                <div class="form-group">
                                                    <label for="selectGenero">Gênero</label>
                                                    <select id="selectGenero" class="form-control">
                                                        <option @if( $user_paciente->paciente->cs_sexo == 'M') selected="selected" @endif>
                                                            Homem
                                                        </option>
                                                        <option @if( $user_paciente->paciente->cs_sexo == 'F') selected="selected" @endif>
                                                            Mulher
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="form-group fc-tit-dn">
                                                    <label>Data de Nascimento</label>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md-4">
                                                        <select id="selectDia" class="form-control">
                                                            @for($i = 1; $i < 32; $i++)
                                                                <option @if( $dt_nascimento[0] == $i) selected="selected" @endif >{{ sprintf("%02d", $i) }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <select id="selectMes" class="form-control">
                                                            @for($i = 1; $i <= 12; $i++)
                                                                <option @if( $dt_nascimento[1] == $i) selected="selected" @endif >{{ sprintf("%02d", $i) }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <select id="selectAno" class="form-control">
                                                            @for($i = date('Y'); $i >= (date('Y')-100); $i--)
                                                                <option @if( $dt_nascimento[2] == $i) selected="selected" @endif>{{ sprintf("%04d", $i) }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </form>
                                        <div>
                                            <a href="#" data-toggle="modal" data-target="#modalTermosUsers">Termo de Uso</a>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-lg-6 col-lista-dependentes">
                                        <span class="label-titulo">Dependentes</span>
                                        <div id="lista-dependentes" class="lista-dependentes">
                                            @for($i = 0; $i < sizeof($dependentes); $i++)
                                                <div class="dependente">
                                                    <div class="row">
                                                        <div class="col-md-10">
                                                            <span class="nm-dependente"><strong>Dep0{{ ($i+1) }}</strong>: {{ $dependentes[$i]->nm_primario.' '.$dependentes[$i]->nm_secundario }}</span>
                                                            <input type="hidden" id="dependente_{{ $dependentes[$i]->id }}" class="dependente_id" value="{{ $dependentes[$i]->id }}">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <a class="exclui-dependente"><i class="fa fa-trash"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endfor
                                            @if(sizeof($dependentes) == 0)
                                                <span id="lbl-no-dependents">NENHUM DEPENDENTE ENCONTRADO!</span>
                                            @endif
                                        </div>

                                        <button type="button" class="btn btn-light btn-add-dependente" data-toggle="modal" data-target="#modalAdicionaDependente">
                                            <i class="fa fa-plus"></i> Adicionar dependente
                                        </button>

                                        <!-- Modal adicionar dependente -->

                                        <div class="modal fade" id="modalAdicionaDependente" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-adiciona-dependente" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLongTitle">Novo
                                                            Dependente</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form>
                                                            <div class="form-group"></div>
                                                            <div class="form-row">
                                                                <label for="inputNomeDependente">Nome</label>
                                                                <input type="text" class="form-control" id="inputNomeDependente" placeholder="Nome *" maxlength="50">
                                                                <input type="hidden" id="inputPacienteId" value="{{ $user_paciente->paciente->id }}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="inputNomeDependente">Sobrenome</label>
                                                                <input type="text" class="form-control" id="inputNmSecundarioDependente" placeholder="Sobrenome *" maxlength="100">
                                                            </div>
                                                            <div class="form-row">
                                                                <div class="form-group col-md-4">
                                                                    <label for="tp_documento_dependente" class="control-label">Tipo
                                                                        Documento</label>
                                                                    <select id="tp_documento_dependente" class="form-control" name="tp_documento_dependente">
                                                                        <option value="RG">RG</option>
                                                                        <option value="CPF">CPF</option>
                                                                        <option value="CNASC">Certi. Nasc.</option>
                                                                        <option value="CTPS">Cart. Trabalho</option>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group col-md-8">
                                                                    <label for="te_documento_dependente" class="control-label">Nr.
                                                                        Documento</label>
                                                                    <input type="text" id="te_documento_dependente" class="form-control" name="te_documento_dependente" placeholder="Nr. Documento">
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-row">
                                                                <div class="col-md-4">
                                                                    <label for="selectGeneroDependente">Gênero</label>
                                                                    <select id="selectGeneroDependente" class="form-control">
                                                                        <option value="M">Masc.</option>
                                                                        <option value="F">Fem.</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-8">
                                                                    <label for="selectParentescoDependente">Parentesco</label>
                                                                    <select id="selectParentescoDependente" class="form-control">
                                                                        <option value="">Selecione</option>
                                                                        <option value="avo">Avô/Avó</option>
                                                                        <option value="conjuge">Cônjuge/Companheiro
                                                                        </option>
                                                                        <option value="enteado">Enteado(a)</option>
                                                                        <option value="filho">Filho(a)</option>
                                                                        <option value="irmao">Irmã(ão)</option>
                                                                        <option value="mae">Mãe</option>
                                                                        <option value="pai">Pai</option>
                                                                        <option value="outros">Outros</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group fc-tit-dn">
                                                                <label>Data de Nascimento</label>
                                                            </div>
                                                            <div class="form-row">
                                                                <div class="form-group col-md-4">
                                                                    <select id="selectDiaDependente" class="form-control">
                                                                        <option value="">Dia</option>
                                                                        @for($i = 1; $i <= 31; $i++)
                                                                            <option value="{{ sprintf('%02d', $i) }}">{{ sprintf('%02d', $i) }}</option>
                                                                        @endfor
                                                                    </select>
                                                                </div>
                                                                <div class="form-group col-md-4">
                                                                    <select id="selectMesDependente" class="form-control">
                                                                        <option value="">Mês</option>
                                                                        @for($i = 1; $i <= 12; $i++)
                                                                            <option value="{{ sprintf('%02d', $i) }}">{{ sprintf('%02d', $i) }}</option>
                                                                        @endfor
                                                                    </select>
                                                                </div>
                                                                <div class="form-group col-md-4">
                                                                    <select id="selectAnoDependente" class="form-control">
                                                                        <option value="">Ano</option>
                                                                        @for($i = date('Y'); $i >= (date('Y'))-110; $i--)
                                                                            <option value="{{ $i }}">{{ $i }}</option>
                                                                        @endfor
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-row">
                                                                <div class="col-md-12 text-center">
                                                                    <button type="button" id="btn-add-dependente" class="btn btn-vermelho" style="width: 200px;">
                                                                        <i class="fa fa-floppy-o"></i><span id="lbl-add-dependente" style="margin-left: 10px;"> Adicionar <i class="fa fa-spin fa-spinner" style="display: none; float: right; font-size: 16px;"></i></span>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="label-titulo">Alteração Cadastral</span>
                                        <p>Para alterar as informações do seu cadastro é necessário entrar em contato
                                            com a nossa central de atendimento pelo número: <strong>(61)
                                                3221-5350</strong></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Pagamento -->

                            <div class="tab-pane fade" id="v-pills-pagamento" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                                <div>
                                    <div class="row">
                                        <div class="col-md-12 col-lg-5">
                                            <span class="label-titulo">Cartões Salvos</span>
                                            <div class="box-pagamento lista-cartoes">
                                                @foreach($cartoes_paciente as $cartao)
                                                    <div class="cartao">
                                                        <div class="row">
                                                            <div class="col-md-10">
                                                                <div class="bandeira {{ strtolower($cartao->bandeira) }}"></div>
                                                                <p>
                                                                    <span class="numero-oculto">●●●● ●●●● ●●●● </span>
                                                                    <span class="final">{{ $cartao->numero }}</span>
                                                                </p>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <a class="exclui-cartao"><i class="fa fa-trash"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                                @if(sizeof($cartoes_paciente) == 0)
                                                    <span id="lbl-cartao-paciente">NENHUM CARTÃO ENCONTRADO!</span>
                                                @endif
                                            </div>
                                            <!-- <button type="button" class="btn btn-light btn-add-cartao" data-toggle="modal" data-target="#modalAdicionarCartao">
                                            	<i class="fa fa-plus"></i> Adicionar cartão
                                            </button> -->
                                            <div class="modal fade" id="modalAdicionarCartao" tabindex="-1" role="dialog" aria-labelledby="modalAdicionarCartao" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLongTitle">Adicionar
                                                                cartão</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form>
                                                                <div class="form-group row">
                                                                    <div class="col col-12 col-sm-6">
                                                                        <label for="inputNumeroCartaoDebito">Número do
                                                                            cartão</label>
                                                                        <input type="text" class="form-control input-numero-cartao" id="inputNumeroCartaoDebito" placeholder="Número do cartão">
                                                                    </div>
                                                                    <div class="col col-12 col-sm-6">
                                                                        <label for="inputNomeCartaoDebito">Nome impresso
                                                                            no cartão</label>
                                                                        <input type="email" class="form-control" id="inputNomeCartaoDebito" placeholder="Nome impresso no cartão">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <div class="col col-6 col-sm-6 col-md-3">
                                                                        <label for="selectValidadeMesDebito">Validade</label>
                                                                        <div class="button dropdown">
                                                                            <select class="form-control select-validade-mes" id="selectValidadeMesDebito">
                                                                                <option>Mês</option>
                                                                                <option>Janeiro</option>
                                                                                <option>Fevereiro</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col col-6 col-sm-6 col-md-3">
                                                                        <label class="label-validade-ano" for="selectValidadeAnoDebito">&nbsp;</label>
                                                                        <div class="button dropdown">
                                                                            <select class="form-control" id="selectValidadeAnoDebito">
                                                                                <option>Ano</option>
                                                                                <option>2018</option>
                                                                                <option>2019</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col col-12 col-sm-12 col-md-6 codigo-seguranca">
                                                                        <label for="inputCodigoDebito" class="label-codigo-seguranca">Código
                                                                            de segurança</label>
                                                                        <div class="area-codigo-seguranca">
                                                                            <input type="text" class="form-control" id="inputCodigoDebito" placeholder="000">
                                                                            <i class="fa fa-credit-card" data-toggle="tooltip" data-placement="top" title="Código de segurança ou CVV são os 3 dígitos eu ficam no verso do seu cartão."></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <button type="submit" class="btn btn-vermelho">Adicionar
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-lg-7">
                                            <span class="label-titulo">Últimas Transações</span>
                                            <div class="box-pagamento lista-transacoes">

                                                @foreach($agendamentos as $agendamento)
                                                    <div class="transacoes">
                                                        <div class="row">
                                                            <div class="col-sm-3 area-data">
                                                                <p class="dia">{{ date('d', strtotime($agendamento->getRawDtAtendimentoAttribute())) }}</p>
                                                                <p class="mes">{{ ucfirst(substr(strftime('%B', strtotime($agendamento->getRawDtAtendimentoAttribute())), 0, 3)) }}</p>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <p class="tipo">
                                                                    @if($agendamento->atendimento->consulta_id != null)
                                                                        Consulta:
                                                                    @elseif($agendamento->atendimento->procedimento_id != null)
                                                                        Exame:
                                                                    @endif
                                                                    {{ substr($agendamento->atendimento->ds_preco, 0, 14) }}
                                                                    @if(strlen($agendamento->atendimento->ds_preco > 14))
                                                                        ...
                                                                    @endif
                                                                </p>
                                                                <p class="clinica">
                                                                    {{ $agendamento->clinica->nm_fantasia }}
                                                                </p>
                                                                <p class="cartao-utilizado">
                                                                    @if($agendamento->itempedidos->first()->pedido->cartao_paciente == null)
                                                                        <span class="bandeira-extenso">-</span>
                                                                        <span class="numero-oculto">Nenhum Cartão informado</span>
                                                                        <span class="final">-</span>
                                                                    @else
                                                                        <span class="bandeira-extenso">{{ $agendamento->itempedidos->first()->pedido->cartao_paciente->bandeira }} </span>
                                                                        <span class="numero-oculto">●●●● ●●●● ●●●● </span>
                                                                        <span class="final">{{ $agendamento->itempedidos->first()->pedido->cartao_paciente->numero }}</span>
                                                                    @endif
                                                                </p>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <p class="valor">R$
                                                                    <span>{{ $agendamento->valor_total }}</span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                                @if(sizeof($agendamentos) == 0)
                                                    <span id="lbl-pedido-paciente">NENHUM PEDIDO ENCONTRADO!</span>
                                                @endif

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="v-pills-anuidade" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                                <div>
                                    <div class="area-tipo-plano">
                                        <div class="row">

                                            <div class="col-md-12">
                                                <span class="label-titulo">ANUIDADE</span>
                                                <p>Valor da anuidade 48,80.</p>
                                                <p>50% de desconto e 6 meses gratuitos para as pessoas que realizarem o cadastro na plataforma até 30.09.2018.</p>
                                                <p>No sétimo mês será cobrado o valor de 24,40.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="v-pills-notificacoes" role="tabpanel" aria-labelledby="v-pills-notifications-tab">
                                <div class="lista-notificacoes">
                                    <div class="accordion" id="accordionNotificacoes">
                                        <div class="card">
                                            <div class="card-header" id="headingTwo" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                <h5 class="mb-0">
                                                    <span class="titulo-notificacao">Você possui um cupom de desconto</span>
                                                    <span class="status-notificacao sn-nao-lido">Não lido</span>
                                                </h5>
                                            </div>
                                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                                                <div class="card-body">
                                                    <p class="titulo">Aproveite seu desconto!</p>
                                                    <p class="texto">Você possui um cupom que garante <strong>10% de
                                                            desconto</strong> no seu primeiro agendamento feito pelo
                                                        site Doutor Hoje. Aproveite, é por tempo limitado. Na tela de
                                                        finalização de compra insira o código: <strong>DOUTOR10</strong>
                                                    </p>
                                                    <button type="button" class="close-div btn btn-secondary" title="Apagar mensagem">
                                                        <i class="fa fa-trash" aria-hidden="true"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="opcoes-notificacao">
                                    <div class="tit-opcoes-notificacao">
                                        <span>Quais notificações deseja receber por E-mail?</span>
                                    </div>
                                    <div class="row">
                                        <div class="tit-opc-not col-9 col-md-9 col-lg-5">
                                            <label for="toggleLembrete"><strong>Lembrete</strong> de consulta ou
                                                exame</label>
                                        </div>
                                        <div class="col-3 col-md-3 col-lg-2">
                                            <input type="checkbox" id="toggleLembrete" data-plugin="switchery" data-color="#3bafda" data-size="small" checked>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="tit-opc-not col-9 col-md-9 col-lg-5">
                                            <label for="toggleConfirmacao"><strong>Confirmação</strong> de consulta
                                                ou
                                                exame</label>
                                        </div>
                                        <div class="col-3 col-md-3 col-lg-2">
                                            <input type="checkbox" id="toggleConfirmacao" data-plugin="switchery" data-color="#3bafda" data-size="small" checked>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="tit-opc-not col-9 col-md-9 col-lg-5">
                                            <label for="toggleCancelamento"><strong>Cancelamento</strong> de consulta ou
                                                exame</label>
                                        </div>
                                        <div class="col-3 col-md-3 col-lg-2">
                                            <input type="checkbox" id="toggleCancelamento" data-plugin="switchery" data-color="#3bafda" data-size="small" checked>

                                        </div>
                                    </div>
                                    <div class="tit-opcoes-notificacao tit-not-mob">
                                        <span>Quais notificações deseja receber no Celular?</span>
                                    </div>
                                    <div class="row">

                                        <div class="tit-opc-not col-9 col-md-9 col-lg-5">
                                            <label for="toggleLembreteMobile"><strong>Lembrete</strong> de consulta ou
                                                exame</label>
                                        </div>
                                        <div class="col-3 col-md-3 col-lg-2">
                                            <input type="checkbox" id="toggleLembreteMobile" data-plugin="switchery" data-color="#3bafda" data-size="small">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="tit-opc-not col-9 col-md-9 col-lg-5">
                                            <label for="toggleConfirmacaoMobile"><strong>Confirmação</strong> de
                                                consulta ou exame</label>
                                        </div>
                                        <div class="col-3 col-md-3 col-lg-2">
                                            <input type="checkbox" id="toggleConfirmacaoMobile" data-plugin="switchery" data-color="#3bafda" data-size="small" checked>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="tit-opc-not col-9 col-md-9 col-lg-5">
                                            <label for="toggleCancelamentoMobile"><strong>Cancelamento</strong> de
                                                consulta ou exame</label>
                                        </div>
                                        <div class="col-3 col-md-3 col-lg-2">
                                            <input type="checkbox" id="toggleCancelamentoMobile" data-plugin="switchery" data-color="#3bafda" data-size="small">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sugestões -->

                            <div class="tab-pane fade" id="v-pills-sugestoes" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                <div class="row">
                                    <div class="col-md-12 col-lg-6">
                                        <span class="label-titulo">Avalie o Doutor Hoje</span>
                                        <p>Deixe sua avaliação e nos ajude a deixar o Doutor Hoje melhor ainda.</p>
                                        <p>Dê uma nota de 1 a 5 estrelas para cada tópico abaixo, sendo 1 para
                                            péssimo e
                                            5 para excelente.</p>
                                        <div class="nota-avaliacao nota-servico">
                                            <p>Serviço</p>
                                            <span class="rating">
                                                <input type="radio" class="rating-input" id="servico-nota-5" name="rating-input-servico">
                                                <label for="servico-nota-5" class="rating-star"></label>
                                                <input type="radio" class="rating-input" id="servico-nota-4" name="rating-input-servico">
                                                <label for="servico-nota-4" class="rating-star"></label>
                                                <input type="radio" class="rating-input" id="servico-nota-3" name="rating-input-servico">
                                                <label for="servico-nota-3" class="rating-star"></label>
                                                <input type="radio" class="rating-input" id="servico-nota-2" name="rating-input-servico">
                                                <label for="servico-nota-2" class="rating-star"></label>
                                                <input type="radio" class="rating-input" id="servico-nota-1" name="rating-input-servico">
                                                <label for="servico-nota-1" class="rating-star"></label>
                                            </span>
                                        </div>
                                        <div class="nota-avaliacao nota-navegacao">
                                            <p>Navegação</p>
                                            <span class="rating">
                                                <input type="radio" class="rating-input" id="navegacao-nota-5" name="rating-input-navegacao">
                                                <label for="navegacao-nota-5" class="rating-star"></label>
                                                <input type="radio" class="rating-input" id="navegacao-nota-4" name="rating-input-navegacao">
                                                <label for="navegacao-nota-4" class="rating-star"></label>
                                                <input type="radio" class="rating-input" id="navegacao-nota-3" name="rating-input-navegacao">
                                                <label for="navegacao-nota-3" class="rating-star"></label>
                                                <input type="radio" class="rating-input" id="navegacao-nota-2" name="rating-input-navegacao">
                                                <label for="navegacao-nota-2" class="rating-star"></label>
                                                <input type="radio" class="rating-input" id="navegacao-nota-1" name="rating-input-navegacao">
                                                <label for="navegacao-nota-1" class="rating-star"></label>
                                            </span>
                                        </div>
                                        <div class="nota-avaliacao nota-organizacao">
                                            <p>Organização</p>
                                            <span class="rating">
                                                <input type="radio" class="rating-input" id="organizacao-nota-5" name="rating-input-organizacao">
                                                <label for="organizacao-nota-5" class="rating-star"></label>
                                                <input type="radio" class="rating-input" id="organizacao-nota-4" name="rating-input-organizacao">
                                                <label for="organizacao-nota-4" class="rating-star"></label>
                                                <input type="radio" class="rating-input" id="organizacao-nota-3" name="rating-input-organizacao">
                                                <label for="organizacao-nota-3" class="rating-star"></label>
                                                <input type="radio" class="rating-input" id="organizacao-nota-2" name="rating-input-organizacao">
                                                <label for="organizacao-nota-2" class="rating-star"></label>
                                                <input type="radio" class="rating-input" id="organizacao-nota-1" name="rating-input-organizacao">
                                                <label for="organizacao-nota-1" class="rating-star"></label>
                                            </span>
                                        </div>
                                        <div class="nota-avaliacao nota-facilidade">
                                            <p>Facilidade</p>
                                            <span class="rating">
                                                <input type="radio" class="rating-input" id="facilidade-nota-5" name="rating-input-facilidade">
                                                <label for="facilidade-nota-5" class="rating-star"></label>
                                                <input type="radio" class="rating-input" id="facilidade-nota-4" name="rating-input-facilidade">
                                                <label for="facilidade-nota-4" class="rating-star"></label>
                                                <input type="radio" class="rating-input" id="facilidade-nota-3" name="rating-input-facilidade">
                                                <label for="facilidade-nota-3" class="rating-star"></label>
                                                <input type="radio" class="rating-input" id="facilidade-nota-2" name="rating-input-facilidade">
                                                <label for="facilidade-nota-2" class="rating-star"></label>
                                                <input type="radio" class="rating-input" id="facilidade-nota-1" name="rating-input-facilidade">
                                                <label for="facilidade-nota-1" class="rating-star"></label>
                                            </span>
                                        </div>
                                        <div class="nota-avaliacao nota-atendimento">
                                            <p>Rede de Atendimento</p>
                                            <span class="rating">
                                                <input type="radio" class="rating-input" id="atendimento-nota-5" name="rating-input-atendimento">
                                                <label for="atendimento-nota-5" class="rating-star"></label>
                                                <input type="radio" class="rating-input" id="atendimento-nota-4" name="rating-input-atendimento">
                                                <label for="atendimento-nota-4" class="rating-star"></label>
                                                <input type="radio" class="rating-input" id="atendimento-nota-3" name="rating-input-atendimento">
                                                <label for="atendimento-nota-3" class="rating-star"></label>
                                                <input type="radio" class="rating-input" id="atendimento-nota-2" name="rating-input-atendimento">
                                                <label for="atendimento-nota-2" class="rating-star"></label>
                                                <input type="radio" class="rating-input" id="atendimento-nota-1" name="rating-input-atendimento">
                                                <label for="atendimento-nota-1" class="rating-star"></label>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-lg-6">
                                        <span class="label-titulo">Mais alguma sugestão?</span>
                                        <p>Deixe sua avaliação e nos ajude a deixar o Doutor Hoje melhor ainda.</p>
                                        <form>
                                            <div class="form-group">
                                                <textarea class="form-control" id="textareaSugestao" rows="6"></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-vermelho">Enviar</button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Sair -->

                            <div class="tab-pane fade" id="v-pills-sair" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                <div class="itens-sair">
                                    <span class="label-titulo">Obrigado por usar o Doutor Hoje</span>
                                </div>
                                <div class="itens-sair">
                                    <img src="/libs/home-template/img/doctor-hoje-notebook.png" alt="">
                                </div>
                                <div class="itens-sair">
                                    <span>Você tem certeza que deseja sair?</span>
                                    <div class="btns-sair">
                                        <button type="button" class="btn btn-light" onclick="window.location.href='{{ route("logout") }}'">
                                            Sim
                                        </button>
                                        <button type="submit" class="btn btn-vermelho">Não</button>
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
            $(document).ready(function () {
                var laravel_token = '{{ csrf_token() }}';
                var resizefunc = [];

                $('#btn-add-dependente').click(function () {

                    var result = true;
                    var nm_primario_dependente = $('#inputNomeDependente');

                    if (nm_primario_dependente.val().length == 0) {
                        nm_primario_dependente.parent().addClass('cvx-has-error');
                        nm_primario_dependente.parent().append('<span class="help-block text-danger"><strong>Campo Obrigatório</strong></span>');

                        $('#inputNomeDependente').keypress(function () {
                            $(this).parent().removeClass('cvx-has-error');
                            $(this).parent().find('span.help-block').remove();
                        });

                        result = false;
                    }

                    var nm_secundario_dependente = $('#inputNmSecundarioDependente');

                    if (nm_secundario_dependente.val().length == 0) {
                        nm_secundario_dependente.parent().addClass('cvx-has-error');
                        nm_secundario_dependente.parent().append('<span class="help-block text-danger"><strong>Campo Obrigatório</strong></span>');

                        $('#inputNmSecundarioDependente').keypress(function () {
                            $(this).parent().removeClass('cvx-has-error');
                            $(this).parent().find('span.help-block').remove();
                        });

                        result = false;
                    }

                    var tp_documento = $('#tp_documento_dependente');

                    if (tp_documento.val() == '') {
                        tp_documento.parent().addClass('cvx-has-error');
                        tp_documento.parent().append('<span class="help-block text-danger"><strong>Campo Obrigatório</strong></span>');

                        $('#tp_documento_dependente').keypress(function () {
                            $(this).parent().removeClass('cvx-has-error');
                            $(this).parent().find('span.help-block').remove();
                        });

                        result = false;
                    }

                    var nr_documento = $('#te_documento_dependente');

                    if (nr_documento.val().length == 0) {
                        nr_documento.parent().addClass('cvx-has-error');
                        nr_documento.parent().append('<span class="help-block text-danger"><strong>Campo Obrigatório</strong></span>');

                        $('#te_documento_dependente').keypress(function () {
                            $(this).parent().removeClass('cvx-has-error');
                            $(this).parent().find('span.help-block').remove();
                        });

                        result = false;
                    }

                    var sexo_dependente = $('#selectGeneroDependente');

                    if (sexo_dependente.selectedIndex == 0) {
                        sexo_dependente.parent().addClass('cvx-has-error');
                        sexo_dependente.parent().append('<span class="help-block text-danger"><strong>Campo Obrigatório</strong></span>');

                        $('#selectGeneroDependente').keypress(function () {
                            $(this).parent().removeClass('cvx-has-error');
                            $(this).parent().find('span.help-block').remove();
                        });

                        result = false;
                    }

                    var dt_nasc_dia_dependente = $('#selectDiaDependente');

                    if (dt_nasc_dia_dependente.val() == '') {
                        dt_nasc_dia_dependente.parent().addClass('cvx-has-error');
                        dt_nasc_dia_dependente.parent().append('<span class="help-block text-danger"><strong>Campo Obrigatório</strong></span>');

                        $('#selectDiaDependente').keypress(function () {
                            $(this).parent().removeClass('cvx-has-error');
                            $(this).parent().find('span.help-block').remove();
                        });

                        result = false;
                    }

                    var dt_nasc_mes_dependente = $('#selectMesDependente');

                    if (dt_nasc_mes_dependente.val() == '') {
                        dt_nasc_mes_dependente.parent().addClass('cvx-has-error');
                        dt_nasc_mes_dependente.parent().append('<span class="help-block text-danger"><strong>Campo Obrigatório</strong></span>');

                        $('#selectMesDependente').keypress(function () {
                            $(this).parent().removeClass('cvx-has-error');
                            $(this).parent().find('span.help-block').remove();
                        });

                        result = false;
                    }

                    var dt_nasc_ano_dependente = $('#selectAnoDependente');

                    if (dt_nasc_ano_dependente.val() == '') {
                        dt_nasc_ano_dependente.parent().addClass('cvx-has-error');
                        dt_nasc_ano_dependente.parent().append('<span class="help-block text-danger"><strong>Campo Obrigatório</strong></span>');

                        $('#selectAnoDependente').keypress(function () {
                            $(this).parent().removeClass('cvx-has-error');
                            $(this).parent().find('span.help-block').remove();
                        });

                        result = false;
                    }

                    var parentesco_dependente = $('#selectParentescoDependente');

                    if (parentesco_dependente.val() == '') {
                        parentesco_dependente.parent().addClass('cvx-has-error');
                        parentesco_dependente.parent().append('<span class="help-block text-danger"><strong>Campo Obrigatório</strong></span>');

                        $('#selectParentescoDependente').keypress(function () {
                            $(this).parent().removeClass('cvx-has-error');
                            $(this).parent().find('span.help-block').remove();
                        });

                        result = false;
                    }

                    if (!result) {
                        return false;
                    }

                    $('#btn-add-dependente').attr('disabled', 'disabled')
                    $('#lbl-add-dependente').html('Enviando <i class="fa fa-spin fa-spinner" style="display: inline-block; float: right; font-size: 16px;"></i>');

                    var nome_dep = nm_primario_dependente.val();
                    var sobrenome_dep = nm_secundario_dependente.val();
                    var tp_documento_dep = tp_documento.val();
                    var nr_documento_dep = nr_documento.val();
                    var sexo_dep = sexo_dependente.val();
                    var parentesco_dep = parentesco_dependente.val();
                    var dia_nasc_dep = dt_nasc_dia_dependente.val();
                    var mes_nasc_dep = dt_nasc_mes_dependente.val();
                    var ano_nasc_dep = dt_nasc_ano_dependente.val();
                    var paciente_id = $('#inputPacienteId').val();

                    jQuery.ajax({
                        type: 'POST',
                        url: '/add-dependente',
                        data: {
                            'nome': nome_dep,
                            'sobrenome': sobrenome_dep,
                            'tp_documento': tp_documento_dep,
                            'nr_documento': nr_documento_dep,
                            'sexo': sexo_dep,
                            'parentesco': parentesco_dep,
                            'dia_nasc': dia_nasc_dep,
                            'mes_nasc': mes_nasc_dep,
                            'ano_nasc': ano_nasc_dep,
                            'paciente_id': paciente_id,
                            '_token': laravel_token
                        },
                        success: function (result) {

                            if (result.status) {

                                var dependente = JSON.parse(result.dependente);
                                $('#lbl-no-dependents').empty();
                                var index = ($('#lista-dependentes').find('div.dependente').length) + 1;

                                var content = '<div class="dependente"> \
                                  <div class="row"> \
	                                  <div class="col-md-10"> \
	                                      <span class="nm-dependente"><strong>Dep0' + index + '</strong>: ' + dependente.nm_primario + ' ' + dependente.nm_secundario + '</span> \
	                                      <input type="hidden" id="dependente_' + dependente.id + '" class="dependente_id" value="' + dependente.id + '"> \
	                                  </div> \
	                                  <div class="col-md-2"> \
	                                  	  <a class="exclui-dependente"><i class="fa fa-trash"></i></a> \
	                                  </div> \
	                              </div> \
	                          	</div>';

                                $('#modalAdicionaDependente').find('input.form-control').val('');
                                $('#modalAdicionaDependente').find('select.form-control').prop('selectedIndex', 0);

                                $('#modalAdicionaDependente').modal('toggle');
                                $('#lista-dependentes').append(content);

                                $.Notification.notify('success', 'top right', 'DrHoje', result.mensagem);
                            }

                            $('#btn-add-dependente').removeAttr('disabled')
                            $('#lbl-add-dependente').html('Adicionar <i class="fa fa-spin fa-spinner" style="display: none; float: right; font-size: 16px;"></i>');
                        },
                        error: function (result) {
                            $('#btn-add-dependente').removeAttr('disabled')
                            $('#lbl-add-dependente').html('Adicionar <i class="fa fa-spin fa-spinner" style="display: none; float: right; font-size: 16px;"></i>');
                            $.Notification.notify('error', 'top right', 'DrHoje', 'Falha na operação!');
                        }
                    });

                });
            });

            /*********************************
             *
             * REMOVE PRODUTO DEPENDENTE E CARTÃO
             *
             *********************************/
            $(".exclui-dependente").on("click", function (event) {
                event.preventDefault();
                var element = $(this);
                if (confirm("Tem certeza que deseja excluir esse dependente?")) {

                    var paciente_id = $(element).parent().parent().find('.dependente_id').val();

                    jQuery.ajax({
                        type: 'POST',
                        url: '/delete-dependente',
                        data: {
                            'paciente_id': paciente_id,
                            '_token': laravel_token
                        },
                        success: function (result) {

                            var dependente = JSON.parse(result.dependente);
                            var num_dependentes = result.num_dependentes;

                            if (result.status) {

                                $(element).parent().parent().parent().remove();

                                for (var i = 0; i < num_dependentes; i++) {
                                    var index = i + 1;
                                    $('#lista-dependentes').find('div.dependente:eq(' + i + ')').find('span.nm-dependente').find('strong').html('Dep0' + index);
                                    //$('#lista-dependentes').find('div.dependente:eq(0)').find('span.nm-dependente').find('strong').html()
                                    //alert($('#lista-dependentes').find('div.dependente:eq('+i+')').find('span.nm-dependente').find('strong').html());
                                }
                                $.Notification.notify('success', 'top right', 'DrHoje', result.mensagem);
                            }
                        },
                        error: function (result) {
                            $.Notification.notify('error', 'top right', 'DrHoje', 'Falha na operação!');
                        }
                    });
                }
            });

            $(".exclui-cartao").on("click", function (event) {
                event.preventDefault();
                if (confirm("Tem certeza que deseja excluir esse cartão?")) {
                    $(this).parent().parent().parent().remove();
                }
            });

            /*********************************
             *
             * ATIVA TOOLTIP
             *
             *********************************/

            $(function () {
                $('[data-toggle="tooltip"]').tooltip()
            })

            /*********************************
             *
             * APAGA MENSAGEM
             *
             *********************************/

            $(".close-div").on("click", function (event) {
                event.preventDefault();
                if (confirm("Tem certeza que deseja apagar essa mensagem?")) {
                    $(this).parent().parent().parent().remove();
                }
            });

            /*********************************
             *
             * SWITCHERY
             *
             *********************************/

            /*var elem = document.querySelector('.email-lembrete');
            var init = new Switchery(elem, {
                size: 'small', // small, default, large
            });
            var elem = document.querySelector('.email-confirmacao');
            var init = new Switchery(elem, {
                size: 'small', // small, default, large
            });
            var elem = document.querySelector('.email-cancelamento');
            var init = new Switchery(elem, {
                size: 'small', // small, default, large
            });

            var elem = document.querySelector('.mobile-lembrete');
            var init = new Switchery(elem, {
                size: 'small', // small, default, large
            });
            var elem = document.querySelector('.mobile-confirmacao');
            var init = new Switchery(elem, {
                size: 'small', // small, default, large
            });
            var elem = document.querySelector('.mobile-cancelamento');
            var init = new Switchery(elem, {
                size: 'small', // small, default, large
            });*/
        </script>

    @endpush

@endsection