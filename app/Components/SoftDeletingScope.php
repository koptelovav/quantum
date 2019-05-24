<?php

namespace App\Components;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\SoftDeletingScope as BaseScope;

/**
 * Class SoftDeletingScope
 * @package App\Components
 */
class SoftDeletingScope extends BaseScope
{
    public function apply(Builder $builder, Model $model)
    {
        //Disable hide deleted items
    }
}
