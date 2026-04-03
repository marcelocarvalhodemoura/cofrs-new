@if ($page_name != 'coming_soon' && $page_name != 'contact_us' && $page_name != 'error404' && $page_name != 'error500' && $page_name != 'error503' && $page_name != 'faq' && $page_name != 'helpdesk' && $page_name != 'maintenence' && $page_name != 'privacy' && $page_name != 'auth_boxed' && $page_name != 'auth_default')

<!--  BEGIN SIDEBAR  -->
<div class="sidebar-wrapper sidebar-theme" aria-expanded="true">

    <nav id="sidebar">
        <div class="profile-info">
            <!--<figure class="user-cover-image"></figure>-->
            <div class="user-info">
                <img src="{{ asset('assets/images/user.png') }}" alt="avatar">
                <h6 class="">{{ \Illuminate\Support\Facades\Session::get('name') }}</h6>
                <p class="">{{ \Illuminate\Support\Facades\Session::get('type') }}</p>
            </div>
        </div>
        <div class="shadow-bottom"></div>
        
        <ul class="list-unstyled menu-categories" id="accordionExample">

            @if(in_array(\Illuminate\Support\Facades\Session::get('typeId'),[1,2,3]))
            <li class="menu">
                <a href="/dashboard" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        <span>Home</span>
                    </div>

                </a>
            </li>
            @endif
            @if(in_array(\Illuminate\Support\Facades\Session::get('typeId'),[1,2]))
            <li class="menu ">
                <a href="/users" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <span>Usuários</span>
                    </div>
                </a>
            </li>
            <li class="menu">
                <a href="/alertas" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-square"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                        <span>Alertas</span>
                    </div>
                </a>
            </li>
            @endif

            <li class="menu">
                <a href="/associates" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        <span>Associados</span>
                    </div>
                </a>
            </li>
            <li class="menu">
                <a href="#covenants-kit" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-airplay">
                            <path d="M5 17H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1"></path>
                            <polygon points="12 15 17 21 7 21 12 15"></polygon>
                        </svg>
                        <span>Convênios</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled " id="covenants-kit" data-parent="#accordionExample">
                    <li class="">
                        <a href="/covenants">Conveniados</a>
                    </li>
                    <li class="">
                        <a href="/processArchive">Processamento de arquivos</a>
                    </li>
                    @if(in_array(\Illuminate\Support\Facades\Session::get('typeId'),[1,2,3]))
                    <li class="">
                        <a href="/covenants-type">Tipos de Convênio</a>
                    </li>
                    <li class="">
                        <a href="/categories-convenants">Categorias de Convênio</a>
                    </li>
                    @endif
                </ul>
            </li>
            @if(in_array(\Illuminate\Support\Facades\Session::get('typeId'),[1,2,3]))

            <li class="menu ">
                <a href="#finances-kit" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign">
                            <line x1="12" y1="1" x2="12" y2="23"></line>
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                        </svg>
                        <span>Financeiro</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled " id="finances-kit" data-parent="#accordionExample">
                    <li class="">
                        <a href="/Cashflow">Fluxo de Caixa</a>
                    </li>
                    <li class="">
                        <a href="/BankAccount">Contas</a>
                    </li>
                    <li class="">
                        <a href="/AccountType">Tipo de Contas</a>
                    </li>
                    <li class="">
                        <a href="/banks">Bancos</a>
                    </li>
                </ul>

            </li>

            <li class="menu ">
                <a href="#report" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer">
                            <polyline points="6 9 6 2 18 2 18 9"></polyline>
                            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                            <rect x="6" y="14" width="12" height="8"></rect>
                        </svg>
                        <span>Relatórios</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled " id="report" data-parent="#accordionExample">
                <li class="">
                        <a href="/report/associate">Por associado</a>
                    </li>
                    <li class="">
                        <a href="/report/allAssociate">Todos os associados</a>
                    </li>
                    <li class="">
                        <a href="/report/covenant">Conveniados</a>
                    </li>
                    <li class="">
                        <a href="/report/reference">Referências</a>
                    </li>
                    <li class="">
                        <a href="/report/agreement">Convênios</a>
                    </li>
                    <li class="">
                        <a href="/report/cashflow">Financeiro</a>
                    </li>
                </ul>
            </li>
            @endif

            @if(in_array(\Illuminate\Support\Facades\Session::get('typeId'),[1,2]))
            <li class="menu ">
                <a href="/log-viewer" aria-expanded="false" class="dropdown-toggle" target="_blank">
                    <div class="">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path></svg>
                        <span>Logs <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="width: 16px;height: 16px; margin-left: 5px;"><g fill="none" fill-rule="evenodd"><path d="M18 14v5a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8c0-1.1.9-2 2-2h5M15 3h6v6M10 14L20.2 3.8"/></g></svg></span>
                    </div>
                </a>
            </li>
            @endif
            <!--
            <li class="menu ">
                <a href="/support" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-life-buoy">
                            <circle cx="12" cy="12" r="10"></circle>
                            <circle cx="12" cy="12" r="4"></circle>
                            <line x1="4.93" y1="4.93" x2="9.17" y2="9.17"></line>
                            <line x1="14.83" y1="14.83" x2="19.07" y2="19.07"></line>
                            <line x1="14.83" y1="9.17" x2="19.07" y2="4.93"></line>
                            <line x1="14.83" y1="9.17" x2="18.36" y2="5.64"></line>
                            <line x1="4.93" y1="19.07" x2="9.17" y2="14.83"></line>
                        </svg>
                        <span>Ajuda</span>
                    </div>
                </a>
            </li>
-->
            
        </ul>

    </nav>

</div>
<!--  END SIDEBAR  -->

@endif
