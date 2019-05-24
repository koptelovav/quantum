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
     * All parents of added items
     *
     * @var array
     */
    protected $ancestors = [];

    /**
     * Next id for new items
     *
     * @var int
     */
    protected $newId = -1;

    public function add(array $attributes): TreeBuilderInterface
    {
        $item = $this->registerItem($attributes);

        $this->resetItemChanges($item['id']);
        $this->registerAncestors($item['id']);
        $this->registerDirectParent($item['id'], $item['parent_id']);
        $this->buildTree($item['id'], $item['parent_id']);
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

        if (isset($this->ancestors[$id])) {
            foreach ($this->ancestors[$id] as $childId => $child) {
                $this->deleteById($childId);
            }
        }

        return $this;
    }

    public function applyChanges(array $idMap = []): TreeBuilderInterface
    {
        if (empty($idMap)) {
            $this->changed = [];
        } else {
            $this->rebuildTree($idMap);
        }

        return $this;
    }

    public function flush(): TreeBuilderInterface
    {
        $this->tree = [];
        $this->items = [];
        $this->parents = [];
        $this->changed = [];
        $this->ancestors = [];
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
        return ['items', 'tree', 'parents', 'changed', 'ancestors', 'newId'];
    }

    protected function resetItemChanges(int $itemId): void
    {
        unset($this->changed[$itemId]);
    }

    /**
     * Register new item
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
     * Register all item`s parents
     * Used to speed up tree searches
     *
     * @param int $itemId
     */
    protected function registerAncestors(int $itemId): void
    {
        foreach ($this->items[$itemId]['ancestors'] as $ancestorId) {
            if (!isset($this->ancestors[$ancestorId])) {
                $this->ancestors[$ancestorId] = [];
            }

            $this->ancestors[$ancestorId][$itemId] = &$this->items[$itemId];
        }
    }

    /**
     * Register item parent
     * Used to build tree
     *
     * @param int $itemId
     * @param int|null $parentId
     */
    protected function registerDirectParent(int $itemId, ?int $parentId): void
    {
        if ($parentId !== null) {
            if (!isset($this->parents[$parentId])) {
                $this->parents[$parentId] = [];
            }
            $this->parents[$parentId][$itemId] = &$this->items[$itemId];
        }
    }

    /**
     * Mark as deleted if the parent object is already deleted in the cache
     *
     * @param int $itemId
     */
    protected function markDeleted(int $itemId): void
    {
        foreach ($this->items[$itemId]['ancestors'] as $ancestorId) {
            if (isset($this->items[$ancestorId]) && $this->items[$ancestorId]['is_deleted']) {
                $this->deleteById($itemId);
                break;
            }
        }
    }

    /**
     * Build item tree for UI
     *
     * @param int $itemId
     * @param int|null $parentId
     */
    protected function buildTree(int $itemId, ?int $parentId): void
    {
        if (isset($this->parents[$itemId])) {
            $this->items[$itemId]['children'] = &$this->parents[$itemId];
            foreach ($this->parents[$itemId] as $childId => $item) {
                unset($this->tree[$childId]);
            }
        }

        if (isset($this->items[$parentId])) {
            $this->items[$parentId]['children'][$itemId] = &$this->items[$itemId];
        } else {
            $this->tree[$itemId] = &$this->items[$itemId];
        }
    }

    /**
     * Build tree with new IDs
     *
     * @param array $idMap
     */
    protected function rebuildTree(array $idMap = []): void
    {
        $items = $this->items;
        $this->flush();

        foreach ($idMap as $oldId => $newId) {
            $items[$newId] = $items[$oldId];
            unset($items[$oldId]);

            $items[$newId]['id'] = $newId;
            $items[$newId]['parent_id'] = $idMap[$items[$newId]['parent_id']] ?? $items[$newId]['parent_id'];

            foreach ($items[$newId]['ancestors'] as $ancestorId) {
                if(isset($idMap[$ancestorId])) {
                    $key = array_search($ancestorId, $items[$newId]['ancestors'], true);
                    unset($items[$newId]['ancestors'][$key]);
                    $items[$newId]['ancestors'][] = $idMap[$ancestorId];
                }
            }
        }

        foreach ($items as $item) {
            $this->add($item);
        }
    }
}
