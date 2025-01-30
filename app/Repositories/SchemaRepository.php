<?php

namespace App\Repositories;

use App\DTO\Interfaces\DtoInterface;
use App\DTO\SchemaDto;
use App\Models\MnemoSchema;
use App\Repositories\Filters\FilterInterface;
use App\Repositories\Filters\NodeFilter;
use App\Repositories\Filters\SchemaFilter;
use App\Repositories\Interfaces\EntityRepository;
use App\Services\SchemaPreviewService;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * Schema Repository
 *
 * @method MnemoSchema getById(int $itemId)
 * @method MnemoSchema store(DtoInterface $dto)
 */
class SchemaRepository extends BaseRepository implements EntityRepository
{
    protected string $className = MnemoSchema::class;

    protected NodeRepository $nodeRepository;
    protected LineRepository $lineRepository;

    protected SchemaPreviewService $schemaPreviewService;

    public function __construct()
    {
        $this->nodeRepository = new NodeRepository();
        $this->lineRepository = new LineRepository();
        $this->schemaPreviewService = new SchemaPreviewService();
    }

    /**
     * Index
     *
     * @param SchemaFilter|FilterInterface|null $filter
     * @return array|Collection
     */
    public function index(
        SchemaFilter|null|Filters\FilterInterface $filter = null,

    ): array|Collection {
        return MnemoSchema::query()
            ->when(
                $filter,
                function (Builder $query, SchemaFilter $filter) {
                    $query
                        ->when(
                            $filter->projectId,
                            fn(Builder $query, int $projectId) => $query->where('project_id', $projectId)
                        )
                        ->when(
                            $filter->name,
                            fn(Builder $query, string $name) => $query->where('name', $name)
                        )
                        ->when(
                            !is_null($filter->active),
                            function (Builder $query) use ($filter) {
                                $query->where('is_active', $filter->active);
                            }
                        )
                        ->when(
                            !is_null($filter->default),
                            function (Builder $query) use ($filter) {
                                $query->where('default', $filter->default);
                            }
                        )
                        ->when(
                            !is_null($filter->hasNodes),
                            function (Builder $query) use ($filter) {
                                if ($filter->hasNodes) {
                                    $query->whereHas('nodes');
                                } else {
                                    $query->whereDoesntHave('nodes');
                                }
                            }
                        )
                        ->when(
                            !is_null($filter->hasNotEmptyParameterCodeNodes),
                            function (Builder $query) {
                                $query->whereHas('nodes.options', function (Builder $builder) {
                                    $builder->whereNotNull('parameter_code');
                                });
                            }
                        );
                }
            )
            ->get();
    }


    /**
     * Update
     *
     * @param MnemoSchema|Model $item
     * @param SchemaDto|DtoInterface $dto
     * @return MnemoSchema|Model
     */
    public function update(MnemoSchema|Model $item, SchemaDto|DtoInterface $dto): MnemoSchema|Model
    {
        $item->name = $dto->name;
        $item->title = $dto->title;
        $item->project_id = $dto->projectId;
        $item->is_active = $dto->isActive;
        $item->default = $dto->default;
        $item->preview_file_name = $dto->previewFileName;

        $item->save();

        return $item;
    }

    /**
     * Destroy
     *
     * @param int|MnemoSchema|Model $item
     * @return bool|null
     * @throws Throwable
     */
    public function destroy(int|MnemoSchema|Model $item): ?bool
    {
        if (is_int($item)) {
            $item = $this->getById($item);
        }

        $this->nodeRepository->destroyBySchemaId($item->getKey());
        $this->lineRepository->destroyBySchemaId($item->getKey());

        return $item->delete();
    }

    /**
     * Set Default Schema
     *
     * @param int|MnemoSchema $schema
     * @return MnemoSchema|Model
     * @throws Throwable
     */
    public function setDefaultSchema(int|MnemoSchema $schema): MnemoSchema|Model
    {
        if (is_int($schema)) {
            $schema = $this->getById($schema);
        }

        DB::beginTransaction();

        // Set All Schemas, except needle Default - false
        $filter = (new SchemaFilter())->setDefault(true);
        $this->index($filter)
            ->except($schema->getKey())
            ->each(function (MnemoSchema $currentSchema) {
                $dto = new SchemaDto(
                    name: $currentSchema->name,
                    title: $currentSchema->title,
                    projectId: $currentSchema->project_id,
                    isActive: $currentSchema->is_active,
                    default: false,
                );
                $this->update($currentSchema, $dto);
            });

        // Set needle Schema Default - true
        $dto = new SchemaDto(
            name: $schema->name,
            title: $schema->title,
            projectId: $schema->project_id,
            isActive: $schema->is_active,
            default: true,
        );
        $schema = $this->update($schema, $dto);

        DB::commit();

        return $schema;
    }

