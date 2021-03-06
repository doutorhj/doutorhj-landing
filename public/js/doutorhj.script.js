$(document).ready(function () {


	/* VALIDA CARTAO INSERIDO QUANDO A BIN FOR IGUAL A DA CAIXA ECONOMICA*/

		$('.inputNumeroCartaoCredito').keydown(function() {
			var cartao = $(this).val();
			var cupom = $('#inputCupom').val();

			validarCupoCartao(cartao, cupom);
		})
		$('#inputNumeroCartaoCredito').keydown(function() {
			var cartao = $(this).val();
			var cupom = $('#inputCupom').val();

			validarCupoCartao(cartao, cupom);
		})




	/* VALIDA CARTAO INSERIDO QUANDO A BIN FOR IGUAL A DA CAIXA ECONOMICA*/
	try{
		if(endereco !=""){
			$('.campos_endereco').slideDown();
			$('.registrarEndereco').hide();
			$('.deletar').show();
			$('#cep').attr('disabled',true)
			$('#Rua').attr('disabled',true)
			$('#Numero').attr('disabled',true)
		}else{
			$('#cep').attr('disabled',false)
			$('#Rua').attr('disabled',false)
			$('#Numero').attr('disabled',false)
			$('.deletar').hide();
			$('.campos_endereco').hide();
			$(".dados").attr("disabled", "disabled");
			
		}
		
	}catch(error){

	}


	$('#cep').keyup(function(){
		
		var efetuar = "";
		var cep = $(this).val();
		var res =cep.replace(/[^\d]+/g,'')

		if(res.length ==8){
			if(efetuar==0){
				$(this).addClass( "loading" );
				$('.cep-user').prop('disabled', true);
				efetuar=1;
				jQuery.ajax({
					type: 'GET',
					url: '/consulta-cep/cep/'+res ,
				 
					  
					success: function (result) {

						try {
							
								if(  JSON.parse(result.endereco).logradouro == undefined){
									$('.registrarEndereco').hide();
									$('.cep-user').prop('disabled', false);
									$('.cep-user').removeClass( "loading" );
									$.Notification.notify('error','top right', 'DrHoje', 'Não conseguimos verificar o cep informado, tente novamente!');
								}else{
									$('.registrarEndereco').slideDown();
									$('.cep-user').removeClass( "loading" );
									$('.cep-user').prop('disabled', false);
									 $('.campos_endereco').slideDown();
									 $('#Complemento').empty().val(JSON.parse(result.endereco).logradouro )
									 $('#Estado').empty().val(JSON.parse(result.endereco).estado)
									 $('#Cidade').empty().val(JSON.parse(result.endereco).cidade)
									 $('#Bairro').empty().val(JSON.parse(result.endereco).bairro )
								}
						}
						catch(err) {
							$('.registrarEndereco').hide();
							$('.cep-user').prop('disabled', false);
						$('.cep-user').removeClass( "loading" );
							$.Notification.notify('error','top right', 'DrHoje', 'Não conseguimos verificar o cep informado, tente novamente!');
						}
						
						
					},
					error: function (result) {
						$('.registrarEndereco').hide();
						$('.cep-user').prop('disabled', false);
						$('.cep-user').removeClass( "loading" );
						$.Notification.notify('error','top right', 'DrHoje', 'Falha na operação!');
					}
				});
			}
			 
		}else{
			$('.registrarEndereco').hide();
			$('.cep-user').prop('disabled', false);
			$('.cep-user').removeClass( "loading" );
			$('.campos_endereco').slideUp();
			efetuar=0	
		}
	})

	$('#documento').change(function(){
	 
		if($(this).val() ==1){
			$('.inputCNPJCredito').val('');
			$('.cnpj').hide();
			$('.cpf').show();
		}else{
			$('.cnpj').show();
			$('.inputCPFCredito').val('');
			$('.cpf').hide();
	 
		}
	})
	 
	$('#documento_boleto').change(function(){
	 
		if($(this).val() ==1){
			$('#id_cnpj_boleto').val('');
			$('.cnpj').hide();
			$('.cpf').show();
		}else{
			$('.cnpj').show();
			$('#id_cpf_boleto').val('');
			$('.cpf').hide();
	 
		}
	})

	$('#tipo_atendimento').change(function(){

		var tipo_atendimento = $(this).val();
		if(tipo_atendimento == '') { return false; }

		if( $(this).val() == 'saude' || $(this).val() == 'odonto' || $(this).val() == 'exame' ){
			$('label[for="especialidade"]').text("Especialidade ou exame");
			$('label[for="local"]').text("Local de Atendimento");
			$('.form-busca').attr('action', '/resultado');
			$('.form-busca').attr('onsubmit', 'return validaBuscaAtendimento()');
		} else if( $(this).val() == 'checkup' ) {
			$('label[for="especialidade"]').text("Check-up");
			$('label[for="local"]').text("Tipo de Check-up");
			$('.form-busca').attr('action', '/resultado-checkup');
			$('.form-busca').attr('onsubmit', 'return validaBuscaCheckup()');
		}
		$('#local_atendimento').empty();
		
		var uf_localizacao = $('#sg_estado_localizacao').val();
		
		
		$('.spinner').fadeIn()
		var uf_localizacao_cookie = window.localStorage.getItem('uf_localizacao');
		
		if(uf_localizacao.length != 0 || uf_localizacao_cookie.length != 0){
			consultaEspecialidades(tipo_atendimento, uf_localizacao_cookie);
		} else{
			$('.spinner').fadeOut()
			$.Notification.notify('error','top right', 'DrHoje', 'Não conseguimos obter o seu estado, escolha um estado e tente novamente!');
		}
		
	});

	$('.registrarEndereco').click(function(){
		
		jQuery.ajax({
				type: 'POST',
				url: '/registrar-endereco' ,
				data: {
					 cep:$('#cep').val(),
					 rua:$('#Rua').val(),
					 numero:$('#Numero').val(),
					 estado:$('#Estado').val(),
					 bairro:$('#Bairro').val(),
					 cidade:$('#Cidade').val(),
					 complemento:$('#Complemento').val(),
					 registrar:true,
					'_token': laravel_token
				},
				
				success: function (result) {
					$('#cep').attr('disabled',true)
					$('#Rua').attr('disabled',true)
					$('#Numero').attr('disabled',true)
					$('.registrarEndereco').hide();
					$('.deletar').show();
					$.Notification.notify('success','top right', 'DrHoje', result.mensagem);

				if(result.carrinho){
					$.Notification.notify('success','top right', 'DrHoje', 'Estamos redirecionando você para o pagamento');
						window.location.href='/pagamento';
				} 
				},
				error: function (result) {
				   
					$.Notification.notify('error','top right', 'DrHoje', 'Falha ao registrar endereço !');

				}
			});
	})
	$('.deletar').click(function(){

		jQuery.ajax({
				type: 'POST',
				url: '/registrar-endereco' ,
				data: {
					 cep:$('#cep').val(),
					 rua:$('#Rua').val(),
					 numero:$('#Numero').val(),
					 estado:$('#Estado').val(),
					 bairro:$('#Bairro').val(),
					 cidade:$('#Cidade').val(),
					 complemento:$('#Complemento').val(),
					 excluir:true,

					'_token': laravel_token
				},
				
				success: function (result) {
					$.Notification.notify('success','top right', 'DrHoje', result.mensagem);
					$('#cep').val('')
					$('#cep').attr('disabled',false)
					$('#Rua').attr('disabled',false)
					$('#Numero').attr('disabled',false)
					$('.campos_endereco').slideUp();
					$('.deletar').hide();
					$('.registrarEndereco').hide();
				 
					
				

				},
				error: function (result) {
				   
			   $.Notification.notify('error','top right', 'DrHoje', 'Não conseguimos deletar seu endereço!');
				}
			});
	})
	
	try {
		var $cardinput = $('.cvx-checkout_card_number');
		$('.cvx-checkout_card_number').validateCreditCard(function(result) {

			if (result.card_type != null)
			{
				$('.inputCodigoCredito').attr('maxlength', 3);
				switch (result.card_type.name)
				{
					case "visa":
						$cardinput.css('background-position', '5px -38px');
						$cardinput.addClass('card_visa');
						$('.inputBandeiraCartaoCredito').val('Visa');
						$('.inputBandeiraCartaoDebito').val('Visa');
						break;

					case "visa_electron":
						$cardinput.css('background-position', '5px -80px');
						$cardinput.addClass('card_visa_electron');
						$('.inputBandeiraCartaoCredito').val('Visa');
						$('.inputBandeiraCartaoDebito').val('Visa');
						break;
					
					case "mastercard":
						$cardinput.css('background-position', '5px -122px');
						$cardinput.addClass('card_mastercard');
						$('.inputBandeiraCartaoCredito').val('Master');
						$('.inputBandeiraCartaoDebito').val('Master');
						break;

					case "maestro":
						$cardinput.css('background-position', '5px -164px');
						$cardinput.addClass('card_maestro');
						$('.inputBandeiraCartaoDebito').val('Maestro');
						break;

					case "diners_club_international":
						$cardinput.css('background-position', '5px -206px');
						$cardinput.addClass('card_discover');
						$('.inputBandeiraCartaoCredito').val('Diners');
						break;	
					case "amex":
						$cardinput.css('background-position', '5px -290px');
						$cardinput.addClass('card_amex');
						$('.inputBandeiraCartaoCredito').val('Amex');
						$('.inputCodigoCredito').attr('maxlength', 4);
						break;
						
					case "diners_club_carte_blanche":
						$cardinput.css('background-position', '5px -248px');
						$cardinput.addClass('card_discover');
						$('.inputBandeiraCartaoCredito').val('Diners');
						break;
					
					case "elo":
						$cardinput.css('background-position', '5px -332px');
						$cardinput.addClass('card_maestro');
						
						$('.inputBandeiraCartaoCredito').val('Elo');
						break;
						
					default:
						$cardinput.css('background-position', '5px 4px');
						break;
				}
			} else {
				$cardinput.css('background-position', '5px 4px');
			}

			// Check for valid card numbere - only show validation checks for invalid Luhn when length is correct so as not to confuse user as they type.
			if (result.length_valid || $cardinput.val().length > 16)
			{
				if (result.luhn_valid) {
					$cardinput.parent().removeClass('has-error').addClass('has-success');
				} else {
					$cardinput.parent().removeClass('has-success').addClass('has-error');
				}
			} else {
				$cardinput.parent().removeClass('has-success').removeClass('has-error');
			}

			if(!result.luhn_valid || !result.length_valid    ){
				removerError('#numeroCartaoCredito')
				$('.verificador-cartao').slideUp();
				$('#verificador-cartao').slideUp();
				if($('#numeroCartaoCredito').val().length ==16){
					$('#numeroCartaoCredito').parent().addClass('cvx-has-error');
					$('#numeroCartaoCredito').parent().append('<span class="help-block text-danger"><strong>Este Cartão não é válido</strong></span>');
				}
				removerError('#inputNumeroCartaoCredito')
				if($('#inputNumeroCartaoCredito').val().length ==16){
					$('#inputNumeroCartaoCredito').parent().addClass('cvx-has-error');
					$('#inputNumeroCartaoCredito').parent().append('<span class="help-block text-danger"><strong>Este Cartão não é válido</strong></span>');
				}

			}else{
				removerError('#numeroCartaoCredito')

				if($('#numeroCartaoCredito').val().length ==16 && result.luhn_valid && result.length_valid  ){
					$('.verificador-cartao').slideDown();
				}
				removerError('#inputNumeroCartaoCredito')
				if($('#inputNumeroCartaoCredito').val().length ==16 && result.luhn_valid && result.length_valid  ){
					$('#verificador-cartao').slideDown();
				}
			}

		});
	} catch (e) {}
	
	$('#btn-finalizar-pedido').click(function(){
		var tipo_pagamento = $('#selectFormaPagamento').val();
		var cartao_cadastrado = $('#selectCartaoCredito').val();
		
		if(tipo_pagamento == 'credito' && cartao_cadastrado == '') {
			pagarCartaoCredito();
		} else if(tipo_pagamento == 'debito' && cartao_cadastrado == '') {
			pagarCartaoDebito();
		} else if(cartao_cadastrado != '') {
			pagarCartaoCadastrado();
		}
	});
	
	$(".select2").select2({
		language: 'pt-BR'
	});
	
	$('#tipo_especialidade').change(function() {
		if( $('#tipo_atendimento').val() != 'checkup' ){
			var atendimento_id = $(this).val();
			var tipo_atendimento = $('#tipo_atendimento').val();
			//var uf_localizacao = $('#sg_estado_localizacao').val();
			var uf_localizacao_cookie = window.localStorage.getItem('uf_localizacao');
			
			if(atendimento_id == '') { return false; }

			jQuery.ajax({
	    		type: 'POST',
	    	  	url: '/consulta-todos-locais-atendimento',
	    	  	data: {
					'tipo_atendimento': tipo_atendimento,
					'uf_localizacao': uf_localizacao_cookie,
	    	  		'especialidade': $(this).val(),
					'_token': laravel_token
				},
				success: function (result) {
	
					if( result != null) {
						var json = result.endereco;
						
						$('#local_atendimento').empty();
						var option = '<option value="">TODOS OS LOCAIS</option>';
						$('#local_atendimento').append($(option));
						
						for(var i=0; i < json.length; i++) {
							option = '<option value="'+json[i].id+'">'+json[i].te_bairro+': ' + json[i].nm_cidade + '</option>';
							$('#local_atendimento').append($(option));
						}
					}
	            },
	            error: function (result) {
	            	$.Notification.notify('error','top right', 'DrHoje', 'Falha na operação!');
	            }
	    	});
		} else {
			jQuery.ajax({
				type: 'POST',
				url: '/consulta-tipos-checkup',
				data: {
					'tipo_atendimento': $('select[name="tipo_especialidade"]').val(),
					'_token': laravel_token
				},
				success: function (result) {
					if( result != null) {
						var json = result;
						
						$('#local_atendimento').empty();
						var option = '<option value="">TODOS</option>';
						$('#local_atendimento').append($(option));
						
						for(var i=0; i < json.length; i++) {
							option = '<option value="'+json[i].tipo+'">'+json[i].tipo+'</option>';
							$('#local_atendimento').append($(option));
						}

						if(json.length > 0) {
							$('#local_atendimento option[value="'+json[0].tipo+'"]').prop("selected", true);
						}
					}
				},
				error: function (result) {
					$.Notification.notify('error','top right', 'DrHoje', 'Falha na operação!');
				}
			});
		}
	});
	
	$('#selectCartaoCredito').change(function(){
		
		if($(this).val() != '') {
			
			var cartao_id = $(this).val();
			
			jQuery.ajax({
	    		type: 'POST',
	    	  	url: '/consulta-cartao-paciente',
	    	  	data: {
					'cartao_id': cartao_id,
					'_token': laravel_token
				},
				success: function (result) {

					if(result) {
						var json = result.cartao;
						$('#inputNomeSaveCard').val(json.nome_impresso);
						$('#inputNumFinalSaveCard').val(json.numero);
						$('#inputExpirationDateSaveCard').val(json.dt_validade);
						$('#inputBrandSaveCard').val(json.bandeira);
						$('#inputSaveCardId').val(json.id);
						
						$('.row-payment-card').css('display', 'none');
						$('.row-payment.repayment').css('display', 'flex');
						$('.row-card-token').css('display', 'flex');


						$('#resumo_compra_final_cartao').html( $('#inputNumFinalSaveCard').val() );
					}
					else {
						$('.row-payment.repayment').css('display', 'none');
						$('#resumo_compra_final_cartao').html( 'XXXX' );	
					}
	            },
	            error: function (result) {
	            	$.Notification.notify('error','top right', 'DrHoje', 'Falha na operação!');
	            }
	    	});
		} else {
			$('.row-payment-card').css('display', 'flex');
			$('.row-card-token').css('display', 'none');
			$('.row-payment.repayment').css('display', 'flex');
			$('#resumo_compra_final_cartao').html( 'XXXX' );	
		}
	});
});

