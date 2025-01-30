<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * MnemoSchema
 *
 * @property int $id
 * @property string $title
 * @property bool $is_active
 * @property bool $default
 * @property int|null $project_id
 * @property string|null $preview_file_name Preview File Name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|MnemoSchema newModelQuery()
 * @method static Builder|MnemoSchema newQuery()
 * @method static Builder|MnemoSchema query()
 * @method static Builder|MnemoSchema whereCreatedAt($value)
 * @method static Builder|MnemoSchema whereId($value)
 * @method static Builder|MnemoSchema whereIsActive($value)
 * @method static Builder|MnemoSchema whereTitle($value)
 * @method static Builder|MnemoSchema whereUpdatedAt($value)
 * @property string $name
 * @method static Builder|MnemoSchema whereName($value)
 *
 * @property Collection<MnemoSchemaNode>|MnemoSchemaNode[] $nodes Nodes of Schema
 * @property Collection<MnemoSchemaNode>|MnemoSchemaNode[] $parent_nodes ...
 * @property Collection<MnemoSchemaNode>|MnemoSchemaNode[] $simple_nodes Simple Nodes of Schema
 * @property Collection<MnemoSchemaNode>|MnemoSchemaNode[] $service_nodes Service Nodes of Schema
 * @property Collection<MnemoSchemaLine>|MnemoSchemaLine[] $lines Lines of Schema
 * @property Collection<CommandQueue>|CommandQueue[] $commands Commands
 * @property Collection<MnemoSchemaNodeLink>|MnemoSchemaNodeLink[] $links Links
 */
class MnemoSchema extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Nodes of Schema
     *
     * @return HasMany
     */
    public function nodes(): HasMany
    {
        return $this->hasMany(MnemoSchemaNode::class, 'schema_id');
    }

    /**
     * Parent nodes of schema
     * @return HasMany
     */
    public function parent_nodes(): HasMany
    {
        return $this->hasMany(MnemoSchemaNode::class, 'schema_id')
            ->whereHas('children_options');
    }

    /**
     * Non-Service Nodes of Schema
     *
     * @return HasMany
     */
    public function simple_nodes(): HasMany
    {
        return $this
            ->nodes()
            ->whereHas('node_type', function (Builder $builder) {
                $builder->where('service_type', false);
            });
    }

    /**
     * Service Nodes of Schema
     *
     * @return HasMany
     */
    public function service_nodes(): HasMany
    {
        return $this
            ->nodes()
            ->whereHas('node_type', function (Builder $builder) {
                $builder->where('service_type', true);
            });
    }

    /**
     * Lines of Schema
     *
     * @return HasMany
     */
    public function lines(): HasMany
    {
        return $this->hasMany(MnemoSchemaLine::class, 'schema_id');
    }

    /**
     * Commands
     *
     * @return HasMany
     */
    public function commands(): HasMany
    {
        return $this->hasMany(CommandQueue::class, 'schema_id');
    }

    /**
     * Schema Links
     *
     * @return HasMany
     */
    public function links(): HasMany
    {
        return $this->hasMany(MnemoSchemaNodeLink::class, 'schema_id');
    }
}
