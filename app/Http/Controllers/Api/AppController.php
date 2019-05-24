<?php

namespace App\Http\Controllers\Api;

use App\Services\TreeCacheService;
use App\Services\NodeService;

/**
 * Class AppController
 * @package App\Http\Controllers\Api
 */
class AppController
{
    protected $cacheService;
    protected $nodeService;

    public function __construct(TreeCacheService $cacheService, NodeService $nodeService)
    {
        $this->cacheService = $cacheService;
        $this->nodeService = $nodeService;
    }

    /**
     * Reset the application to its original state
     */
    public function reset(): void
    {
        $tree = $this->cacheService->getFromCache()->flush();
        $this->cacheService->saveToCache($tree);
        $this->nodeService->flush();
        $this->fillDb();
    }

    /**
     * Simple DB seeder
     */
    protected function fillDb(): void
    {
        $this->nodeService->batchSave([
            ['id' => 1, 'value' => 'node 1'],
            ['id' => 2, 'value' => 'node 2', 'parent_id' => 1],
            ['id' => 3, 'value' => 'node 3', 'parent_id' => 1],
            ['id' => 4, 'value' => 'node 4', 'parent_id' => 3],
            ['id' => 5, 'value' => 'node 5', 'parent_id' => 4],
            ['id' => 6, 'value' => 'node 6', 'parent_id' => 2],
            ['id' => 7, 'value' => 'node 7', 'parent_id' => 4],
            ['id' => 8, 'value' => 'node 8', 'parent_id' => 7],
            ['id' => 9, 'value' => 'node 9', 'parent_id' => 8],
            ['id' => 10, 'value' => 'node 10', 'parent_id' => 7],
            ['id' => 11, 'value' => 'node 11', 'parent_id' => 9],
            ['id' => 12, 'value' => 'node 12', 'parent_id' => 11],
            ['id' => 13, 'value' => 'node 13', 'parent_id' => 11],
            ['id' => 14, 'value' => 'node 14', 'parent_id' => 7],
        ]);
    }
}
