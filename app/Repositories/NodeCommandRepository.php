<?php

namespace App\Repositories;

use App\DTO\Interfaces\DtoInterface;
use App\DTO\NodeCommandDto;
use App\Models\MnemoSchemaNodeCommand;
use App\Repositories\Filters\FilterInterface;
use App\Repositories\Filters\NodeCommandFilter;
use App\Repositories\Interfaces\EntityRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * Node Command Repository
 *
 * @method MnemoSchemaNodeCommand getById(int $itemId)
 * @method MnemoSchemaNodeCommand store(DtoInterface $dto)
 */
class NodeCommandRepository extends BaseRepository implements EntityRepository
{
    protected string $className = MnemoSchemaNodeCommand::class;

    /**
     * Index
     *
     * @param NodeCommandFilter|FilterInterface|null $filter
     * @return Collection|array
     */
    public function index(NodeCommandFilter|FilterInterface|null $filter = null): Collection|array
    {
        return MnemoSchemaNodeCommand::query()
            ->when(
                $filter,
                function (Builder $query, NodeCommandFilter $filter) {
                    $query
                        ->when(
                            $filter->nodeId,
                            fn(Builder $query, int $nodeId) => $query->where('node_id', $nodeId)
                        )
                        ->when(
                            $filter->parameterCode,
                            fn(Builder $query, int $parameterCode) => $query->where('parameter_code', $parameterCode)
                        )
                        ->when(
                            !is_null($filter->parameterCodesNotIn),
                            fn(Builder $query) => $query->whereNotIn('parameter_code', $filter->parameterCodesNotIn)
                        );
                }
            )
            ->get();
    }

    /**
     * Update
     *
     * @param MnemoSchemaNodeCommand|Model $item
     * @param NodeCommandDto|DtoInterface $dto
     * @return MnemoSchemaNodeCommand|Model
     */
    public function update(
        MnemoSchemaNodeCommand|Model $item,
        NodeCommandDto|DtoInterface $dto
    ): MnemoSchemaNodeCommand|Model {
        $item->node_id = $dto->nodeId;
        $item->parameter_code = $dto->parameterCode;

        $item->save();

        return $item;
    }

    /**
     * Destroy By Node ID and Parameter Code
     *
     * @param int $nodeId
     * @param int $parameterCode
     * @return mixed
     */
    public function destroyByNodeIdAndParameterCode(int $nodeId, int $parameterCode): mixed
    {
        return MnemoSchemaNodeCommand::query()
            ->where('node_id', $nodeId)
            ->where('parameter_code', $parameterCode)
            ->delete();
    }

    /**
     * Sync Commands by Parameter Codes
     *
     * @param int $nodeId
     * @param array $parameterCodes
     * @return void
     * @throws Throwable
     */
    public function syncNodeParameterCodes(int $nodeId, array $parameterCodes): void
    {
        DB::beginTransaction();

        // Destroy Rest Commands
        $filter = (new NodeCommandFilter())
            ->setNodeId($nodeId)
            ->setParameterCodesNotIn($parameterCodes);
        $this->index($filter)
            ->each(function (MnemoSchemaNodeCommand $command) {
                $this->destroy($command);
            });

        // Create New
        $filter = (new NodeCommandFilter())->setNodeId($nodeId);
        $existedParameterCodes = $this->index($filter)->pluck('parameter_code')->toArray();
        foreach ($parameterCodes as $parameterCode) {
            if (!in_array($parameterCode, $existedParameterCodes)) {
                $dto = new NodeCommandDto(nodeId: $nodeId, parameterCode: $parameterCode);
                $this->store($dto);
            }
        }

        DB::commit();
    }
}
