<?php

namespace App\Components\TreeBuilder;

/**
 * Class TreeBuilder
 * @package App\Models
 */
class TreeBuilder implements TreeBuilderInterface
{
    /**
     * Items added
     *
     * @var array
     */
    protected $items = [];

    /**
     * Completed items tree
     *
     * @var array
     */
    protected $tree = [];

    /**
     * Changed items ready to save
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
        //re-add item from DB. restores the name and state of the item.
        if (isset($this->items[$attributes['id']])) {
            $this->items[$attributes['id']]['value'] = $attributes['value'];
            $this->items[$attributes['id']]['is_deleted'] = $attributes['is_deleted'];
            return $this;
        }

        $item = $this->registerItem($attributes);
        $this->addToTree($item['id']);
        $this->markDeleted($item['id']);

        return $this;
    }

    public function addChild(int $parentId): TreeBuilderInterface
    {
        $ancestors = $this->items[$parentId]['ancestors'];
        $ancestors[] = $parentId;

        $this->add([
            'id' => $this->newId,
            'value' => 'new' . $this->newId,
            'parent_id' => $parentId,
            'ancestors' => $ancestors,
        ]);

        $this->changed[$this->newId] = &$this->items[$this->newId];
        $this->newId--;

        return $this;
    }

    public function updateName(int $id, string $value): TreeBuilderInterface
    {
        $this->items[$id]['value'] = $value;
        $this->changed[$id] = &$this->items[$id];

        return $this;
    }

    public function deleteById(int $id): TreeBuilderInterface
    {
        $this->items[$id]['is_deleted'] = true;
        $this->changed[$id] = &$this->items[$id];

        foreach ($this->items[$id]['children'] as $child) {
            $this->deleteById($child['id']);
        }

        //check unrelated items
        foreach ($this->tree as $rootNode) {
            if (in_array($id, $rootNode['ancestors'], true)) {
                $this->deleteById($rootNode['id']);
            }
        }

        return $this;
    }

    public function applyChanges(array $idMap = []): TreeBuilderInterface
    {
        foreach ($idMap as $oldId => $newId) {
            //replace item ID
            $this->items[$oldId]['id'] = $newId;

            //replace item parent ID
            if (isset($idMap[$this->items[$oldId]['parent_id']])) {
                $this->items[$oldId]['parent_id'] = $idMap[$this->items[$oldId]['parent_id']];
            }

            //replace item ancestors ID
            foreach ($this->items[$oldId]['ancestors'] as $key => $ancestorId) {
                if (isset($idMap[$ancestorId])) {
                    $this->items[$oldId]['ancestors'][$key] = $idMap[$ancestorId];
                }
            }

            //register item with new ID
            $this->items[$newId] = &$this->items[$oldId];
            unset($this->items[$oldId]);
        }

        $this->changed = [];
        return $this;
    }

    public function flush(): TreeBuilderInterface
    {
        $this->tree = [];
        $this->items = [];
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
        return ['items', 'tree', 'changed', 'newId'];
    }

    /**
     * Add a new item to registry (item array)
     *
     * @param array $attributes
     * @return array
     */
    protected function registerItem(array $attributes): array
    {
        $this->items[$attributes['id']] = [
            'id' => $attributes['id'],
            'value' => $attributes['value'],
            'parent_id' => $attributes['parent_id'] ?? null,
            'is_deleted' => $attributes['is_deleted'] ?? false,
            'ancestors' => $attributes['ancestors'] ?? [],
            'children' => [],
        ];

        return $this->items[$attributes['id']];
    }

    /**
     * Add a new item to tree
     *
     * @param int $id
     */
    protected function addToTree(int $id): void
    {
        $this->tree[] = &$this->items[$id];

        foreach ($this->tree as $key => $rootItem) {
            if (isset($this->items[$rootItem['parent_id']])) {
                $this->items[$rootItem['parent_id']]['children'][] = &$this->items[$rootItem['id']];
                unset($this->tree[$key]);
            }
        }
    }

    /**
     * Mark the item as deleted if its parent has already been deleted.
     *
     * @param int $id
     */
    protected function markDeleted(int $id): void
    {
        foreach ($this->items[$id]['ancestors'] as $ancestorId) {
            if (isset($this->items[$ancestorId]) && $this->items[$ancestorId]['is_deleted']) {
                $this->items[$id]['is_deleted'] = true;
            }
        }
    }
}
