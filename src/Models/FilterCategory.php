<?php
namespace EvolutionCMS\Ddafilters\Models;

use Illuminate\Database\Eloquent;

/**
 * EvolutionCMS\Ddafilters\Models\ActivateSms
 *
 * @property int $id
 * @property string $alias
 * @property string $name
 *
 * @mixin \Eloquent
 */
class FilterCategory extends Eloquent\Model
{
    protected $table = 'filter_category';

    protected $fillable = [
        'name',
    ];
}

