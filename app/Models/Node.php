<?php

namespace App\Models;

use App\Components\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kalnoy\Nestedset\NodeTrait;

/**
 * Class Node
 * @property $id int
 * @property $value string
 * @property $parent_id int
 * @property $is_deleted int
 * @property $_lft
 * @property $_rgt
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 * @package App
 */
class Node extends Model
{
    use NodeTrait;
    use SoftDeletes;

    protected $fillable = [
        'id',
        'value',
        'parent_id',
    ];

    protected $appends = ['is_deleted', 'ancestors'];

    protected $hidden = ['_lft', '_rgt', 'created_at', 'updated_at', 'deleted_at'];

    public static function bootSoftDeletes(): void
    {
        static::addGlobalScope(new SoftDeletingScope);
    }

    /**
     * Get is deleted
     * @return bool
     */
    public function getIsDeletedAttribute(): bool
    {
        return (bool)$this->deleted_at;
    }

    /**
     * Get node ancestors (all patent`s node ids)
     * @return array
     */
    public function getAncestorsAttribute(): array
    {
        return $this->getAncestors()->pluck('id')->toArray();
    }
}
