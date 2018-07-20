<?php

namespace App;

use Illuminate\Support\Carbon;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;

class Agendamento extends Model
{
    use Sortable;
        
    public $fillable  = ['id', 'te_ticket', 'profissional_id', 'paciente_id', 'clinica_id', 'dt_atendimento', 'cs_status'];
    public $sortable  = ['id', 'te_ticket', 'dt_atendimento', 'cs_status'];
    public $dates 	  = ['dt_atendimento'];
    
    
    /*
     * Constants
     */
    const PRE_AGENDADO   = 10;
    const CONFIRMADO     = 20;
    const NAO_CONFIRMADO = 30;
    const FINALIZADO     = 40;
    const AUSENTE        = 50;
    const CANCELADO      = 60;
    const AGENDADO       = 70;
    const RETORNO        = 80;
    
    protected static $cs_status = array(
        self::PRE_AGENDADO   => 'Pré-Agendado',
        self::CONFIRMADO     => 'Confirmado',
        self::NAO_CONFIRMADO => 'Não Confirmado',
        self::FINALIZADO     => 'Finalizado',
        self::AUSENTE        => 'Ausente',
        self::CANCELADO      => 'Cancelado',
        self::AGENDADO       => 'Agendado',
        self::RETORNO        => 'Retorno'
    );
    
    
    
    /*
     * Relationship
     */
    public function atendimento()
    {
        return $this->belongsTo('App\Atendimento');
    }
    
    public function paciente()
    {
        return $this->belongsTo('App\Paciente');
    }
    
    public function clinica()
    {
        return $this->belongsTo('App\Clinica');
    }
    
    public function profissional()
    {
        return $this->belongsTo('App\Profissional');
    }
    
    public function cupom_desconto()
    {
        return $this->belongsTo('App\CupomDesconto');
    }
    
    public function filial()
    {
        return $this->belongsTo('App\Filial');
    }
    
    public function itempedidos()
    {
    	return $this->hasMany('App\Itempedido');
    }

	public function checkup()
	{
		return $this->belongsTo('App\Checkup');
	}

	public function datahoracheckups(){
		return $this->hasMany("App\Datahoracheckup");
	}
    
    /*
     * Getters and Setters
     */
    public function getCsStatusAttribute($cdStatus) {
        return static::$cs_status[$cdStatus];
    }
    
    public function getDtAtendimentoAttribute($data) {
        $obData = new Carbon($data);
        return $obData->format('d/m/Y H:i');
    }
    
    public function getRawDtAtendimentoAttribute() {
		return $this->attributes['dt_atendimento'];
    }
    
    public function getRawCsStatusAttribute() {
        return $this->attributes['cs_status'];
    }

	/**
	 * @param $clinica_id
	 * @param $profissional_id
	 * @param $dataHoraAgendamento
	 * @return \Illuminate\Http\JsonResponse
	 */
	static public function validaHorarioDisponivel($clinica_id, $profissional_id, $dataHoraAgendamento)
	{
		return !Agendamento::where('clinica_id', '=', $clinica_id)
			->when($profissional_id, function ($query, $profissional_id) {
				return $query->where('profissional_id', $profissional_id);
			})
			->where('dt_atendimento', '=', \DateTime::createFromFormat('d/m/Y H:i', $dataHoraAgendamento)->format('Y-m-d H:i:s'))
			->exists();
	}
}