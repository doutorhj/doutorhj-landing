<?php

namespace App\Http\Controllers;

use App\Checkup;
use App\Datahoracheckup;
use App\Especialidade;
use App\Http\Requests\CartaoPacienteRequest;
use App\ItemCheckup;
use App\Payment;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Request as CVXRequest;
use Darryldecode\Cart\Facades\CartFacade as CVXCart;
use App\Pedido;
use App\Agendamento;
use App\Paciente;
use App\Itempedido;
use Illuminate\Support\Facades\DB;
use App\CreditCardResponse;
use App\DebitCardResponse;
use App\CartaoPaciente;
use App\CupomDesconto;
use App\Atendimento;
use App\Mensagem;
use App\MensagemDestinatario;
use App\Contato;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use MundiAPILib\MundiAPIClient;
use App\FuncoesPagamento;
use App\Preco;
use App\Empresa;
use App\User;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//
	}

	public function successPayment($hash){

		$hashVerificar = "58493ab3daa14af1c4b87cdcc592ace8";

		$resultado = strcmp($hashVerificar,$hash );

		if($resultado ==0){
			return view('payments.success');
		}else{
			return redirect('/');
		}

	}

	public function failedPayment($hash){
		$hashVerificar = "58493ab3daa14af1c4b87cdcc592ace989";

		$resultado = strcmp($hashVerificar,$hash );

		if($resultado ==0){
			return view('payments.failed');
		}else{
			return redirect('/');
		}
	}
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		//


		//$input = Input::only('paciente_id');
		$input =CVXRequest::post('paciente_id');
		echo $input;
		//var_dump(request('valor_servicos'));
		die;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Payment  $payment
	 * @return \Illuminate\Http\Response
	 */
	public function show(Payment $payment)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Payment  $payment
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Payment $payment)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Payment  $payment
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Payment $payment)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Payment  $payment
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Payment $payment)
	{
		//
	}

	/**
	 * notificacao a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function notificacao(Request $request)
	{
		$RecurrentPaymentId 	= CVXRequest::post('RecurrentPaymentId');
		$PaymentId 				= CVXRequest::post('PaymentId');
		$ChangeType 			= CVXRequest::post('ChangeType');

		$result = [
			'message' => 'HTTP Status Code 200 OK',
			'RecurrentPaymentId' => $RecurrentPaymentId,
			'PaymentId' => $PaymentId,
			'ChangeType' => $ChangeType
		];

		return response()->json('HTTP Status Code 200 OK', 200);
	}

	public function informaCartao($verify_hash)
	{
		$decryptString = Crypt::decryptString($verify_hash);
		$dados = explode('@', $decryptString);

		$paciente_id = $dados[0];
		$empresa_id = $dados[1];
		$agendamento_ids = json_decode($dados[2]);

		$paciente = Paciente::find($paciente_id);
		$agendamentos = Agendamento::whereIn('id', $agendamento_ids)->get();

		if(!Agendamento::whereIn('id', $agendamento_ids)->exists()) {
			return redirect('/')->with('info-alert', 'Não existem agendamentos cadastrados.');
		}

		/** Verifica se algum agendamento ja foi pré-agendado */
		if(Agendamento::whereIn('id', $agendamento_ids)->where('cs_status', '<>', Agendamento::PRE_AUTORIZAR)->exists()) {
			return redirect('/')->with('info-alert', 'Agendamento já autorizado.');
		}

		$valorIndividual = Itempedido::whereIn('agendamento_id', $agendamento_ids)
			->with(['pedido', 'pedido.cartao_paciente'])->whereHas('pedido', function($query) {
				$query->where('tp_pagamento', 'individual');
			})->sum('valor');

		$itemPedidoEmpresarial = Itempedido::whereIn('agendamento_id', $agendamento_ids)
			->with(['pedido', 'pedido.cartao_paciente'])->whereHas('pedido', function($query) {
				$query->where('tp_pagamento', 'empresarial');
			});

		return view('payments.informa_cartao', compact('verify_hash', 'cartao_pacientes', 'valorIndividual'));
	}

	public function finalizaPreAutorizacao($verify_hash, CartaoPacienteRequest $request)
	{
		$decryptString = Crypt::decryptString($verify_hash);
		$dados = explode('@', $decryptString);

		$dadosReq = $request->all();

		$paciente_id = $dados[0];
		$empresa_id = $dados[1];
		$agendamento_ids = json_decode($dados[2]);

		$paciente = Paciente::find($paciente_id);
		$agendamentos = Agendamento::whereIn('id', $agendamento_ids)->get();


		if(!Agendamento::whereIn('id', $agendamento_ids)->exists()) {
			return redirect('/')->with('info-alert', 'Não existem agendamentos cadastrados.');
		}

		/** Verifica se algum agendamento ja foi pré-agendado */
		if(Agendamento::whereIn('id', $agendamento_ids)->where('cs_status', '<>', Agendamento::PRE_AUTORIZAR)->exists()) {
			return redirect('/')->with('info-alert', 'Agendamento já autorizado.');
		}

		$itemPedidoIndividual = Itempedido::whereIn('agendamento_id', $agendamento_ids)
			->with(['pedido', 'pedido.cartao_paciente'])->whereHas('pedido', function($query) {
				$query->where('tp_pagamento', 'individual');
			})->sum('valor');



		$itemPedidoEmpresarial = Itempedido::whereIn('agendamento_id', $agendamento_ids)
			->with(['pedido', 'pedido.cartao_paciente'])->whereHas('pedido', function($query) {
				$query->where('tp_pagamento', 'empresarial');
			});


		$exp = explode('/', $dadosReq['validade']);
		$validaCartao = UtilController::validaCartao($dadosReq['numero'], $dadosReq['codigo_seg']);

		if(!$validaCartao[1] && !$validaCartao[2]) {
			return redirect()->back()->withErrors('Cartão invalido.')->withInput($dadosReq);
		}

		DB::beginTransaction();
		try {

			$client = new Client(['timeout'  => 1500,]);

			$valor = str_replace(".", ",", $itemPedidoIndividual);

			try{
				if(env('APP_ENV') != 'production') {
					$to = env('API_URL_HOMOLOG') ;
				}else{
					$to = env('API_URL_PROD') ;
				}

				 $client->request('POST', $to.'payment-dthoje', [
					 'headers' => [
						 'Authorization'     => env('TOKEN_PAGAMENTO_PRE_AUTORIZAR')
					 ],
					'form_params' => [
						'method' 	=> '0',
						'empresa' 	=> '0',
						'custom_id' => $paciente->mundipagg_token,
						'valor' 	=> Payment::convertRealEmCentavos($valor),
						'cartao_id' => null,
						'parcelas' 	=> $dadosReq['parcelas'],
						'card' =>json_encode([
									'number' =>$dadosReq['numero'],
									'holdername' =>$dadosReq['nome_impresso'],
									'month' =>$exp[0],
									'year' =>$exp[1],
									'cvv' =>$dadosReq['codigo_seg']
								])

					]
				]);

			} catch (\Exception $e) {

				return redirect()->back()->withErrors($e->getMessage())->withInput($dados);

			}

			Agendamento::whereIn('id', $agendamento_ids)->update(['cs_status' => Agendamento::PRE_AGENDADO]);

			foreach($agendamentos as $agendamento){
				try {

					if(!is_null($agendamento->atendimento_id))
						$merchantId = $agendamento->itemPedidos->first()->pedido->id;
						$this->enviarEmailPreAgendamento($paciente, $merchantId, $agendamento);
				} catch (Exception $e) {}

			}

		} catch(ServerException $e) {
			DB::rollback();
//			dd($e->getCode(), $e);
			return redirect()->back()->withErrors($e->getMessage())->withInput($dados);
		} catch(ClientException $e) {
			DB::rollback();
			return redirect()->back()->withErrors($e->getMessage())->withInput($dados);
		}

		DB::commit();
		return redirect('/')->with('success-alert', 'Seu pagamento foi realizado com sucesso! Seu procedimento foi Pré-Agendado.');
	}

	public function fullTransactionFinish(Request $request)
	{
		setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
		date_default_timezone_set('America/Sao_Paulo');


		$result_agendamentos =   $request->session()->get('result_agendamentos'); //json_decode(, true);
		$valor_empresa =   $request->session()->get('valor_empresa'); //json_decode(, true);
		$valor_credito =   $request->session()->get('varlor_credito'); //json_decode(, true);

		if ($result_agendamentos == null) {
			return redirect()->route('landing-page');
		}

		$pedido = $request->session()->get('pedido');

		$valor_total_pedido = $request->session()->get('valor_total_pedido');

		$boleto_bancario = $request->session()->get('descricao_boleto');

		$transferencia_bancaria = $request->session()->get('trans_bancario');

		$request->session()->forget('result_agendamentos');
		$request->session()->forget('pedido');
		$request->session()->forget('valor_total_pedido');
		$request->session()->forget('valor_empresa');
		$request->session()->forget('varlor_credito');

		return view('payments.finalizar_pedido', compact('result_agendamentos', 'pedido', 'valor_total_pedido', 'boleto_bancario', 'transferencia_bancaria', 'valor_credito', 'valor_empresa'));
	}

	/**
	 * realiza o pagamento na Cielo por cartao de credito no padrao completo.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */


	/**
	 * Pagamentos
	 * 1 para crédito empresarial
	 * 2 para credito empresarial mais cartao de crédito
	 * 3 para cartao de crédito
	 * 4 para boleto bancario
	 * 5 para transferencia bancaria
	 *
	 */
	public function fullTransaction(Request $request)
	{
		$basicAuthUserName = env('MUNDIPAGG_KEY');

		$basicAuthPassword = "";

		$client = new MundiAPIClient($basicAuthUserName, $basicAuthPassword);

		// metodo de pagamento
		$metodoPagamento  = CVXRequest::post('metodo');

		$dados  = (object) CVXRequest::post('dados');

		$cod_cupom_desconto = CVXRequest::post('cod_cupom_desconto');

		$percentual_desconto = 0; // '0' indica que o cliente vai pagar 100% do valor total dos produtos-----

		if($cod_cupom_desconto != '') {
			$percentual_desconto = $this->validarCupomDesconto($cod_cupom_desconto);
		}

		$carrinho = CVXCart::getContent()->toArray();

		//--verifica se as condicoes de agendamento estao disponiveis------
		$agendamento_disponivel = true;

		$agendamentos = (array) CVXRequest::post('agendamentos');
		//$agendamentos = (($agenda[0]));

		$listCarrinho = $this->listCarrinhoItens();

		$paciente_id = CVXRequest::post('paciente_id');

		$titulo_pedido = CVXRequest::post('titulo_pedido');

		$num_parcela_selecionado = CVXRequest::post('num_parcela_selecionado');

		$paciente =(object) Paciente::select("*")->where('id', $paciente_id)->first();

		//--verifica se todos os agendamentos possuem um atendimento relacionado------
		$agendamento_atendimento = true;

		//--verifica se profissional existe, indicando que se trata de um exame/procedimento que não precisa de profissional e nem data/hora--
		//--ou verifica que se trata de uma consulta ou atendimento em uma clinica que sempre necessita de data/hora--
		for ($i = 0; $i < count($agendamentos); $i++) {

			$item_agendamento = json_decode($agendamentos[$i]);

			if(!empty($item_agendamento->atendimento_id)) {

				$atendimento_id_temp = $item_agendamento->atendimento_id;

				$item_atendimento = Atendimento::findorfail($atendimento_id_temp);

				$item_atendimento->load('clinica');

				$item_agendamento->dt_atendimento = \DateTime::createFromFormat('Y-m-d H:i', $item_agendamento->dt_atendimento);

				if ($item_agendamento->profissional_id && $item_agendamento->profissional_id != 'null') {
					$agendamento = Agendamento::where('clinica_id', '=', $item_agendamento->clinica_id)->where('profissional_id', $item_agendamento->profissional_id)->where('dt_atendimento', '=', $item_agendamento->dt_atendimento->format('Y-m-d H:i:s'))->get();

					if (sizeof($agendamento) > 0) {
						$agendamento_disponivel = false;
					}

				} elseif ($item_atendimento->consulta_id != null | $item_atendimento->clinica->tp_prestador == 'CLI') {
					$agendamento = Agendamento::where('clinica_id', '=', $item_agendamento->clinica_id)->where('dt_atendimento', '=', $item_agendamento->dt_atendimento->format('Y-m-d H:i:s'))->get();

					if (sizeof($agendamento) > 0) {
						$agendamento_disponivel = false;
					}
				}

				if ($item_atendimento == null) {
					$agendamento_atendimento = false;
				}

				$item_agendamento->dt_atendimento = !empty($item_agendamento->dt_atendimento) ? $item_agendamento->dt_atendimento->format('d/m/Y H:i') : null;
				$agendamentoItens[] = $item_agendamento;
			} elseif(!empty($item_agendamento->checkup_id)) {
				$idCarrinho = $listCarrinho['checkups'][$item_agendamento->checkup_id];
				$item_agendamento->itens = $carrinho[$idCarrinho]['attributes']['items_checkup'];

				if(!Checkup::where(['id' => $item_agendamento->checkup_id])->exists()) {
					return response()->json([
						'mensagem' => 'O seu Agendamento não foi realizado, pois um dos checkups informados não existe. Por favor, tente novamente.'
					], 403);
				}

				if(count($item_agendamento->itens) != ItemCheckup::where(['checkup_id' => $item_agendamento->checkup_id])->get()->count()) {
					return response()->json([
						'mensagem' => 'Quantidade de itens fornecidos no checkup invalida. Por favor, tente novamente.'
					], 400);
				}

				foreach ($item_agendamento->itens as $key=>$item) {
					if(!empty($item['data_agendamento']) || !empty($item['hora_agendamento'])) {
						$dataHoraAgendamento = $item['data_agendamento'] . ' ' . $item['hora_agendamento'];
						$item['dt_atendimento'] = \DateTime::createFromFormat('d.m.Y H:i', $dataHoraAgendamento)->format('d/m/Y H:i');

						$atendimento = Atendimento::join('item_checkups', 'item_checkups.atendimento_id', '=', 'atendimentos.id')
							->where(['item_checkups.id' => $item['id'], 'item_checkups.checkup_id' => $item_agendamento->checkup_id])
							->first();

						if(!Agendamento::validaHorarioDisponivel($atendimento->clinica_id, $atendimento->profissional_id, $item['dt_atendimento'])) {
							return response()->json([
								'checkup_id' => $item_agendamento->checkup_id,
								'profissional_id' => $item->profissional_id,
								'clinica_id' => $item->clinica_id,
								'mensagem' => 'O seu Agendamento não foi realizado, pois um dos horários escolhidos não estão disponíveis. Por favor, tente novamente.'
							], 403);
						}

						$item_agendamento->itens[$key]['dt_atendimento'] = $item['dt_atendimento'];
					} else {
						$item_agendamento->itens[$key]['dt_atendimento'] = null;
					}
				}

				$checkups_id[] = $item_agendamento->checkup_id;
				$agendamentoItens[] = $item_agendamento;
			}
		}

		if (!$agendamento_disponivel) {
			return response()->json(['status' => false, 'mensagem' => 'O seu Agendamento não foi realizado, pois um dos horários escolhidos não estão disponíveis. Por favor, tente novamente.']);
		}

		if (!$agendamento_atendimento) {
			return response()->json(['status' => false, 'mensagem' => 'O seu Agendamento não foi realizado, pois um dos itens não possui um Atendimento Relacionado. Por favor, tente novamente.']);
		}

		########### STARTING TRANSACTION ############
		DB::beginTransaction();
		#############################################

		$valor_total = CVXCart::getTotal();

		$valor_desconto = $valor_total*$percentual_desconto;

		$pedido = new Pedido();
		$descricao = '';
		$dt_pagamento = date('Y-m-d H:i:s');
		$pedido->titulo         = $titulo_pedido;
		$pedido->descricao      = $descricao;
		$pedido->dt_pagamento   = $dt_pagamento;
		$pedido->tp_pagamento   = $this->verificaTp($metodoPagamento);
		$pedido->paciente_id    = $paciente_id;

		//================================================================= VALIDACOES DO TIPO DE PAGAMENTO ============================================================//
		/**valida a bandeira do cartao*/
		if(!empty($dados->numero)){
			if(empty($dados->cvv)){
				return response()->json([
					'mensagem' => 'CVV obrigatorio quando enviado apenas o cartao_id.'
				], 422);
			}

			$bandeira = UtilController::validaCartao($dados->numero, $dados->cvv);

			if(!$bandeira[1]) {
				return response()->json([
					'mensagem' => 'Número do cartão inválido!',
				], 400);
			} elseif(!$bandeira[2]) {
				return response()->json([
					'mensagem' => 'Número do código de segurança inválido!',
				], 400);
			} else {
				$bandeira = $bandeira[0];
			}
		}

		/** Credito Empresarial */
		if($metodoPagamento == Payment::METODO_CRED_EMP) {

			$cartaoEmpresarialDados  = (object) CartaoPaciente::where('empresa_id',$paciente->empresa_id )->first();

			$limiteCartaoFuncionario =Auth::user()->paciente->saldo_empresarial;

			if($limiteCartaoFuncionario == 0){
				return response()->json([
					'mensagem' => 'Não existe limite no cartao empresarial.'
				], 422);
			} else {
				$valorLimiteRestante = $limiteCartaoFuncionario ;
			}

			// adicionar o cartao id do cartao empresarial no pedido
			// $pedido->cartao_id =
			//valor para fim de calculo
			$valorPagamentoEmpresarial = $valor_total-$valor_desconto;

			$valorCartaoEmpresarialOne = Payment::convertRealEmCentavos(number_format($valorPagamentoEmpresarial, 2, ',', '.'));

			$paciente = Paciente::where(['id'=> $paciente_id])->first();

			$cartaoPaciente = CartaoPaciente::where('empresa_id',$paciente->empresa_id )->first();

			if(empty($cartaoPaciente)){
				DB::rollBack();
				return response()->json([
					'mensagem' => 'Paciente não está vinculado a nenhuma empresa.',
				], 500);
			}
			$empresa = Empresa::where('id',$paciente->empresa_id)->first();
			if(empty($empresa)){
				DB::rollBack();
				return response()->json([
					'mensagem' => 'Empresa não encontrada.',
				], 500);
			}

		}

		/** Credito empresarial + Cartao de credito */
		if($metodoPagamento == Payment::METODO_CRED_EMP_CRED_IND) {

			$limiteCartaoFuncionario =Auth::user()->paciente->saldo_empresarial;

			$cartaoEmpresarialDados  = (object) CartaoPaciente::where('empresa_id',$paciente->empresa_id )->first();

			if($limiteCartaoFuncionario == 0){
				return response()->json([
					'mensagem' => 'Não existe limite no cartao empresarial.'
				], 422);
			} else {
				$valorLimiteRestante = $limiteCartaoFuncionario ;
			}

			/**caso tenha id do cartão resgatar o id token do mesmo para realizar a transação*/
			if(!empty($dados->cartaoid)) {

				$cartao = CartaoPaciente::where(['id'=>$dados->cartaoid , 'paciente_id' => $paciente_id]);

				if(!$cartao->exists()) {
					DB::rollback();
					return response()->json([
						'mensagem' => 'ID do Cartão do Paciente não encontrado. Por favor, tente novamente.'
					], 404);
				}

				if(empty($dados->cvv)){
					return response()->json([
						'mensagem' => 'CVV obrigatorio quando enviado apenas o cartao_id.'
					], 422);
				}

				// card_id quando o cartao está salvo no sistema
				$cartao =$cartao->first()->card_token;

				$metodoCartao=1;

			}
			else {
				/** caso o usuario não queira salvar o cartão é criado um token enviando os dados do cartao para mundipagg*/
				if( $dados->salvar == 0){

					try {
						/**cria token cartao*/
						$cartaoToken = $client->getTokens()->createToken(env('MUNDIPAGG_KEY_PUBLIC'), FuncoesPagamento::criarTokenCartao($dados->numero, $dados->nome,$dados->mes, $dados->ano, $dados->cvv));
						/**token gerado a partir da mundipagg sem salvar o cartao do usuario.*/
						$metodoCartao=2;
						$cartao = $cartaoToken->id;

					} catch(\Exception $e) {
						DB::rollBack();
						return response()->json([
							'mensagem' => 'Não foi possivel criar o token do cartão de credito,agendamento não realizado,  Pagamento não processado! '. $e->getMessage() ,
							'errors' => $e->getMessage(),
						], 500);
					}
				} else {
					try {
						$saveCartao = $client->getCustomers()->createCard($paciente->mundipagg_token, FuncoesPagamento::criarCartao(
							$dados->numero,
							$dados->nome,
							$dados->mes,
							$dados->ano,
							$dados->cvv,
							$bandeira
						));

						$cartao_paciente = new CartaoPaciente();
						$cartao_paciente->bandeira 		= $bandeira;
						$cartao_paciente->nome_impresso = $dados->nome;
						$cartao_paciente->numero 		= substr($dados->numero, -4);
						$cartao_paciente->dt_validade 	= $dados->mes . '/' . $dados->ano;
						$cartao_paciente->card_token 	= $saveCartao->id;
						$cartao_paciente->paciente_id 	= $paciente->id;

						if($cartao_paciente->save()) {

							// card_id cartao salvo
							$metodoCartao=1;

							$cartao = $saveCartao->id;

						}
					} catch(\Exception $e) {
						DB::rollBack();
						return response()->json([
							'mensagem' => 'Não conseguimos! '.$e->getMessage(),
							'errors' => $e->getMessage(),
						], 500);
					}

				}
			}

			if(empty($dados->porcentagem)) {
				return response()->json([
					'mensagem' => 'Campo porcentagem nulo '.$dados->porcentagem
				], 500);
			}

			if($dados->porcentagem < 0 || $dados->porcentagem > 100) {
				return response()->json([
					'mensagem' => 'Valor de porcentagem informado incorretamente valor recebido '.$dados->porcentagem
				], 500);
			}

			//valor para fim de calculo
			$valorFinal = $valor_total-$valor_desconto;

			// efetua o desconto sobre o valor restante do credito empresarial definido pelo usuario
			$formatLimit =(float) str_replace(".","",$valorLimiteRestante)  ;

			$empresarial = (($dados->porcentagem *  $valorFinal)/100);
			if(number_format($empresarial, 2) > floatval($formatLimit) ){
				return response()->json([
					'mensagem' => 'Calculo somatorio maior que o limite disponivel'
				], 500);
			}


			$cartaoCredito = floatval($valorFinal) - floatval($empresarial);

			($dados->parcelas > 3) ? $valorCartaoCredito = Payment::convertRealEmCentavos(  number_format( $cartaoCredito * (1 + 0.05) **$dados->parcelas, 2, ',', '.') ) : $valorCartaoCredito = Payment::convertRealEmCentavos( number_format(  $cartaoCredito , 2, ',', '.') ) ;

			$valorCartaoEmpresarial = Payment::convertRealEmCentavos( number_format(  $empresarial , 2, ',', '.') );

			$cartaoPaciente = CartaoPaciente::where('empresa_id',$paciente->empresa_id )->first();

			if(empty($cartaoPaciente)){
				DB::rollBack();
				return response()->json([
					'mensagem' => 'Paciente não está vinculado a nenhuma empresa.',
				], 500);
			}
			$empresa = Empresa::where('id',$paciente->empresa_id)->first();
			if(empty($empresa)){
				DB::rollBack();
				return response()->json([
					'mensagem' => 'Empresa não encontrada.',
				], 500);
			}

		}

		/**faz validação para efetuar compra com o  cartao de credito utilizando cartão de credito pessoal*/
		if($metodoPagamento == Payment::METODO_CRED_IND) {
			if(!empty($dados->cartaoid)){
				$cartao = CartaoPaciente::where(['id'=>$dados->cartaoid , 'paciente_id' =>$paciente_id]);
				if(!$cartao->exists()) {
					DB::rollback();
					return response()->json([
						'mensagem' => 'ID do Cartão do Paciente não encontrado. Por favor, tente novamente.'
					], 404);
				}
				if(empty($dados->cvv)){
					return response()->json([
						'mensagem' => 'CVV obrigatorio quando enviado apenas o cartao_id.'
					], 422);
				}

				// card_id quando o cartao está salvo no sistema
				$cartao =$cartao->first()->card_token;
				$metodoCartao=1;
			} else {
				if($dados->salvar == 0){
					try {
						// cria token cartao
						$cartaoToken = $client->getTokens()->createToken(env('MUNDIPAGG_KEY_PUBLIC'), FuncoesPagamento::criarTokenCartao($dados->numero, $dados->nome,$dados->mes, $dados->ano, $dados->cvv));
						// token gerado a partir da mundipagg sem salvar o cartao do usuario.

						$metodoCartao = 2;
						$cartao =   $cartaoToken->id;
					} catch(\Exception $e) {
						DB::rollBack();
						return response()->json([
							'mensagem' => 'Não foi possivel efetuar o pagamento com o cartao de crédito! '. $e->getMessage(),
							'errors' => $e->getMessage(),
						], 500);
					}
				}
				else {
					try {
						$saveCartao = $client->getCustomers()->createCard($paciente->mundipagg_token, FuncoesPagamento::criarCartao(
							$dados->numero,
							$dados->nome,
							$dados->mes,
							$dados->ano,
							$dados->cvv,
							$bandeira
						));

						$cartao_paciente = new CartaoPaciente();
						$cartao_paciente->bandeira 		= $bandeira;
						$cartao_paciente->nome_impresso = $dados->nome;
						$cartao_paciente->numero 		= substr($dados->numero, -4);
						$cartao_paciente->dt_validade 	= $dados->mes . '/' . $dados->ano;
						$cartao_paciente->card_token 	= $saveCartao->id;
						$cartao_paciente->paciente_id 	= $paciente->id;

						if($cartao_paciente->save()) {
							// card_id cartao salvo
							$metodoCartao=1;
							$cartao = $saveCartao->id;
						}
					} catch(\Exception $e) {
						DB::rollBack();
						return response()->json([
							'mensagem' => 'Não conseguimos registrar no sistema, agendamento não realizado, pagamento não processado! '.$e->getMessage(),
							'errors' => $e->getMessage(),
						], 500);
					}
				}
			}
		}

		// caso o pagamento seja diferente do empresarial ou multcartoes
		$valor =  Payment::convertRealEmCentavos( number_format( $valor_total-$valor_desconto, 2, ',', '.') ) ;

		//================================================================= FIM VALIDACOES DO TIPO DE PAGAMENTO ============================================================//
		/** Verifica se colaborador vai utilizar Crédito Empresarial  */
		if(!is_null($paciente->empresa_id) && $paciente->empresa->pre_autorizar && ($metodoPagamento == Payment::METODO_CRED_EMP || $metodoPagamento == Payment::METODO_CRED_EMP_CRED_IND)) {
			$cartaoToken;
			$cartao;
			$valorFinal = $valor_total-$valor_desconto;
			if($metodoPagamento == Payment::METODO_CRED_EMP_CRED_IND) {
				$valorEmpresarial = floor(($dados->porcentagem * $valorFinal) / 100);
				$valorEmpresarial = number_format($valorEmpresarial, 2);
			} else {
				$valorEmpresarial = $valorFinal;
			}
			$parcelas = $dados->parcelas ?? 1;

			$respAgend = $this->saveAgendamento($paciente, $agendamentoItens, $metodoPagamento, $cod_cupom_desconto, 'titulo pedido', $parcelas, $valorFinal, $valorEmpresarial);
			if($respAgend) {
				CVXCart::clear(); /** Limpa carrinho e retorna reposta */
				return response()->json([
					'status' => true,
					'mensagem' => 'O Pedido foi realizado com sucesso!'
				]);
			} else {
				return response()->json([
					'status' => false,
					'mensagem' => 'Erro ao solicitar pre-autorizaçao!'
				]);
			}
		}
		//================================================================= AGENDAMENTOS ============================================================//
		//-- dados do comprador---------------------------------------
		$customer = Paciente::findorfail($paciente_id);
		$customer->load('user');
		$customer->load('documentos');
		$customer->load('contatos');
		// caso o pagamento seja cartao de credito + empresarial executa a condição
		if($metodoPagamento == Payment::METODO_CRED_EMP_CRED_IND){
			// cria o pedido para o pagamento com cartao empresarial
			$pedidoEmpresarial  = new Pedido();
			$descricao = '';
			$dt_pagamento = date('Y-m-d H:i:s');
			$pedidoEmpresarial->titulo         = $titulo_pedido;
			$pedidoEmpresarial->descricao      = $descricao;
			$pedidoEmpresarial->dt_pagamento   = $dt_pagamento;
			$pedidoEmpresarial->tp_pagamento   = $this->verificaTp($metodoPagamento, 1);
			$pedidoEmpresarial->paciente_id    = $paciente_id;
			$pedidoEmpresarial->cartao_id = $cartaoEmpresarialDados->id;

			// cria o pedido para o pagamento com cartao de credito
			$pedidoCredito = new Pedido();
			$descricao = '';
			$dt_pagamento = date('Y-m-d H:i:s');
			$pedidoCredito->titulo         = $titulo_pedido;
			$pedidoCredito->descricao      = $descricao;
			$pedidoCredito->dt_pagamento   = $dt_pagamento;
			$pedidoCredito->tp_pagamento   = $this->verificaTp($metodoPagamento, 2);
			$pedidoCredito->paciente_id    = $paciente_id;

			// valor a ser pago com credito empresarial
			$restoEmpresarial = $empresarial;
			// valor a ser pago com cartão de credito
			$restoCredito = $cartaoCredito ;
		}

		$valorEmpresa=null;
		$valorCredito=null;
		$payment_ids=[];
		$result_agendamentos=[];
		$agendamento_id=[];
		$atendimento_id = [];
		$empresarialSalvar=0;
		$creditoCartaoSalvar =  0;
		$resp =null;
		$conta=0;
		$valores=[];
		$empresarialLaco=1;
		$pedidos_array=[];
		$dividirValores =0;

		// caso o metodo de pagamento seja diferente de credito + empresarial entra na condição
		if($metodoPagamento != Payment::METODO_CRED_EMP_CRED_IND){
			// caso o metodo seja apenas credito empresarial
			if($metodoPagamento == Payment::METODO_CRED_EMP) {

				$pedido->cartao_id = $cartaoEmpresarialDados->id;

				if (!$pedido->save()) {
					########### FINISHIING TRANSACTION ##########
					DB::rollback();
					#############################################
					return response()->json(['status' => false, 'mensagem' => 'O Pedido não foi salvo. Por favor, tente novamente.']);
				}
				$MerchantOrderId =$pedido->id;
				array_push($pedidos_array,$pedido->id);
			} else {

				if (!$pedido->save()) {
					########### FINISHIING TRANSACTION ##########
					DB::rollback();
					#############################################
					return response()->json(['status' => false, 'mensagem' => 'O Pedido não foi salvo. Por favor, tente novamente.']);
				}
				$MerchantOrderId =$pedido->id;
				array_push($pedidos_array,$pedido->id);
			}
		}

		foreach($agendamentoItens as $i=>$item_agendamento) {

			$MerchantOrderId = $pedido->id;
			$agendamento 						= new Agendamento();
			$agendamento->te_ticket				= UtilController::getAccessToken();
			$agendamento->cs_status         	= 10;
			$agendamento->bo_remarcacao     	= 'N';
			$agendamento->bo_retorno        	= 'N';
			$agendamento->paciente_id       	= $item_agendamento->paciente_id;
			$agendamento->vigencia_paciente_id	= session('vigencia_id');

			if(!empty($item_agendamento->atendimento_id)) {
				$agendamento->dt_atendimento    = isset($item_agendamento->dt_atendimento) && !empty($item_agendamento->dt_atendimento) ? \DateTime::createFromFormat('d/m/Y H:i', $item_agendamento->dt_atendimento)->format('Y-m-d H:i:s') : null;
				$agendamento->clinica_id        = $item_agendamento->clinica_id;
				$agendamento->filial_id			= $item_agendamento->filial_id;
				$agendamento->atendimento_id    = $item_agendamento->atendimento_id;
				$agendamento->profissional_id   = isset($item_agendamento->profissional_id) && !empty($item_agendamento->profissional_id) ? $item_agendamento->profissional_id : null;
			} elseif(!empty($item_agendamento->checkup_id)) {
				$agendamento->checkup_id = $item_agendamento->checkup_id;
			}

			//--busca o cupom de desconto------------
			$cupom_desconto = $this->buscaCupomDesconto($cod_cupom_desconto);

			if (sizeof($cupom_desconto) > 0) {
				$agendamento->cupom_id = $cupom_desconto->first()->id;
			}

			if ($agendamento->save()) {
				$agendamento->atendimentos()->attach( $item_agendamento->atendimento_id, ['created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s') ] );
				$agendamento_id[] = $agendamento->id;
				$atendimento_id[] = $item_agendamento->atendimento_id;
				$agendamento->load('atendimento');
				$agendamento->load('clinica');
				$agendamento->load('filial');
				$agendamento->load('profissional');
				$agendamento->load('paciente');

				$item_pedido = new Itempedido();

				if (!empty($item_agendamento->atendimento_id)) {
					$conta = $conta+1;
					$user_session = Auth::user();
					$plano = $user_session->paciente->getPlanoAtivo($user_session->paciente->id);

					$atendimento = Atendimento::where(['atendimentos.id' => $item_agendamento->atendimento_id])
						->with(['precoAtivo' => function($query) use($plano) {
							$query->where('precos.plano_id', '=', $plano);
						}])->first();


					$resp = json_decode(json_encode($atendimento), true);

					$number = str_replace(',', '.', preg_replace('#[^\d\,]#is', '', $resp['preco_ativo']['vl_comercial']));

					$valores[] = $number ;

					/**
					 * PAGAMENTO COM CARTAO DE CREDITO + EMPRESARIAL
					 *
					 * Efetuar as validações do valores com base no valor escolhido em
					 * cada cartao pelo o usuario.
					 *
					 */
					if ($metodoPagamento == Payment::METODO_CRED_EMP_CRED_IND) {
						$empresarial = $restoEmpresarial;

						if ($empresarial != $empresarialSalvar) {
							$empresarialSalvar += $number;

							if ($empresarialSalvar >$empresarial) {
								$resp = $empresarialSalvar - $empresarial;
								$empresarialSalvar = $empresarialSalvar -$resp;
								$creditoCartaoSalvar += $resp;
							}
						} else {
							$creditoCartaoSalvar +=$number;
						}

					} else {
						$item_pedido->agendamento_id = $agendamento_id[$i]  ;
						$item_pedido->pedido_id = $MerchantOrderId;
						$item_pedido->valor = $number   * (1 - $percentual_desconto);
					}
				} else {
					foreach ($item_agendamento->itens as $item) {
						$dataHoraCheckup = new Datahoracheckup();
						$dataHoraCheckup->agendamento_id = $agendamento->id;
						$dataHoraCheckup->itemcheckup_id = $item['id'];

						if (!empty($item['dt_atendimento'])) {
							$dtAtendimento = Carbon::createFromFormat('d/m/Y H:i', $item['dt_atendimento'])->toDateTimeString();
							$dataHoraCheckup->dt_atendimento = $dtAtendimento;
						}

						if (!$dataHoraCheckup->save()) {
							DB::rollback();
							return response()->json([
								'mensagem' => 'Erro ao salvar a dataHoraCheckup!'
							], 500);
						}
					}
					$item_pedido->valor	= ItemCheckup::query()->where('checkup_id', $checkups_id)->sum('vl_com_checkup');
				}

				if($metodoPagamento != Payment::METODO_CRED_EMP_CRED_IND) {
					if(!$item_pedido->save()) {
						return response()->json(['status' => false, 'mensagem' => 'O Pedido não foi salvo. Por favor, tente novamente.']);
					}
				}

				if($metodoPagamento == Payment::METODO_CRED_EMP_CRED_IND) {
					// condição  executa apenas quando o laço dos agendamentos estiver sido executado
					if (($conta) == count($agendamentoItens)) {

						$pedidoEmpresarial->save();
						$pedidoCredito->save();
						$valorEmpresa = $empresarialSalvar;
						$valorCredito = $creditoCartaoSalvar ;
						$contaa=0;
						$pedido =0;
						$totalcredito=0;


						for ($o =0; $o<count($agendamento_id) ; $o++) {

							$contaa = $contaa+1;

							if(($empresarialLaco) != 0){
								$item_pedidoEmpresarial = new Itempedido();
								$item_pedidoEmpresarial->agendamento_id = $agendamento_id[$o] ;
								$item_pedidoEmpresarial->pedido_id = $pedidoEmpresarial->id ;
								$item_pedidoEmpresarial->valor =str_replace(",",".", number_format( $empresarialSalvar, 2, ',', '.'));
								$item_pedidoEmpresarial->save();
								$pedido= $pedidoEmpresarial->id ;
								$empresarialLaco=0;
								array_push($pedidos_array, $item_pedidoEmpresarial);

								// caso haja apenas um procedimento para para realizar o pagamento
								if(count($agendamento_id)==1){
									$item_pedidoCredito = new Itempedido();
									$totalcredito = ($creditoCartaoSalvar  );

									$item_pedidoCredito->agendamento_id = $agendamento_id[$o];
									$item_pedidoCredito->pedido_id = $pedidoCredito->id;
									$item_pedidoCredito->valor =str_replace(",",".", number_format( $totalcredito, 2, ',', '.'));
									$item_pedidoCredito->save();
									$pedido=$pedidoCredito->id;
									array_push($pedidos_array, $item_pedidoCredito);
								}
								// se houver apenas um procedimento essa condição não será utilizada
							} else {

								$item_pedidoCredito = new Itempedido();
								if( $dividirValores ==0){
									$dividirValores =1;
									if(count($agendamento_id) >2){
										$dividir = count($agendamento_id)-1;
										$totalcredito = (($creditoCartaoSalvar )/ $dividir  );
									}else{
										$totalcredito = ($creditoCartaoSalvar  );
									}
								}

								$item_pedidoCredito->agendamento_id = $agendamento_id[$o];
								$item_pedidoCredito->pedido_id = $pedidoCredito->id;
								$item_pedidoCredito->valor =str_replace(",",".", number_format( $totalcredito, 2, ',', '.'));
								$item_pedidoCredito->save();
								$pedido=$pedidoCredito->id;
								array_push($pedidos_array, $item_pedidoCredito);

							}

							//--busca pelas especialidades do atendimento--------------------------------------
							$especialidade=null;
							$especialidade_obj=null;

							$especialidade_obj = new Especialidade();
							$especialidade = $especialidade_obj->getNomeEspecialidade( $agendamento_id[$o]);
							$agenda = Agendamento::find($agendamento_id[$o]);
							$agenda->save();

							$agenda->load('atendimento');
							$agenda->load('clinica');
							$agenda->load('filial');
							$agenda->load('profissional');
							$agenda->load('paciente');
							$agenda->ds_atendimento =  $especialidade['ds_atendimento'];
							$agenda->nome_especialidade = $especialidade['nome_especialidades'];

							//--busca os itens de pedido relacionados------------------------------------------
							$agenda->load('itempedidos');
							if(!is_null($agendamento->checkup_id)) {
								$agenda->load('checkup');
								$agenda->load('datahoracheckups');
							}

							$agenda->valores = $valores[$o];
							array_push($result_agendamentos,  $agenda );
							array_push($payment_ids,   $pedido);

						}
					}
					$MerchantOrderId =$pedido;

					// caso o metodo de pagamento não seja por dois meios, cartao de credito e empresarial
				} else {
					//--busca pelas especialidades do atendimento--------------------------------------

					$especialidade_obj = new Especialidade();
					$especialidade = $especialidade_obj->getNomeEspecialidade($agendamento->id);

					$agendamento->ds_atendimento = $especialidade['ds_atendimento'];
					$agendamento->nome_especialidade = $especialidade['nome_especialidades'];

					//--busca os itens de pedido relacionados------------------------------------------
					$agendamento->load('itempedidos');

					if(!is_null($agendamento->checkup_id)) {
						$agendamento->load('checkup');
						$agendamento->load('datahoracheckups');
					}
					$MerchantOrderId =$pedido->id;
					array_push($result_agendamentos,  $agendamento);
					array_push($payment_ids,    $pedido->id);
				}
			}
		}

		//=================================================================FIM AGENDAMENTOS ============================================================//

		/** ================================================================= REALIZACAO DO PAGAMENTO  ============================================================*/
		/**pagamento com cartão   empresarial*/
		if($metodoPagamento == Payment::METODO_CRED_EMP) {
			$metodoCartao = 1;
			try {
				$criarPagamento = $client->getOrders()->createOrder(FuncoesPagamento::criarPagementoEmpresarial($empresa->mundipagg_token,$valorCartaoEmpresarialOne, 1, "Doutor hoje cart",$cartaoPaciente->card_token, "Doutor hoje"  ))    ;
			} catch(\Exception $e) {
				DB::rollBack();
				return response()->json([
					'mensagem' => 'Não foi possivel efetuar o pagamento com o cartao de crédito  , pagamento não efetuado! '.$e->getMessage(),
					'errors' => $e->getMessage(),
				], 500);
			}
		}

		/**pagamento com cartão de credito e empresarial*/
		if($metodoPagamento == Payment::METODO_CRED_EMP_CRED_IND) {

			$dados = FuncoesPagamento::pagamentoMultiMeio(
				$empresa->mundipagg_token,  // custom token empresa buscar
				$valorCartaoCredito+$valorCartaoEmpresarial,
				$titulo_pedido,
				$valorCartaoCredito,
				$valorCartaoEmpresarial,
				$dados->parcelas,
				1,
				'Doutor Hoje',
				'Doutor Hoje',
				$cartao,
				$cartaoPaciente->card_token,
				$metodoCartao,
				1
			);

			try {
				$criarPagamento =   $client->getOrders()->createOrder($dados);
			} catch(\Exception $e) {
				DB::rollBack();
				return response()->json([
					'mensagem' => 'Erro ao efetuar o pagamento com o cartão de crédito! '. $e->getMessage(),
					'errors' => $e->getMessage(),
				], 500);
			}
		}

		// pagamento com cartão de credito
		if($metodoPagamento == Payment::METODO_CRED_IND) {
			try{

				$criarPagamento =  $client->getOrders()->createOrder(FuncoesPagamento::criarPagamentoCartaoUnico($paciente->mundipagg_token,$valor, $dados->parcelas, "Doutor Hoje",$cartao, "Doutor hoje",$metodoCartao,$dados->cvv ))    ;

			}catch(\Exception $e){
				DB::rollBack();
				return response()->json([
					'mensagem' => 'Não foi possivel efetuar o pagamento com o cartao de crédito, pagamento  não efetuado! '. $e->getMessage(),
					'errors' => $e->getMessage(),
				], 500);
			}

		}

		/** Boleto */
		if($metodoPagamento == Payment::METODO_BOLETO) {
			try{
				//$paciente->mundipagg_token
				$enderecos =[];

				$endereco = $paciente->enderecos()->first();
				$paciente->load('documentos');
				if($endereco->cs_status != "I"){
					$endereco = 	$client->getCustomers()->getAddress($paciente->mundipagg_token,$endereco->mundipagg_token );
					$lista = explode(",", $endereco->line1);

					/*$paciente->user->name,
						$paciente->user->email,
						$paciente->documentos[0]->te_documento,
						$lista[0],
						$endereco->line2,
						$lista[1],
						$endereco->zipCode,
						$lista[2],
						$endereco->city,
						$endereco->state*/

					$object = FuncoesPagamento::pagamentoBoleto($valor//,$paciente->mundipagg_token,$endereco->id


					);

					$criarPagamento = $client->getOrders()->createOrder($object) ;
					dd( $criarPagamento);
					//$criarPagamento = $client->getOrders()->createOrder(FuncoesPagamento::pagamentoBoleto($valor,$paciente->nm_primario . ' ' . $paciente->nm_secundario,$user->email ,$dados->documento_endereco, $dados->rua_endereco, $dados->numero_endereco, $endereco->zipCode, $endereco->zipCode, $dados->bairro_endereco,  $endereco->city,  $endereco->state, 123456, "Pagar até o vencimento boleto")) ;
				}

				//	$criarPagamento = $client->getOrders()->createOrder(FuncoesPagamento::pagamentoBoleto($valor,$paciente->nm_primario . ' ' . $paciente->nm_secundario,$user->email ,$dados->documento_endereco, $dados->rua_endereco, $dados->numero_endereco, $dados->complemento_endereco, $dados->cep_endereco, $dados->bairro_endereco, $dados->cidade_endereco, $dados->estado_endereco, 123456, "Pagar até o vencimento boleto")) ;
				//echo json_encode($criarPagamento); die;
			} catch(\Exception $e) {
				DB::rollBack();
				return response()->json([
					'mensagem' => 'Não foi possivel gerar o boleto de pagamento! '.$e->getMessage(),
					'errors' => $e->getMessage(),
				], 500);
			}
		}

		/** Transferencia Bancária */
		if($metodoPagamento == Payment::METODO_TRANSFERENCIA) {
			try{
				$criarPagamento = $client->getOrders()->createOrder(FuncoesPagamento::criarTranferencia($valor,"Doutor hoje",$paciente->mundipagg_token));
				//var_dump($criarPagamento); die;
			} catch(\Exception $e) {
				DB::rollBack();
				return response()->json([
					'mensagem' => 'Não foi possivel realizar transferencia bancaria, pagamento não efetuado! '. $e->getMessage(),
					'errors' => $e->getMessage(),
				], 500);
			}
		}
		//================================================================= FIM REALIZACAO DO PAGAMENTO  ============================================================//


		if(!empty($criarPagamento)){
			$dadosPagamentos = json_decode( json_encode($criarPagamento), true);

			if(
				$dadosPagamentos['charges'][0]['last_transaction']['status'] ==="not_authorized" ||
				$dadosPagamentos['charges'][0]['last_transaction']['status'] ==="refunded" ||
				$dadosPagamentos['charges'][0]['last_transaction']['status'] ==="voided" ||
				$dadosPagamentos['charges'][0]['last_transaction']['status'] ==="partial_refunded" ||
				$dadosPagamentos['charges'][0]['last_transaction']['status'] ==="partial_void" ||
				$dadosPagamentos['charges'][0]['last_transaction']['status'] ==="error_on_voiding" ||
				$dadosPagamentos['charges'][0]['last_transaction']['status'] ==="error_on_refunding" ||
				$dadosPagamentos['charges'][0]['last_transaction']['status'] ==="waiting_cancellation" ||
				$dadosPagamentos['charges'][0]['last_transaction']['status'] ==="with_error" ||
				$dadosPagamentos['charges'][0]['last_transaction']['status'] ==="failed"
			) {
				DB::rollback();
				return response()->json([

					'code' => $dadosPagamentos['charges'][0]['last_transaction']['gateway_response']['code'],
					'mensagem' => 'Pagamento não foi realizado e o agendamento não foi processado ! '.$dadosPagamentos['charges'][0]['last_transaction']['status'],

				], 422);
			} else {
				//--enviar mensagem informando o pre agendamento da solicitacao----------------
				foreach($result_agendamentos as $agendamento){
					try {
						$customer->credit_card_brand = $dadosPagamentos['charges'][0]['last_transaction']['card']['brand'];
						if(!is_null($agendamento->atendimento_id))
							$this->enviarEmailPreAgendamento($customer, $MerchantOrderId, $agendamento);
					} catch (Exception $e) {}

				}

				foreach($pedidos_array as $pedidosArray){

					$Payment                                 	= new Payment();
					$Payment->merchant_order_id             	= $dadosPagamentos['id'];
					$Payment->payment_id                     	= $dadosPagamentos['charges'][0]['id'];
					$Payment->tid 								= $metodoPagamento == Payment::METODO_CRED_IND ? $dadosPagamentos['charges'][0]['last_transaction']['acquirer_tid'] : '';
					$Payment->payment_type 						= $dadosPagamentos['charges'][0]['payment_method'];
					$Payment->amount                        	= $dadosPagamentos['items'][0]['amount'];
					$Payment->currency                     		= $dadosPagamentos['charges'][0]['currency'];
					$Payment->country                     		= "BRA";
					$Payment->installments 				     	= $metodoPagamento == Payment::METODO_CRED_IND ? $dadosPagamentos['charges'][0]['last_transaction']['installments'] : 0;
					$Payment->pedido_id  						= isset($pedidosArray->pedido_id) ? $pedidosArray->pedido_id : $pedidosArray;
					$Payment->cs_status							=  $dadosPagamentos['charges'][0]['last_transaction']['status'];
					$Payment->cielo_result                 		= json_encode($criarPagamento);
					$Payment->save();
				}

				$dados = json_decode(json_encode($criarPagamento), true);

				$boleto = null;
				if($metodoPagamento == Payment::METODO_BOLETO) {

					$boleto = [
						"instrucoes" => $dados['charges'][0]['last_transaction']['instructions'],
						"url" => 	$dados['charges'][0]['last_transaction']['url'],
						"qr_code" => $dados['charges'][0]['last_transaction']['qr_code'],
						"pdf_url" => $dados['charges'][0]['last_transaction']['pdf']
					];
					//	$this->enviarEmailPagamentoRealizado($paciente, $pedido, $dados['charges'][0]['last_transaction']['url']);
//                     $url_boleto = $dados['charges'][0]['last_transaction']['url'];
//                     $this->enviarEmailPreAgendamentoBoleto($paciente, $pedido, $agendamento, $url_boleto);
				}

				$transferencia = null;
				if($metodoPagamento == Payment::METODO_TRANSFERENCIA) {
					$transferencia = [
						"metodo" => $dados['charges'][0]['payment_method'],
						"url" => 	$dados['charges'][0]['last_transaction']['url']	,
						'datas'=>$dados
					];
				}

				########### FINISHIING TRANSACTION ##########
				DB::commit();
				#############################################
				CVXCart::clear();

				$valor_total_pedido = $valor_total-$valor_desconto;
				$request->session()->put('result_agendamentos', $result_agendamentos);

				$request->session()->put('pedido',$MerchantOrderId);

				$request->session()->put('valor_empresa', $valorEmpresa);
				$request->session()->put('varlor_credito', $valorCredito);
				$request->session()->put('valor_total_pedido', $valor_total_pedido);
				$request->session()->put('descricao_boleto', $boleto);
				$request->session()->put('trans_bancario', $transferencia)	;

				return response()->json(['status' => true, 'mensagem' => 'O Pedido foi realizado com sucesso!', 'pagamento' => $criarPagamento]);
			}
		} else {
			DB::rollback();
			return response()->json([
				'mensagem' =>'informe um tipo de pagamento correto, tipo de pagamento enviado: ' ,
			], 422);
		}
	}

	public function saveAgendamento(Paciente $paciente, Array $agendamentos, $metodoPagamento, $codCupomDesconto, $titulo_pedido, $parcelas, $valor_total, $valor_emp = null, CartaoPaciente $cartaoInd = null)
	{
		$dt_pagamento = date('Y-m-d H:i:s');
		$user_session = Auth::user();
		$valorEmp = $valor_emp ?? 0;

		// cria o pedido para o pagamento com cartao individual
		if($metodoPagamento != Payment::METODO_CRED_EMP) {
			$pedidoInd = new Pedido();
			$pedidoInd->titulo = $titulo_pedido;
			$pedidoInd->descricao = null;
			$pedidoInd->dt_pagamento = $dt_pagamento;
			$pedidoInd->tp_pagamento = $this->verificaTp($metodoPagamento, 2);
			$pedidoInd->paciente_id = $paciente->id;
			if (!is_null($cartaoInd)) $pedidoInd->cartao_id = $cartaoInd->id;
			$pedidoInd->saveOrFail();
		}

		// cria o pedido para o pagamento com cartao empresarial
		if(in_array($metodoPagamento, [Payment::METODO_CRED_EMP, Payment::METODO_CRED_EMP_CRED_IND])) {
			$pedidoEmpresarial  = new Pedido();

			$pedidoEmpresarial->titulo			= $titulo_pedido;
			$pedidoEmpresarial->descricao		= null;
			$pedidoEmpresarial->dt_pagamento	= $dt_pagamento;
			$pedidoEmpresarial->tp_pagamento	= $this->verificaTp($metodoPagamento, 1);
			$pedidoEmpresarial->paciente_id		= $paciente->id;
			$pedidoEmpresarial->cartao_id		= $paciente->empresa->cartaoPacientes()->first()->id;
			$pedidoEmpresarial->saveOrFail();

			$status_agendamento = $paciente->empresa->pre_autorizar ? Agendamento::PRE_AUTORIZAR : Agendamento::PRE_AGENDADO;
		} else {
			$status_agendamento = Agendamento::PRE_AGENDADO;
		}

		if($metodoPagamento == Payment::METODO_CRED_EMP_CRED_IND) {
			$valor_ind = $valor_total - $valor_emp;

			if($parcelas > 3) {
				$valor_ind_pag = Payment::convertRealEmCentavos(number_format($valor_ind * (1 + 0.05) ** $parcelas, 2, ',', '.'));
			} else {
				$valor_ind_pag = Payment::convertRealEmCentavos(number_format($valor_ind , 2, ',', '.'));
			}
		} else {
			$valor_ind = null;
			$valor_ind_pag = null;
		}

		foreach($agendamentos as $i=>$agend) {
			$agendamento = new Agendamento();
			$agendamento->te_ticket 			= UtilController::getAccessToken();
			$agendamento->cs_status 			= $status_agendamento;
			$agendamento->bo_remarcacao 		= 'N';
			$agendamento->bo_retorno 			= 'N';
			$agendamento->paciente_id 			= $agend->paciente_id;
			$agendamento->vigencia_paciente_id	= session('vigencia_id');

			if(!empty($agend->atendimento_id)) {
				$agendamento->dt_atendimento    = isset($agend->dt_atendimento) && !empty($agend->dt_atendimento) ? \DateTime::createFromFormat('d/m/Y H:i', $agend->dt_atendimento)->format('Y-m-d H:i:s') : null;
				$agendamento->clinica_id        = $agend->clinica_id;
				$agendamento->filial_id			= $agend->filial_id;
				$agendamento->atendimento_id    = $agend->atendimento_id;
				$agendamento->profissional_id   = isset($agend->profissional_id) && !empty($agend->profissional_id) ? $agend->profissional_id : null;
			} elseif(!empty($agend->checkup_id)) {
				$agendamento->checkup_id = $agend->checkup_id;
			}

			//--busca o cupom de desconto------------
			$cupomDesconto = $this->buscaCupomDesconto($codCupomDesconto);

			if (count($cupomDesconto) > 0) {
				$agendamento->cupom_id = $cupomDesconto->first()->id;
			}

			if ($agendamento->save()) {
				$agendamento->atendimentos()->attach($agend->atendimento_id, ['created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);
				$agendamento_id[] = $agendamento->id;
				$atendimento_id[] = $agend->atendimento_id;

				$itempedido = new Itempedido();

				if (!empty($agendamento->atendimento_id)) {
					$atendimento = Atendimento::where(['atendimentos.id' => $agendamento->atendimento_id])
						->with(['precoAtivo' => function($query) use($user_session) {
							$query->where('precos.plano_id', '=', $user_session->paciente->plano_ativo->id);
						}])->first();

					$vlAtendRest = $atendimento->precoAtivo->vl_comercial_bd;
					$valores[] = $atendimento->precoAtivo->vl_comercial_bd;

					/** Define quanto será pago no cartão EMPRESARIAL */
					if(in_array($metodoPagamento, [Payment::METODO_CRED_EMP, Payment::METODO_CRED_EMP_CRED_IND])) {
						if($atendimento->precoAtivo->vl_comercial_bd <= $valorEmp) {
							$itempedido->agendamento_id 	= $agendamento->id;
							$itempedido->pedido_id 			= $pedidoEmpresarial->id;
							$itempedido->valor				= $atendimento->precoAtivo->vl_comercial_bd;
							$itempedido->saveOrFail();

							$valorEmp -= $atendimento->precoAtivo->vl_comercial_bd; //Subtrai o valor do atendimento do valor a ser pago no cartão empresarial
							$vlAtendRest -= $atendimento->precoAtivo->vl_comercial_bd;
						} else {
							$itempedido->agendamento_id 	= $agendamento->id;
							$itempedido->pedido_id 			= $pedidoEmpresarial->id;
							$itempedido->valor				= $valorEmp;
							$itempedido->saveOrFail();

							$vlAtendRest -= $valorEmp;
							$valorEmp = 0;
						}

					}
					$valorEmp = number_format($valorEmp, 2);

					if($valorEmp == 0 && $vlAtendRest != 0) {
						$itempedido = new Itempedido();
						$itempedido->agendamento_id		= $agendamento->id;
						$itempedido->pedido_id			= $pedidoInd->id;
						$itempedido->valor				= $vlAtendRest;
						$itempedido->saveOrFail();
					}
				}
			}
		}

		DB::commit();
		$agendamentos = Agendamento::whereIn('id', $agendamento_id)->with(['itempedidos.pedido'])->get();
		$this->enviarEmailPreAutorizar($paciente, $agendamentos);

		return true;
	}

	private function verificaTp($tp, $op = null) {
		switch($tp) {
			case Payment::METODO_CRED_EMP:
				return 'empresarial';break;
			case Payment::METODO_CRED_EMP_CRED_IND:
				if(!is_null($op) && $op == 1) {
					return 'empresarial';break;
				} elseif(!is_null($op) && $op == 2) {
					return "individual";break;
				}
			case Payment::METODO_CRED_IND:
				return "individual";break;
			case Payment::METODO_BOLETO:
				return "boleto";break;
			case Payment::METODO_TRANSFERENCIA:
				return "transferencia";break;
		}
	}




	/**
	 * validarCupomDesconto a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function validarCupomDesconto($cod_cupom_desconto)
	{
		$cupom_desconto = [];

		if ($cod_cupom_desconto == '') {
			return 0;
		}

		$ct_date = date('Y-m-d H:i:s');

		$cupom_desconto = CupomDesconto::where('codigo', '=', $cod_cupom_desconto)->where('cs_status', '=', 'A')->whereDate('dt_inicio', '<=', date('Y-m-d H:i:s', strtotime($ct_date)))->whereDate('dt_fim', '>=', date('Y-m-d H:i:s', strtotime($ct_date)))->first();

		if($cupom_desconto === null) {
			return 0;
		}

		$user_session = Auth::user();
		$paciente_id = $user_session->paciente->id;
		$cupom_id = $cupom_desconto->id;

		$agendamento_cupom = Agendamento::where('paciente_id', '=', $paciente_id)->where('cupom_id', '=', $cupom_id)->get();

		if(sizeof($agendamento_cupom) > 0) {
			return 0;
		}

		$percentual = $cupom_desconto->percentual/100;

		return $percentual;
	}

	/**
	 * buscaCupomDesconto a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function buscaCupomDesconto($cod_cupom_desconto)
	{
		$cupom_desconto = [];

		if ($cod_cupom_desconto == '') {
			return $cupom_desconto;
		}

		$ct_date = date('Y-m-d H:i:s');

		$cupom_desconto = CupomDesconto::where('codigo', '=', $cod_cupom_desconto)->where('cs_status', '=', 'A')->whereDate('dt_inicio', '<=', date('Y-m-d H:i:s', strtotime($ct_date)))->whereDate('dt_fim', '>=', date('Y-m-d H:i:s', strtotime($ct_date)))->get();

		if($cupom_desconto === null) {
			return $cupom_desconto;
		}

		$user_session = Auth::user();
		$paciente_id = $user_session->paciente->id;
		$cupom_id = $cupom_desconto->first()->id;

		$agendamento_cupom = Agendamento::where('paciente_id', '=', $paciente_id)->where('cupom_id', '=', $cupom_id)->get();

		if(sizeof($agendamento_cupom) > 0) {
			return [];
		}

		return $cupom_desconto;
	}


	public function testeEnviarEmail(){

		$send_message = false;
		try {
			$paciente_id = 26;
			$paciente = Paciente::with('user')->findOrFail($paciente_id);
//      		dd($paciente);

//     		$verify_hash = Crypt::encryptString($paciente_id);
//     		$from = 'contato@doutorhoje.com.br';
//     		$to = $paciente->user->email;
//     		$subject = 'Contato DoutorHoje';

//     		$paciente_nm_primario = $paciente->nm_primario;
//     		$paciente_email = $paciente->user->email;

//     		$url = route('ativar_conta', $verify_hash);
			$user_temp = new UserController();
			$send_message = $user_temp->sendTokenEmail('111111', 'teocomp@gmail.com', 'Theo Duarte', '01/12/2018 às 16:49:00');

		} catch (Exception $e) {
			report($e);

			return false;
		}

//         $send_message = $this->enviarEmailPreAgendamento($paciente, $pedido, $agendamento);

		if ($send_message) {
			dd('O e-mail foi enviado com sucesso!');
			//dd($output);
		}
		dd('O e-mail não foi enviado.');

//         return view('emails.email_confirma_cadastro', compact('paciente_nm_primario', 'url', 'paciente_email'));
	}

	public function enviarEmailPreAutorizar(Paciente $paciente, Collection $agendamentos)
	{
		foreach ($agendamentos as $agendamento) {
			$mensagem					= new Mensagem();
			$mensagem->rma_nome			= $paciente->nm_primario.' '.$paciente->nm_secundario;
			$mensagem->rma_email		= $paciente->user->email;
			$mensagem->assunto			= 'Pré-Autorização Solicitada';

			$nome 						= $paciente->nm_primario.' '.$paciente->nm_secundario;
			$email 						= $paciente->user->email;
			$telefone 					= $paciente->contatos->first()->ds_contato;
			$especialidade				= Especialidade::getNomeEspecialidade($agendamento->id);

			$agendamento->ds_atendimento 	= $especialidade['ds_atendimento'];
			$agendamento->nm_especialidade 	= $especialidade['nome_especialidades'];

			if (!empty($item['dt_atendimento'])) {
				$dt_atendimento = Carbon::createFromFormat('d/m/Y H:i', $agendamento->dt_atendimento);
			} else {
				$dt_atendimento = null;
			}

			if (!empty($agendamento->profissional_id)) {
				$agendamento->nm_profissional		= "Dr(a): ".$agendamento->profissional->nm_primario." ".$agendamento->profissional->nm_secundario;
			} else {
				$agendamento->nm_profissional = '---------';
			}

			if ($agendamento->filial->endereco != null) {
				$enderecoClinica = $agendamento->filial->endereco;
				$agendamento->enderecoAgendamento = $enderecoClinica->te_endereco.', '.$enderecoClinica->nr_logradouro.', '.$enderecoClinica->te_bairro.', '.$enderecoClinica->cidade->nm_cidade.'/ '.$enderecoClinica->cidade->sg_estado;
			} else {
				$agendamento->enderecoAgendamento = '--------------------';
			}

			$conteudo = "<h4>Pré-Autorização de Cliente:</h4><br>
							<ul>
								<li>Nome: $nome</li>
								<li>E-mail: $email</li>
								<li>Telefone: $telefone</li>
							</ul>";

			$mensagem->conteudo     	= $conteudo;
			$mensagem->saveOrFail();

			$destinatario                      = new MensagemDestinatario();
			$destinatario->tipo_destinatario   = 'DH';
			$destinatario->mensagem_id         = $mensagem->id;
			$destinatario->destinatario_id     = 1;
			$destinatario->saveOrFail();

			$destinatario                      = new MensagemDestinatario();
			$destinatario->tipo_destinatario   = 'DH';
			$destinatario->mensagem_id         = $mensagem->id;
			$destinatario->destinatario_id     = 3;
			$destinatario->saveOrFail();

			/** Dados da mensagem para o cliente */
			$msgCliente            		= new Mensagem();
			$msgCliente->rma_nome     	= 'Contato DoutorHoje';
			$msgCliente->rma_email       = 'contato@doutorhoje.com.br';
			$msgCliente->assunto     	= 'Pré-Agendamento Solicitado';

			$conteudo = "<h4>Seu Pré-Agendamento:</h4><br>
						<ul>
							<li>Nº do Agendamento: {$agendamento->id}</li>
							<li>{$agendamento->nm_especialidade}</li>
							<li>Dr(a): {$agendamento->nm_profissional}</li>
							<li>Data: ".(!is_null($dt_atendimento) ? $dt_atendimento->format('d/m/Y') : '----')."</li>
							<li>Horário: ".(!is_null($dt_atendimento) ? $dt_atendimento->format('H:i') : '----')." (por ordem de chegada)</li>
							<li>Endereço: {$agendamento->enderecoAgendamento}</li>
						</ul>";

			$msgCliente->conteudo     	= $conteudo;
			$msgCliente->saveOrFail();

			$from = 'contato@doutorhoje.com.br';
			$to = $email;
			$subject = 'Autorização Solicitada';

			$htmlCliente = view('payments.email_confirma_pre_autorizacao', compact('agendamento', 'dt_atendimento'));
			$htmlCliente = str_replace(["\r", "\n", "\t"], '', $htmlCliente);

			$send_message[] = UtilController::sendMail($to, $from, $subject, $htmlCliente);
		}

		if(!is_null($paciente->empresa->resp_financeiro_id))
			$toEmpresa = $paciente->empresa->responsavelFinanceiro->user->email;
		else
			$toEmpresa = $paciente->empresa->representantes()->first()->user->email;

		$subjectEmpresa = 'Autorizar Utilização de Crédito Empresarial';
		$vlPedidoEmpresarial = Agendamento::getValorPedidoEmpresarial($agendamentos->pluck('id')->toArray());
		$verify_hash = Crypt::encryptString($paciente->id.'@'.$paciente->empresa->id.'@'.$agendamentos->pluck('id')->toJson());
		$url = route('autoriza_credito_empresarial', $verify_hash);


		$htmlEmpresa = view('payments.email_autorizar_cred_empresarial', compact('paciente', 'vlPedidoEmpresarial', 'url'));
		$htmlEmpresa = str_replace(["\r", "\n", "\t"], '', $htmlEmpresa);
		$send_message[] = UtilController::sendMail($toEmpresa, $from, $subjectEmpresa, $htmlEmpresa);

		return $send_message;
	}

	/**
	 * enviarEmailPreAgendamento a newly external user created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function enviarEmailPreAgendamento($paciente, $pedido, $agendamento)
	{
		setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
		date_default_timezone_set('America/Sao_Paulo');

		# dados da mensagem
		$mensagem_drhj            		= new Mensagem();

		$mensagem_drhj->rma_nome     	= $paciente->nm_primario.' '.$paciente->nm_secundario;
		$mensagem_drhj->rma_email       = $paciente->user->email;
		$mensagem_drhj->assunto     	= 'Pré-Agendamento Solicitado';

		$nome 		= $paciente->nm_primario.' '.$paciente->nm_secundario;
		$email 		= $paciente->user->email;
		$telefone 	= $paciente->contatos->first()->ds_contato;

		$nm_primario 			= $paciente->nm_primario;
		$nr_pedido 				= sprintf("%010d", $pedido );
		$nome_especialidade 	= "Especialidade/exame: <span>".$agendamento->nome_especialidade."</span>";
		$tipo_atendimento		= "";

		$paciente_id = $paciente->id;
		$plano_ativo_id = Paciente::getPlanoAtivo($paciente_id);
		$preco_ativo = 'R$ 0,00';

		if (!empty($agendamento->atendimento)) {
			if(!is_null($agendamento->atendimento->consulta_id)) {
				$tipo_atendimento = "Consulta";
			}

			if(!is_null($agendamento->atendimento->procedimento_id)) {
				$tipo_atendimento = "Exame";
			}

			$atendimento_id = $agendamento->atendimento->id;
			$atend_temp = new Atendimento();
			$preco_ativo = $atend_temp->getPrecoByPlano($plano_ativo_id, $atendimento_id);
			$preco_ativo = 'R$ '.$preco_ativo->vl_comercial;
		}
//         dd($preco_ativo);
		$tipo_pagamento = '--------';
		$pedido_obj = Pedido::findorfail($pedido);
		if(!empty($pedido_obj)) {
			if($pedido_obj->tp_pagamento == 'Crédito' | $pedido_obj->tp_pagamento == 'credito' | $pedido_obj->tp_pagamento == 'empresarial' | $pedido_obj->tp_pagamento == 'EMPRESARIAL' | $pedido_obj->tp_pagamento == 'individual' |  $pedido_obj->tp_pagamento == 'INDIVIDUAL') {
				$pedido_obj->load('pagamentos');
				$tipo_pagamento = 'CRÉDITO';

				try {
					$crc_brand = $paciente->credit_card_brand;
					$tipo_pagamento = $tipo_pagamento.' - '.strtoupper($crc_brand);
				} catch (\Exception $e) {}

			} else {
				$tipo_pagamento = strtoupper($pedido_obj->tp_pagamento);
			}

		}

		$nome_profissional = '---------';
		$data_agendamento = '---------';
		$hora_agendamento = '---------';

		if (!empty($agendamento->profissional_id)) {
			$nome_profissional		= "Dr(a): ".$agendamento->profissional->nm_primario." ".$agendamento->profissional->nm_secundario."";
		}

		if(!empty($agendamento->clinica->tp_prestador)){
			if($agendamento->consulta_id != null | $agendamento->clinica->tp_prestador == 'CLI') {
				$data_agendamento		= date('d', strtotime($agendamento->getRawDtAtendimentoAttribute())).' de '.strftime('%B', strtotime($agendamento->getRawDtAtendimentoAttribute())).' / '.strftime('%A', strtotime($agendamento->getRawDtAtendimentoAttribute())) ;
				$hora_agendamento		= date('H:i', strtotime($agendamento->getRawDtAtendimentoAttribute())).' (por ordem de chegada)';
			}
		}


		$nome_especialidade 	= "Descrição do atendimento: <span>".$agendamento->ds_atendimento." (".$agendamento->nome_especialidade.")</span>";
		$ds_especialidade		= $agendamento->nome_especialidade;

		$endereco_agendamento = '--------------------';

		//$agendamento->clinica->load('enderecos');
		$agendamento->filial->load('endereco');
		$enderecos_clinica = $agendamento->filial->endereco;

		if ($agendamento->filial->endereco != null) {
			$enderecos_clinica->load('cidade');
			$cidade_clinica = $enderecos_clinica->cidade;

			if ($cidade_clinica != null) {
				$endereco_agendamento = $enderecos_clinica->te_endereco.', '.$enderecos_clinica->nr_logradouro.', '.$enderecos_clinica->te_bairro.', '.$cidade_clinica->nm_cidade.'/ '.$cidade_clinica->sg_estado;
			}
		}

		$agendamento_status = 'Pré-agendado';

		$mensagem_drhj->conteudo     	= "<h4>Pré-Agendamento de Cliente:</h4><br><ul><li>Nome: $nome</li><li>E-mail: $email</li><li>Telefone: $telefone</li></ul>";

		$mensagem_drhj->save();

		/* if(!$mensagem->save()) {
			return redirect()->route('landing-page')->with('error', 'A Sua mensagem não foi enviada. Por favor, tente novamente');
		} */

		$destinatario                      = new MensagemDestinatario();
		$destinatario->tipo_destinatario   = 'DH';
		$destinatario->mensagem_id         = $mensagem_drhj->id;
		$destinatario->destinatario_id     = 1;
		$destinatario->save();

		$destinatario                      = new MensagemDestinatario();
		$destinatario->tipo_destinatario   = 'DH';
		$destinatario->mensagem_id         = $mensagem_drhj->id;
		$destinatario->destinatario_id     = 3;
		$destinatario->save();

		#dados da mensagem para o cliente
		$mensagem_cliente            		= new Mensagem();

		$mensagem_cliente->rma_nome     	= 'Contato DoutorHoje';
		$mensagem_cliente->rma_email       	= 'contato@doutorhoje.com.br';
		$mensagem_cliente->assunto     		= 'Pré-Agendamento Solicitado';
		$mensagem_cliente->conteudo     	= "<h4>Seu Pré-Agendamento:</h4><br><ul><li>Nº do Pedido: $nr_pedido</li><li>$nome_especialidade</li><li>Dr(a): $nome_profissional</li><li>Data: $data_agendamento</li><li>Horário: $hora_agendamento (por ordem de chegada)</li><li>Endereço: $endereco_agendamento</li></ul>";
		$mensagem_cliente->save();

		$destinatario                      = new MensagemDestinatario();
		$destinatario->tipo_destinatario   = 'PC';
		$destinatario->mensagem_id         = $mensagem_cliente->id;
		$destinatario->destinatario_id     = $paciente->user->id;
		$destinatario->save();

		$from = 'contato@doutorhoje.com.br';
		$to = $email;
		$subject = 'Pré-Agendamento Solicitado';

		######################## ENVIO DE EMAIL DE PRE-AGENDAMENTO##############################
		$html_message = view('emails.compra_concluida', compact('nm_primario', 'nr_pedido', 'tipo_atendimento', 'nome_especialidade', 'ds_especialidade', 'preco_ativo', 'nome_profissional', 'tipo_pagamento', 'data_agendamento', 'hora_agendamento', 'endereco_agendamento', 'agendamento_status'))->render();

		$html_message = str_replace(array("\r", "\n", "\t"), '', $html_message);

		$send_message = UtilController::sendMail($to, $from, $subject, $html_message);
		########################################################################################

		return $send_message;
	}

	/**
	 * enviarEmailPreAgendamento a newly external user created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function enviarEmailPreAgendamentoBoleto($paciente, $pedido, $agendamento, $url_boleto)
	{
		setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
		date_default_timezone_set('America/Sao_Paulo');

		# dados da mensagem
		$mensagem_drhj            		= new Mensagem();

		$mensagem_drhj->rma_nome     	= $paciente->nm_primario.' '.$paciente->nm_secundario;
		$mensagem_drhj->rma_email       = $paciente->user->email;
		$mensagem_drhj->assunto     	= 'Pré-Agendamento Solicitado';

		$nome 		= $paciente->nm_primario.' '.$paciente->nm_secundario;
		$email 		= $paciente->user->email;
		$telefone 	= $paciente->contatos->first()->ds_contato;

		$nm_primario 			= $paciente->nm_primario;
		$nr_pedido 				= sprintf("%010d", $pedido );
		$nome_especialidade 	= "Especialidade/exame: <span>".$agendamento->nome_especialidade."</span>";
		$tipo_atendimento		= "";

		$paciente_id = $paciente->id;
		$plano_ativo_id = Paciente::getPlanoAtivo($paciente_id);
		$preco_ativo = 'R$ 0,00';

		if (!empty($agendamento->atendimento)) {
			if(!is_null($agendamento->atendimento->consulta_id)) {
				$tipo_atendimento = "Consulta";
			}

			if(!is_null($agendamento->atendimento->procedimento_id)) {
				$tipo_atendimento = "Exame";
			}

			$preco_ativo = $agendamento->atendimento->getPrecoByPlano($plano_ativo_id);
			$preco_ativo = 'R$ '.$preco_ativo;
		}

		$tipo_pagamento = '--------';

		if(!empty($pedido)) {
			if($pedido->tp_pagamento == 'Crédito' | $pedido->tp_pagamento == 'credito' | $pedido->tp_pagamento == 'empresarial' | $pedido->tp_pagamento == 'EMPRESARIAL' | $pedido->tp_pagamento == 'individual' |  $pedido->tp_pagamento == 'INDIVIDUAL') {
				$pedido->load('cartao_paciente');
				$tipo_pagamento = 'CRÉDITO';

				if(!empty($pedido->cartao_paciente)) {
					$tipo_pagamento = $tipo_pagamento.' - '.strtoupper($pedido->cartao_paciente->bandeira);
				}
			} else {
				$tipo_pagamento = strtoupper($pedido->tp_pagamento);
			}

		}

		$nome_profissional = '---------';
		$data_agendamento = '---------';
		$hora_agendamento = '---------';

		if (!empty($agendamento->profissional_id)) {
			$nome_profissional		= "Dr(a): <span>".$agendamento->profissional->nm_primario." ".$agendamento->profissional->nm_secundario."</span>";
		}

		if(!empty($agendamento->clinica->tp_prestador)){
			if($agendamento->consulta_id != null | $agendamento->clinica->tp_prestador == 'CLI') {
				$data_agendamento		= date('d', strtotime($agendamento->getRawDtAtendimentoAttribute())).' de '.strftime('%B', strtotime($agendamento->getRawDtAtendimentoAttribute())).' / '.strftime('%A', strtotime($agendamento->getRawDtAtendimentoAttribute())) ;
				$hora_agendamento		= date('H:i', strtotime($agendamento->getRawDtAtendimentoAttribute())).' (por ordem de chegada)';
			}
		}


		$nome_especialidade 	= "Descrição do atendimento: <span>".$agendamento->ds_atendimento." (".$agendamento->nome_especialidade.")</span>";
		$ds_especialidade		= $agendamento->nome_especialidade;

		$endereco_agendamento = '--------------------';

		//$agendamento->clinica->load('enderecos');
		$agendamento->filial->load('endereco');
		$enderecos_clinica = $agendamento->filial->endereco;

		if ($agendamento->filial->endereco != null) {
			$enderecos_clinica->load('cidade');
			$cidade_clinica = $enderecos_clinica->cidade;

			if ($cidade_clinica != null) {
				$endereco_agendamento = $enderecos_clinica->te_endereco.', '.$enderecos_clinica->nr_logradouro.', '.$enderecos_clinica->te_bairro.', '.$cidade_clinica->nm_cidade.'/ '.$cidade_clinica->sg_estado;
			}
		}

		$agendamento_status = 'Pré-agendado';

		$mensagem_drhj->conteudo     	= "<h4>Pré-Agendamento de Cliente:</h4><br><ul><li>Nome: $nome</li><li>E-mail: $email</li><li>Telefone: $telefone</li></ul>";

		$mensagem_drhj->save();

		/* if(!$mensagem->save()) {
		 return redirect()->route('landing-page')->with('error', 'A Sua mensagem não foi enviada. Por favor, tente novamente');
		 } */

		$destinatario                      = new MensagemDestinatario();
		$destinatario->tipo_destinatario   = 'DH';
		$destinatario->mensagem_id         = $mensagem_drhj->id;
		$destinatario->destinatario_id     = 1;
		$destinatario->save();

		$destinatario                      = new MensagemDestinatario();
		$destinatario->tipo_destinatario   = 'DH';
		$destinatario->mensagem_id         = $mensagem_drhj->id;
		$destinatario->destinatario_id     = 3;
		$destinatario->save();

		#dados da mensagem para o cliente
		$mensagem_cliente            		= new Mensagem();

		$mensagem_cliente->rma_nome     	= 'Contato DoutorHoje';
		$mensagem_cliente->rma_email       	= 'contato@doutorhoje.com.br';
		$mensagem_cliente->assunto     		= 'Pré-Agendamento Solicitado';
		$mensagem_cliente->conteudo     	= "<h4>Seu Pré-Agendamento:</h4><br><ul><li>Nº do Pedido: $nr_pedido</li><li>$nome_especialidade</li><li>Dr(a): $nome_profissional</li><li>Data: $data_agendamento</li><li>Horário: $hora_agendamento (por ordem de chegada)</li><li>Endereço: $endereco_agendamento</li></ul>";
		$mensagem_cliente->save();

		$destinatario                      = new MensagemDestinatario();
		$destinatario->tipo_destinatario   = 'PC';
		$destinatario->mensagem_id         = $mensagem_cliente->id;
		$destinatario->destinatario_id     = $paciente->user->id;
		$destinatario->save();

		$from = 'contato@doutorhoje.com.br';
		$to = $email;
		$subject = 'Pré-Agendamento Solicitado';

		######################## ENVIO DE EMAIL DE PRE-AGENDAMENTO##############################
		$html_message = view('emails.compra_boleto', compact('nm_primario', 'nr_pedido', 'url_boleto', 'tipo_atendimento', 'nome_especialidade', 'ds_especialidade', 'preco_ativo', 'nome_profissional', 'tipo_pagamento', 'data_agendamento', 'hora_agendamento', 'endereco_agendamento', 'agendamento_status'))->render();

		$html_message = str_replace(array("\r", "\n", "\t"), '', $html_message);

		$send_message = UtilController::sendMail($to, $from, $subject, $html_message);
		########################################################################################

		//     	echo "<script>console.log( 'Debug Objects: " . $send_message . "' );</script>";
		//     	return redirect()->route('provisorio')->with('success', 'A Sua mensagem foi enviada com sucesso!');

		return $send_message;
	}

	private function listCarrinhoItens()
	{
		$itensCarrinho = CVXCart::getContent()->toArray();

		foreach($itensCarrinho as $item) {
			if(!empty($item['attributes']['atendimento_id'])) {
				$atendimento_id = $item['attributes']['atendimento_id'];
				$list['atendimentos'][$atendimento_id] = $item['id'];
			} elseif(!empty($item['attributes']['checkup_id'])) {
				$checkup_id = $item['attributes']['checkup_id'];
				$list['checkups'][$checkup_id] = $item['id'];
			}
		}
		return $list;
	}
}
