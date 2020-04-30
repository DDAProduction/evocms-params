<?php
namespace EvolutionCMS\Ddafilters\Controllers;



use EvolutionCMS\Ddafilters\Models\FilterCategory;
use EvolutionCMS\Ddafilters\Models\FilterParams;
use EvolutionCMS\Ddafilters\Models\FilterParamsCategory;
use EvolutionCMS\Ddafilters\Models\FilterParamValues;

/**
 * EvolutionCMS\Ddafilters\Models\ActivateSms
 *
 * @property int $id
 * @property string $alias
 * @property string $name
 *
 * @mixin \Eloquent
 */
class HelperController
{
    public static function response($data, $status = 'OK', $message=''){
        header('Content-Type: application/json');

        $arrAnswer = ['status'=>$status,'message'=>$message,'data'=>$data];
        echo json_encode($arrAnswer, JSON_UNESCAPED_UNICODE);
        exit();
    }

    public static function answerCategory($answer){
        if($answer !== true){
            self::response([],'error',$answer);
        }else {
            self::response(FilterCategory::all());
        }
    }
    public static function answerParams($answer){
        if($answer !== true){
            self::response([],'error',$answer);
        }else {
            self::response(FilterParams::all());
        }
    }
    public static function answerParamValues($answer){
        if($answer !== true){
            self::response([],'error',$answer);
        }else {
            self::response(FilterParamValues::where('param_id',$_GET['param_id'])->get());
        }
    }
    public static function answerParamsCategory($answer){
        if($answer !== true){
            self::response([],'error',$answer);
        }else {

            self::response(FilterParamsCategory::select('filter_params_category.*','filter_params.desc as name')->where('category_id',$_GET['category_id'])->join('filter_params','filter_params_category.param_id','filter_params.id')->get());
        }
    }
}