/*  ------------------------------------------------------------------------------------------INICIO------------------------------------------------------------------------------------------------------ */
$(function() {
	var valorDisponivel = $('#valor_disponivel').val();
	var total_pagar = $('#total_pagar').val();


 	$('.total_a_pagar').text('R$ '+total_pagar);

	var slider = document.getElementById("myRange");

    var valor="";
    var valor_formatado=""
    var resp="";
    var resultado="";
    var final ="";
    var totalPagar="";
    var totalPagarFormatado="";
    var resCOmplemento="";
    var subtrair="";
    var finalCOmplemento="";
    var vl_desconto=""

	if(slider != null) {
		var output = document.getElementById("porcentagem_credito_empresarial");

		slider.oninput = function() {
			output.innerHTML   =(parseFloat(this.value)).formatMoney(2, '.', '.')

            vl_desconto = $('#valor_desconto').val();

			valor = this.value 

			// valor disponivel credito empresarial
			valor_formatado = ( $('#valor_disponivel').val());

			if(valor_formatado != 'undefined' && valor_formatado.length > 6) {
				resp = (valor_formatado.replace('.',''))
			} else {
				// valor credito especial formatado
				resp = (valor_formatado.replace(',','.'))
			}

			// valor a ser pago pelas consultas
			totalPagar =  ($('#total_pagar').val());
			totalPagarFormatado = (totalPagar.replace(',','.')) //respCOmplemento
		
			if(parseFloat(totalPagarFormatado) > parseFloat(resp)) {
				// valor a ser debitado do credito especial
			//	resultado =parseFloat( ((valor * totalPagarFormatado )  )/100   );
					resultado = (((parseFloat(slider.value) ) * parseFloat(totalPagarFormatado)) / 100)
			} else {
				// valor a ser debitado do credito especial
				resultado =parseFloat((valor * parseFloat(resp)  ) /100  );
			}

			// fomata o valor
			final = resultado.formatMoney(2, ',', '.');

			resCOmplemento = parseFloat( (100 * totalPagarFormatado )/100 );

			subtrair = (resCOmplemento - resultado )

            finalCOmplemento = subtrair.formatMoney(2, ',', '.');
			if(parseFloat(vl_desconto) != 0){
                subtrair =   subtrair - parseFloat(vl_desconto)
                finalCOmplemento = subtrair.formatMoney(2, ',', '.');
            }
			printParcelamento(subtrair);
            $('.valor-total-produtos').empty().html('<p>R$ '+subtrair.formatMoney(2, ',', '.')+'</p>' )
			$('#total_a_pagar').empty().html('R$ '+subtrair.formatMoney(2, ',', '.'));
			$('.valor_cartao_empresarial').empty().html('<p>- R$ '+final+'</p>');
            $('#valor_empresarial').val(final);
			$('.valor_cartao_credito').empty().html('<p>R$ '+finalCOmplemento +'</p>');
			$('.valor_complementar').text('R$ '+finalCOmplemento)
			$('.creditoAserDebitado').text('R$ '+final);
		}
	}



	$('.escolherMetodoPagamento  ').on('change',function(){

		if($(this).val() == "2"){

			var resp="";
			var valor_formatado = ( $('#valor_disponivel').val());
			setTimeout(function() {
			 
			
				if(!(typeof valor_formatado === 'undefined') && valor_formatado.length > 6) {
					resp = (valor_formatado.replace('.',''))
				} else if(!(typeof valor_formatado === 'undefined')) {
					// valor credito especial formatado
					resp = (valor_formatado.replace(',','.'))
				}
                vl_desconto = $('#valor_desconto').val();

                complemento =  ($('#total_pagar').val());
				respCOmplemento = !(typeof complemento === 'undefined') ? (complemento.replace(',','.')) : '';
			 
				
				if(parseFloat(respCOmplemento) > parseFloat(resp)) {
					var valorComplemento =  parseFloat(respCOmplemento)  -parseFloat(resp)
					var totalEmpresarial = parseFloat(respCOmplemento)  - valorComplemento
					var porcentagem = (totalEmpresarial /parseFloat(respCOmplemento)) * 100;
					var empresa=0;		 
					var complemt=0;
	
					 
					slider.max = (porcentagem) //- 0.1
		 
					if(valor_formatado.length >6) {
						resp = (valor_formatado.replace('.','')) 
					} else {
						resp = (valor_formatado.replace(',','.'))
					}

					$('.vlr-ce').text('R$ '+(parseFloat(resp)).formatMoney(2, '.', '.') );

					complemento =  ($('#total_pagar').val());

					respCOmplemento = (complemento.replace(',','.'))

					if(parseFloat(respCOmplemento) > parseFloat(resp)) {
						var valorComplemento =  parseFloat(respCOmplemento)  -parseFloat(resp)
						var totalEmpresarial = parseFloat(respCOmplemento)  - valorComplemento
						var porcentagem = (totalEmpresarial /parseFloat(respCOmplemento)) * 100;
						var empresa=0;		 
						var complemt=0;
						
						slider.max = (porcentagem) //- 0.1
						
						slider.value =  (porcentagem) //- 0.1;

						empresa = (((parseFloat(slider.value) ) * parseFloat(respCOmplemento)) / 100)
						 
						complemt  = (parseFloat(respCOmplemento) - empresa)	
						 
						output.innerHTML =(parseFloat(slider.value)).formatMoney(2, '.', '.') 

					 
						$('.valor_cartao_empresarial').empty().html('<p> - R$ '+empresa.formatMoney(2, ',', '.')+'</p>');

                        $('#valor_empresarial').val(empresa.formatMoney(2, ',', '.'));

                        if(parseFloat(vl_desconto) != 0){
                            complemt =   complemt - parseFloat(vl_desconto)
                        }

                        $('.valor-total-produtos').empty().html('<p>R$ '+complemt.formatMoney(2, ',', '.')+'</p>' )

						$('#total_a_pagar').empty().html('R$ '+complemt.formatMoney(2, ',', '.'));

                        printParcelamento(complemt );
						
						$('.valor_complementar').text('R$ '+complemt.formatMoney(2, ',', '.'))
						
						$('.creditoAserDebitado').text('R$ '+  empresa.formatMoney(2, ',', '.')) 
	
					} else {
						var porcentagem = parseFloat(respCOmplemento) / parseFloat(resp)  * 100
			
						var totalEmpresarial = ((porcentagem  ) * parseFloat(resp)) /100
						var valorComplemento =  (respCOmplemento) - totalEmpresarial

						if(parseFloat(vl_desconto) != 0){
                            valorComplemento =   valorComplemento - parseFloat(vl_desconto)
                        }
						printParcelamento(valorComplemento);
						slider.max = parseFloat(porcentagem) //- 0.1;
						slider.value =parseFloat(porcentagem) // - 0.1;
						output.innerHTML =(parseFloat(porcentagem)  ).formatMoney(2, ',', '.')						
						$('.valor_cartao_empresarial').empty().html('<p>- R$ '+totalEmpresarial+'</p>');
						$('#valor_empresarial').val(totalEmpresarial);
						$('.valor_complementar').text('R$ '+valorComplemento)
						$('.creditoAserDebitado').text('R$ '+totalEmpresarial) 
					}
	
				}
																					   
			}, 10);
		}else{
            var total = ($('#total_pagar').val());
            vl_desconto = $('#valor_desconto').val();
            var valor = 0;

                valor =   parseFloat(total) - parseFloat(vl_desconto)

			$('#total_a_pagar').empty().html('R$ '+valor.formatMoney(2, ',', '.'));
			$('.valor-total-produtos').empty().html('<p>R$ '+valor.formatMoney(2, ',', '.') +'</p>' )
            printParcelamento(valor );
		}
	})


	//return string.split(' ').join('');

	$('#documento').attr('disabled', 'disabled');

	$('#numeroCartaoCredito').on('paste', function (e) {

		var pastedData = e.originalEvent.clipboardData.getData('text');
		var number =pastedData.split(' ').join('');
		var verificador = isNumber(number);

		if(verificador){
			removerError('#numeroCartaoCredito')
			$(this).val(number);
		}else{
			$.Notification.notify('warning','top right', 'DrHoje','Informe apenas número');
			setTimeout(function(e) {
				$('#numeroCartaoCredito').val('');
			}, 10);

		}



	});
	$('#inputNumeroCartaoCredito').on('paste', function (e) {

		var pastedData = e.originalEvent.clipboardData.getData('text');
		var number =pastedData.split(' ').join('');
		var verificador = isNumber(number);
		if(verificador){
			removerError('#inputNumeroCartaoCredito')
			$(this).val(number);
		}else{
			removerError('#inputNumeroCartaoCredito')
			$.Notification.notify('warning','top right', 'DrHoje','Informe apenas número');
			setTimeout(function(e) {
				$('#inputNumeroCartaoCredito').val('');
			}, 10);

		}



	});

	function isNumber(n) {
		return !isNaN(parseFloat(n)) && isFinite(n);
	}

    $('#btn-validar-cupom').click(function () {

        var codigo = $('#inputCupom').val();
        var activeCupom = localStorage.getItem('activeCupom');
        var valor_total = $('#valor_servicos').val();

        if (codigo.length == 0 | activeCupom == 't') {
            return false;
        }

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: '/validar-cupom-desconto',
            data: {
                'codigo': codigo,
                'valor_parcelamento': valor_total,
                '_token': laravel_token
            },
            timeout: 15000,
            success: function (result) {

                if (result.status) {
                    var desconto = valor_total * result.percentual;
                    var valor_com_desconto = valor_total - desconto;
                    $('#valor_desconto').val(desconto)
                    var parcelamentos = result.parcelamentos;
					var validaInput =true;
                    $('.cvx-check-cupom-desconto').removeClass('cvx-no-loading');
                    $('#valor_desconto').parent().find('p').html('- R$ ' + numberToReal(desconto));

                    if(result.titulo =="CAIXA"){
						var selectobject=document.getElementById("cartaoCadastrado");

						$('#cartaoCadastrado').prop('selectedIndex',0);
						$('#cartaoCadastrado').attr('disabled', 'disabled');
						$('#cartaoCadastrado').empty();
						$('#cartaoCadastrado').append("<option value=''>Novo Cartão</option>")
						$('#numeroCartaoCredito').val('');
						$('#inputNumeroCartaoCredito').val('');

						$('.verificador-cartao').slideUp();
						$('#verificador-cartao').slideUp();

						$('.anoCartao').hide();
						$('.mesCartao').hide();
						$('.selectValidadeMesCredito').show();
						$('.selectValidadeAnoCredito').show();
						$('.inputNomeCartaoCredito').val('').prop("disabled",  false);
						$('.inputNumeroCartaoCredito').val('').prop("disabled", false);
						$('.row-payment-card').css('display', 'flex');
						$('.row-card-token').css('display', 'none');
						$('.row-payment.repayment').css('display', 'flex');
						$('#resumo_compra_final_cartao').html( 'XXXX' );

					}

					if($('#plano_ativo').val() != $('#plano_open').val()){
						if($('.escolherMetodoPagamento option:selected').val() ==2){
							var valor = ($('#valor_empresarial').val());
							var empresa = valor.replace(',','.');
							var total = (parseFloat($('#valor_servicos').val()) - (parseFloat(empresa) + 	(desconto)))
							$('.valor-total-produtos').html('R$ '+total.formatMoney(2, ',', '.'));
							$('.valor_complementar').html('R$ '+total.formatMoney(2, ',', '.'));
							$('#total_a_pagar').empty().html('R$ '+total.formatMoney(2, ',', '.'));

							printParcelamento(total );

							var valorEmpresarial = parseFloat($('#valor_disponivel').val())

							if(parseFloat((total).formatMoney(2, '.', ',')) < valorEmpresarial){

								var selectobject=document.getElementById("escolherMetodoPagamento2");
								for (var i=0; i<selectobject.length; i++){
									if (selectobject.options[i].value == '2' ){
										selectobject.remove(i);
									}


									if (  validaInput ==true  ){

										if (selectobject.options[i].value == '1' ){
											selectobject.remove(i);


										}

										$('#escolherMetodoPagamento2').append($('<option>', {
											value: 1,
											text: 'Crédito Empresarial'
										}));
										validaInput=false;
									}
								}

								$('.cartao-credito').slideUp();

								$('.metodoPagamento').empty().html('Escolha o método de pagamento')
								$('.transferenciaBancaria').hide();
								$('.boletoBancario').hide();
								$('.cartaocredito').hide();
								$('.cartaoEmpresarial_Credito').hide();
								$('.cartaoEmpresarial').hide();
							}
							//parcelamento-cartao
						}
						else{
							var valor = ($('#valor_empresarial').val());

							$('.valor-total-produtos').find('p').html('R$ ' + numberToReal(valor_com_desconto));
							$('.valor_complementar').html('R$ '+numberToReal(valor_com_desconto));
							$('#total_a_pagar').empty().html('R$ '+numberToReal(valor_com_desconto));

							printParcelamento(valor_com_desconto );
							var valorEmpresarial = parseFloat($('#valor_disponivel').val())
							if(parseFloat((valor_com_desconto).formatMoney(2, '.', ',')) < valorEmpresarial){

								var selectobject=document.getElementById("escolherMetodoPagamento2");

								for (var i=0; i<selectobject.length; i++){
									if (selectobject.options[i].value == '2' ){
										selectobject.remove(i);
									}


									if (validaInput ==true ){
										$("#escolherMetodoPagamento2 option[value='1']").remove();
										$('#escolherMetodoPagamento2').append($('<option>', {
											value: 1,
											text: 'Crédito Empresarial'
										}));
										validaInput=false;
									}

								}

								$('.cartao-credito').slideUp();

								$('.metodoPagamento').empty().html('Escolha o método de pagamento')
								$('.transferenciaBancaria').hide();
								$('.boletoBancario').hide();
								$('.cartaocredito').hide();
								$('.cartaoEmpresarial_Credito').hide();
								$('.cartaoEmpresarial').hide();
							}
						}
					}

                } else {
					$('#inputCupom').val('');
                    swal(
                        {
                            title: '<div class="tit-sweet tit-error"><i class="fa fa-times-circle" aria-hidden="true"></i> Ocorreu um erro</div>',
                            text: result.mensagem
                        }
                    );

                }
            },
            error: function (result) {
                $.Notification.notify('error', 'top right', 'DrHoje', 'Falha na operação!');
            }
        });
    });
		
	Number.prototype.formatMoney = function (c, d, t) {
		var n = this,
			c = isNaN(c = Math.abs(c)) ? 2 : c,
			d = d == undefined ? "." : d,
			t = t == undefined ? "," : t,
			s = n < 0 ? "-" : "",
			i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
			j = (j = i.length) > 3 ? j % 3 : 0;
		return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
	};


	$('.anoCartao').hide();
	$('.mesCartao').hide();
	$('#anoCartao').hide();
	$('#mesCartao').hide();
	$('.cartaoCadastrado ').change(function() {
		
		removerError('#numeroCartaoCredito')
		removerError('#nomeImpressoCartaoCredito')
		removerError('#mesCartaoCredito')
		removerError('#anoCartaoCredito')
		removerError('#codigoCartaoCredito')
		removerError('#cpfTitularCartaoCredito')

		if($(this).val() != '') {

			var cartao_id = $(this).val();

			jQuery.ajax({
				type: 'POST',
				url: '/consulta-cartao-paciente',
				data: {
					'cartao_id': cartao_id,
					'_token': laravel_token
				},
				success: function (result) {

					if(result) {
						var json = result.cartao;
						$('.verificador-cartao').slideDown();
						$('.inputNomeCartaoCredito').val(json.nome_impresso).prop("disabled", true);
						$('.inputNumeroCartaoCredito').val(json.numero).prop("disabled", true);
						$('.inputExpirationDateSaveCard').val(json.dt_validade).prop("disabled", true);


						var val = json.dt_validade.split("/");

						$('.inputBrandSaveCard').val(json.bandeira).prop("disabled", true);
						$('.inputSaveCardId').val(json.id).prop("disabled", true);


						$('.mesCartao').val(val[0]).prop('disabled', true).show();
						$('.selectValidadeMesCredito').hide();
						$('.selectValidadeAnoCredito').hide();
						$('.anoCartao').val(val[1]).prop('disabled', true).show();


						$('.row-payment-card').css('display', 'none');
						$('.row-payment.repayment').css('display', 'flex');
						$('.row-card-token').css('display', 'flex');


						$('#resumo_compra_final_cartao').html( $('#inputNumFinalSaveCard').val() );
					}
					else {
						$('.row-payment.repayment').css('display', 'none');
						$('#resumo_compra_final_cartao').html( 'XXXX' );
					}
				},
				error: function (result) {
					$.Notification.notify('error','top right', 'DrHoje', 'Falha na operação!');
				}
			});
		} else {
			$('.verificador-cartao').slideUp();

			$('.anoCartao').hide();
			$('.mesCartao').hide();
			$('.selectValidadeMesCredito').show();
			$('.selectValidadeAnoCredito').show();
			$('.inputNomeCartaoCredito').val('').prop("disabled",  false);
			$('.inputNumeroCartaoCredito').val('').prop("disabled", false);
			$('.row-payment-card').css('display', 'flex');
			$('.row-card-token').css('display', 'none');
			$('.row-payment.repayment').css('display', 'flex');
			$('#resumo_compra_final_cartao').html( 'XXXX' );
		}
	});

    $('.change-parcelamento').change(function(){

        $('.parcelamento-cartao').empty().append($(".change-parcelamento option:selected").text())
    })

	$('.change-parcelamento-credito').change(function() {
        $('.parcelamento-cartao').empty().append($(".change-parcelamento-credito option:selected").text())
	})
    $('#cartaoCadastrado').change(function() {
		removerError('#numeroCartaoCredito')
		removerError('#nomeImpressoCartaoCredito')
		removerError('#mesCartaoCredito')
		removerError('#anoCartaoCredito')
		removerError('#codigoCartaoCredito')
		removerError('#cpfTitularCartaoCredito')

		if($(this).val() != '') {
			var cartao_id = $(this).val();

			jQuery.ajax({
				type: 'POST',
				url: '/consulta-cartao-paciente',
				data: {
					'cartao_id': cartao_id,
					'_token': laravel_token
				},
				success: function (result) {

					if(result) {
						var json = result.cartao;

						$('#inputNomeCartaoCredito').val(json.nome_impresso).prop("disabled", true);
						$('#inputNumeroCartaoCredito').val(json.numero).prop("disabled", true);
						$('#inputExpirationDateSaveCard').val(json.dt_validade).prop("disabled", true);

						var val = json.dt_validade.split("/");

						$('#inputBrandSaveCard').val(json.bandeira).prop("disabled", true);
						$('#inputSaveCardId').val(json.id).prop("disabled", true);

						$('#selectValidadeMesCredito').hide();
						$('#selectValidadeAnoCredito').hide();
						$('#mesCartao').val(val[0]).prop('disabled', true).slideDown();

						$('#anoCartao').val(val[1]).prop('disabled', true).slideDown();


						$('.row-payment-card').css('display', 'none');
						$('.row-payment.repayment').css('display', 'flex');
						$('.row-card-token').css('display', 'flex');


						$('#resumo_compra_final_cartao').html( $('#inputNumFinalSaveCard').val() );
					}
					else {
						$('.row-payment.repayment').css('display', 'none');
						$('#resumo_compra_final_cartao').html( 'XXXX' );
					}
				},
				error: function (result) {
					$.Notification.notify('error','top right', 'DrHoje', 'Falha na operação!');
				}
			});
		} else {
			$('#anoCartao').hide();
			$('#mesCartao').hide();
			$('#selectValidadeMesCredito').show();
			$('#selectValidadeAnoCredito').show();
			$('#inputNomeCartaoCredito').val('').prop("disabled",  false);
			$('#inputNumeroCartaoCredito').val('').prop("disabled", false);
			$('.row-payment-card').css('display', 'flex');
			$('.row-card-token').css('display', 'none');
			$('.row-payment.repayment').css('display', 'flex');
			$('#resumo_compra_final_cartao').html( 'XXXX' );
		}
	});

	$('#btn-finalizar-pedido-landing').click(function(e){
		removerError('#numeroCartaoCredito')
		removerError('#nomeImpressoCartaoCredito')
		removerError('#mesCartaoCredito')
		removerError('#anoCartaoCredito')
		removerError('#codigoCartaoCredito')
		removerError('#cpfTitularCartaoCredito')

		removerError('#inputNumeroCartaoCredito')
		removerError('#inputNomeCartaoCredito')
		removerError('#selectValidadeMesCredito')
		removerError('#selectValidadeAnoCredito')
		removerError('#inputCodigoCredito')
		removerError('#inputCPFCredito')

	
		$('#btn-finalizar-pedido-landing').attr('disabled', 'disabled');
		$('#btn-finalizar-pedido-landing').find('#lbl-finalizar-pedido').html('Processando... <i class="fa fa-spin fa-spinner" style="float: right; font-size: 16px;"></i>');
	
		e.preventDefault();
		efetuarPagamento();



	});
	$('.cartao-credito').hide();
	
 	const dadosResumo = $('.metodoPagamento');
	$('.escolherMetodoPagamento').change(function() {
			removerError('#numeroCartaoCredito')
			removerError('#nomeImpressoCartaoCredito')
			removerError('#mesCartaoCredito')
			removerError('#anoCartaoCredito')
			removerError('#codigoCartaoCredito')
			removerError('#cpfTitularCartaoCredito')
		switch($(this).val()) {
			case "0":
			$('.cartao-credito').slideUp();
			
			dadosResumo.empty().html('Escolha o método de pagamento')
			$('.transferenciaBancaria').hide();
			$('.boletoBancario').hide();
			$('.cartaocredito').hide();
			$('.cartaoEmpresarial_Credito').hide();
			$('.cartaoEmpresarial').hide();
			break;
			case "1":
			$('.transferenciaBancaria').hide();
			$('.boletoBancario').hide();
			$('.cartaocredito').hide();
			$('.cartaoEmpresarial_Credito').hide();
			$('.cartaoEmpresarial').slideDown();

                $('.parcelamento-cartao').hide();
                $('.empresarial-valor-resumo').hide();

			$('.cartao-credito').slideDown();
			dadosResumo.empty().html('Cartão Empresarial')
				break;
			case "2":
			$('.transferenciaBancaria').hide();
			$('.boletoBancario').hide();
			$('.cartaocredito').hide();
			$('.cartaoEmpresarial_Credito').slideDown();
			$('.cartaoEmpresarial').hide();
                $('.empresarial-valor-resumo').show();
			$('.cartao-credito').slideDown();
                $('.parcelamento-cartao').show();
                $('.empresarial-valor-resumo').show();
			dadosResumo.empty().html('Cartão Empresarial + Cartão de Crédito Pessoal')
				break;
			case "3":
			$('.credito-valor-resumo').hide();
			$('.empresarial-valor-resumo').hide();
			$('.transferenciaBancaria').hide();
			$('.boletoBancario').hide();

			$('.cartaoEmpresarial_Credito').hide();
			$('.cartaoEmpresarial').hide();

			$('.cartaocredito').slideDown();
			$('.cartao-credito').slideDown();
             $('.parcelamento-cartao').show();
             $('.empresarial-valor-resumo').hide();
			dadosResumo.empty().html('  Cartão de Crédito')
				break;

			case "4":
			$('.transferenciaBancaria').hide();
			$('.boletoBancario').slideDown();
			$('.cartaocredito').hide();
			$('.cartaoEmpresarial_Credito').hide();
			$('.cartaoEmpresarial').hide();
			$('.cartao-credito').slideUp();
			dadosResumo.empty().html('  Boleto Bancario')
				break;
			case "5":
			$('.transferenciaBancaria').slideDown();
			$('.boletoBancario').hide();
			$('.cartaocredito').hide();
			$('.cartaoEmpresarial_Credito').hide();
			$('.cartaoEmpresarial').hide();
			$('.cartao-credito').slideUp();
			dadosResumo.empty().html('  Transferencia Bancaria')
				break;
			default:
			$('.cartao-credito').slideUp();
	
			dadosResumo.empty().html('Escolha o metodo de pagamento')
			$('.transferenciaBancaria').hide();
			$('.boletoBancario').hide();
			$('.cartaocredito').hide();
			$('.cartaoEmpresarial_Credito').hide();
			$('.cartaoEmpresarial').hide();
		}
	});

	$('#anoCartaoCredito').change(function(){
		$(this).parent().removeClass('cvx-has-error');
		$(this).parent().find('span.help-block').remove();
	});
	$('#mesCartaoCredito').change(function(){
		$(this).parent().removeClass('cvx-has-error');
		$(this).parent().find('span.help-block').remove();
	});


	$('#numeroCartaoCredito').change(function(){
		$(this).parent().removeClass('cvx-has-error');
		$(this).parent().find('span.help-block').remove();
	});

})

