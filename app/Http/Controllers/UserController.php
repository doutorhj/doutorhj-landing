<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Paciente;
use App\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request as CVXRequest;
use App\Mail\PacienteSender;
use App\Documento;
use App\Contato;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\UsuariosRequest;

class UserController extends Controller
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    /**
     * Register a newly external user created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(UsuariosRequest $request)
    {
    	$access_token = UtilController::getAccessToken();
    	$time_to_live = date('Y-m-d H:i:s');
    	
//     	$encrypted = Crypt::encryptString('5');
    	
//     	$decrypted = Crypt::decryptString($encrypted);
    	
    	# dados de acesso do usuário paciente
    	$usuario            		= new User();
    	$usuario->name      		= $request->input('nm_primario').' '.$request->input('nm_secundario');
    	$usuario->email     		= $request->input('email');
    	$usuario->password  		= bcrypt($access_token);
    	$usuario->tp_user   		= 'PAC';
    	$usuario->cs_status 		= 'I';
    	$usuario->perfiluser_id 	= 3;
    	$usuario->save();
    	
    	# dados do paciente
    	$paciente           		= new Paciente();
    	$paciente->user_id 			= $usuario->id;
    	$paciente->nm_primario      = $request->input('nm_primario');
    	$paciente->nm_secundario    = $request->input('nm_secundario');
    	$paciente->cs_sexo     		= $request->input('cs_sexo');
    	$paciente->dt_nascimento 	= preg_replace("/(\d+)\D+(\d+)\D+(\d+)/","$3-$2-$1", CVXRequest::post('dt_nascimento'));
    	$paciente->access_token    	= $access_token;
    	$paciente->time_to_live    	= date('Y-m-d H:i:s', strtotime($time_to_live . '+2 hour'));
    	//dd($usuario);
    	$paciente->save();
    	
    	# cpf do paciente
    	$documento 					= new Documento();
    	$documento->tp_documento 	=  'CPF';
    	$documento->te_documento 	=  UtilController::retiraMascara($request->input('te_documento'));
    	$documento->save();
    	$documento_ids = [$documento->id];
    	
    	# contato do paciente
    	$contato1             		= new Contato();
    	$contato1->tp_contato 		= 'CP';
    	$contato1->ds_contato 		= $request->input('ds_contato');
    	$contato1->save();
    	$contato_ids = [$contato1->id];
    	
    	$paciente = $this->setPacienteRelations($paciente, $documento_ids, $contato_ids);
    	
    	# envia o e-mail de ativação
    	//Mail::to($usuario->email)->send(new PacienteSender($paciente));
    	//$this->from('administrador@comvex.com.br', 'DoctorHoje')->subject('Contato DoctorHoje')->view('emails.paciente_verificacao_conta')->with(['verify_hash' => Crypt::encryptString($this->paciente->id)])
    	$verify_hash = Crypt::encryptString($paciente->id);
    	$from = 'contato@doctorhoje.com.br';
    	$to = $usuario->email;
    	$subject = 'Contato DoctorHoje';
    	
    	$paciente_nm_primario = $paciente->nm_primario;
    	$paciente_email = $usuario->email;
    	
    	$url = route('ativar_conta', $verify_hash);
    	//$html_message = "<!DOCTYPE html><html><head><title>DoctorHoje Ativação</title></head><body><h2><a href='$url'>Clique no link aqui para Ativar sua conta DoctorHoje</a></h2></body></html>";
    	
    	$html_message = <<<HEREDOC
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
        <title>DoctorHoje</title>
    </head>
    <body style='margin: 0;'>
        <table width='600' border='0' cellspacing='0' cellpadding='0' align='center'>
            <tr style='background-color:#fff;'>
                <td width='480' style='text-align:left'>&nbsp;</td>
                <td width='120' style='text-align:right'>&nbsp;</td>
            </tr>
        </table>
        <table width='600' border='0' cellspacing='0' cellpadding='0' align='center'>
            <tr style='background-color:#fff;'>
                <td width='480' style='text-align:left'><span style='font-family:Arial, Helvetica, sans-serif; font-size:11px; color:#434342;'>DoctorHoje - Confirmação de cadastro</span></td>
                <td width='120' style='text-align:right'><a href='#' target='_blank' style='font-family:Arial, Helvetica, sans-serif; font-size:11px; color:#434342;'>Abrir no navegador</a></td>
            </tr>
        </table>
        <br>
        <table width='600' border='0' cellspacing='0' cellpadding='0' align='center'>
            <tr>
                <td><img src='https://doctorhoje.com.br/libs/home-template/img/email/h1.png' width='600' height='113' alt=''/></td>
            </tr>
        </table>
        <table width='600' border='0' cellspacing='0' cellpadding='0' align='center'>
            <tr>
                <td style='background: #1d70b7; font-family:Arial, Helvetica, sans-serif; text-align: center; color: #ffffff; font-size: 28px; line-height: 80px;'><strong>Confirmação de cadastro</strong></td>
            </tr>
        </table>
        <br>
        <br>
        <table width='600' border='0' cellspacing='0' cellpadding='0' align='center'>
            <tr>
                <td width='30' style='background-color: #fff;'>&nbsp;</td>
                <td width='540' style='font-family:Arial, Helvetica, sans-serif; font-size: 28px; line-height: 50px; color: #434342; background-color: #fff; text-align: center;'>
                    Olá, <strong style='color: #1d70b7;'>$paciente_nm_primario</strong>
                </td>
                <td width='30' style='background-color: #fff;'>&nbsp;</td>
            </tr>
        </table>
        <br>
        <br>
        <table width='600' border='0' cellspacing='0' cellpadding='0' align='center'>
            <tr>
                <td width='30' style='background-color: #fff;'>&nbsp;</td>
                <td width='540' style='font-family:Arial, Helvetica, sans-serif; font-size: 16px; line-height: 22px; color: #434342; background-color: #fff; text-align: justify;'>
                    <strong>Seja bem-vindo(a)</strong> e obrigado(a) por escolher o Doctor Hoje. Estamos muito felizes em saber que agora você faz parte da melhor plataforma de consultas e exames do Distrito Federal. Nosso objetivo é facilitar sua vida e buscar os melhores serviços de saúde à preços acessíveis. Você pode consultar seus dados cadastrais no nosso site e em breve pelo seu celular no aplicativo Doctor Hoje.
                </td>
                <td width='30' style='background-color: #fff;'>&nbsp;</td>
            </tr>
        </table>
        <br>
        <br>
        <table width='600' border='0' cellspacing='0' cellpadding='0' align='center'>
            <tr>
                <td width='220' style='background-color: #fff;'>&nbsp;</td>
                <td width='159' style='text-align: center;'>
                    <img src='https://doctorhoje.com.br/libs/home-template/img/email/devices.png' width='155' height='74' alt=''/>
                </td>
                <td width='221' style='background-color: #fff;'>&nbsp;</td>
            </tr>
        </table>
        <br>
        <br>
        <br>
        <table width='600' border='0' cellspacing='0' cellpadding='0' align='center'>
            <tr>
                <td width='130' style='background-color: #fff;'>&nbsp;</td>
                <td width='340' style='background: #1d70b7; font-family:Arial, Helvetica, sans-serif; font-size: 14px; line-height: 50px; color: #434342; text-align: center;'>
                    <a href='$url' style='color: #ffffff; text-decoration: none;'><strong style='color: #ffffff;'>CLIQUE AQUI PARA ATIVAR SEU CADASTRO</strong></a>
                </td>
                <td width='130' style='background-color: #fff;'>&nbsp;</td>
            </tr>
        </table>
        <br>
        <br>
        <br>
        <table width='600' border='0' cellspacing='0' cellpadding='0' align='center'>
            <tr>
                <td width='30' style='background-color: #fff;'>&nbsp;</td>
                <td width='540' style='font-family:Arial, Helvetica, sans-serif; font-size: 16px; line-height: 22px; color: #434342; background-color: #fff;'>
                    Seu cadastro foi realizado através do e-mail:
                    <span style='color: #1d70b7;'><strong>$paciente_email</strong></span><br>
                    <br>
                    Para logar no seu perfil, digite seu e-mail e token recebido por SMS
                    a cada novo acesso.                     
                </td>
                <td width='30' style='background-color: #fff;'>&nbsp;</td>
            </tr>
        </table>
        <br>
        <br>
        <table width='600' border='0' cellspacing='0' cellpadding='0' align='center'>
            <tr>
                <td width='30' style='background-color: #fff;'>&nbsp;</td>
                <td width='540' style='font-family:Arial, Helvetica, sans-serif; font-size: 16px; line-height: 22px; color: #434342; background-color: #fff; text-align: center;'>
                    Abraços,<br>
                    Equipe Doctor Hoje                    
                </td>
                <td width='30' style='background-color: #fff;'>&nbsp;</td>
            </tr>
        </table>
        <br>
        <br>
        <br>
        <table width='600' border='0' cellspacing='0' cellpadding='10' align='center'>
            <tr style='background-color: #f9f9f9;'>
                <td width='513'>
                    &nbsp;
                </td>
            </tr>
        </table>
        <table width='600' border='0' cellspacing='0' cellpadding='10' align='center'>
            <tr style='background-color: #f9f9f9;'>
                <td width='209'></td>
                <td width='27'><a href='#'><img src='https://doctorhoje.com.br/libs/home-template/img/email/facebook.png' width='27' height='24' alt=''/></a></td>
                <td width='27'><a href='#'><img src='https://doctorhoje.com.br/libs/home-template/img/email/youtube.png' width='27' height='24' alt=''/></a></td>
                <td width='27'><a href='#'><img src='https://doctorhoje.com.br/libs/home-template/img/email/instagram.png' width='27' height='24' alt=''/></a></td>
                <td width='210'></td>
            </tr>
        </table>
        <table width='600' border='0' cellspacing='0' cellpadding='10' align='center'>
            <tr style='background-color: #f9f9f9;'>
                <td width='513'>
                    &nbsp;
                </td>
            </tr>
        </table>
        <table width='600' border='0' cellspacing='0' cellpadding='0' align='center'>
            <tr style='background-color: #f9f9f9;'>
                <td width='30'></td>
                <td width='540' style='line-height:16px; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#434342; text-align: center;'>
                    Em caso de qualquer dúvida, fique à vontade <br>
                    para responder esse e-mail ou
                    nos contatar no <br><br>
                    <a href='mailto:meajuda@doctorhoje.com.br' style='color:#1d70b7; text-decoration: none;'>meajuda@doctorhoje.com.br</a>
                    <br><br>
                    Ou ligue para (61) 3221-5350, o atendimento é de<br>
                    segunda à sexta-feira
                    das 8h00 às 18h00. <br><br>
                    <strong>Doctor Hoje</strong> 2018 
                </td>
                <td width='30'></td>
            </tr>
        </table>
        <table width='600' border='0' cellspacing='0' cellpadding='10' align='center'>
            <tr style='background-color: #f9f9f9;'>
                <td width='513'>
                    &nbsp;
                </td>
            </tr>
        </table>
    </body>
</html>
HEREDOC;
    	
    	$html_message = str_replace(array("\r", "\n"), '', $html_message);
    	
    	$send_message = UtilController::sendMail($to, $from, $subject, $html_message);
    	
    	echo "<script>console.log( 'Debug Objects: " . $send_message . "' );</script>";
    	return view('users.register', compact('access_token'));
    }
    
    /**
     * sendToken the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendToken(Request $request)
    {   
        //DB::enableQueryLog();
        $ds_contato = UtilController::retiraMascara(CVXRequest::post('ds_contato'));
        $contato1 = Contato::where(DB::raw("regexp_replace(ds_contato , '[^0-9]*', '', 'g')"), '=', $ds_contato)->get();
        //$query = DB::getQueryLog();
        //print_r($query);
        $contato = $contato1->first();
        $contato_id = $contato->id;
        
        $paciente_temp = Paciente::with('user')
        	->join('contato_paciente', function($join1) { $join1->on('pacientes.id', '=', 'contato_paciente.paciente_id');})
	        ->join('contatos', function($join2) use ($contato_id) { $join2->on('contato_paciente.contato_id', '=', 'contatos.id')->on('contatos.id', '=', DB::raw($contato_id));})
	        ->select('pacientes.*')
	        ->get();
	    
        $user = $paciente_temp->first()->user;
        
        //--quando o usuario tenta ir para a tela de pagamento sem solicitar um atendimento
        if($user === null) {
            return view('login');
        }
        
        # atualiza o token do paciente
        $paciente = $paciente_temp->first();
        
        $access_token = UtilController::getAccessToken();
        $time_to_live = date('Y-m-d H:i:s');
        
        $paciente->access_token = $access_token;
        $paciente->time_to_live = date('Y-m-d H:i:s', strtotime($time_to_live . '+2 hour'));
        $paciente->save();
        
        # realiza a criptografia do token do paciente
        $user->password = bcrypt($access_token);
        $user->save();
        
        $number = UtilController::retiraMascara($contato->ds_contato);
        $remetente = 'DoctorHoje';
        $message = "Seu Novo Token de acesso ao DoctorHoje: $access_token";
        
        UtilController::sendSms($number, $remetente, $message);
    }
    
    //############# PERFORM RELATIONSHIP ##################
    /**
     * Perform relationship.
     *
     * @param  Paciente  $paciente, array $documento_ids, array $documento_ids
     * @return \Illuminate\Http\Response
     */
    private function setPacienteRelations(Paciente $paciente, array $documento_ids, array $contatos_ids)
    {
    	$paciente->documentos()->sync($documento_ids);
    	$paciente->contatos()->sync($contatos_ids);
    
    	return $paciente;
    }
}
