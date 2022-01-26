<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Cashflow extends Migration
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $dates = ['deleted_at'];
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('movimentacao')) {
            Schema::create('movimentacao', function (Blueprint $table) {
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_unicode_ci';
                $table->increments('id');
                $table->integer('id_conta')->unsigned();
                $table->foreign('id_conta')->references('id')->on('contas')
                    ->constrained()
                    ->onUpdate('no action')
                    ->onDelete('no action');
                $table->integer('id_estatus')->unsigned();
                $table->foreign('id_estatus')->references('id')->on('estatus')
                    ->constrained()
                    ->onUpdate('no action')
                    ->onDelete('no action');
                $table->text('descricao');
                $table->date('data_vencimento');
                $table->tinyInteger('credito')->default(0)->nullable(false);
                $table->decimal('valor', 25, 2)->nullable(false)->unsigned();
                $table->softDeletes();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movimentacao');
    }
}