/*
function apenasLetras(string)
 {
 var numsStr = string.replace(/[^0-9,.;:="']/g,'');
 console.log(numsStr)
 return (numsStr);
 }
 */

function consultaEspecialidades(tipo_atendimento, uf_localizacao_cookie) {
	
	jQuery.ajax({
		type: 'POST',
		  url: '/consulta-especialidades',
		  data: {
			'tipo_atendimento': tipo_atendimento,
			'uf_localizacao':   uf_localizacao_cookie  ,
			'_token'		  : laravel_token
		},
		success: function (result) {
		
			var json = JSON.parse(result.atendimento);
			
			if(json.length !=0){
										 		
					$('#tipo_especialidade').empty();

					if( $('#tipo_atendimento').val() != 'checkup' ){
						for(var i=0; i < json.length; i++) {
							var option = '<option value="'+json[i].id+'">'+json[i].descricao+'</option>';
							$('#tipo_especialidade').append($(option));
						}

						if( !$('#tipo_especialidade').val()  ) { return false; }

						jQuery.ajax({
							type: 'POST',
							  url: '/consulta-todos-locais-atendimento',
							  data: {
								'tipo_atendimento': tipo_atendimento,
								'uf_localizacao': uf_localizacao_cookie,
								  'especialidade': $('#tipo_especialidade').val(),
								'_token': laravel_token
							},
							success: function (result) {
								$('.spinner').fadeOut()
								if( result != null) {
									var json = result.endereco;
									$('#local_atendimento').empty();
									var option = '<option value="">TODOS OS LOCAIS</option>';
									$('#local_atendimento').append($(option));
									
									for(var i=0; i < json.length; i++) {
										option = '<option value="'+json[i].id+'">'+json[i].te_bairro+': ' + json[i].nm_cidade + '</option>';
										$('#local_atendimento').append($(option));
									}
								}
							},
							error: function (result) {
								$('.spinner').fadeOut()
				
								$.Notification.notify('error','top right', 'DrHoje', 'Falha na operação!');
							}
						});
					} else {
						for(var i=0; i < json.length; i++) {
							var option = '<option value="'+json[i].id+'">'+json[i].descricao+'</option>';
							$('#tipo_especialidade').append($(option));
						}

						jQuery.ajax({
							type: 'POST',
							url: '/consulta-tipos-checkup',
							data: {
								'tipo_atendimento': $('select[name="tipo_especialidade"]').val(),
								'_token': laravel_token
							},
							success: function (result) {
								$('.spinner').fadeOut()
								if( result != null) {
									var json = result;
									
									$('#local_atendimento').empty();
									var option = '<option value="">TODOS</option>';
									$('#local_atendimento').append($(option));
									
									for(var i=0; i < json.length; i++) {
										option = '<option value="'+json[i].tipo+'">'+json[i].tipo+'</option>';
										$('#local_atendimento').append($(option));
									}

									if(json.length > 0) {
										$('#local_atendimento option[value="'+json[0].tipo+'"]').prop("selected", true);
									}
								}
							},
							error: function (result) {
								$('.spinner').fadeOut()
								$.Notification.notify('error','top right', 'DrHoje', 'Falha na operação!');
							}
						});
					}
				
			}else{
				$('.spinner').fadeOut()
			//	$('#form-buscar').trigger("reset");
			//	$('#form-buscar').reset();
			
			
			$('#tipo_atendimento').prop('selectedIndex',0);
			$('#local_atendimento').empty().html('<option value="" disabled selected hidden>Ex.: Asa Sul</option>')
			$('#tipo_especialidade').empty().html('<option value="" disabled selected hidden>Ex.: Clínica Médica</option>')
				$.Notification.notify('error','top right', 'DrHoje', 'Não existe atendimentos para o estado selecionado !');
			}

			
		},
		error: function (result) {
			$('.spinner').fadeOut()
			
			$.Notification.notify('error','top right', 'DrHoje', 'Falha na operação!');
		}
	});
} 

