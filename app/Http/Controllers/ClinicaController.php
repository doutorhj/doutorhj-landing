<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PrestadoresRequest;
use App\Http\Requests\EditarPrestadoresRequest;
use Illuminate\Support\Facades\Request as CVXRequest;
use LaravelLegends\PtBrValidator\Validator as CVXValidador;
use App\Clinica;
use App\User;
use App\Cargo;
use App\Cidade;
use App\Profissional;
use App\Atendimento;
use App\Estado;
use App\Especialidade;
use App\Documento;
use App\Contato;
use App\Endereco;
use App\Procedimento;
use App\Responsavel;

class ClinicaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $prestadores = Clinica::where(function($query){
                                        if(!empty(Request::input('nm_busca'))){
                                            switch (Request::input('tp_filtro')){
                                                case "nm_razao_social" :
                                                    $query->where(DB::raw('to_str(nm_razao_social)'), 'like', '%'.UtilController::toStr(Request::input('nm_busca')).'%');
                                                    break;
                                                case "nm_fantasia" :
                                                    $query->where(DB::raw('to_str(nm_fantasia)'), 'like', '%'.UtilController::toStr(Request::input('nm_busca')).'%');
                                                    break;
                                                default:
                                                    $query->where(DB::raw('to_str(nm_razao_social)'), 'like', '%'.UtilController::toStr(Request::input('nm_busca')).'%');
                                            }
                                        }
                                    })->sortable()->paginate(20);
        $prestadores->load('contatos');
        $prestadores->load('responsavel');
        
        Request::flash();
                
        return view('clinicas.index', compact('prestadores'));
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $estados = Estado::orderBy('ds_estado')->get();
        $cargos  = Cargo::orderBy('ds_cargo')->get(['id', 'ds_cargo']);
        
        $precoconsultas = null;
        $precoprocedimentos = null;
        
