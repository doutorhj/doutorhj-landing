<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Support\Carbon;

class Paciente extends Model
{
	use Sortable;
	
	public $fillable      = ['id', 'nm_primario', 'nm_secundario', 'cs_sexo', 'dt_nascimento', 'parentesto', 'user_id', 'cargo_id', 'responsavel_id'];
	public $sortable      = ['id', 'nm_primario', 'nm_secundario', 'parentesto' ];
	public $dates 	      = ['dt_nascimento'];
    
	public function cargo(){
	    return $this->belongsTo('App\Cargo');
	}

	public function contatos(){
	    return $this->belongsToMany('App\Contato');
	}
    
	public function enderecos(){
	    return $this->belongsToMany('App\Endereco');
	}
	
	public function documentos(){
	    return $this->belongsToMany('App\Documento'); 
	}
	
	public function user(){
	    return $this->belongsTo('App\User');
	}
	
	public function cartoes()
	{
		return $this->hasMany('App\CartaoPaciente');
	}
	
	public function dependentes()
	{
		return $this->hasMany('App\Paciente', 'responsavel_id');
	}
    
	public function setDtNascimentoAttribute($data)
	{
	    $date = new Carbon($data);
	    $date->format('Y-m-d H:i:s');
        
	    $this->attributes['dt_nascimento'] = $date;
	}
    
	public function getDtNascimentoAttribute()
	{
	    $date = new Carbon($this->attributes['dt_nascimento']);
	    return $date->format('d/m/Y');
	}
}