function efetuarPagamento() {
	var metodoPagamento = $('.escolherMetodoPagamento option:selected').val();
	var dados=null;
	var executar = true;
	switch(metodoPagamento) {
		case "1":
			dados = "Empresarial"
			break;
		case "2":
		removerError('#documento')
			if($('#cnpjTitularCartaoCredito').val().length ==0 && $('#cpfTitularCartaoCredito').val().length ==0 ){
				
				validarCampos($('#cnpjTitularCartaoCredito').val(), '#documento', "Documento é obrigatório")
				dados = null;
			 
			} 
		

		 
			if(parseInt($('#valor_disponivel').val()) >0){
				dados = cartaoCreditoEmpresarial();
			} else{
				dados = null;
			}
		
			break;
		case "3":
				removerError('#documento')
				if($('.inputCPFCredito').val().length ==0 && $('.inputCNPJCredito').val().length ==0 ){
					validarCampos($('.inputCPFCredito').val(), '#documento', "Documento é obrigatório")
					 
					
					dados = null;
				} 
				$( '.inputCNPJCredito').keypress(function(){
					$('#documento').parent().removeClass('cvx-has-error');
					$('#documento').parent().find('span.help-block').remove();
				});
				$( '.inputCPFCredito').keypress(function(){
					$('#documento').parent().removeClass('cvx-has-error');
					$('#documento').parent().find('span.help-block').remove();
				});

			dados =	cartaoCredito();
			break;
		case "4":

				
				var resp = []
				var permission=true;
				removerError('#cep')
				removerError('#Complemento')
				removerError('#Bairro')
				removerError('#Estado')
				removerError('#Cidade')
				removerError('#Numero')
				removerError('#Rua')
				removerError('#Documento')
				var documento = "";
				if($('#documento_boleto option:selected').val()==1){
					var cpf = $('.cpf-boleto').val();
					var res =cpf.replace(/[^\d]+/g,'')
					documento = res;
					if(res.length != 11){
						 
						dados = null;
						$( '#id_cpf_boleto').parent().addClass('cvx-has-error');
						$( '#id_cpf_boleto').parent().append('<span class="help-block text-danger"><strong>Informe um número de documento correto</strong></span>');
				
						$( '#id_cpf_boleto').keypress(function(){
							$(this).parent().removeClass('cvx-has-error');
							$(this).parent().find('span.help-block').remove();
						});
					}
				}else{
				 
					var cpnj = $('.cnpj-boleto').val();
					var res =cpnj.replace(/[^\d]+/g,'')
					documento = res;
					if(res.length != 14){
						dados = null;
						$( '#id_cnpj_boleto').parent().addClass('cvx-has-error');
						$( '#id_cnpj_boleto').parent().append('<span class="help-block text-danger"><strong>Informe um número de documento correto</strong></span>');
				
						$( '#id_cnpj_boleto').keypress(function(){
							$(this).parent().removeClass('cvx-has-error');
							$(this).parent().find('span.help-block').remove();
						});
					}
				}

				resp.push( validarCampos($('#Rua  ').val(), '#Rua', "Rua é obrigatório"));
				resp.push(  validarCampos($('#Numero  ').val(), '#Numero', "Numero é obrigatório"));
				resp.push( validarCampos($('#Cidade  ').val(), '#Cidade', "Cidade é obrigatório"));
				resp.push( validarCampos($('#Estado  ').val(), '#Estado', "Estado é obrigatório"));
				resp.push( validarCampos($('#Bairro  ').val(), '#Bairro', "Bairro é obrigatório"));
				resp.push( validarCampos($('#Complemento  ').val(), '#Complemento', "Complemento é obrigatório"));
				resp.push( validarCampos($('#cep  ').val(), '#cep', "Cep é obrigatório"));


				resp.forEach(function(entry) {
					if(!entry){
						permission=false;
					}

				});

				if(permission){
					console.log('liberado')
					dados = {
						rua_endereco:$('#Rua  ').val(),
						numero_endereco:$('#Numero  ').val(),
						cidade_endereco:$('#Cidade  ').val(),
						estado_endereco:$('#Estado  ').val(),
						bairro_endereco:$('#Bairro  ').val(),
						complemento_endereco:$('#Complemento  ').val(),
						cep_endereco:$('#cep  ').val(),
						documento_endereco:documento
					};
					 
				}else{
					dados = null;
				 
				}
	 
			break;
		case "5":
			dados = "transferencia"
			break;
		default:
			break;
	}


    var cupom_desconto 	=  $('#inputCupom').val() != "" ? $('#inputCupom').val() :  '';
	var pacientes		= $('.paciente_agendamento_id');

	pacientes.each(function(){
		if($(this).val() === 'Selecione o Paciente deste Atendimento') {
			
			swal(
		        {
		            title: '<div class="tit-sweet tit-error"><i class="fa fa-times-circle" aria-hidden="true"></i> DrHoje: informa!</div>',
		            text: 'É necessário Definir o Paciente em cada Atendimento para poder Finalizar o Pedido!'
		        }
		    );
			
			executar = false;
		}
	});

	var titulo_pedido = $('#titulo_pedido').val();

	var num_itens = $('.card-resumo-compra').length;
	var agendamentos = [];
	var paciente_id = $('#paciente_id').val();
	
	for(var i = 0; i < num_itens; i++) {
		var dt_atendimento = $('#dt_atendimento_'+i).val()+' '+ $('#hr_atendimento_'+i).val();
		var profissional_id_temp = $('#profissional_id_'+i).val();
		var atendimento_id_temp = $('#atendimento_id_'+i).val();
		var checkup_id_temp = $('#checkup_id_'+i).val();
		var clinica_id_temp = $('#clinica_id_'+i).val();
		var filial_id_temp = $('#filial_id_'+i).val();
		
		if(typeof profissional_id_temp === 'undefined') {
			profissional_id_temp = 'null';
		}
		
		var dt_atendimento_temp = $('#dt_atendimento_'+i).val();
		
		if(typeof dt_atendimento_temp === 'undefined') dt_atendimento = 'null';
		if(typeof atendimento_id_temp === 'undefined') atendimento_id_temp = 'null';
		if(typeof checkup_id_temp === 'undefined') checkup_id_temp = 'null';
		if(typeof clinica_id_temp === 'undefined') clinica_id_temp = 'null';
		if(typeof filial_id_temp === 'undefined') filial_id_temp = 'null';

		var paciente_agendamento_id = $('#paciente_id_'+i).val();
		
		var item = '{"dt_atendimento":"'+dt_atendimento+'","paciente_id":'+paciente_agendamento_id+',"clinica_id":'+
			clinica_id_temp+',"filial_id":'+ filial_id_temp+',"atendimento_id":'+ atendimento_id_temp+',"profissional_id":'+
			profissional_id_temp+',"checkup_id":'+ checkup_id_temp+'}';
		agendamentos.push(item);
	}
	

	 if(executar==true && dados  != null){



		$.ajax({
			type:'post',
			   dataType:'json',
			   url: '/finalizar_pedido',
			   data: {
				dados:dados,
				metodo: metodoPagamento,
                cod_cupom_desconto:cupom_desconto,
				'agendamentos':agendamentos,
				paciente_id:paciente_id,
				titulo_pedido:titulo_pedido,
				'_token': laravel_token
				},
			   timeout: 15000,
			   success: function (result) {

				$('#btn-finalizar-pedido-landing').find('#lbl-finalizar-pedido').html('FINALIZAR PAGAMENTO <i class="fa fa-spin fa-spinner" style="display: none; float: right; font-size: 16px;"></i>');
			//	$('#btn-finalizar-pedido-landing').removeAttr('disabled');

				if(result.status) {
					$.Notification.notify('success','top right', 'DrHoje', result.mensagem);
					window.location.href='/concluir_pedido';
				} else {
					$('#btn-finalizar-pedido-landing').removeAttr('disabled');
  //				  $.Notification.notify('error','top right', 'DrHoje', result.mensagem);
					swal(
							  {
								  title: '<div class="tit-sweet tit-error"><i class="fa fa-times-circle" aria-hidden="true"></i>DrHoje: Ocorreu um erro</div>',
								  text: result.responseJSON.mensagem
							  }
						  );

				}

				},
				error: function (result) {



					swal(
							  {
								  title: '<div class="tit-sweet tit-error"><i class="fa fa-times-circle" aria-hidden="true"></i>DrHoje: Ocorreu um erro</div>',

								  text:  result.responseJSON.mensagem

							  }
						  );
						  $('#btn-finalizar-pedido-landing').find('#lbl-finalizar-pedido').html('FINALIZAR PAGAMENTO <i class="fa fa-spin fa-spinner" style="display: none; float: right; font-size: 16px;"></i>');
						  $('#btn-finalizar-pedido-landing').removeAttr('disabled');
				}
		  });
	} else {
		$('#btn-finalizar-pedido-landing').find('#lbl-finalizar-pedido').html('FINALIZAR PAGAMENTO <i class="fa fa-spin fa-spinner" style="display: none; float: right; font-size: 16px;"></i>');
		$('#btn-finalizar-pedido-landing').removeAttr('disabled');

		swal(
			{
				title: '<div class="tit-sweet tit-error"><i class="fa fa-times-circle" aria-hidden="true"></i>DrHoje: Atenção.</div>',
				text: 'Verifique o metodo de pagamento selecionado'
			}
		);
	}
}
function cartaoEmpresarial(){

}


