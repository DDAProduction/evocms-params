<?php
namespace EvolutionCMS\Ddafilters\Models;

use Illuminate\Database\Eloquent;

/**
 * EvolutionCMS\Ddafilters\Models\ActivateSms
 *
 * @property int $id
 * @property int $tv_id
 * @property string $prefix
 * @property string $alias
 * @property string $desc
 * @property string $desc_ua
 * @property string $desc_ru
 * @property string $desc_en
 * @property string $typeinput
 *
 * @mixin \Eloquent
 */
class FilterParamsUnits extends Eloquent\Model
{
    protected $table = 'filter_params_units';

    protected $fillable = [
        'desc',
        'desc_ua',
        'desc_ru',
        'desc_en',
    ];
}

