<?php

namespace App\Http\Controllers\Api;

use App\Services\NodeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

/**
 * Class DbController
 * @package App\Http\Controllers\Api
 */
class DbController extends BaseController
{
    protected $nodeManager;

    public function __construct(NodeService $nodeManager)
    {
        $this->nodeManager = $nodeManager;
    }

    /**
     * Get tree from DB
     *
     * @return JsonResponse
     */
    public function get(): JsonResponse
    {
        return response()->json($this->nodeManager->getTree());
    }
}
