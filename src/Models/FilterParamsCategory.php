<?php
namespace EvolutionCMS\Ddafilters\Models;

use Illuminate\Database\Eloquent;

/**
 * EvolutionCMS\Ddafilters\Models\ActivateSms
 *
 * @property int $id
 * @property int $category_id
 * @property int $param_id
 * @property int $show_in_category
 * @property int $show_in_filter
 * @property string $type_output
 *
 * @mixin \Eloquent
 */
class FilterParamsCategory extends Eloquent\Model
{
    protected $table = 'filter_params_category';

    protected $fillable = [
        'category_id',
        'param_id',
        'show_in_category',
        'show_in_filter',
        'type_output',
        'order',
    ];
}

