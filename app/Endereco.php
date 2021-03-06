<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\UtilController;

class Endereco extends Model
{
	const ATIVO = 'A';
	const INATIVO = 'I';

	public $fillable   = ['cidade_id', 'sg_logradouro', 'te_endereco', 'nr_logradouro', 'te_bairro', 'nr_cep', 'te_complemento', 'nr_latitude_gps', 'nr_longitude_gps'];
	
	
	public function cidade(){
	    return $this->belongsTo("App\Cidade");
	}
	
	public function getCepFormatado()
	{
	    return UtilController::formataCep($this->attributes['nr_cep']);
	}
}
