<div id="checkup" class="area-checkup">
    <div class="container">
        <div class="selo">
            <img src="/libs/home-template/img/ckp-selo.png" alt="">
        </div>
        <div class="row row-eq-height">
            <div class="col-md-12 col-lg-6">
                <div class="pacote-checkup">
                    <div class="resumo">
                        <div class="textos">
                            <h3>CheckUp Masculino</h3>
                            <p class="qtde-procedimentos">9 Procedimentos</p>
                            <ul>
                                <li><p><strong>1 Consulta:</strong> Consulta Urológica</p></li>
                                <li><p><strong>7 Exames:</strong> Creatina; Lipidograma; Glicemia de Jejum; Hemograma
                                        Completo; TGO; TGP; Urina I</p></li>
                                <li><p><strong>1 Imagem:</strong> Ultrassom da Próstata</p></li>
                            </ul>
                        </div>
                        <div class="valor">
                            <div class="row">
                                <div class="col-md-5">
                                    <p class="vlr-mercado"><strike>R$ 638,00</strike></p>
                                    <p>valor médio de mercado</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="vlr-drhj">R$ 350,00</p>
                                    <p>Valor DoutorHJ</p>
                                </div>
                                <div class="col-md-3">
                                    <p class="txtPorcentagem">Economize</p>
                                    <p class="valorPorcentagem">R$ 288,00</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="area-btn">
                        <a href="/resultado-checkup?tipo_atendimento=checkup&tipo_especialidade={{ $checkupMasculino->id }}&local_atendimento=TODOS&endereco_id=">
                            <button type="button" class="btn btn-agendar-plano-1">Agendar</button>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-6">
                <div class="pacote-checkup">
                    <div class="resumo">
                        <div class="textos">
                            <h3>CheckUp Feminino</h3>
                            <p class="qtde-procedimentos">12 Procedimentos</p>
                            <ul>
                                <li><p><strong>1 Consulta:</strong> Consulta Ginecológica</p></li>
                                <li><p><strong>8 Exames:</strong> Creatina; Lipidograma; Glicemia de Jejum; Hemograma
                                        Completo; TGO; TGP; TSH; Urina I</p></li>
                                <li><p><strong>2 Imagens:</strong> Ultrassom das Mamas; Ultrassom Transvaginal</p></li>
                                <li><p><strong>Exame Clínico Laboratorial:</strong> Papanicolau</p></li>
                            </ul>
                        </div>
                        <div class="valor">
                            <div class="row">
                                <div class="col-md-5">
                                    <p class="vlr-mercado"><strike>R$ 789,00</strike></p>
                                    <p>valor médio de mercado</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="vlr-drhj">R$ 438,00</p>
                                    <p>Valor DoutorHJ</p>
                                </div>
                                <div class="col-md-3">
                                    <p class="txtPorcentagem">Economize</p>
                                    <p class="valorPorcentagem">R$ 351,00</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="area-btn">
                        <a href="/resultado-checkup?tipo_atendimento=checkup&tipo_especialidade={{ $checkupFeminino->id }}&local_atendimento=TODOS&endereco_id=">
                            <button type="button" class="btn btn-agendar-plano-2">Agendar</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- <div id="checkup" class="area-checkup">
    <div class="container">
        <div class="selo">
            <img src="/libs/home-template/img/ckp-selo.png" alt="">
        </div>
        <div class="row row-eq-height">
            <div class="col-md-12 col-lg-6">
                <div class="pacote-checkup">
                    <div class="resumo">
                        <div class="textos">
                            <h3>{{ $checkupMasculino->titulo }}</h3>
                            <p class="qtde-procedimentos">{{ $checkupMasculino->itemCheckups->count('id') }} Ítens</p>
                            <ul>
                                @foreach( $checkupMasculinoSummary as $summary )
                                <li>
                                    <p><strong>{{ $summary->qty }} {{ $summary->tipo }}:</strong> {{ $summary->tag }}.</p>
                                </li>

                                @endforeach
                            </ul>
                        </div>
                        <div class="valor">
                            <div class="row">
                                <div class="col-md-5">
                                    <p class="vlr-mercado"><strike>R$ {{ number_format( $checkupMasculino->itemCheckups->sum('vl_mercado'),  2, ',', '.')  }}</strike></p>
                                    <p>valor médio de mercado</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="vlr-drhj">R$ {{ number_format( $checkupMasculino->itemCheckups->sum('vl_com_checkup'),  2, ',', '.')  }}</p>
                                    <p>Valor DoutorHJ</p>
                                </div>
                                <div class="col-md-3">
                                    <p class="txtPorcentagem">Economize</p>
                                    <p class="valorPorcentagem">
                                        {{ number_format( ($checkupMasculino->itemCheckups->sum('vl_com_checkup')/ ( $checkupMasculino->itemCheckups->sum('vl_mercado') > 0 ? $checkupMasculino->itemCheckups->sum('vl_mercado') : $checkupMasculino->itemCheckups->sum('vl_com_checkup') )) * 100,  2, ',', '.') }}%</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="area-btn">
                        <a href="/resultado-checkup?tipo_atendimento=checkup&tipo_especialidade={{ $checkupMasculino->id }}&local_atendimento=TODOS&endereco_id="><button type="button" class="btn btn-agendar-plano-1">Agendar</button></a>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-6">
                <div class="pacote-checkup">
                    <div class="resumo">
                        <div class="textos">
                            <h3>{{ $checkupFeminino->titulo }}</h3>
                            <p class="qtde-procedimentos">{{ $checkupFeminino->itemCheckups->count('id') }} Ítens</p>
                            <ul>
                                @foreach( $checkupFemininoSummary as $summary )
                                <li>
                                    <p><strong>{{ $summary->qty }} {{ $summary->tipo }}:</strong> {{ $summary->tag }}.</p>
                                </li>

                                @endforeach
                            </ul>
                        </div>
                        <div class="valor">
                            <div class="row">
                                <div class="col-md-5">
                                    <p class="vlr-mercado"><strike>R$ {{ number_format( $checkupFeminino->itemCheckups->sum('vl_mercado'),  2, ',', '.')  }}</strike></p>
                                    <p>valor médio de mercado</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="vlr-drhj">R$ {{ number_format( $checkupFeminino->itemCheckups->sum('vl_com_checkup'),  2, ',', '.')  }}</p>
                                    <p>Valor DoutorHJ</p>
                                </div>
                                <div class="col-md-3">
                                    <p class="txtPorcentagem">Economize</p>
                                    <p class="valorPorcentagem">
                                        {{ number_format( ($checkupFeminino->itemCheckups->sum('vl_com_checkup')/ ( $checkupFeminino->itemCheckups->sum('vl_mercado') > 0 ? $checkupFeminino->itemCheckups->sum('vl_mercado') : $checkupFeminino->itemCheckups->sum('vl_com_checkup') )) * 100,  2, ',', '.') }}%</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="area-btn">
                        <a href="/resultado-checkup?tipo_atendimento=checkup&tipo_especialidade={{ $checkupFeminino->id }}&local_atendimento=TODOS&endereco_id="><button type="button" class="btn btn-agendar-plano-2">Agendar</button></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->
