<?php

namespace App\Components\TreeBuilder;

/**
 * Class TreeBuilder
 * @package App\Models
 */
class TreeBuilder implements TreeBuilderInterface
{
    /**
     * Completed items tree
     *
     * @var array
     */
    protected $tree = [];
    /**
     * Items added
     *
     * @var array
     */
    protected $items = [];

    /**
     * All items with parent ID
     *
     * @var array
     */
    protected $parents = [];

    /**
     * Modified items are ready to save
     *
     * @var array
     */
    protected $changed = [];

    /**
     * Next id for new items
     *
     * @var int
     */
    protected $newId = -1;

    public function add(array $attributes): TreeBuilderInterface
    {
        $id = $attributes['id'];
        $name = $attributes['name'];
        $parentId = $attributes['parent_id'] ?? null;
        $isDeleted = $attributes['is_deleted'] ?? false;

        $this->items[$id] = [
            'id' => $id,
            'name' => $name,
            'parent_id' => $parentId,
            'is_deleted' => $isDeleted,
            'children' => []
        ];

        if (!isset($this->parents[$parentId])) {
            $this->parents[$parentId] = [];
        }

        $this->parents[$parentId][$id] = &$this->items[$id];

        if (isset($this->parents[$id])) {
            $this->items[$id]['children'] = &$this->parents[$id];
            foreach ($this->parents[$id] as $childId => $item) {
                unset($this->tree[$childId]);
            }
        }

        if (isset($this->items[$parentId])) {
            $this->items[$parentId]['children'][$id] = &$this->items[$id];
        } else {
            $this->tree[$id] = &$this->items[$id];
        }

        return $this;
    }

    public function addChild(int $parentId): TreeBuilderInterface
    {
        if (isset($this->items[$parentId]) && !$this->items[$parentId]['is_deleted']) {
            $this->add([
                'id' => $this->newId,
                'name' => 'new' . $this->newId,
                'parent_id' => $parentId,
            ]);
            $this->changed[$this->newId] = &$this->items[$this->newId];
            $this->newId--;
        }

        return $this;
    }

    public function updateName(int $id, string $name): TreeBuilderInterface
    {
        if (isset($this->items[$id]) && !$this->items[$id]['is_deleted']) {
            $this->items[$id]['name'] = $name;
            $this->changed[$id] = &$this->items[$id];
        }

        return $this;
    }

    public function deleteById(int $id): TreeBuilderInterface
    {
        if (isset($this->items[$id])) {
            $this->items[$id]['is_deleted'] = true;
            $this->changed[$id] = &$this->items[$id];
        }

        return $this;
    }

    public function flush(): TreeBuilderInterface
    {
        $this->tree = [];
        $this->items = [];
        $this->parents = [];
        $this->changed = [];
        $this->newId = -1;

        return $this;
    }

    public function getChangedItems(): array
    {
        return $this->changed;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getTree(): array
    {
        return $this->tree;
    }

    /**
     * Serializable class values
     *
     * @return array
     */
    public function __sleep()
    {
        return ['items', 'tree', 'parents', 'changed', 'newId'];
    }
}