//         $list_profissionals = Clinica::findorfail($idClinica)->load('profissionals');
        $list_profissionals = [];
        
        return view('clinicas.create', compact('estados', 'cargos', 'precoprocedimentos', 'precoconsultas', 'list_profissionals'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PrestadoresRequest $request)
    {  
        # dados de acesso do usuário que é o profissional responsável pela empresa
        $usuario            = new User();
        $usuario->name      = $request->input('name_responsavel');
        $usuario->email     = $request->input('email');
        $usuario->password  = bcrypt($request->input('password'));
        $usuario->tp_user   = 'CLI';
        $usuario->cs_status = 'A';
        $usuario->perfiluser_id = 2;
        $usuario->save();
        
        
        # documento da empresa CNPJ
        $documentoCnpj      = new Documento();
        $documentoCnpj->tp_documento = 'CNPJ';   
        $documentoCnpj->te_documento = UtilController::retiraMascara($request->input('te_documento'));
        $documentoCnpj->save();
        $documento_ids = [$documentoCnpj->id];
        
        
        # endereco da empresa
        $endereco           = new Endereco($request->all());
        $cidade             = Cidade::where(['cd_ibge'=>$request->input('cd_cidade_ibge')])->get()->first();
        $endereco->nr_cep = UtilController::retiraMascara($request->input('nr_cep'));
        $endereco->cidade()->associate($cidade);
        $endereco->nr_latitude_gps = $request->input('nr_latitude_gps');
        $endereco->nr_longitute_gps = $request->input('nr_longitute_gps');
        $endereco->save();
        $endereco_ids = [$endereco->id];
        
        # responsavel pela empresa
        $responsavel      = new Responsavel();
        $responsavel->telefone = $request->input('telefone_responsavel');;
        $responsavel->cpf = UtilController::retiraMascara($request->input('cpf_responsavel'));
        $responsavel->user_id = $usuario->id;
        $responsavel->save();
                    
        # telefones 
        $arContatos = array();
        
        $contato1             = new Contato();
        $contato1->tp_contato = $request->input('tp_contato1');
        $contato1->ds_contato = $request->input('ds_contato1');
        $contato1->save();
        array_push($arContatos, $contato1->id);
        
        if(!empty($request->input('ds_contato2'))){
            $contato2             = new Contato();
            $contato2->tp_contato = $request->input('tp_contato2');
            $contato2->ds_contato = $request->input('ds_contato2');
            $contato2->save();
            array_push($arContatos, $contato2->id);
        }
        
        if(!empty($request->input('ds_contato3'))){
            $contato3             = new \App\Contato();
            $contato3->tp_contato = $request->input('tp_contato3');
            $contato3->ds_contato = $request->input('ds_contato3');
            $contato3->save();
            array_push($arContatos, $contato3->id);
        }
        
        # clinica
        $clinica = Clinica::create($request->all());
        $clinica->responsavel_id = $responsavel->id;
        $clinica->save();
        
        $prestador = $this->setClinicaRelations($clinica, $documento_ids, $endereco_ids, $arContatos);
        
        return redirect()->route('clinicas.index')->with('success', 'O prestador foi cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($idClinica)
    {
        $estados = Estado::orderBy('ds_estado')->get();
        $cargos  = Cargo::orderBy('ds_cargo')->get(['id', 'ds_cargo']);
        
        
        $prestador = Clinica::findorfail($idClinica);
        $prestador->load('enderecos');
        $prestador->load('contatos');
        $prestador->load('documentos');
        $prestador->load('profissionals');
        
        $user   = User::findorfail($prestador->responsavel->user_id); 
        $cidade = Cidade::findorfail($prestador->enderecos->first()->cidade_id); 
        $documentoprofissional = [];
        
        $precoprocedimentos = Atendimento::where(['clinica_id'=> $idClinica, 'consulta_id'=> null])->get();
        $precoprocedimentos->load('procedimento');
        
        $precoconsultas = Atendimento::where(['clinica_id'=> $idClinica, 'procedimento_id'=> null])->get();
        $precoconsultas->load('consulta');
        
        
        return view('clinicas.show', compact('estados', 'cargos', 'prestador', 'user', 'cargo',
                                                'cidade', 'documentoprofissional', 'precoprocedimentos', 'precoconsultas'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($idClinica)
    {
        $estados = Estado::orderBy('ds_estado')->get();
        $cargos  = Cargo::orderBy('ds_cargo')->get(['id', 'ds_cargo']);
        
        $get_term = CVXRequest::get('search_term');
        $search_term = UtilController::toStr($get_term);
        
        $prestador = Clinica::findorfail($idClinica);
        $prestador->load('enderecos');
        $prestador->load('contatos');
        $prestador->load('documentos');
        //$prestador->load('profissional');
        
        
        $documentosclinica = $prestador->documentos;
        
        $user   = User::findorfail($prestador->responsavel->user_id);
        $precoprocedimentos = Atendimento::where('clinica_id', $idClinica)->where('procedimento_id', '<>', null)->orderBy('ds_preco', 'asc')->orderBy('vl_atendimento', 'desc')->get();
        $precoconsultas =     Atendimento::where('clinica_id', $idClinica)->where('consulta_id', '<>', null)->orderBy('ds_preco', 'asc')->orderBy('vl_atendimento', 'desc')->get();
        
        $documentoprofissional = [];

        //$prestador->load('profissionals')->orderBy('updated_at', 'desc');
        
        if($search_term != '') {
            $list_profissionals = Profissional::where(DB::raw('to_str(nm_primario)'), 'LIKE', '%'.$search_term.'%')->where('clinica_id', $prestador->id)->where('cs_status', '=', 'A')->orderBy('updated_at', 'desc')->get();
        } else {
            $list_profissionals = Profissional::where('clinica_id', $prestador->id)->where('cs_status', '=', 'A')->orderBy('updated_at', 'desc')->get();
        }
        
        $list_especialidades = Especialidade::orderBy('ds_especialidade', 'asc')->pluck('ds_especialidade', 'id');
        
        return view('clinicas.edit', compact('estados', 'cargos', 'prestador', 'user', 
                                                'documentoprofissional', 'precoprocedimentos', 
                                                'precoconsultas', 'documentosclinica', 'list_profissionals', 'list_especialidades'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditarPrestadoresRequest $request, $idClinica)
    {
        $prestador = Clinica::findOrFail($idClinica);
        
        $prestador->update($request->all());
        
        //--atualizar usuário-----------------
        $usuario_id         = CVXRequest::post('responsavel_user_id');
        $usuario            = User::findorfail($usuario_id);
        $usuario->name      = $request->input('name_responsavel');
        $usuario->password  = bcrypt($request->input('password'));
        $usuario->save();
        
        //--salvar CNPJ------------------------
        $documento_ids = [];
        $cnpj_id = CVXRequest::post('cnpj_id');
        $documento = Documento::findorfail($cnpj_id);
        $documento->tp_documento = CVXRequest::post('tp_documento_'.$cnpj_id);
        $documento->te_documento = UtilController::retiraMascara(CVXRequest::post('te_documento_'.$cnpj_id));
        $documento->save();
        $documento_ids = [$documento->id];
        
        //--salvar enderecos----------------------
        $endereco_ids = [];
        $endereco_id = CVXRequest::post('endereco_id');
        $endereco = Endereco::findorfail($endereco_id);
        $endereco->nr_cep = UtilController::retiraMascara(CVXRequest::post('nr_cep'));
        $endereco->sg_logradouro = CVXRequest::post('sg_logradouro');
        $endereco->te_endereco = CVXRequest::post('te_endereco');
        $endereco->nr_logradouro = CVXRequest::post('nr_logradouro');
        $endereco->te_complemento = CVXRequest::post('te_complemento');
        $endereco->nr_latitude_gps = CVXRequest::post('nr_latitude_gps');
        $endereco->nr_longitute_gps = CVXRequest::post('nr_longitute_gps');
        $endereco->te_bairro = CVXRequest::post('te_bairro');
        $endereco->te_bairro = CVXRequest::post('te_bairro');
        $endereco->save();
        $endereco_ids = [$endereco->id];
        
        //--salvar contatos----------------------
        $contato_ids = [];
        $contato_id = CVXRequest::post('contato_id');
        $contato = Contato::findorfail($contato_id);
        $contato->tp_contato = CVXRequest::post('tp_contato_'.$contato_id);
        $contato->ds_contato = CVXRequest::post('ds_contato_'.$contato_id);
        $contato->save();
        $contato_ids = [$contato->id];
        
        # responsavel pela empresa
        $responsavel_id         = CVXRequest::post('responsavel_id');
        $responsavel            = Responsavel::findorfail($responsavel_id);
        $responsavel->telefone  = $request->input('telefone_responsavel');
        $responsavel->save();
        
        $prestador = $this->setClinicaRelations($prestador, $documento_ids, $endereco_ids, $contato_ids);
        $prestador->save();
        //$prestador->save();
        
        return redirect()->route('clinicas.index')->with('success', 'Prestador alterado com sucesso!');
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($idClinica)
    {
        $clinica = Clinica::findorfail($idClinica);
        $clinica->forceDelete();
        $clinica->contatos()->forceDelete();
        $clinica->enderecos()->forceDelete();
        $clinica->documentos()->forceDelete();
        $clinica->user()->forceDelete();
        Atendimento::where('clinica_id', $idClinica)->delete();
        
        
        return redirect()->route('clinicas.index')->with('success', 'Clínica excluída com sucesso!');        
    }
    
    /**
     * Consulta para alimentar autocomplete
     * 
     * @param string $term
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProcedimentos($termo){
        $arResultado = array();
        $procedimentos = Procedimento::where(DB::raw('to_str(ds_procedimento)'), 'like', '%'.UtilController::toStr($termo).'%')->get();
        
        foreach ($procedimentos as $query)
        {
            $arResultado[] = [ 'id' =>  $query->id.' | '.$query->cd_procedimento .' | '.$query->ds_procedimento, 'value' => $query->ds_procedimento ];
        }
        
        return Response()->json($arResultado);
    }
    
    /**
     * Consulta para alimentar autocomplete
     * 
     * @param string $termo
     * @return \Illuminate\Http\JsonResponse
     */
    public function getConsultas($termo){
        $arResultado = array();
        $consultas = \App\Consulta::where(DB::raw('to_str(ds_consulta)'), 'like', '%'.UtilController::toStr($termo).'%')->get();
        
        foreach ($consultas as $query)  
        {
            $arResultado[] = [ 'id' => $query->id.' | '.$query->cd_consulta.' | '.$query->ds_consulta, 'value' => $query->ds_consulta ];
        }
        
        return Response()->json($arResultado);
    }
    
    //############# PERFORM RELATIONSHIP ##################
    /**
     * Perform relationship.
     *
     * @param  \App\Perfiluser  $perfiluser
     * @return \Illuminate\Http\Response
     */
    private function setClinicaRelations(Clinica $prestador, array $documento_ids, array $endereco_ids, array $contato_ids)
    {
        $prestador->documentos()->sync($documento_ids);
        $prestador->enderecos()->sync($endereco_ids);
        $prestador->contatos()->sync($contato_ids);
        
        return $prestador;
    }
    
    /**
     * Perform relationship.
     *
     * @param  \App\Profissional  $profissional
     * @return \Illuminate\Http\Response
     */
    private function setProfissionalRelations(Profissional $profissional, array $documento_ids, array $contatos_ids)
    {
        $profissional->documentos()->sync($documento_ids);
        //$profissional->contatos()->sync($contatos_ids);
        
        return $profissional;
    }
    
    //############# AJAX SERVICES ##################
    /**
     * addProfissionalStore a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addProfissionalStore(Request $request)
    {
        $clinica_id = CVXRequest::post('clinica_id');
        $clinica = Clinica::findorfail($clinica_id);
        
        $profissional_id = CVXRequest::post('profissional_id');
        if ($profissional_id != '') {
            $profissional = Profissional::findorfail($profissional_id);
            $profissional->load('documentos');
        }
        
        if (isset($profissional) && isset($profissional->documentos) && sizeof($profissional->documentos) > 0) {
            $documento_id = $profissional->documentos[0]->id;
            $documento = Documento::findorfail($documento_id);
            
            $documento->tp_documento = CVXRequest::post('tp_documento');
            $documento->te_documento = CVXRequest::post('te_documento');
            $documento->save();
            
            $documento_ids = [$documento->id];
        } else {
            $documento = new Documento();
            $documento->tp_documento =  CVXRequest::post('tp_documento');
            $documento->te_documento =  CVXRequest::post('te_documento');
            $documento->save();
            $documento_ids = [$documento->id];
        }
        
        //         $contato = new Contato();
        //         $contato->save();
        //         $contatos_ids = [$contato->id];
        $contatos_ids = [];
        
        if (!isset($profissional)) {
            $profissional = new Profissional();
        }
        $profissional->nm_primario = CVXRequest::post('nm_primario');
        $profissional->nm_secundario = CVXRequest::post('nm_secundario');
        $profissional->cs_sexo = CVXRequest::post('cs_sexo');
        $profissional->dt_nascimento = preg_replace("/(\d+)\D+(\d+)\D+(\d+)/","$3-$2-$1", CVXRequest::post('dt_nascimento'));
        $profissional->clinica_id = intval($clinica_id);
        $profissional->especialidade_id = CVXRequest::post('especialidade_id');
        $profissional->tp_profissional = CVXRequest::post('tp_profissional');
        $profissional->cs_status = CVXRequest::post('cs_status');
        
        if (!$profissional->save()) {
            return response()->json(['status' => false, 'mensagem' => 'O Profissional não foi salvo. Por favor, tente novamente.']);
        }
        
        $profissional = $this->setProfissionalRelations($profissional, $documento_ids, $contatos_ids);
        $profissional->save();
        
        $profissional->load('especialidade');
        
        return response()->json(['status' => true, 'mensagem' => 'O Profissional foi salvo com sucesso!', 'profissional' => $profissional->toJson()]);
    }
    
    /**
     * viewProfissionalShow a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function viewProfissionalShow()
    {
        $profissional_id = CVXRequest::post('profissional_id');
        $profissional = Profissional::findorfail($profissional_id);
        $profissional->load('documentos');
        
        return response()->json(['status' => true, 'mensagem' => '', 'profissional' => $profissional->toJson()]);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteProfissionalDestroy()
    {
        $profissional_id = CVXRequest::post('profissional_id');
        $profissional = Profissional::findorfail($profissional_id);
        $profissional->cs_status = 'I';
        
        if (!$profissional->save()) {
            return response()->json(['status' => false, 'mensagem' => 'O Profissional não foi removido. Por favor, tente novamente.']);
        }
        
        return response()->json(['status' => true, 'mensagem' => 'O Profissional foi removido com sucesso!', 'profissional' => $profissional->toJson()]);
    }
    
    /**
     * addProcedimentoPrecoStore a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addProcedimentoPrecoStore(Request $request)
    {
        $atendimento_id = CVXRequest::post('atendimento_id');
        $atendimento = $atendimento_id != '' ? Atendimento::findorfail($atendimento_id) : [];
        
        $clinica_id = CVXRequest::post('clinica_id');
        $procedimento_id = CVXRequest::post('procedimento_id');
        $ds_procedimento = CVXRequest::post('ds_procedimento');
        $vl_procedimento = CVXRequest::post('vl_procedimento');
        
        if (sizeof($atendimento) == 0) {
            $atendimento = new Atendimento();
        }
        $atendimento->ds_preco =  $ds_procedimento;
        $atendimento->vl_atendimento = UtilController::moedaBanco($vl_procedimento);
        $atendimento->clinica_id = $clinica_id;
        $atendimento->procedimento_id = $procedimento_id;
        $atendimento->cs_status = 'A';
        
        if (!$atendimento->save()) {
            return response()->json(['status' => false, 'mensagem' => 'O Procedimento não foi salvo. Por favor, tente novamente.']);
        }
        
        $atendimento->load('procedimento');
        
        return response()->json(['status' => true, 'mensagem' => 'O Procedimento foi salvo com sucesso!', 'atendimento' => $atendimento->toJson()]);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteProcedimentoDestroy()
    {
        $atendimento_id = CVXRequest::post('atendimento_id');
        $atendimento = Atendimento::findorfail($atendimento_id);
        $atendimento->cs_status = 'I';
        
        if (!$atendimento->save()) {
            return response()->json(['status' => false, 'mensagem' => 'O Atendimento não foi removido. Por favor, tente novamente.']);
        }
        
        return response()->json(['status' => true, 'mensagem' => 'O Atendimento foi removido com sucesso!', 'atendimento' => $atendimento->toJson()]);
    }
    
    /**
     * addConsultaPrecoStore a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addConsultaPrecoStore(Request $request)
    {
        $atendimento_id = CVXRequest::post('atendimento_id');
        $atendimento = $atendimento_id != '' ? Atendimento::findorfail($atendimento_id) : [];
        
        $clinica_id = CVXRequest::post('clinica_id');
        $consulta_id = CVXRequest::post('consulta_id');
        $ds_consulta = CVXRequest::post('ds_consulta');
        $vl_consulta = CVXRequest::post('vl_consulta');
        
        if (sizeof($atendimento) == 0) {
            $atendimento = new Atendimento();
        }
        $atendimento->ds_preco =  $ds_consulta;
        $atendimento->vl_atendimento = UtilController::moedaBanco($vl_consulta);
        $atendimento->clinica_id = $clinica_id;
        $atendimento->consulta_id = $consulta_id;
        $atendimento->cs_status = 'A';
        
        if (!$atendimento->save()) {
            return response()->json(['status' => false, 'mensagem' => 'A Consulta não foi salva. Por favor, tente novamente.']);
        }
        
        $atendimento->load('consulta');
        
        return response()->json(['status' => true, 'mensagem' => 'A Consulta foi salva com sucesso!', 'atendimento' => $atendimento->toJson()]);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteConsultaDestroy()
    {
        $atendimento_id = CVXRequest::post('atendimento_id');
        $atendimento = Atendimento::findorfail($atendimento_id);
        $atendimento->cs_status = 'I';
        
        if (!$atendimento->save()) {
            return response()->json(['status' => false, 'mensagem' => 'A Consulta não foi removida. Por favor, tente novamente.']);
        }
        
        return response()->json(['status' => true, 'mensagem' => 'A Consulta foi removida com sucesso!', 'atendimento' => $atendimento->toJson()]);
    }
    
    //############# PUBLIC SERVICES - NOT AUTHENTICATED ##################
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function findClinicaSearch()
    {
        $prestadores = Clinica::where(function($query){
            if(!empty(Request::input('nm_busca'))){
                switch (Request::input('tp_filtro')){
                    case "nm_razao_social" :
                        $query->where(DB::raw('to_str(nm_razao_social)'), 'like', '%'.UtilController::toStr(Request::input('nm_busca')).'%');
                        break;
                    case "nm_fantasia" :
                        $query->where(DB::raw('to_str(nm_fantasia)'), 'like', '%'.UtilController::toStr(Request::input('nm_busca')).'%');
                        break;
                    default:
                        $query->where(DB::raw('to_str(nm_razao_social)'), 'like', '%'.UtilController::toStr(Request::input('nm_busca')).'%');
                }
            }
        })->sortable()->paginate(20);
        $prestadores->load('contatos');
        $prestadores->load('responsavel');
        
        Request::flash();
        
        return view('resultado', compact('prestadores'));
    }

    public function paginaPagamento(){
        return view('pagamento');
    }
    public function informaBeneficiario(){
        return view('informa-beneficiario');
    }
    public function confirmaAgendamento(){
        return view('confirmacao');
    }
    public function homePrestador(){
        return view('home-prestador');
    }
    public function confirmaCadastro(){
        return view('confirma-cadastro');
    }
}