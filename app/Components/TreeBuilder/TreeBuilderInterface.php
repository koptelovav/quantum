<?php

namespace App\Components\TreeBuilder;

/**
 * Interface TreeBuilderInterface
 * @package App\Models
 */
interface TreeBuilderInterface
{
    /**
     * Add new node to cache
     *
     * @param array $array
     * @return TreeBuilderInterface
     */
    public function add(array $array): self;

    /**
     * Add node child
     *
     * @param $id
     * @return TreeBuilderInterface
     */
    public function addChild(int $id): self;

    /**
     * Update node name
     *
     * @param $id
     * @param string $name
     * @return TreeBuilderInterface
     */
    public function updateName(int $id, string $name): self;

    /**
     * Delete item by id
     *
     * @param int $id
     * @return TreeBuilderInterface
     */
    public function deleteById(int $id): self;

    /**
     * Flush cache
     *
     * @return TreeBuilderInterface
     */
    public function flush(): self;

    /**
     * Get changed nodes
     *
     * @return array
     */
    public function getChangedItems(): array;

    /**
     * Get tree for UI
     *
     * @return array
     */
    public function getTree(): array;
}
