<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

/**
 * Class Node
 * @property $id int
 * @property $name string
 * @property $parent_id int
 * @property $is_deleted int
 * @package App
 */
class Node extends Model
{
    use NodeTrait;

    protected $fillable = [
        'id',
        'name',
        'parent_id',
        'is_deleted'
    ];

    protected $hidden = ['_lft', '_rgt', 'created_at', 'updated_at'];
}