    /**
     * @return MnemoSchema[]|Builder[]|Collection
     */
    public function getAllSchemasWithData(): array|Collection
    {
        return MnemoSchema::query()
            ->with([
                'lines' => ['options', 'appearance'],
                'service_nodes' => function (HasMany $builder) {
                    $builder->with(['node_type', 'options', 'appearance', 'geometry', 'link']);
                },
                'nodes' => function (HasMany $builder) {
                    $builder->with(['node_type', 'options', 'appearance', 'geometry', 'link']);
                },
                'parent_nodes' => function (HasMany $builder) {
                    $builder->with('children_options');
                }
            ])
            ->get();
    }

    /**
     * @param string|null $schemaName
     * @return Collection
     */
    public function getSchemasWithSignals(string|null $schemaName = null): Collection
    {
        $filter = (new SchemaFilter())
            ->setActive(true)
            ->setHasNodes(true)
            ->setHasNotEmptyParameterCodeNodes(true);

        if ($schemaName !== null) {
            $filter->setName($schemaName);
        }

        $schemas = $this->index($filter);

        return $schemas
            ->load([
                'nodes' => function (HasMany $builder) {
                    return $builder->with(['options', 'commands.node.options']);
                }
            ]);
    }

    /**
     * @param string $schemaName
     * @return MnemoSchema|Model|null
     */
    public function getSchemaDataByName(string $schemaName): MnemoSchema|Model|null
    {
        $filter = (new SchemaFilter())->setName($schemaName);

        $items = $this->index($filter);

        return $items
            ->load([
                'lines' => ['options', 'appearance'],
                'nodes' => function (HasMany $builder) {
                    $builder->with(['node_type',  'options', 'appearance', 'geometry', 'link']);
                },
                'service_nodes' => function (HasMany $builder) {
                    $builder->with(['node_type', 'options', 'appearance', 'geometry', 'link']);
                },
                'parent_nodes' => function (HasMany $builder) {
                    $builder
                        ->with('children_options');
                }
            ])
            ->first();
    }

    /**
     * @param string $schemaName
     * @return MnemoSchema|Model|null
     */
    public function getNodeParamsByName(string $schemaName): MnemoSchema|Model|null
    {
        $filter = (new SchemaFilter())->setName($schemaName);

        $items = $this->index($filter);

        return $items->load(['nodes.options'])->first();
    }


    /**
     * @param string $name
     * @return MnemoSchema|Builder|Model
     */
    public function getByName(string $name): Model|Builder|MnemoSchema
    {
        return MnemoSchema::query()->where('name', $name)->firstOrFail();
    }

    /**
     * Get max Z-index of Schema Nodes
     *
     * @param int $schemaId
     * @return int|null
     */
    public function getMaxZIndex(int $schemaId): ?int
    {
        $filter = (new NodeFilter())->setSchemaId($schemaId);
        return (new NodeRepository())
            ->index($filter)
            ->load('options')
            ->max('options.z_index');
    }

    /**
     * Set Schema Preview
     *
     * @param int|MnemoSchema $schema
     * @param UploadedFile $previewFile
     * @return MnemoSchema|null
     * @throws Exception
     */
    public function setPreview(int|MnemoSchema $schema, UploadedFile $previewFile): ?MnemoSchema
    {
        if (is_int($schema)) {
            $schema = $this->getById($schema);
        }

        if ($schema->preview_file_name) {
            $this->schemaPreviewService->deleteFile($schema->preview_file_name);
        }

        $fileName = $this->schemaPreviewService->saveFile($previewFile);

        if ($fileName) {
            $dto = new SchemaDto(
                name: $schema->name,
                title: $schema->title,
                projectId: $schema->project_id,
                isActive: $schema->is_active,
                default: $schema->default,
                previewFileName: $fileName
            );

            return $this->update($schema, $dto);
        }

        return null;
    }

    /**
     * Unset Schema Preview
     *
     * @param int|MnemoSchema $schema
     * @return MnemoSchema|null
     * @throws Exception
     */
    public function unsetPreview(int|MnemoSchema $schema): ?MnemoSchema
    {
        if (is_int($schema)) {
            $schema = $this->getById($schema);
        }

        if ($schema->preview_file_name) {
            $this->schemaPreviewService->deleteFile($schema->preview_file_name);

            $dto = new SchemaDto(
                name: $schema->name,
                title: $schema->title,
                projectId: $schema->project_id,
                isActive: $schema->is_active,
                default: $schema->default,
                previewFileName: null
            );
            return $this->update($schema, $dto);
        }

        return $schema;
    }
}
