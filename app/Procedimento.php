<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Procedimento extends Model
{
	use Sortable;
	
	public $fillable = ['cd_procedimento', 'ds_procedimento', 'tipoatendimento_id', 'grupoprocedimento_id'];
    public $sortable = ['id', 'cd_procedimento', 'ds_procedimento', 'tipoatendimento_id', 'grupoprocedimento_id'];

    private static $_tipoAtendimentoExameProcedimento = [3,4];
    
    private static $_tipoAtendimentoOdonto = [2];
    
    public function tipoatendimento()
    {
        return $this->belongsTo('App\TipoAtendimento');
    }
    
    public function grupoprocedimento()
    {
    	return $this->belongsTo('App\GrupoProcedimento');
    }
    
    public function atendimentos()
    {
        return $this->hasMany('App\Atendimento');
    }
    
    public function tag_populars()
    {
    	return $this->hasMany('App\TagPopular');
    }
    
    public function especialidades()
    {
        return $this->belongsToMany('App\Especialidade');
    }
    
    public function cupom_descontos()
    {
        return $this->belongsToMany('App\CupomDesconto');
    }

    public function getActiveExameProcedimento($planoId, $uf_localizacao) {
     //    DB::enableQueryLog();
        $query = DB::table('procedimentos')
            ->select( DB::raw("COALESCE(tag_populars.cs_tag, atendimentos.ds_preco, procedimentos.ds_procedimento) descricao, 'exame' tipo, procedimentos.id") )
            ->distinct()
            ->join('atendimentos', function ($join) {
                $join->on('procedimentos.id', '=', 'atendimentos.procedimento_id')
                ->where('atendimentos.cs_status', '=', 'A');
            })
            ->join('clinicas', 'clinicas.id', '=', 'atendimentos.clinica_id')
            ->leftJoin('tag_populars', function($query) { $query->on('tag_populars.procedimento_id', '=', 'procedimentos.id');})
			->join('precos as pr', function($join8) use ($planoId) {
				$join8->on('pr.atendimento_id', '=', 'atendimentos.id')
					->where('pr.cs_status', '=', 'A')
					->where('pr.data_inicio', '<=', date('Y-m-d H:i:s'))
					->where('pr.data_fim', '>=', date('Y-m-d H:i:s'))
					->where(function($query) use ($planoId) {
						$query->where('pr.plano_id', '=', $planoId)
							->orWhere('pr.plano_id', '=', Plano::OPEN);
					});
			})
			->whereExists(function ($query) use ($uf_localizacao) {
                $query->select(DB::raw(1))
                      ->from('filials')
                      ->join('enderecos', function($join1) { $join1->on('filials.endereco_id', '=', 'enderecos.id');})
                      ->join('cidades', function($join2) use ($uf_localizacao) { $join2->on('cidades.id', '=', 'enderecos.cidade_id')->on('cidades.sg_estado', '=', DB::raw("'$uf_localizacao'"));})
                      ->whereRaw("filials.clinica_id = clinicas.id AND filials.cs_status = 'A'");
            })
            ->where(['atendimentos.cs_status' => 'A', 'clinicas.cs_status' => 'A'])
            ->whereIn('procedimentos.tipoatendimento_id', self::$_tipoAtendimentoExameProcedimento)
            ->orderby('descricao', 'asc')
            ->get();

        //dd( DB::getQueryLog() ); 
        return $query;
    }

    public function getActiveOdonto($planoId, $uf_localizacao){
        // DB::enableQueryLog();
        $query = DB::table('procedimentos')
            ->select( DB::raw("COALESCE(tag_populars.cs_tag, atendimentos.ds_preco, procedimentos.ds_procedimento) descricao, 'odonto' tipo, procedimentos.id") )
            ->distinct()
            ->join('atendimentos', function ($join) {
                $join->on('procedimentos.id', '=', 'atendimentos.procedimento_id')
                ->where('atendimentos.cs_status', '=', 'A');
            })
            ->join('clinicas', 'clinicas.id', '=', 'atendimentos.clinica_id')
            ->leftJoin('tag_populars', function($query) { $query->on('tag_populars.procedimento_id', '=', 'procedimentos.id');})
			->join('precos as pr', function($join8) use ($planoId) {
				$join8->on('pr.atendimento_id', '=', 'atendimentos.id')
					->where('pr.cs_status', '=', 'A')
					->where('pr.data_inicio', '<=', date('Y-m-d H:i:s'))
					->where('pr.data_fim', '>=', date('Y-m-d H:i:s'))
					->where(function($query) use ($planoId) {
						$query->where('pr.plano_id', '=', $planoId)
							->orWhere('pr.plano_id', '=', Plano::OPEN);
					});
			})
			->whereExists(function ($query) use ($uf_localizacao) {
                $query->select(DB::raw(1))
                      ->from('filials')
                      ->join('enderecos', function($join1) { $join1->on('filials.endereco_id', '=', 'enderecos.id');})
                      ->join('cidades', function($join2) use ($uf_localizacao) { $join2->on('cidades.id', '=', 'enderecos.cidade_id')->on('cidades.sg_estado', '=', DB::raw("'$uf_localizacao'"));})
                      ->whereRaw("filials.clinica_id = clinicas.id AND cs_status = 'A'");
            })
            ->where('atendimentos.cs_status', 'A')
            ->whereIn('procedimentos.tipoatendimento_id', self::$_tipoAtendimentoOdonto)
            ->orderby('descricao', 'asc')
            ->get();

        // dd( DB::getQueryLog() );
        return $query;
    }

    public function getActiveAddress( $procedimentoId, $uf_localizacao ){
        $query = DB::select("   select string_agg( CAST(id AS varchar), ',' ) id, string_agg( CAST(filial_id AS varchar), ',' ) filial_id, 
                                       te_bairro, nm_cidade
                                  from (
                                select distinct e.id, e.te_bairro, cd.nm_cidade, f.id filial_id
                                  from filials f
                                  join enderecos e on (f.endereco_id = e.id)
                                  join cidades cd on (e.cidade_id = cd.id AND cd.sg_estado = ".DB::raw("'$uf_localizacao'").")
                                  join clinicas c on (f.clinica_id = c.id)
                                  join atendimentos at on (c.id = at.clinica_id)
                                 where at.cs_status = :status
                                   and c.cs_status = :status
                                   and at.procedimento_id = :procedimentoId) general
                                 group by te_bairro, nm_cidade
                                 order by te_bairro",  [ 'procedimentoId' => $procedimentoId, 'status' => 'A' ] );

        return $query;
    }

    public function getActiveAtendimentos( $procedimentoId, $enderecoIds, $sortItem, $planoId, $uf_localizacao ) {
        //DB::enableQueryLog();
        $query = DB::table('atendimentos as at')
			->distinct()
			->select( DB::raw("at.id, pr.vl_comercial, at.ds_preco,
							   c.id clinica_id, p.id procedimento_id, COALESCE(tp.cs_tag, at.ds_preco, p.ds_procedimento) tag,
							   case when f.eh_matriz = 'S' then 'Matriz' else 'Filial' end tipo, e.id endereco_id, e.sg_logradouro,
							   e.te_endereco, e.nr_logradouro, e.te_bairro, e.nr_cep,
							   e.nr_latitude_gps, e.nr_longitute_gps, c.id cidade_id, cd.nm_cidade, cd.sg_estado, p.ds_procedimento,
							   c.nm_fantasia, c.tp_prestador, f.eh_matriz, f.nm_nome_fantasia, f.id filial_id") )
			->join('procedimentos as p', 'at.procedimento_id', '=', 'p.id')
			->leftJoin('tag_populars as tp', 'p.id', '=', 'tp.procedimento_id')
			->join('clinicas as c', 'at.clinica_id', '=', 'c.id')
			->join('filials as f', 'c.id', '=', 'f.clinica_id')
			->join('enderecos as e', 'f.endereco_id', '=', 'e.id')
			->join('precos as pr', function($join8) use ($planoId) {
			    $join8->where('pr.id', function($query) use ($planoId) {
					$query->select('id')
						->from('precos')
						->whereRaw('atendimento_id = at.id')
						->where('cs_status', '=', 'A')
						->where('plano_id', '=', DB::raw("'$planoId'"))
						->where('data_inicio', '<=', date('Y-m-d H:i:s'))
						->where('data_fim', '>=', date('Y-m-d H:i:s'))
						->limit(1);
				});
			})
			//->join('cidades as cd', 'e.cidade_id', '=', 'cd.id')
			->join('cidades as cd', function($join9) use ($uf_localizacao) { $join9->where('e.cidade_id', '=', DB::raw('"cd"."id"'))->where('cd.sg_estado', '=', DB::raw("'$uf_localizacao'")); } )
			->where('at.cs_status', 'A')
			->where('c.cs_status', 'A')
			->where('f.cs_status', 'A')
			->where('at.procedimento_id', $procedimentoId);

        if( !empty($enderecoIds) ) {
            $query->whereIn('f.endereco_id', explode(',', $enderecoIds) );
        }

        $query->orderBy('pr.vl_comercial', $sortItem)
			->orderBy('f.eh_matriz', 'desc')
			->orderBy('c.nm_fantasia', 'asc');
  
		$result = $query->get();
		//dd( DB::getQueryLog() );
			
        return $result;
        

        
        /*$queryStr = " select distinct at.id, at.vl_com_atendimento, at.ds_preco, 
                                     c.id clinica_id, p.id procedimento_id, COALESCE(tp.cs_tag, at.ds_preco, p.ds_procedimento) tag,
                                     case when f.eh_matriz = 'S' then 'Matriz' else 'Filial' end tipo, e.id endereco_id, e.sg_logradouro, 
                                     e.te_endereco, e.nr_logradouro, e.te_bairro, e.nr_cep,
                                     e.nr_latitude_gps, e.nr_longitute_gps, c.id cidade_id, cd.nm_cidade, cd.sg_estado, p.ds_procedimento,
                                     c.nm_fantasia, c.tp_prestador, f.eh_matriz, f.nm_nome_fantasia, f.id filial_id
                        from atendimentos at 
                        join procedimentos p on (at.procedimento_id = p.id)
                        left join tag_populars tp on (p.id = tp.procedimento_id)
                        join clinicas c on (at.clinica_id = c.id) 
                        left join filials f on (c.id = f.clinica_id) 
                        left join enderecos e on (f.endereco_id = e.id) 
                        join cidades cd on (e.cidade_id = cd.id)
                       where at.cs_status = :status
                         and c.cs_status = :status
                         and f.cs_status = :status
                         and at.procedimento_id = :procedimentoId";
        
        if( !empty($enderecoIds) ) {
            $queryStr .= " and f.endereco_id in ($enderecoIds)";
        }

        $queryStr .= " order by at.vl_com_atendimento $sortItem";
        $queryStr .= ", f.eh_matriz desc, c.nm_fantasia";

        // dd($queryStr);
        
        $query = DB::select( $queryStr, [ 'procedimentoId' => $procedimentoId, 'status' => 'A' ]);
        return $query;*/
    }
    
}