function cartaoCreditoEmpresarial(){
	var objeto=null;
	var resp =[];
	var metodo = $('.escolherMetodoPagamento option:selected').val()
	var cartaoid = $('#cartaoCadastrado option:selected').val()
	var numero = $('#inputNumeroCartaoCredito ').val()
	var nome = $('#inputNomeCartaoCredito ').val()
	var mes = $('#selectValidadeMesCredito ').val()
	var ano = $('#selectValidadeAnoCredito').val()
	var cvv = $('#inputCodigoCredito  ').val()
	var titularcpf = $('#inputCPFCredito').val()
	var titularcnpj = $('#cnpjTitularCartaoCredito').val() 
	if(titularcpf.length != 0){
		titularcpf= $('#inputCPFCredito').val()
	} 

	if(titularcnpj.length != 0){
		titularcpf= $('#cnpjTitularCartaoCredito').val() 
	}
	
	var parcelas = $('#selectParcelamentoCredito').val()

 
	var salvar =   0
	var porcentagemCreditoEspecial = ($('#porcentagem_credito_empresarial').text()).replace(',', '.');


	var permission=true;
	resp.push( validarCampos($('#inputNumeroCartaoCredito  ').val(), '#inputNumeroCartaoCredito', "Número cartão obrigatório"));
	resp.push(  validarCampos($('#inputNomeCartaoCredito  ').val(), '#inputNomeCartaoCredito', "Nome impresso é obrigatório"));
	resp.push( validarCampos($('#selectValidadeMesCredito  ').val(), '#selectValidadeMesCredito', "Mês cartão é obrigatório"));
	resp.push( validarCampos($('#selectValidadeAnoCredito  ').val(), '#selectValidadeAnoCredito', "Ano do cartão é obrigatório"));
	resp.push( validarCampos($('#inputCodigoCredito  ').val(), '#inputCodigoCredito', "Código do cartão é obrigatório"));
	


	resp.forEach(function(entry) {
		if(!entry){
			permission=false;
		}

	});

	if(permission){
		objeto = {
			metodo:metodo,
			cartaoid:cartaoid,
			numero:numero,
			nome:nome,
			mes:mes,
			ano:ano,
			cvv:cvv,
			titularcpf:titularcpf,
			parcelas:parcelas,
			salvar:salvar,
			porcentagem:porcentagemCreditoEspecial
		}
	}else{
		swal(
			{
				title: '<div class="tit-sweet tit-error"><i class="fa fa-times-circle" aria-hidden="true"></i> Ocorreu um erro</div>',
				text: 'Por favor, verifique os campos e tente novamente.'
			}
		);
	$('#btn-finalizar-pedido').find('#lbl-finalizar-pedido').html('FINALIZAR PAGAMENTO <i class="fa fa-spin fa-spinner" style="display: none; float: right; font-size: 16px;"></i>');
	$('#btn-finalizar-pedido').removeAttr('disabled');
	}
	return objeto;
}

function printParcelamento(valor ){
	var options = ""
	$('.selectParcelamentoCredito').empty()
    $('.parcelamento-cartao').empty()

	if(parseFloat(valor) >50 && parseFloat(valor) < 500 ){

		$('.selectParcelamentoCredito').empty().append( '  <option value="1" > 1 x R$ '+ valor.formatMoney(2, ',', '.')+' sem juros </option>' )
		$('.parcelamento-cartao').empty().html('1 x R$ '+valor.formatMoney(2, ',', '.')+' sem juros')

		var i=0;
		for (i = 2; i <=3; i++) {
			var vl = parseFloat(valor) / i
			if(i <=3){
				$('.selectParcelamentoCredito').append( '  <option value="'+i+'" > '+i+' x R$ '+(vl).formatMoney(2, ',', '.')+' sem juros </option>' )
			}
		}

	}else if(parseFloat(valor) >500) {


		$('.parcelamento-cartao').empty().html('1 x R$ '+valor.formatMoney(2, ',', '.')+' sem juros')
		var i=0;
		for (i = 1; i <=10; i++) {
			var vl = parseFloat(valor) / i
			if(i <=3){
				$('.selectParcelamentoCredito').append( '  <option value="'+i+'" > '+i+' x R$ '+(vl).formatMoney(2, ',', '.')+' sem juros </option>' )
			}else if(i >3){
				$('.selectParcelamentoCredito').append( '  <option value="'+i+'" > '+i+' x R$ '+(parseFloat(vl) *1.05).formatMoney(2, ',', '.') +' com juros (5% a.m.) </option>' )
			}
		}
	}else{



	 	$('.parcelamento-cartao').empty().html('1 x R$ '+(valor).formatMoney(2, ',', '.')  +' sem juros')
		$('.selectParcelamentoCredito').empty().append( '  <option value="1" > 1 x R$ '+(valor).formatMoney(2, ',', '.')  +' sem juros </option>' )
	}

}

