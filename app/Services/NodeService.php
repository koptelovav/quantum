<?php

namespace App\Services;

use App\Models\Node;
use Illuminate\Support\Facades\DB;

/**
 * Class NodeService
 * @package App\Managers
 */
class NodeService
{
    /**
     * Get exist node by ID
     *
     * @param int $id
     * @return array|null
     */
    public function getById(int $id): ?array
    {
        $node = Node::where(['is_deleted' => false])->find($id);
        return $node === null ? null : $node->toArray();
    }

    /**
     * Truncate node table
     */
    public function flush(): void
    {
        Node::query()->truncate();
    }

    public function getTree(): array
    {
        return Node::get()->toTree()->toArray();
    }

    /**
     * Batch cache saving
     *
     * @param array $items
     * @return bool
     */
    public function batchSave(array $items): bool
    {
        DB::beginTransaction();

        try {
            $idMap = [];
            foreach ($items as $item) {
                $oldId = null;
                /** @var Node $node */
                $node = Node::find($item['id']);

                if ($node === null) {
                    $node = new Node();
                    if ($item['id'] < 0) {
                        $oldId = $item['id'];
                        $item['id'] = null;
                    }
                    if (isset($item['parent_id']) && $item['parent_id'] < 0) {
                        if (isset($idMap[$item['parent_id']])) {
                            $item['parent_id'] = $idMap[$item['parent_id']];
                        } else {
                            throw new \RuntimeException('Parent node #' . $item['parent_id'] . ' not found');
                        }
                    }
                } elseif ($node->is_deleted) {
                    //skip previously deleted node
                    continue;
                }

                $node->fill($item);
                if (!$node->save()) {
                    throw new \RuntimeException('Node #' . $item['id'] . ' not saved');
                }
                if ($oldId !== null) {
                    $idMap[$oldId] = $node->id;
                }
            }
        } catch (\Exception $e) {
            //Add a logger if you needed
            DB::rollBack();
            return false;
        }

        DB::commit();
        return true;
    }
}
