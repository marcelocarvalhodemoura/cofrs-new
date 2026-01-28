<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ProcessarBaixasArquivos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'baixa:processar'; // Nome do comando no terminal
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Processa as baixas de arquivos pendentes';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
    $this->info('Iniciando o processamento...');

    // Instancia o controller através do container do Laravel e chama o método
    app(\App\Http\Controllers\ConvenantController::class)->darBaixaAutomatica();

    $this->info('Finalizado!');
    }
}