function cartaoCredito() {
	var objeto=null;
	var resp =[];
	var permission = true;
	var metodo = $('.escolherMetodoPagamento option:selected').val()
	var cartaoid = $('.cartaoCadastradoCredito option:selected').val()
	var numero = $('.inputNumeroCartaoCredito ').val()
	var nome = $('.inputNomeCartaoCredito ').val()
	var mes = $('.selectValidadeMesCredito ').val()
	var ano = $('.selectValidadeAnoCredito').val()
	var cvv = $('.inputCodigoCreditoCartao  ').val()
	var titularcpf = $('.inputCPFCredito ').val()
	var titularcnpj = $('.inputCNPJCredito ').val()
	if(titularcpf.length !=0){
		titularcpf =titularcpf
	}
	if(titularcnpj.length !=0){
		titularcpf =titularcnpj
	}



	var parcelas = $('.change-parcelamento-credito').find(":selected").val();
	var salvar = $('input[name=gravar_cartao_credito]:checked').is(":checked")===true ? 1 : 0

	if(cartaoid != ""){

		resp = validarCampos($('.inputCodigoCreditoCartao  ').val(), '#codigoCartaoCredito', "Mes cartão");

		if(resp){
			objeto = {salvar:salvar, parcelas:parcelas, cartaoid:cartaoid, cvv:cvv}
		} else {
			//$.Notification.notify('error','top right', 'Solicitação Falhou!', 'Por favor, verifique os campos e tente novamente.');
			swal({
				title: '<div class="tit-sweet tit-error"><i class="fa fa-times-circle" aria-hidden="true"></i> Ocorreu um erro</div>',
				text: 'Por favor, verifique os campos e tente novamente.'
			});
			$('#btn-finalizar-pedido').find('#lbl-finalizar-pedido').html('FINALIZAR PAGAMENTO <i class="fa fa-spin fa-spinner" style="display: none; float: right; font-size: 16px;"></i>');
			$('#btn-finalizar-pedido').removeAttr('disabled');
			objeto=null
		}
	} else {
		resp.push( validarCampos($('.inputBandeiraCartaoCredito  ').val(), '#numeroCartaoCredito', "Número cartão obrigatório"));
		resp.push(  validarCampos($('.inputNomeCartaoCredito  ').val(), '#nomeImpressoCartaoCredito', "Nome impresso é obrigatório"));
		resp.push( validarCampos($('.selectValidadeMesCredito  ').val(), '#mesCartaoCredito', "Mês cartão é obrigatório"));
		resp.push( validarCampos($('.selectValidadeAnoCredito  ').val(), '#anoCartaoCredito', "Ano do cartão é obrigatório"));
		resp.push( validarCampos($('.inputCodigoCreditoCartao  ').val(), '#codigoCartaoCredito', "Código do cartão é obrigatório"));


		resp.forEach(function(entry) {
			if(!entry) {
				permission=false;
			}
		});

		if(permission) {
			objeto = {
				metodo:metodo,
				cartaoid:cartaoid,
				numero:numero,
				nome:nome,
				mes:mes,
				ano:ano,
				cvv:cvv,
				titularcpf:titularcpf,
				parcelas:parcelas,
				salvar:salvar
			}
		} else {
			swal({
				title: '<div class="tit-sweet tit-error"><i class="fa fa-times-circle" aria-hidden="true"></i> Ocorreu um erro</div>',
				text: 'Por favor, verifique os campos e tente novamente.'
			});
			$('#btn-finalizar-pedido').find('#lbl-finalizar-pedido').html('FINALIZAR PAGAMENTO <i class="fa fa-spin fa-spinner" style="display: none; float: right; font-size: 16px;"></i>');
			$('#btn-finalizar-pedido').removeAttr('disabled');
		}
	}

	return objeto
}

function removerError(attrb){
	$(attrb).parent().removeClass('cvx-has-error');
	$(attrb).parent().find('span.help-block').remove();
}

function validarCampos  (field, attrb, mensagem) {
	if(field.length == 0 || field =="Mês" || field =="Ano") {

		$(attrb).parent().addClass('cvx-has-error');
		$(attrb).parent().append('<span class="help-block text-danger"><strong>'+mensagem+'</strong></span>');

		$(attrb).keypress(function(){
			$(this).parent().removeClass('cvx-has-error');
			$(this).parent().find('span.help-block').remove();
		});

		return false;
	}else{
		$(this).parent().removeClass('cvx-has-error');
		$(this).parent().find('span.help-block').remove();
		return true;
	}
}
/*  ------------------------------------------------------------------------------------------FIM------------------------------------------------------------------------------------------------------ */

function pagarCartaoCredito() {
	var result = true;
	var cartaoEmpresarial = $('#selecionaCreditoEmpresarial option:selected').val();
	var numero_cartao 	= $('#inputNumeroCartaoCredito');
	var nome_impresso 	= $('#inputNomeCartaoCredito');
	var mes_credito 	= $('#selectValidadeMesCredito');
	var ano_credito 	= $('#selectValidadeAnoCredito');
	var cod_seg 		= $('#inputCodigoCredito');
	var cpf_titular 	= $('#inputCPFCredito');
	var parcelamento 	= $('#selectParcelamentoCredito');
	var cupom_desconto 	= $('#inputCupom');
	var pacientes		= $('.paciente_agendamento_id');

	if(numero_cartao.val().length < 16) {
		numero_cartao.parent().addClass('cvx-has-error');
		numero_cartao.parent().append('<span class="help-block text-danger"><strong>Este Cartão não é válido</strong></span>');

		$('#inputNumeroCartaoCredito').keypress(function(){
			$(this).parent().removeClass('cvx-has-error');
			$(this).parent().find('span.help-block').remove();
		});

		result = false;
	}

	if(nome_impresso.val().length == 0) {
		nome_impresso.parent().addClass('cvx-has-error');
		nome_impresso.parent().append('<span class="help-block text-danger"><strong>Campo Obrigatório</strong></span>');

		$('#inputNomeCartaoCredito').keypress(function(){
			$(this).parent().removeClass('cvx-has-error');
			$(this).parent().find('span.help-block').remove();
		});

		result = false;
	}

	if(mes_credito.val().length == 0) {
		mes_credito.parent().addClass('cvx-has-error');
		mes_credito.parent().append('<span class="help-block text-danger"><strong>Mês Cartão</strong></span>');

		$('#selectValidadeMesCredito').change(function(){
			$(this).parent().removeClass('cvx-has-error');
			$(this).parent().find('span.help-block').remove();
		});

		result = false;
	}

	if(ano_credito.val().length == 0) {
		ano_credito.parent().addClass('cvx-has-error');
		ano_credito.parent().append('<span class="help-block text-danger"><strong>Ano Cartão</strong></span>');

		$('#selectValidadeAnoCredito').change(function(){
			$(this).parent().removeClass('cvx-has-error');
			$(this).parent().find('span.help-block').remove();
		});

		result = false;
	}

	if(cod_seg.val().length == 0) {
		cod_seg.parent().addClass('cvx-has-error');
		cod_seg.parent().append('<span class="help-block text-danger"><strong>Campo Obrigatório</strong></span>');

		$('#inputCodigoCredito').keypress(function(){
			$(this).parent().removeClass('cvx-has-error');
			$(this).parent().find('span.help-block').remove();
		});

		result = false;
	}

	if(cpf_titular.val().length == 0) {
		cpf_titular.parent().addClass('cvx-has-error');
		cpf_titular.parent().append('<span class="help-block text-danger"><strong>Campo Obrigatório</strong></span>');

		$('#inputCPFCredito').keypress(function(){
			$(this).parent().removeClass('cvx-has-error');
			$(this).parent().find('span.help-block').remove();
		});

		result = false;
	}


	pacientes.each(function(){

		if($(this).val() == '0') {

			swal(
				{
					title: '<div class="tit-sweet tit-error"><i class="fa fa-times-circle" aria-hidden="true"></i> DrHoje: informa!</div>',
					text: 'É necessário Definir o Paciente em cada Atendimento para poder Finalizar o Pedido!'
				}
			);

			result = false;
		}
	});

	var num_itens = $('.card-resumo-compra').length;
	var agendamentos = [];

	for(var i = 0; i < num_itens; i++) {
		var dt_atendimento = $('#dt_atendimento_'+i).val()+' '+ $('#hr_atendimento_'+i).val();
		var profissional_id_temp = $('#profissional_id_'+i).val();
		var atendimento_id_temp = $('#atendimento_id_'+i).val();
		var checkup_id_temp = $('#checkup_id_'+i).val();
		var clinica_id_temp = $('#clinica_id_'+i).val();
		var filial_id_temp = $('#filial_id_'+i).val();

		if(typeof profissional_id_temp === 'undefined') {
			profissional_id_temp = 'null';
		}

		var dt_atendimento_temp = $('#dt_atendimento_'+i).val();

		if(typeof dt_atendimento_temp === 'undefined') dt_atendimento = 'null';
		if(typeof atendimento_id_temp === 'undefined') atendimento_id_temp = 'null';
		if(typeof checkup_id_temp === 'undefined') checkup_id_temp = 'null';
		if(typeof clinica_id_temp === 'undefined') clinica_id_temp = 'null';
		if(typeof filial_id_temp === 'undefined') filial_id_temp = 'null';

		var paciente_agendamento_id = $('#paciente_id_'+i).val();

		var item = '{"dt_atendimento":"'+dt_atendimento+'","paciente_id":'+paciente_agendamento_id+',"clinica_id":'+
			clinica_id_temp+',"filial_id":'+ filial_id_temp+',"atendimento_id":'+ atendimento_id_temp+',"profissional_id":'+
			profissional_id_temp+',"checkup_id":'+ checkup_id_temp+'}';
		agendamentos.push(item);
	}

	$('#btn-finalizar-pedido').attr('disabled', 'disabled');
	$('#btn-finalizar-pedido').find('#lbl-finalizar-pedido').html('Processando... <i class="fa fa-spin fa-spinner" style="float: right; font-size: 16px;"></i>');
	setTimeout(function(){ $('#btn-finalizar-pedido').find('#lbl-finalizar-pedido').html('FINALIZAR PAGAMENTO <i class="fa fa-spin fa-spinner" style="display: none; float: right; font-size: 16px;"></i>'); $('#btn-finalizar-pedido').removeAttr('disabled'); }, 30000);

	var tipo_pagamento = $('#selectFormaPagamento').val();
	var titulo_pedido = $('#titulo_pedido').val();
	var paciente_id = $('#paciente_id').val();

	var num_cartao_credito = numero_cartao.val();
	var nome_impresso_cartao_credito = nome_impresso.val();
	var mes_cartao_credito = mes_credito.val();
	var ano_cartao_credito = ano_credito.val();
	var cod_seg_cartao_credito = cod_seg.val();
	var gravar_cartao_credito = $('#checkGravarCartaoCredito').is(':checked') ? 'on' : 'off';
	var bandeira_cartao_credito = $('#inputBandeiraCartaoCredito').val();
	var cod_cupom_desconto = $('#inputCupom').val();
	var num_parcela_selecionado = $('#selectParcelamentoCredito2').is(':visible') ? $('#selectParcelamentoCredito2').val() : $('#selectParcelamentoCredito').val();

	if(!result) {
		//$.Notification.notify('error','top right', 'Solicitação Falhou!', 'Por favor, verifique os campos e tente novamente.');
		swal({
			title: '<div class="tit-sweet tit-error"><i class="fa fa-times-circle" aria-hidden="true"></i> Ocorreu um erro</div>',
			text: 'Por favor, verifique os campos e tente novamente.'
		});
		$('#btn-finalizar-pedido').find('#lbl-finalizar-pedido').html('FINALIZAR PAGAMENTO <i class="fa fa-spin fa-spinner" style="display: none; float: right; font-size: 16px;"></i>');
		$('#btn-finalizar-pedido').removeAttr('disabled');

		return false;
	}

	if($('#cartaoCadastrado option:selected').val() != ''){
	const	data=  {
			'tipo_pagamento': tipo_pagamento,
			'titulo_pedido': titulo_pedido,
			'paciente_id': paciente_id,
			'num_cartao': num_cartao_credito,
			'nome_impresso_cartao': nome_impresso_cartao_credito,
			'mes_cartao': mes_cartao_credito,
			'ano_cartao': ano_cartao_credito,
			'cod_seg_cartao': cod_seg_cartao_credito,
			'gravar_cartao': gravar_cartao_credito,
			'bandeira_cartao': ( tipo_pagamento == 'debito' ) ? bandeira_cartao_debito : bandeira_cartao_credito,
			'cod_cupom_desconto': cod_cupom_desconto,
			'num_parcela_selecionado': num_parcela_selecionado,
			'agendamentos': agendamentos,
			'_token': laravel_token
		}
	} else {
		const	data=  {
			'tipo_pagamento': tipo_pagamento,
			'titulo_pedido': titulo_pedido,
			'paciente_id': paciente_id,
			'num_cartao': num_cartao_credito,
			'nome_impresso_cartao': nome_impresso_cartao_credito,
			'mes_cartao': mes_cartao_credito,
			'ano_cartao': ano_cartao_credito,
			'cod_seg_cartao': cod_seg_cartao_credito,
			'gravar_cartao': gravar_cartao_credito,
			'bandeira_cartao': ( tipo_pagamento == 'debito' ) ? bandeira_cartao_debito : bandeira_cartao_credito,
			'cod_cupom_desconto': cod_cupom_desconto,
			'num_parcela_selecionado': num_parcela_selecionado,
			'agendamentos': agendamentos,
			'_token': laravel_token
		}
	}

	 
	/*
	$.ajax({
		type:'post',
		dataType:'json',
		url: '/finalizar_pedido',
		data: {
			'tipo_pagamento': tipo_pagamento,
			'titulo_pedido': titulo_pedido,
			'paciente_id': paciente_id,
			'num_cartao': num_cartao_credito,
			'nome_impresso_cartao': nome_impresso_cartao_credito,
			'mes_cartao': mes_cartao_credito,
			'ano_cartao': ano_cartao_credito,
			'cod_seg_cartao': cod_seg_cartao_credito,
			'gravar_cartao': gravar_cartao_credito,
			'bandeira_cartao': ( tipo_pagamento == 'debito' ) ? bandeira_cartao_debito : bandeira_cartao_credito,
			'cod_cupom_desconto': cod_cupom_desconto,
			'num_parcela_selecionado': num_parcela_selecionado,
			'agendamentos': agendamentos,
			'_token': laravel_token
		},
		timeout: 15000,
		success: function (result) {

				console.log(result);


				if(result.status) {
				$.Notification.notify('success','top right', 'DrHoje', result.mensagem);
				window.location.href='/concluir_pedido';
			} else {
//				  $.Notification.notify('error','top right', 'DrHoje', result.mensagem);
				swal(
							{
								title: '<div class="tit-sweet tit-error"><i class="fa fa-times-circle" aria-hidden="true"></i>DrHoje: Ocorreu um erro</div>',
								text: result.mensagem
							}
						);
				$('#btn-finalizar-pedido').find('#lbl-finalizar-pedido').html('FINALIZAR PAGAMENTO <i class="fa fa-spin fa-spinner" style="display: none; float: right; font-size: 16px;"></i>');
				$('#btn-finalizar-pedido').removeAttr('disabled');
			}
			},
			error: function (result) {
				swal(
						{
							title: '<div class="tit-sweet tit-error"><i class="fa fa-times-circle" aria-hidden="true"></i>DrHoje</div>',
							text: 'Falha na operação!'
						}
					);
				$('#btn-finalizar-pedido').find('#lbl-finalizar-pedido').html('FINALIZAR PAGAMENTO <i class="fa fa-spin fa-spinner" style="display: none; float: right; font-size: 16px;"></i>');
				$('#btn-finalizar-pedido').removeAttr('disabled');
			}
	}); */

	return result;
}

