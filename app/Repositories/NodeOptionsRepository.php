<?php

namespace App\Repositories;

use App\DTO\Interfaces\DtoInterface;
use App\DTO\NodeOptionsDto;
use App\Models\MnemoSchemaNodeOptions;
use App\Repositories\Interfaces\EntityRepository;
use Exception;
use Illuminate\Database\Eloquent\Model;

/**
 * Node Options Repository
 *
 * @method MnemoSchemaNodeOptions getById(int $itemId)
 * @method MnemoSchemaNodeOptions store(DtoInterface $dto)
 */
class NodeOptionsRepository extends BaseRepository implements EntityRepository
{
    protected string $className = MnemoSchemaNodeOptions::class;

    protected NodeRepository $nodeRepository;

    public function __construct()
    {
        $this->nodeRepository = new NodeRepository();
    }

    /**
     * Get By Node ID
     *
     * @param int $nodeId
     * @return MnemoSchemaNodeOptions|Model
     */
    public function getByNodeId(int $nodeId): MnemoSchemaNodeOptions|Model
    {
        return MnemoSchemaNodeOptions::query()->where('node_id', $nodeId)->firstOrFail();
    }

    /**
     * Update
     *
     * @param MnemoSchemaNodeOptions|Model $item
     * @param NodeOptionsDto|DtoInterface $dto
     * @return MnemoSchemaNodeOptions|Model
     */
    public function update(
        MnemoSchemaNodeOptions|Model $item,
        NodeOptionsDto|DtoInterface $dto
    ): MnemoSchemaNodeOptions|Model {
        $item->node_id = $dto->nodeId;
        $item->z_index = $dto->zIndex;
        $item->parameter_code = $dto->parameterCode;
        $item->hardware_code = $dto->hardwareCode;
        $item->label = $dto->label;
        $item->parent_id = $dto->parentId;

        $item->save();

        return $item;
    }

    /**
     * Update Z-Index of Node
     *
     * @param int|MnemoSchemaNodeOptions $nodeOptions Node Options ID or Model of Node Options
     * @param int $zIndex
     * @return MnemoSchemaNodeOptions|Model
     * @throws Exception
     */
    public function updateZIndex(int|MnemoSchemaNodeOptions $nodeOptions, int $zIndex): MnemoSchemaNodeOptions|Model
    {
        if (is_int($nodeOptions)) {
            $nodeOptions = $this->getById($nodeOptions);
        }

        $dto = new NodeOptionsDTO(
            nodeId: $nodeOptions->node_id,
            zIndex: $zIndex,
            parameterCode: $nodeOptions->parameter_code,
            hardwareCode: $nodeOptions->hardware_code,
            parentId: $nodeOptions->parent_id,
            label: $nodeOptions->label,
        );

        return $this->update($nodeOptions, $dto);
    }

    /**
     * Get Max Z-index of Neighbour Nodes
     *
     * @param int $nodeId
     * @return int|null
     * @throws Exception
     */
    public function getMaxZIndexOfNeighbourNodes(int $nodeId): int|null
    {
        $node = $this->nodeRepository->getById($nodeId);

        $node->load(['options', 'neighbours.options']);

        return $node
            ->neighbours
            ->except($node->getKey())
            ->max('options.z_index');
    }

    /**
     * Get Min Z-index of Neighbour Nodes
     *
     * @param int $nodeId
     * @return int|null
     * @throws Exception
     */
    public function getMinZIndexOfNeighbourNodes(int $nodeId): int|null
    {
        $node = $this->nodeRepository->getById($nodeId);

        $node->load(['options', 'neighbours.options']);

        return $node
            ->neighbours
            ->except($node->getKey())
            ->min('options.z_index');
    }


}
