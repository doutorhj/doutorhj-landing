<?php

namespace App\Http\Controllers;

use App\Cargo;
use App\Cidade;
use App\Endereco;
use App\Estado;
use App\Especialidade;
use App\Paciente;
use App\Plano;
use App\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\PacientesRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as CVXRequest;
use Illuminate\Support\Facades\Crypt;
use App\Documento;
use App\Http\Requests\EmailRequest;
use Illuminate\Support\Facades\Session;

/**
 * @author Frederico Cruz <frederico.cruz@s1saude.com.br>
 * 
 */
class PacienteController extends Controller
{    
    /**
     * Método para mostrar a página de cadastro do paciente 
     * 
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index(){
        $arCargos        = Cargo::orderBy('ds_cargo')->get(['id', 'ds_cargo']);
        $arEstados       = Estado::orderBy('ds_estado')->get();
        $arEspecialidade = Especialidade::orderBy('ds_especialidade')->get();
        
        return view('paciente', ['arEstados' => $arEstados, 'arCargos'=> $arCargos, 'arEspecialidade'=>$arEspecialidade]);
    }
    
    /**
     * ativarConta the specified resource in storage.
     *
     * @param  String  $verify_hash
     * @return \Illuminate\Http\Response
     */
    public function ativarConta($verify_hash)
    {
        //$this->validate($request, Volunteer::$rules);
        
        $paciente_id = Crypt::decryptString($verify_hash);
        
        $paciente = Paciente::findOrFail($paciente_id);

		if($paciente->cs_status != Paciente::ATIVO) {
			return redirect()->route('landing-page')->with('error-alert', 'Paciente não cadastrado.!');
		}

        if($paciente === null) {
        	return redirect()->route('landing-page')->with('error-alert', 'Sua Conta DoutorHoje não foi ativada. Por favor, tente novamente!');
        }
        
        $paciente->load('user');
        $user_activate_temp = $paciente->user;
        $user_id = $user_activate_temp->id;
        $user_activate = User::findOrFail($user_id);
        $user_activate->cs_status = 'A';
        
        if($user_activate->save()) {
        	
        	$from = 'contato@doutorhoje.com.br';
        	$to = $user_activate->email;
        	$subject = 'Contato DoutorHoje';
        	
        	$paciente_nm_primario = $paciente->nm_primario;
        	$paciente_email = $user_activate->email;
        	
        	$url = route('landing-page');
        	
        	############ VERIFICACAO TEMPORARIA EXCLUSIVA PARA CLIENTE CAIXA ####################
        	if ($paciente->empresa_id == 5) {
        	    $html_message = view('emails.confirma_ativacao_caixa', compact('paciente_nm_primario', 'url', 'paciente_email'))->render();
        	} else {
        	    $html_message = view('emails.confirma_ativacao', compact('paciente_nm_primario', 'url', 'paciente_email'))->render();
        	}
        	
        	$html_message = str_replace(array("\r", "\n", "\t"), '', $html_message);
        	
        	$send_message = UtilController::sendMail($to, $from, $subject, $html_message);
        }
        
//         return redirect()->route( 'activate-redirect' );
        return view('pacientes.activate');
    }

    /**
     * ativarConta redirect
     *
     * @return \Illuminate\Http\Response
     */
    public function ativarContaRedirect()
    {
        return view('pacientes.activate');
    }
    
    /**
     * enviarEmailConfirmacao redirect
     *
     * @return \Illuminate\Http\Response
     */
    public function enviarEmailConfirmacao(EmailRequest $request)
    {
    	# envia o e-mail de confirmacao
    	$nome          = CVXRequest::post('nome');
    	$email         = CVXRequest::post('email');
    	$telefone      = CVXRequest::post('telefone');
    	$mensagem      = CVXRequest::post('mensagem');
    	
    	$from = 'contato@doutorhoje.com.br';
    	 
    	//$to = 'viviane.herica@doutorhoje.com.br';
    	$to = [
    			[
    					'email' => 'viviane.herica@doutorhoje.com.br',
    					'name' => 'Viviane Hérica'
    			],
    			[
    					'email' => 'teocomp@gmail.com',
    					'name' => 'Theo Duarte'
    			],
    			[
    					'email' => 'comercial@doutorhoje.com.br',
    					'name' => 'Comercial Doutor Hoje'
    			],
    	];
    	
    	$to_prestador = [
    			[
    					'email' => $email,
    					'name' => $nome
    			]
    	];
    	$subject = 'DoutorHoje - Confirmação de contato';
    	 
    	//--enviar e-mail para o cliente------------------------------------------------------
    	$html_message = view('emails.send_confirmation', compact('nome', 'email', 'telefone', 'mensagem'))->render();
    	 
    	$html_message = str_replace(["\r", "\n", "\t"], '', $html_message);
    	 
    	$send_message = UtilController::sendMail($to_prestador, $from, $subject, $html_message);
    	
    	//--enviar e-mail a equipe comercial do doutorhoje-------------------------------------
    	$subject = 'Contato de Cliente Interessada';
    	$html_message = view('emails.send_contato', compact('nome', 'email', 'telefone', 'mensagem'))->render();
    	 
    	$html_message = str_replace(["\r", "\n", "\t"], '', $html_message);
    	 
    	$send_message = UtilController::sendMail($to, $from, $subject, $html_message);
    	 
    	 
    	if ($send_message) {
    		return redirect()->route('planos-individuais')->with('cart', 'Sua Mensagem foi enviada com sucesso!');
    	} else {
    		return redirect()->route('planos-individuais')->with('error-cart', 'Sua Mensagem não foi enviada. Por favor, tente novamente.');
    	}
    }
     