function montarCartao(val) {
	if(val !=""){
		data=  {
			'tipo_pagamento': tipo_pagamento,
			'titulo_pedido': titulo_pedido,
			'paciente_id': paciente_id,
			'num_cartao': num_cartao_credito,
			'nome_impresso_cartao': nome_impresso_cartao_credito,
			'mes_cartao': mes_cartao_credito,
			'ano_cartao': ano_cartao_credito,
			'cod_seg_cartao': cod_seg_cartao_credito,
			'gravar_cartao': gravar_cartao_credito,
			'bandeira_cartao': ( tipo_pagamento == 'debito' ) ? bandeira_cartao_debito : bandeira_cartao_credito,
			'cod_cupom_desconto': cod_cupom_desconto,
			'num_parcela_selecionado': num_parcela_selecionado,
			'agendamentos': agendamentos,
			'_token': laravel_token
		}
	}else {
		data=  {
			'tipo_pagamento': tipo_pagamento,
			'titulo_pedido': titulo_pedido,
			'paciente_id': paciente_id,
			'num_cartao': num_cartao_credito,
			'nome_impresso_cartao': nome_impresso_cartao_credito,
			'mes_cartao': mes_cartao_credito,
			'ano_cartao': ano_cartao_credito,
			'cod_seg_cartao': cod_seg_cartao_credito,
			'gravar_cartao': gravar_cartao_credito,
			'bandeira_cartao': ( tipo_pagamento == 'debito' ) ? bandeira_cartao_debito : bandeira_cartao_credito,
			'cod_cupom_desconto': cod_cupom_desconto,
			'num_parcela_selecionado': num_parcela_selecionado,
			'agendamentos': agendamentos,
			'_token': laravel_token
		}
	}

	return data;
}

function verificarCreditoEmpresarial() {

}


function pagarCartaoDebito() {

	var result = true;
	var numero_cartao 	= $('#inputNumeroCartaoDebito');
	var nome_impresso 	= $('#inputNomeCartaoDebito');
	var mes_debito 		= $('#selectValidadeMesDebito');
	var ano_debito 		= $('#selectValidadeAnoDebito');
	var cod_seg 		= $('#inputCodigoDebito');
	var cpf_titular 	= $('#inputCPFDebito');
	var cupom_desconto 	= $('#inputCupom');
	var pacientes		= $('.paciente_agendamento_id');

	if(numero_cartao.val().length < 16) {
		numero_cartao.parent().addClass('cvx-has-error');
		numero_cartao.parent().append('<span class="help-block text-danger"><strong>Este Cartão não é válido</strong></span>');

		$('#inputNumeroCartaoDebito').keypress(function(){
			$(this).parent().removeClass('cvx-has-error');
			$(this).parent().find('span.help-block').remove();
		});

		result = false;
	}

	if(nome_impresso.val().length == 0) {
		nome_impresso.parent().addClass('cvx-has-error');
		nome_impresso.parent().append('<span class="help-block text-danger"><strong>Campo Obrigatório</strong></span>');

		$('#inputNomeCartaoDebito').keypress(function(){
			$(this).parent().removeClass('cvx-has-error');
			$(this).parent().find('span.help-block').remove();
		});

		result = false;
	}

	if(mes_debito.val().length == 0) {
		mes_debito.parent().addClass('cvx-has-error');
		mes_debito.parent().append('<span class="help-block text-danger"><strong>Mês Cartão</strong></span>');

		$('#selectValidadeMesDebito').change(function(){
			$(this).parent().removeClass('cvx-has-error');
			$(this).parent().find('span.help-block').remove();
		});

		result = false;
	}

	if(ano_debito.val().length == 0) {
		ano_debito.parent().addClass('cvx-has-error');
		ano_debito.parent().append('<span class="help-block text-danger"><strong>Ano Cartão</strong></span>');

		$('#selectValidadeAnoDebito').change(function(){
			$(this).parent().removeClass('cvx-has-error');
			$(this).parent().find('span.help-block').remove();
		});

		result = false;
	}

	if(cod_seg.val().length == 0) {
		cod_seg.parent().addClass('cvx-has-error');
		cod_seg.parent().append('<span class="help-block text-danger"><strong>Campo Obrigatório</strong></span>');

		$('#inputCodigoDebito').keypress(function(){
			$(this).parent().removeClass('cvx-has-error');
			$(this).parent().find('span.help-block').remove();
		});

		result = false;
	}

	if(cpf_titular.val().length == 0) {
		cpf_titular.parent().addClass('cvx-has-error');
		cpf_titular.parent().append('<span class="help-block text-danger"><strong>Campo Obrigatório</strong></span>');

		$('#inputCPFDebito').keypress(function(){
			$(this).parent().removeClass('cvx-has-error');
			$(this).parent().find('span.help-block').remove();
		});

		result = false;
	}

	pacientes.each(function(){
		if($(this).val() == '0') {
			swal({
				title: '<div class="tit-sweet tit-error"><i class="fa fa-times-circle" aria-hidden="true"></i> DrHoje: informa!</div>',
				text: 'É necessário Definir o Paciente em cada Atendimento para poder Finalizar o Pedido!'
			});

			result = false;
		}
	});

	var num_itens = $('.card-resumo-compra').length;
	var agendamentos = [];

	for(var i = 0; i < num_itens; i++) {
		var dt_atendimento = $('#dt_atendimento_'+i).val()+' '+ $('#hr_atendimento_'+i).val();
		var profissional_id_temp = $('#profissional_id_'+i).val();
		var atendimento_id_temp = $('#atendimento_id_'+i).val();
		var checkup_id_temp = $('#checkup_id_'+i).val();
		var clinica_id_temp = $('#clinica_id_'+i).val();
		var filial_id_temp = $('#filial_id_'+i).val();

		if(typeof profissional_id_temp === 'undefined') {
			profissional_id_temp = 'null';
		}

		var dt_atendimento_temp = $('#dt_atendimento_'+i).val();

		if(typeof dt_atendimento_temp === 'undefined') dt_atendimento = 'null';
		if(typeof atendimento_id_temp === 'undefined') atendimento_id_temp = 'null';
		if(typeof checkup_id_temp === 'undefined') checkup_id_temp = 'null';
		if(typeof clinica_id_temp === 'undefined') clinica_id_temp = 'null';
		if(typeof filial_id_temp === 'undefined') filial_id_temp = 'null';

		var paciente_agendamento_id = $('#paciente_id_'+i).val();

		var item = '{"dt_atendimento":"'+dt_atendimento+'","paciente_id":'+paciente_agendamento_id+',"clinica_id":'+
			clinica_id_temp+',"filial_id":'+ filial_id_temp+',"atendimento_id":'+ atendimento_id_temp+',"profissional_id":'+
			profissional_id_temp+',"checkup_id":'+ checkup_id_temp+'}';
		agendamentos.push(item);
	}

	$('#btn-finalizar-pedido').attr('disabled', 'disabled');
	$('#btn-finalizar-pedido').find('#lbl-finalizar-pedido').html('Processando... <i class="fa fa-spin fa-spinner" style="float: right; font-size: 16px;"></i>');
	setTimeout(function(){ $('#btn-finalizar-pedido').find('#lbl-finalizar-pedido').html('FINALIZAR PAGAMENTO <i class="fa fa-spin fa-spinner" style="display: none; float: right; font-size: 16px;"></i>'); $('#btn-finalizar-pedido').removeAttr('disabled'); }, 30000);

	var tipo_pagamento = $('#selectFormaPagamento').val();
	var titulo_pedido = $('#titulo_pedido').val();
	var paciente_id = $('#paciente_id').val();

	var num_cartao_debito = numero_cartao.val();
	var nome_impresso_cartao_debito = nome_impresso.val();
	var mes_cartao_debito = mes_debito.val();
	var ano_cartao_debito = ano_debito.val();
	var cod_seg_cartao_debito = cod_seg.val();
	var bandeira_cartao_debito = $('#inputBandeiraCartaoDebito').val();
	var cod_cupom_desconto = $('#inputCupom').val();

	if(!result) {
//		$.Notification.notify('error','top right', 'Solicitação Falhou!', 'Por favor, verifique os campos e tente novamente.');
		swal({
			title: '<div class="tit-sweet tit-error"><i class="fa fa-times-circle" aria-hidden="true"></i> Ocorreu um erro</div>',
			text: 'Por favor, verifique os campos e tente novamente.'
		});
		$('#btn-finalizar-pedido').find('#lbl-finalizar-pedido').html('FINALIZAR PAGAMENTO <i class="fa fa-spin fa-spinner" style="display: none; float: right; font-size: 16px;"></i>');
		$('#btn-finalizar-pedido').removeAttr('disabled');

		return false;
	}

	$.ajax({
		type:'post',
		dataType:'json',
		url: '/finalizar_pedido',
		data: {
			'tipo_pagamento': tipo_pagamento,
			'titulo_pedido': titulo_pedido,
			'paciente_id': paciente_id,
			'num_cartao': num_cartao_debito,
			'nome_impresso_cartao': nome_impresso_cartao_debito,
			'mes_cartao': mes_cartao_debito,
			'ano_cartao': ano_cartao_debito,
			'cod_seg_cartao': cod_seg_cartao_debito,
			'bandeira_cartao': bandeira_cartao_debito,
			'cod_cupom_desconto': cod_cupom_desconto,
			'agendamentos': agendamentos,
			'_token': laravel_token
		},
		timeout: 15000,
		success: function (result) {
			if(result.status) {
				$.Notification.notify('success','top right', 'DrHoje', result.mensagem);
				window.location.href='/concluir_pedido';
			} else {
				swal({
					title: '<div class="tit-sweet tit-error"><i class="fa fa-times-circle" aria-hidden="true"></i>DrHoje: Ocorreu um erro</div>',
					text: result.mensagem
				});
				$('#btn-finalizar-pedido').find('#lbl-finalizar-pedido').html('FINALIZAR PAGAMENTO <i class="fa fa-spin fa-spinner" style="display: none; float: right; font-size: 16px;"></i>');
				$('#btn-finalizar-pedido').removeAttr('disabled');
			}
		},
		error: function (result) {
//          $.Notification.notify('error','top right', 'DrHoje', 'Falha na operação!');
			swal({
				title: '<div class="tit-sweet tit-error"><i class="fa fa-times-circle" aria-hidden="true"></i>DrHoje</div>',
				text: 'Falha na operação!'
			});
			$('#btn-finalizar-pedido').find('#lbl-finalizar-pedido').html('FINALIZAR PAGAMENTO <i class="fa fa-spin fa-spinner" style="display: none; float: right; font-size: 16px;"></i>');
			$('#btn-finalizar-pedido').removeAttr('disabled');
		}
	});

	return result;
}

