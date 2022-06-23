<?php

namespace App\Repositories;
use Illuminate\Support\Facades\Cache;

abstract class AbstractRepository
{
    protected $model;
    protected $tableCache;
    protected $limit_page;

    public function __construct()
    {   // Intância o model
        $this->model = $this->resolveModel();
        // Guarda o nome da tabela
        $this->tableCache = $this->model->getTable();
        $this->limit_page = env('PAGINATION_LIMIT');
        $this->time_cache = env('TIME_CACHE_IN_SECONDS');

    }

    public function get($active = false){
        if($active){
            if(!Cache::tags($this->tableCache)->has('_get_active')){
                $records = $this->model::where('status', '1')->orderBy('id', 'desc')->get();
                Cache::tags([$this->tableCache])->put('_get_active', $records, $this->time_cache);
            }
            return Cache::tags([$this->tableCache])->get('_get_active');
        }else {
            if(!Cache::tags($this->tableCache)->has('_get')){
                $records = $this->model::orderBy('id', 'desc')->get();
                Cache::tags([$this->tableCache])->put('_get', $records, $this->time_cache);
            }
            return Cache::tags([$this->tableCache])->get('_get');
        }
        
    }

    public function store($data){
       return $this->model::create($data);
    }
    public function update($data, $id){
        $find = $this->model::find($id);
        $find->update($data);
        return $find;
    }

    public function first()
    {
        if(!Cache::tags($this->tableCache)->has('_first')){
            $records = $this->model::first();
            Cache::tags([$this->tableCache])->put('_first', $records, $this->time_cache);
        }
        return Cache::tags([$this->tableCache])->get('_first');
    }
    
    public function find($id)
    {
        if(!Cache::tags($this->tableCache)->has('_record_id_' . $id)){
            $records = $this->model::find($id);
            Cache::tags([$this->tableCache])->put('_record_id_' . $id, $records, $this->time_cache);
        }
        return Cache::tags([$this->tableCache])->get('_record_id_' . $id);
    }

    public function paginate() {
        $page = request()->get('page', 1);
        
        if(!Cache::tags($this->tableCache)->has('_page_' . $page)){
            $records = $this->model::orderBy('id', 'desc')->paginate($this->limit_page);
            Cache::tags([$this->tableCache])->put('_page_' . $page, $records, $this->time_cache);
        }
        return Cache::tags([$this->tableCache])->get('_page_' . $page);
    }

    public function forgetCacheTag(){
       Cache::tags($this->tableCache)->flush();
    }
    public function searchName($search){
        $page = request()->get('page', 1);
        $queryPut = '_page_' . $page . '_search_' . \Str::slug($search);
        if(!Cache::tags($this->tableCache)->has($queryPut)){
            $records = $this->model::where('name', 'Like', '%' . $search . '%')->orderBy('id', 'desc')->paginate(10);
            Cache::tags([$this->tableCache])->put($queryPut, $records, 86400 * 7);
        }
        return Cache::tags([$this->tableCache])->get($queryPut);
    }

    public function resolveModel(){
        // Identifica qual o Model e atribui na variável
        return app($this->model);
    }
}