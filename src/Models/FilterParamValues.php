<?php
namespace EvolutionCMS\Ddafilters\Models;

use Illuminate\Database\Eloquent;

/**
 * EvolutionCMS\Ddafilters\Models\ActivateSms
 *
 * @property int $id
 * @property int $tv_id
 * @property string $alias
 * @property string $value
 * @property string $value_ua
 * @property string $value_ru
 * @property string $value_en
 *
 * @mixin \Eloquent
 */
class FilterParamValues extends Eloquent\Model
{
    protected $table = 'filter_param_values';

    protected $fillable = [
        'tv_id',
        'param_id',
        'alias',
        'value',
        'value_ua',
        'value_ru',
        'value_en',
    ];
}