    /**
     * 
     * @param PacientesRequest $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function gravar(PacientesRequest $request){
        DB::beginTransaction();
        
        try{
            $usuario = new User();
            $usuario->name = strtoupper($request->input('nm_primario').' '.$request->input('nm_secundario'));
            $usuario->email = $request->input('email');
            $usuario->password = bcrypt($request->input('password'));
            $usuario->tp_user = 'PAC';
            $usuario->save();  
            
            
            $documento = new \App\Documento($request->all());
            $documento->save();
            
            
            $endereco = new Endereco($request->all());
            $idCidade = Cidade::where(['cd_ibge'=>$request->input('cd_ibge_cidade')])->get(['id'])->first();
            $endereco->cidade_id = $idCidade->id;
            $endereco->save();
            
            
            # telefones ---------------------------------------------
            $arContatos = array();
            
            $contato1 = new \App\Contato();
            $contato1->tp_contato = $request->input('tp_contato1');
            $contato1->ds_contato = $request->input('ds_contato1');
            $contato1->save();
            $arContatos[] = $contato1->id;
            
            if(!empty($request->input('ds_contato2'))){
                $contato2 = new \App\Contato();
                $contato2->tp_contato = $request->input('tp_contato2');
                $contato2->ds_contato = $request->input('ds_contato2');
                $contato2->save();
                $arContatos[] = $contato2->id;
            }
            
            
            if(!empty($request->input('ds_contato3'))){
                $contato3 = new \App\Contato();
                $contato3->tp_contato = $request->input('tp_contato3');
                $contato3->ds_contato = $request->input('ds_contato3');
                $contato3->save();
                $arContatos[] = $contato3->id;
            }
            
            $paciente  = new \App\Paciente($request->all());
            $paciente->users_id = $usuario->id;       
            $paciente->save();

            
            $paciente->contatos()->attach($arContatos);
            $paciente->enderecos()->attach([$endereco->id]);
            $paciente->documentos()->attach([$documento->id]);
            $paciente->save();
            
            DB::commit();
            
            return redirect()->route('home', ['nome' => $request->input('nm_primario')]);
        } catch (\Exception $e){
            DB::rollBack(); 
            
            throw new \Exception($e->getCode().'-'.$e->getMessage());
        }
    }
    
    /**
     * addDependenteStore a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addDependenteStore(Request $request)
    {
        $nm_primario_dep    = CVXRequest::post('nome');
        $nm_secundario_dep  = CVXRequest::post('sobrenome');
        $tp_documento_dep   = CVXRequest::post('tp_documento');
        $nr_documento_dep   = CVXRequest::post('nr_documento');
        $sexo_dep           = CVXRequest::post('sexo');
        $parentesco_dep     = CVXRequest::post('parentesco');
        $dia_nasc_dep       = CVXRequest::post('dia_nasc');
        $mes_nasc_dep       = CVXRequest::post('mes_nasc');
        $ano_nasc_dep       = CVXRequest::post('ano_nasc');
        $responsavel_id     = CVXRequest::post('paciente_id');
        
        # salva o documento do dependente
        $documento_ids = [];
        $documento = new Documento();
        $documento->tp_documento = $tp_documento_dep;
        $documento->te_documento = UtilController::retiraMascara($nr_documento_dep);
        $documento->save();
        $documento_ids = [$documento->id];
        
        # salva os dados do dependente
        $dependente                 = new Paciente();
        
        $dependente->nm_primario    = $nm_primario_dep;
        $dependente->nm_secundario  = $nm_secundario_dep;
        $dependente->parentesco     = $parentesco_dep;
        $dependente->cs_sexo        = $sexo_dep;
        $dependente->dt_nascimento  = $ano_nasc_dep.'-'.$mes_nasc_dep.'-'.$dia_nasc_dep;
        $dependente->responsavel_id = $responsavel_id;
        
        if (!$dependente->save()) {
            return response()->json(['status' => false, 'mensagem' => 'O Dependente não foi salvo. Por favor, tente novamente.']);
        }
        
        $dependente  = $this->setDependenteRelations($dependente, $documento_ids);
        $dependente->load('documentos');
        
        return response()->json(['status' => true, 'mensagem' => 'O Dependente foi salvo com sucesso!', 'dependente' => $dependente->toJson()]);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteDependenteDestroy()
    {
    	$paciente_id = CVXRequest::post('paciente_id');
    	$dependente = Paciente::findorfail($paciente_id);
    	$dependente->cs_status = 'I';
    
    	if (!$dependente->save()) {
    		return response()->json(['status' => false, 'mensagem' => 'O Dependente não foi desabilitado. Por favor, tente novamente.']);
    	}
    	
    	$responsavel_id = $dependente->responsavel_id;
    	$dependentes_restante = Paciente::where('responsavel_id', $responsavel_id)->where('cs_status', '=', 'A')->get();
    	
    	$num_dependentes = sizeof($dependentes_restante);
    
    	return response()->json(['status' => true, 'mensagem' => 'O Dependente foi desabilitado com sucesso!', 'dependente' => $dependente->toJson(), 'num_dependentes' => $num_dependentes]);
    }
    
    //############# PERFORM RELATIONSHIP ##################
    /**
     * Perform relationship.
     *
     * @param  \App\Perfiluser  $perfiluser
     * @return \Illuminate\Http\Response
     */
    private function setDependenteRelations(Paciente $dependente, array $documento_ids)
    {
    	$dependente->documentos()->sync($documento_ids);
    
    	return $dependente;
    }

	public function alteraVigenciaAtiva($id)
	{
		if(Auth::user()->paciente->validaVigencia($id))
			Session::put('vigencia_id', $id);
		else
			Session::put('vigencia_id', Auth::user()->paciente->vigencia_principal->id);

		return redirect()->back();
	}
}
