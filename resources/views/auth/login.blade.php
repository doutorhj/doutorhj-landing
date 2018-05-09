@extends('layouts.base')

@section('title', 'DoutorHJ: Login')

@push('scripts')

@endpush

@section('content')
    <section class="login">
        <div class="container">
            <div class="area-container">
                <div class="titulo">
                    <strong>Seu Cadastro</strong>
                    <p>Se você já tem cadastro conosco, faça o login para avançar, ou realize o seu cadastro.</p>
                </div>
                <div class="row">
                    <div class="col-md-12 col-lg-6">
                        <div class="card card-formulario c-login">
                            <div class="card-header">
                                Já é cadastrado?
                            </div>
                            <div class="card-body">
                                <span class="card-span">E-mail ou Celular obrigatórios para o login.</span>
                                <h5 class="card-title">Dados de acesso</h5>
                                <form action="{{ route('login') }}" method="post">

                                    {{ csrf_field() }}

                                    <div class="form-group row area-label btn-send-token">
                                        <label for="inputEmailTelefone" class="col-sm-12">E-mail ou Celular</label>
                                    </div>
                                    <div class="form-group row btn-send-token">
                                        <div class="col col-lg-7 col-xl-8">
                                            <input type="text" id="inputEmailTelefone"
                                                   class="form-control mascaraTelefone" placeholder="E-mail ou Celular">
                                        </div>
                                        <div class="col col-lg-5 col-xl-4">
                                            <button type="button" id="btn-send-token" class="btn btn-vermelho"><i class="fa fa-key"></i> Enviar Token
                                            </button>
                                        </div>
                                    </div>
                                    <div class="form-group row area-label btn-login-token">
                                        <label for="inputToken" class="col-sm-12 ">Token de Acesso</label>
                                    </div>
                                    <div class="form-group row btn-login-token">
                                        <div class="col col-lg-7 col-xl-8">
                                            <input type="text" id="inputToken" class="form-control" name="cvx_token" placeholder="Token de Acesso">
                                            <input type="hidden" id="input_hidden_EmailTelefone" name="cvx_telefone">
                                        </div>
                                        <div class="col col-lg-5 col-xl-4">
                                            <button type="submit" id="btn-login-token" class="btn btn-vermelho">
                                            	<i class="fa fa-arrow-right"></i>
                                            	Acessar Conta
                                            </button>
                                        </div>
                                    </div>
                                    <div class="form-group links-login">
                                        <a href="">Esqueci meu login</a> | <a onclick="$('.btn-send-token').show(); $('.btn-login-token').hide();">Reenviar Token</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-6">
                        <div class="card card-formulario">
                            <div class="card-header">
                                Ainda não tem cadastro?
                            </div>
                            <div class="card-body">
                                <span class="card-span">Cadastre-se para obter acesso e continuar.</span>
                                <h5 class="card-title">Dados cadastrais</h5>

                                <form class="form-horizontal " action="{{ route('registrar') }}" method="post" onsubmit="return validaRegistrar()">

                                    {{ csrf_field() }}

                                    <div class="form-group row area-label {{ $errors->has('nm_primario') | $errors->has('nm_secundario') ? ' cvx-has-error' : '' }}">
                                        <label for="inputNome" class="col col-sm-12">Nome/Sobrenome</label>
                                    </div>
                                    <div class="form-group row {{ $errors->has('nm_primario') ? ' cvx-has-error' : '' }}">
                                        <div class="col col-sm-5">
                                            <input type="text" id="inputNome" class="form-control" name="nm_primario" value="{{ old('nm_primario') }}" placeholder="Nome" required="required">
                                            @if ($errors->has('nm_primario'))
                                                <span class="help-block"><strong>{{ $errors->first('nm_primario') }}</strong></span>
                                            @endif
                                        </div>
                                        <div class="col col-sm-7">
                                            <input type="text" id="inputSobrenome" class="form-control" name="nm_secundario" value="{{ old('nm_secundario') }}" placeholder="Sobrenome" required="required">
                                            @if ($errors->has('nm_secundario'))
                                                <span class="help-block"> <strong>{{ $errors->first('nm_secundario') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group row area-label {{ $errors->has('email') ? ' cvx-has-error' : '' }}">
                                        <label for="inputEmail" class="col col-sm-12">E-mail</label>
                                    </div>
                                    <div class="form-group row {{ $errors->has('email') ? ' cvx-has-error' : '' }}">
                                        <div class="col col-sm-12">
                                            <input type="email" id="inputEmail" class="form-control" name="email" value="{{ old('email') }}" placeholder="E-mail" required="required">
                                            @if ($errors->has('email'))
                                                <span class="help-block"> <strong>{{ $errors->first('email') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col col-sm-6">
                                            <label for="inputSexo">Sexo</label>
                                            <select id="cs_sexo" class="form-control" name="cs_sexo" required="required">
                                                <option value="">Sexo</option>
                                                <option value="M" @if( old('cs_sexo') == 'M' ) selected="selected" @endif >Masculino</option>
                                                <option value="F" @if( old('cs_sexo') == 'F' ) selected="selected" @endif>Feminino</option>
                                            </select>
                                        </div>
                                        <div class="col col-sm-6 {{ $errors->has('dt_nascimento') ? ' cvx-has-error' : '' }}">
                                            <label for="inputTelefone">Data de Nascimento</label>
                                            <input type="text" id="inputNascimento" class="form-control mascaraData" name="dt_nascimento" value="{{ old('dt_nascimento') }}" placeholder="Data de Nascimento" required="required">
                                            @if ($errors->has('dt_nascimento'))
                                                <span class="help-block"><strong>{{ $errors->first('dt_nascimento') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col col-sm-6 {{ $errors->has('te_documento') ? ' cvx-has-error' : '' }}">
                                            <label for="inputCPF">CPF</label>
                                            <input type="text" id="inputCPF" class="form-control mascaraCPF" name="te_documento" value="{{ old('te_documento') }}" placeholder="CPF" required="required">
                                            @if ($errors->has('te_documento'))
                                                <span class="help-block"><strong>{{ $errors->first('te_documento') }}</strong></span>
                                            @endif
                                        </div>
                                        <div class="col col-sm-6 {{ $errors->has('ds_contato') ? ' cvx-has-error' : '' }}">
                                            <label for="inputCelular">Celular</label>
                                            <input type="text" id="inputCelular" class="form-control mascaraTelefone" name="ds_contato" value="{{ old('ds_contato') }}" placeholder="Celular" required="required">
                                            @if ($errors->has('ds_contato'))
                                                <span class="help-block"><strong>{{ $errors->first('ds_contato') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-check fc-checkbox">
                                        <input type="checkbox" class="form-check-input" id="termoCheck" required="required">
                                        <label class="form-check-label" for="termoCheck">Declaro que li e concordo com os <a href="#">termos de uso do Doctor Hoje</a></label>
                                    </div>
                                    <button type="submit" id="btn-criar-conta" class="btn btn-vermelho btn-criar-conta"><i class="fa fa-user"></i> <span id="lbl-criar-conta">Criar conta <i class="fa fa-spin fa-spinner" style="display: none; float: right; font-size: 16px;"></i></span></button>
                                </form>
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

                $('.btn-login-token').hide();

                $('#btn-send-token').click(function () {
                    if ($('#inputEmailTelefone').val() == '') {
                        return false;
                    }

                    var ds_contato = $('#inputEmailTelefone').val();
                    $('#input_hidden_EmailTelefone').val(ds_contato);

                    jQuery.ajax({
                        type: 'POST',
                        url: "{{ route('enviar_token') }}",
                        data: {
                            'ds_contato': ds_contato,
                            '_token': laravel_token
                        },
                        success: function (result) {

                            if (result != null) {
                                $.Notification.notify('success', 'top right', 'DrHoje', 'Seu TOKEN foi enviado via SMS com sucesso!');

                                $('.btn-send-token').hide();
                                $('.btn-login-token').show();

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
                                $('#sg_logradouro').prop('selectedIndex', 0);
                                $('#nr_latitude_gps').val('');
                                $('#nr_longitute_gps').val('');
                            }
                        },
                        error: function (result) {
                            $.Notification.notify('error', 'top right', 'DrHoje', 'Falha na operação!');
                        }
                    });
                });

                /*********************************
                 *
                 * CALENDARIO
                 *
                 *********************************/

                jQuery.datetimepicker.setLocale('pt-BR');

                /* jQuery('#inputNascimento').datetimepicker({
                    timepicker:false,
                    format:'d.m.Y',
                }); */
            });

            function validaRegistrar() {
            	
            	if( $('#inputNome').val().length == 0 ) return false;
                if( $('#inputSobrenome').val().length == 0 ) return false;
                if( $('#inputEmail').val().length == 0 ) return false;
                if( $('#cs_sexo').val().length == 0 ) return false;
                if( $('#inputNascimento').val().length == 0 ) return false;	
                if( $('#inputCPF').val().length == 0 ) return false;
                if( $('#inputCelular').val().length == 0 ) return false;

                $('#btn-criar-conta').attr('disabled', 'disabled');
                $('#btn-criar-conta').find('#lbl-criar-conta').html('Processando... <i class="fa fa-spin fa-spinner" style="float: right; font-size: 16px;"></i>');
                setTimeout(function(){ $('#btn-criar-conta').find('#lbl-criar-conta').html('Criar conta <i class="fa fa-spin fa-spinner" style="display: none; float: right; font-size: 16px;"></i>'); $('#btn-criar-conta').removeAttr('disabled'); }, 30000);

                return true;
            }
            
        </script>
    @endpush

@endsection
