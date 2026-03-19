@extends('layouts.app2')

@section('content')

    <div class="layout-px-spacing">

        <div class="row layout-top-spacing">

            <div class="col-xl-8 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="row widget-statistic">
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                        <div class="widget widget-one_hybrid widget-followers">
                            <div class="widget-heading mb-5 pb-5">
                                <div class="w-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>                                </div>
                                <p class="w-value">{{$ass_total}}</p>
                                <h5 class="mb-2">Associados</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                        <div class="widget widget-one_hybrid widget-referral">
                            <div class="widget-heading mb-5 pb-5">
                                <div class="w-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>                                </div>
                                <p class="w-value">{{$ass_nconveniados}}</p>
                                <h5 class="mb-2">Associados não conveniados</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                        <div class="widget widget-one_hybrid widget-engagement">
                            <div class="widget-heading mb-5 pb-5">
                                <div class="w-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-plus"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>                                
                                </div>
                                <p class="w-value">{{$ass_conveniados}}</p>
                                <h5 class="mb-2">Associados conveniados</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contratos não averbados --> 
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-card-two">
                    <div class="widget-content">

                        <div class="media text-danger">
                            <div class="w-img">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-link"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>
                            </div>
                            <div class="media-body">
                                <h6>Contratos não averbados</h6>
                                <p class="meta-date-time">Vigência {{$nao_averbados->vigencia}}</p>
                            </div>
                        </div>

                        <div class="card-bottom-section">
                            <h6>{{$nao_averbados->quantidade}} contratos</h6>
                            @if ($nao_averbados->quantidade > 0)
                            <button type="button" onclick="nAverbadosModal()" class="btn btn-outline-danger">Detalhes</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                <div class="widget-four">
                    <div class="widget-heading">
                        <h5 class="">Associados conveniados</h5>
                    </div>
                    <div class="widget-content">
                        <div class="vistorsBrowser">

                            <?php
                            foreach($conveniados_fonte as $fonte){
                                if($fonte->assoc_ativosn == 1){
                                    $atv = 'ativos';
                                } else {
                                    $atv = 'desativados';
                                }
                            ?>
                            <div class="browser-list">
                                <div class="w-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>                               
                                </div>
                                <div class="w-browser-details">
                                    <div class="w-browser-info">
                                        <h6><?=ucfirst(strtolower($fonte->tipassoc_nome))?> <?=$atv?></h6>
                                        <p class="browser-count"><?=$fonte->qntd?> (<?=number_format((($fonte->qntd/$ass_conveniados)*100),1,',')?>%)</p>
                                    </div>
                                    <div class="w-browser-stats">
                                        <div class="progress">
                                            <div class="progress-bar bg-gradient-primary" role="progressbar" style="width: <?=(($fonte->qntd/$ass_conveniados)*100)?>%" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>

                        </div>

                    </div>
                </div>
            </div>



            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                <div class="widget-four">
                    <div class="widget-heading">
                        <h5 class="">Associados ativos por convênio</h5>
                    </div>
                    <div class="widget-content">
                        <div class="vistorsBrowser">

                            <?php
                            foreach($tipo_convenio as $convenio){
                            ?>
                            <div class="browser-list">
                                <div class="w-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>                               
                                </div>
                                <div class="w-browser-details">
                                    <div class="w-browser-info">
                                        <h6><?=ucfirst(strtolower($convenio->con_nome))?></h6>
                                        <p class="browser-count"><?=$convenio->qntd?></p>
                                    </div>
                                    <div class="w-browser-stats">
                                        <div class="progress">
                                            <div class="progress-bar bg-gradient-primary" role="progressbar" style="width: <?=(($convenio->qntd/$total)*100)?>%" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>

                        </div>

                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                <div class="widget-four">
                    <div class="widget-heading">
                        <h5 class="">Associados desativados com parcela em aberto</h5>
                    </div>
                    <div class="widget-content">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-4">
<!--
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                    </tr>
                                </thead>
-->
                                <tbody>
                                    <?php
                                    foreach($desativados as $desativado){
                                        
                                    
                                    ?>
                                    <tr>
                                        <td><?=$desativado->assoc_nome?></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


        </div>

    </div>


    

<!-- Não Averbados Modal -->
<div class="modal fade" id="nAverbadosModal" tabindex="-1" role="dialog" aria-labelledby="nAverbadosModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="nAverbadosModalLabel">Contratos não averbados em {{$vigencia}}</h5>
            </div>
            <div class="modal-body">
                <table class="table table-hover" id="tableNAverbados">
                    <thead>
                        <th>Associado</th>
                        <th>Contrato</th>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Fechar</button>
            </div>
        </div>
    </div>
</div>



@endsection

@stack('scripts')
