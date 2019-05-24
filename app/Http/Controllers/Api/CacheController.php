<?php

namespace App\Http\Controllers\Api;

use App\Services\TreeCacheService;
use App\Services\NodeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class CacheController
 * @package App\Http\Controllers\Api
 */
class CacheController extends BaseController
{
    protected $treeCacheService;
    protected $nodeService;

    public function __construct(TreeCacheService $cacheService, NodeService $nodeService)
    {
        $this->treeCacheService = $cacheService;
        $this->nodeService = $nodeService;
    }

    /**
     * Get cache tree
     *
     * @return JsonResponse
     */
    public function get(): JsonResponse
    {
        return response()->json($this->treeCacheService->getFromCache()->getTree());
    }

    /**
     * Load node from DB
     *
     * @param $id
     * @return JsonResponse
     */
    public function load($id): JsonResponse
    {
        $node = $this->nodeService->getById((int)$id);
        if ($node === null) {
            throw new NotFoundHttpException(sprintf('Node #%s not found', $id));
        }

        $tree = $this->treeCacheService->getFromCache()->add($node);
        $this->treeCacheService->saveToCache($tree);

        return response()->json($tree->getTree());
    }

    /**
     * Delete node
     *
     * @param $id
     * @return JsonResponse
     */
    public function delete($id): JsonResponse
    {
        $tree = $this->treeCacheService->getFromCache()->deleteById($id);
        $this->treeCacheService->saveToCache($tree);

        return response()->json($tree->getTree());
    }

    /**
     * Add new node to parent node
     *
     * @param $id
     * @return JsonResponse
     */
    public function add($id): JsonResponse
    {
        $tree = $this->treeCacheService->getFromCache()->addChild($id);
        $this->treeCacheService->saveToCache($tree);

        return response()->json($tree->getTree());
    }

    /**
     * Update node name
     *
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        $value = $request->input('value');
        if ($value === null) {
            throw new BadRequestHttpException('Value can not be blank');
        }

        $tree = $this->treeCacheService->getFromCache()->updateName($id, $value);
        $this->treeCacheService->saveToCache($tree);

        return response()->json($tree->getTree());
    }

    /**
     * Save cache to DB
     */
    public function save(): void
    {
        $tree = $this->treeCacheService->getFromCache();
        if ($this->nodeService->batchSave($tree->getChangedItems())) {
            $tree->applyChanges();
            $this->treeCacheService->saveToCache($tree);
        } else {
            throw new \RuntimeException('Error while saving cache');
        }
    }
}
