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
                                <span class="card-span">Celular obrigatório para o login.</span>
                                <h5 class="card-title">Dados de acesso</h5>
                                <form action="{{ route('login') }}" method="post" onsubmit="return validaCriarConta()">

                                    {{ csrf_field() }}

                                    <div class="form-group row area-label btn-send-token">
                                        <label for="inputEmailTelefone" class="col-sm-12">Celular</label>
                                    </div>
                                    <div class="form-group row btn-send-token">
                                        <div class="col col-lg-6 col-xl-7">
                                            <input type="text" id="inputEmailTelefone" class="form-control mascaraTelefone" placeholder="Número do Celular">
                                        </div>
                                        <div class="col col-lg-6 col-xl-5">
                                            <button type="button" id="btn-send-token" class="btn btn-vermelho">
                                                <i class="fa fa-key"></i>
                                                <span id="lbl-enviar-token">Enviar Token <i class="fa fa-spin fa-spinner" style="display: none; font-size: 14px; margin-left: 5px;"></i></span>
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
                                        <a href="">Esqueci meu login</a> |
                                        <a onclick="$('.btn-send-token').show(); $('.btn-login-token').hide();">Reenviar
                                            Token</a>
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

                                    <div class="form-group row ">
                                        <div class="col col-sm-6 {{ $errors->has('nm_primario') ? ' cvx-has-error' : '' }}">
                                            <label for="inputNome">Nome</label>
                                            <input type="text" id="inputNome" class="form-control" name="nm_primario" value="{{ old('nm_primario') }}" placeholder="Nome" required="required">
                                            @if ($errors->has('nm_primario'))
                                                <span class="help-block"><strong>{{ $errors->first('nm_primario') }}</strong></span>
                                            @endif
                                        </div>
                                        <div class="col col-sm-6 {{ $errors->has('nm_secundario') ? ' cvx-has-error' : '' }}">
                                            <label for="inputSobrenome">Sobrenome</label>
                                            <input type="text" id="inputSobrenome" class="form-control" name="nm_secundario" value="{{ old('nm_secundario') }}" placeholder="Sobrenome" required="required">
                                            @if ($errors->has('nm_secundario'))
                                                <span class="help-block"> <strong>{{ $errors->first('nm_secundario') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <div class="col col-sm-6 {{ $errors->has('te_documento') ? ' cvx-has-error' : '' }}">
                                            <label for="inputCPF">CPF</label>
                                            <input type="text" id="inputCPF" class="form-control mascaraCPF" name="te_documento" value="{{ old('te_documento') }}" placeholder="CPF" required="required">
                                            @if ($errors->has('te_documento'))
                                                <span class="help-block"><strong>{{ $errors->first('te_documento') }}</strong></span>
                                            @endif
                                        </div>
                                        <div class="col col-sm-6 {{ $errors->has('email') ? ' cvx-has-error' : '' }}">
                                            <label for="inputEmail">E-mail</label>
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
                                                <option value="M" @if( old('cs_sexo') == 'M' ) selected="selected" @endif >
                                                    Masculino
                                                </option>
                                                <option value="F" @if( old('cs_sexo') == 'F' ) selected="selected" @endif>
                                                    Feminino
                                                </option>
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
                                        <div class="col col-sm-6 {{ $errors->has('ds_contato') ? ' cvx-has-error' : '' }}">
                                            <label for="inputCelular">Celular</label>
                                            <input type="text" id="inputCelular" class="form-control mascaraTelefone" name="ds_contato" value="{{ old('ds_contato') }}" placeholder="Celular" required="required">
                                            @if ($errors->has('ds_contato'))
                                                <span class="help-block"><strong>{{ $errors->first('ds_contato') }}</strong></span>
                                            @endif
                                        </div>
                                        <div class="col col-sm-6 {{ $errors->has('ds_contato_confirmation') ? ' cvx-has-error' : '' }}">
                                            <label for="inputCelularConfirma">Confirme o Celular</label>
                                            <input type="text" id="inputCelularConfirma" class="form-control mascaraTelefone" name="ds_contato_confirmation" value="{{ old('ds_contato_confirmation') }}" placeholder="Confirme o Celular" required="required">
                                            @if ($errors->has('ds_contato_confirm'))
                                                <span class="help-block"><strong>{{ $errors->first('ds_contato_confirmation') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-check fc-checkbox">
                                        <input type="checkbox" class="form-check-input" id="termoCheck" required="required">
                                        <label class="form-check-label" for="termoCheck">Declaro que li e concordo com
                                            os <a href="#" data-toggle="modal" data-target="#modalTermos">termos de uso
                                                do Doutor Hoje</a></label>
                                    </div>
                                    {{--<div class="form-check fc-checkbox">
                                        <input type="checkbox" class="form-check-input" id="termoAnuidade" required="required">
                                        <label class="form-check-label" for="termoAnuidade">Declaro que estou ciente e
                                            de acordo com a cobrança da anuidade, que me possibilitará utilizar a
                                            plataforma digital para agendamento de consultas e exames, no valor de 48,80
                                            (quarenta e oito reais e oitenta centavos) sendo que aderindo até
                                            30.09.2018, terei 50% de desconto e 180 dias de gratuidade, assim, pagarei
                                            24,40 ( vinte e quatro reais e quarenta centavos) no sétimo mês contados da
                                            data da minha inclusão na plataforma. Após 12 meses contados da data da
                                            minha inclusão pagarei o valor de 48,80 referente a anuidade. </label>
                                    </div>--}}
                                    
                                    <button type="submit" id="btn-criar-conta" class="btn btn-vermelho btn-criar-conta">
                                        <i class="fa fa-user"></i>
                                        <span id="lbl-criar-conta">Criar conta <i class="fa fa-spin fa-spinner" style="display: none; font-size: 14px; margin-left: 5px;"></i></span>
                                    </button>
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
                    if ($('#inputEmailTelefone').val().length < 15) {
                        swal(
                            {
                                title: '<div class="tit-sweet tit-error"><i class="fa fa-times-circle" aria-hidden="true"></i> DoutorHoje Informa:</div>',
                                text: 'O telefone informado não é válido'
                            }
                        );
                        return false;
                    }

                    var ds_contato = $('#inputEmailTelefone').val();
                    $('#input_hidden_EmailTelefone').val(ds_contato);

                    $('#btn-send-token').attr('disabled', 'disabled');
                    $('#btn-send-token').find('#lbl-enviar-token').html('Processando... <i class="fa fa-spin fa-spinner" style="display: inline-block; font-size: 14px; margin-left: 5px;"></i>');
                    setTimeout(function () {
                        $('#btn-send-token').find('#lbl-enviar-token').html('Enviar Token <i class="fa fa-spin fa-spinner" style="display: none; font-size: 14px; margin-left: 5px;"></i>');
                        $('#btn-send-token').removeAttr('disabled');
                    }, 30000);

                    jQuery.ajax({
                        type: 'POST',
                        url: "{{ route('enviar_token') }}",
                        data: {
                            'ds_contato': ds_contato,
                            '_token': laravel_token
                        },
                        success: function (result) {

                            if (result.status) {
                                $.Notification.notify('success', 'top right', 'DrHoje', result.mensagem);

                                $('.btn-send-token').hide();
                                $('.btn-login-token').show();

                            } else {

                                swal(
                                    {
                                        title: '<div class="tit-sweet tit-error"><i class="fa fa-times-circle" aria-hidden="true"></i> DoutorHoje Informa:</div>',
                                        text: result.mensagem
                                    }
                                );
                            }

                            $('#btn-send-token').find('#lbl-enviar-token').html('Enviar Token <i class="fa fa-spin fa-spinner" style="display: none; float: right; font-size: 16px;"></i>');
                            $('#btn-send-token').removeAttr('disabled');
                        },
                        error: function (result) {
                            $.Notification.notify('error', 'top right', 'DrHoje', 'Falha na operação!');
                            $('#btn-send-token').find('#lbl-enviar-token').html('Enviar Token <i class="fa fa-spin fa-spinner" style="display: none; float: right; font-size: 16px;"></i>');
                            $('#btn-send-token').removeAttr('disabled');
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

                if ($('#inputNome').val().length == 0) return false;
                if ($('#inputSobrenome').val().length == 0) return false;
                if ($('#inputEmail').val().length == 0) return false;
                if ($('#cs_sexo').val().length == 0) return false;
                if ($('#inputNascimento').val().length == 0) return false;
                if ($('#inputCPF').val().length == 0) return false;
                if ($('#inputCelular').val().length == 0) return false;

                $('#btn-criar-conta').attr('disabled', 'disabled');
                $('#btn-criar-conta').find('#lbl-criar-conta').html('Processando... <i class="fa fa-spin fa-spinner" style="float: right; font-size: 16px;"></i>');
                setTimeout(function () {
                    $('#btn-criar-conta').find('#lbl-criar-conta').html('Criar conta <i class="fa fa-spin fa-spinner" style="display: none; float: right; font-size: 16px;"></i>');
                    $('#btn-criar-conta').removeAttr('disabled');
                }, 30000);

                return true;
            }

            function validaCriarConta() {

                if ($('#inputEmailTelefone').val().length == 0) return false;
                if ($('#input_hidden_EmailTelefone').val().length == 0) return false;
                if ($('#inputToken').val().length == 0) return false;

                return true;
            }

        </script>
    @endpush

@endsection
