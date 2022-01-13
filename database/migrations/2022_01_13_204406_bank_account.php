<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class BankAccount extends Migration
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
        Schema::create('contas', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->increments('id');
            $table->integer('id_banco')->unsigned();
            $table->foreign('id_banco')->references('id')->on('banks')
                ->constrained()
                ->onUpdate('no action')
                ->onDelete('no action');
            $table->integer('id_tipoconta')->unsigned();
            $table->foreign('id_tipoconta')->references('id')->on('tipoconta')
                ->constrained()
                ->onUpdate('no action')
                ->onDelete('no action');
            $table->string('agencia', 20)->nullable(false);
            $table->string('conta', 50)->nullable(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contas');
    }
}
