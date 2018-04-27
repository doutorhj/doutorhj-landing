@extends('layouts.logado')

@section('title', 'Confirma Agendamento - DoctorHj')

@push('scripts')

@endpush

@section('content')
<section class="confirmacao">
    <div class="container">
        <div class="area-container">
            <div class="titulo">
                <strong>Pré-agendamento realizado com sucesso!</strong>            
            </div>
            <div class="row">
                <div class="col-md-12 texto-confirmacao">
                    <p>Sua <strong>solicitação</strong> foi realizada com sucesso. Em até 48 horas você receberá a confirmação do agendamento escolhido.</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-xl-7">
                    <div class="dados-confirmacao">
                        <ul>
                            <li><i class="fas fa-user"></i> Nome do paciente: <span>Paulo Dias</span></li>
                            <li><i class="fas fa-shopping-cart"></i> Nº do pedido: <span>000000</span></li>
                            <li>
                                <i class="fas fa-user-md"></i>
                                Especialidade / exame: <span>Cardiologia</span><br>
                                Dr(a): <span>Jõao Silva</span>
                            </li>
                            <li><i class="far fa-calendar-alt"></i> <span>21 de março / quarta-feira</span></li>
                            <li><i class="far fa-clock"></i> <span>9h00 (por ordem de chegada)</span></li>
                            <li><i class="fas fa-map-marker-alt"></i> <span>Rua Antenor Lemos, 57 709 Menino Jesus Porto Alegre/ RS</span></li>
                            <li><i class="far fa-calendar-check"></i> Status do agendamento: <span>Pré-agendado</span></li>
                            <li><i class="far fa-check-circle"></i> Status do pagamento: <span>Pago</span></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-12 col-xl-5">
                    <div class="condicoes">
                        <p>Acompanhe no Perfil do Cliente o <strong>status</strong> da sua solicitação de agendamento.</p>
                        <p>Evite transtornos! Caso ocorra algum imprevisto, impossibilitando o comparecimento ao serviço contratado, nos informe com até 24 horas de antecedência.</p>                        
                    </div>                    
                </div>
            </div>
        </div>
    </div>
</section>
@push('scripts')
    <script type="text/javascript">       
        $(document).ready(function(){

            var laravel_token = '{{ csrf_token() }}';
            var resizefunc = [];

        });
	</script>	
    
@endpush

@endsection