function validarCupoCartao(cartao, cupom){
	if(cartao.length==6 && cupom.length ==6){
		jQuery.ajax({
			type: 'POST',
			url: '/validar-cupom-cartao' ,
			data: {
				cartao:cartao,
				cupom: cupom,
				'_token': laravel_token
			},
			dataType: "json",
			success: function (result) {

				if(!result.cartao && result.cliente && !result.status) {
					//alert(result.mensagem)
					swal({
						title: '<div class="tit-sweet tit-warning">  Ops!</div>',
						text: result.mensagem,
						animation: true,
						showCancelButton: true,
						confirmButtonColor: '#3085d6',
						allowEscapeKey: false,
						allowOutsideClick: false,
						cancelButtonColor: '#d33',
						confirmButtonText: 'Pagar sem desconto',
						cancelButtonText: 'Pagar com desconto',
						useRejections: true
					}).then(
						value => {
							location.reload();


							// handle confirm
						},
						dismiss => {
							$('#inputNumeroCartaoCredito').val('');
							$('#numeroCartaoCredito').val('');

							// handle dismiss
						}
					).catch(swal.noop)

				}
			},
			error: function (result) {

			}
		});
	}
}
function pagarCartaoCadastrado()
{
	var result = true;
	var cartao_id 		= $('#selectCartaoCredito');
	var nome_impresso 	= $('#inputNomeSaveCard');
	var final_cartao 	= $('#inputNumFinalSaveCard');
	var dt_validade 	= $('#inputExpirationDateSaveCard');
	var cod_seg 		= $('#inputCodigoSegSaveCard');
	var parcelamento 	= $('#selectParcelamentoCredito');
	var cupom_desconto 	= $('#inputCupom');
	var pacientes		= $('.paciente_agendamento_id');

	if(cartao_id.val().length == 0) {
		cartao_id.parent().addClass('cvx-has-error');
		cartao_id.parent().append('<span class="help-block text-danger"><strong>Nenhum Cartão foi selecionado</strong></span>');

		$('#selectCartaoCredito').change(function(){
			$(this).parent().removeClass('cvx-has-error');
			$(this).parent().find('span.help-block').remove();
		});

		result = false;
	}

	if(cod_seg.val().length == 0) {
		cod_seg.parent().addClass('cvx-has-error');
		cod_seg.parent().append('<span class="help-block text-danger"><strong>Campo Obrigatório</strong></span>');

		$('#inputCodigoSegSaveCard').keypress(function(){
			$(this).parent().removeClass('cvx-has-error');
			$(this).parent().find('span.help-block').remove();
		});

		result = false;
	}

	pacientes.each(function(){
		if($(this).val() == '0') {

			swal({
				title: '<div class="tit-sweet tit-error"><i class="fa fa-times-circle" aria-hidden="true"></i> DrHoje: informa!</div>',
				text: 'É necessário Definir o Paciente em cada Atendimento para poder Finalizar o Pedido!'
			});

			result = false;
		}
	});

	var num_itens = $('.card-resumo-compra').length;
	var agendamentos = [];

	for(var i = 0; i < num_itens; i++) {
		var dt_atendimento = $('#dt_atendimento_'+i).val()+' '+ $('#hr_atendimento_'+i).val();
		var profissional_id_temp = $('#profissional_id_'+i).val();
		var atendimento_id_temp = $('#atendimento_id_'+i).val();
		var checkup_id_temp = $('#checkup_id_'+i).val();
		var clinica_id_temp = $('#clinica_id_'+i).val();
		var filial_id_temp = $('#filial_id_'+i).val();

		if(typeof profissional_id_temp === 'undefined') {
			profissional_id_temp = 'null';
		}

		var dt_atendimento_temp = $('#dt_atendimento_'+i).val();

		if(typeof dt_atendimento_temp === 'undefined') dt_atendimento = 'null';
		if(typeof atendimento_id_temp === 'undefined') atendimento_id_temp = 'null';
		if(typeof checkup_id_temp === 'undefined') checkup_id_temp = 'null';
		if(typeof clinica_id_temp === 'undefined') clinica_id_temp = 'null';
		if(typeof filial_id_temp === 'undefined') filial_id_temp = 'null';

		var paciente_agendamento_id = $('#paciente_id_'+i).val();

		var item = '{"dt_atendimento":"'+dt_atendimento+'","paciente_id":'+paciente_agendamento_id+',"clinica_id":'+
			clinica_id_temp+',"filial_id":'+ filial_id_temp+',"atendimento_id":'+ atendimento_id_temp+',"profissional_id":'+
			profissional_id_temp+',"checkup_id":'+ checkup_id_temp+'}';
		agendamentos.push(item);
	}

	$('#btn-finalizar-pedido').attr('disabled', 'disabled');
	$('#btn-finalizar-pedido').find('#lbl-finalizar-pedido').html('Processando... <i class="fa fa-spin fa-spinner" style="float: right; font-size: 16px;"></i>');
	setTimeout(function(){ $('#btn-finalizar-pedido').find('#lbl-finalizar-pedido').html('FINALIZAR PAGAMENTO <i class="fa fa-spin fa-spinner" style="display: none; float: right; font-size: 16px;"></i>'); $('#btn-finalizar-pedido').removeAttr('disabled'); }, 30000);

	var tipo_pagamento = 'cadastrado';
	var titulo_pedido = $('#titulo_pedido').val();
	var paciente_id = $('#paciente_id').val();

	var cartao_paciente = cartao_id.val();
	var nome_impresso_cartao_credito = nome_impresso.val();
	var final_cartao_credito = final_cartao.val();
	var validade_cartao_credito = dt_validade.val();
	var cod_seg_cartao_credito = cod_seg.val();
	var cod_cupom_desconto = $('#inputCupom').val();
	var num_parcela_selecionado = $('#selectParcelamentoCredito2').is(':visible') ? $('#selectParcelamentoCredito2').val() : $('#selectParcelamentoCredito').val();

	console.log(num_parcela_selecionado);

	if(!result) {
//		$.Notification.notify('error','top right', 'Solicitação Falhou!', 'Por favor, verifique os campos e tente novamente.');
		swal({
			title: '<div class="tit-sweet tit-error"><i class="fa fa-times-circle" aria-hidden="true"></i> Ocorreu um erro</div>',
			text: 'Por favor, verifique os campos e tente novamente.'
		});
		$('#btn-finalizar-pedido').find('#lbl-finalizar-pedido').html('FINALIZAR PAGAMENTO <i class="fa fa-spin fa-spinner" style="display: none; float: right; font-size: 16px;"></i>');
		$('#btn-finalizar-pedido').removeAttr('disabled');

		return false;
	}

	$.ajax({
		type:'post',
		dataType:'json',
		url: '/finalizar_pedido_cartao_cadastrado',
		data: {
			'tipo_pagamento': tipo_pagamento,
			'titulo_pedido': titulo_pedido,
			'paciente_id': paciente_id,
			'cartao_paciente': cartao_paciente,
			'nome_impresso_cartao': nome_impresso_cartao_credito,
			'final_cartao_credito': final_cartao_credito,
			'validade_cartao_credito': validade_cartao_credito,
			'cod_seg_cartao': cod_seg_cartao_credito,
			'cod_cupom_desconto': cod_cupom_desconto,
			'agendamentos': agendamentos,
			'num_parcela_selecionado': num_parcela_selecionado,
			'_token': laravel_token
		},
		timeout: 15000,
		success: function (result) {

			if(result.status) {
				$.Notification.notify('success','top right', 'DrHoje', result.mensagem);
				window.location.href='/concluir_pedido';
			} else {
//				$.Notification.notify('info','top right', 'DrHoje', result.mensagem);
				swal({
					title: '<div class="tit-sweet tit-info"><i class="fa fa-info-circle" aria-hidden="true"></i> Informação</div>',
					text: result.mensagem
				});
				$('#btn-finalizar-pedido').find('#lbl-finalizar-pedido').html('FINALIZAR PAGAMENTO <i class="fa fa-spin fa-spinner" style="display: none; float: right; font-size: 16px;"></i>');
				$('#btn-finalizar-pedido').removeAttr('disabled');
			}
		},
		error: function (result) {
//          $.Notification.notify('error','top right', 'DrHoje', 'Falha na operação!');
			swal({
				title: '<div class="tit-sweet tit-error"><i class="fa fa-times-circle" aria-hidden="true"></i>DrHoje</div>',
				text: 'Falha na operação!'
			});
			$('#btn-finalizar-pedido').find('#lbl-finalizar-pedido').html('FINALIZAR PAGAMENTO <i class="fa fa-spin fa-spinner" style="display: none; float: right; font-size: 16px;"></i>');
			$('#btn-finalizar-pedido').removeAttr('disabled');
		}
	});

	return result;
}

function numberToReal(numero)
{
	var c = isNaN(c = Math.abs(c)) ? 2 : c, d = d == undefined ? "," : d, t = t == undefined ? "." : t, s = numero < 0 ? "-" : "", i = parseInt(numero = Math.abs(+numero || 0).toFixed(c)) + "", j = (j = i.length) > 3 ? j % 3 : 0;
    return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(numero - i).toFixed(c).slice(2) : "");
}

function moedaParaNumero(valor)
{
    return isNaN(valor) == false ? parseFloat(valor) :   parseFloat(valor.replace("R$","").replace(".","").replace(",","."));
}

function pad(n)
{
    return n > 9 ? "" + n: "0" + n;
}

function validaBuscaAtendimento()
{
	var tipo_atendimento 	= $('#tipo_atendimento');
	var tipo_especialidade 	= $('#tipo_especialidade');
	
	if( tipo_atendimento.val().length == 0 ) {
		tipo_atendimento.parent().addClass('cvx-has-error');
		tipo_atendimento.focus();
		swal(
				  {
					  title: '<div class="tit-sweet tit-info"><i class="fa fa-info-circle" aria-hidden="true"></i>DrHoje: Solicitação Falhou!</div>',
					  text: 'Selecione o Tipo de Atendimento para Prosseguir'
				  }
	       	);
		
		$('.cvx-has-error .form-control').change(function(){
			$(this).parent().removeClass('cvx-has-error');
		});
		
		return false;
	}
	
	if( tipo_especialidade.val().length == 0 ) {
		tipo_especialidade.parent().addClass('cvx-has-error');
		tipo_especialidade.focus();
		swal(
				  {
					  title: '<div class="tit-sweet tit-info"><i class="fa fa-info-circle" aria-hidden="true"></i>DrHoje: Solicitação Falhou!</div>',
					  text: 'Selecione a Especialidade/Exame para Prosseguir'
				  }
	       	);
		
		$('.cvx-has-error .form-control').change(function(){
			$(this).parent().removeClass('cvx-has-error');
		});
		
		return false;
	}
		
	return true;
}


function onlyNumbers(evt)
{
    var theEvent = evt || window.event;
    var key = theEvent.keyCode || theEvent.which;
    var keychar = String.fromCharCode(key);
    var keycheck = /^[0-9_\b]+$/;
	
    if (!(key == 8 || key == 9 || key == 17 || key == 27 || key == 44 || key == 46 || key == 37 || key == 39)) {
        if (!keycheck.test(keychar)) {
            theEvent.returnValue = false;//for IE
            if (theEvent.preventDefault) {
                theEvent.preventDefault();//Firefox
            }
        }
    }
}