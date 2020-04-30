<?php

namespace DdaParams;
use EvolutionCMS\Ddafilters\Models\FilterParamValues;


class __ALIAS_BIG_FIRST__Controller
{
    public function __construct()
    {
        $query = FilterParamValues::select('id', 'value as pagetitle', 'value as text', 'value as html');
        if(isset($_REQUEST['search'])){
            $query->where(function($q) {
                $q->where('value', 'like', '%'.$_REQUEST['search'].'%')
                    ->orWhere('alias', 'like', '%'.$_REQUEST['search'].'%')
                    ->orWhere('value_ru', 'like', '%'.$_REQUEST['search'].'%')
                    ->orWhere('value_ua', 'like', '%'.$_REQUEST['search'].'%')
                    ->orWhere('value_en', 'like', '%'.$_REQUEST['search'].'%');
            });
        }
        echo $query->where('param_id', __TV__ID__)->get()->toJson();

    }
}