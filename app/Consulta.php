<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Consulta extends Model
{
	use Sortable;
	
    public $fillable = ['cd_consulta', 'ds_consulta', 'especialidade_id', 'tipoatendimento_id'];
    public $sortable = ['id', 'cd_consulta', 'ds_consulta', 'especialidade_id', 'tipoatendimento_id'];
	
    public function especialidade()
    {
        return $this->belongsTo('App\Especialidade');
    }
    
    public function tipoatendimento()
    {
    	return $this->belongsTo('App\Tipoatendimento');
    }
    
    public function atendimentos()
    {
    	return $this->hasMany('App\Atendimento');
    }
    
    public function tag_populars()
    {
    	return $this->hasMany('App\TagPopular');
    }
}