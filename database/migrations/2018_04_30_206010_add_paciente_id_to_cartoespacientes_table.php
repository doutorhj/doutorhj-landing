<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPacienteIdToCartoesPacientesTable extends Migration
{
    public function up()
    {
    	Schema::table('cartoespacientes', function (Blueprint $table) {
    		$table->integer('paciente_id')
            	  ->unsigned()
            	  ->nullable()
            	  ->after('valor');
    		
    		$table->foreign('paciente_id')->references('id')->on('pacientes');
    	});
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    	Schema::table('cartoespacientes', function (Blueprint $table) {
    	    $table->dropForeign('cartoespacientes_paciente_id_foreign');
    		$table->dropColumn('paciente_id');
    	});
    }
}