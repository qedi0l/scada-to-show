<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * MnemoSchemaNode
 *
 * @property int $id
 * @property string $title
 * @property string $type
 * @property int $type_id
 * @property int $schema_id
 * @property int $group_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|MnemoSchemaNode newModelQuery()
 * @method static Builder|MnemoSchemaNode newQuery()
 * @method static Builder|MnemoSchemaNode query()
 * @method static Builder|MnemoSchemaNode whereCreatedAt($value)
 * @method static Builder|MnemoSchemaNode whereGroupId($value)
 * @method static Builder|MnemoSchemaNode whereId($value)
 * @method static Builder|MnemoSchemaNode whereSchemaId($value)
 * @method static Builder|MnemoSchemaNode whereTitle($value)
 * @method static Builder|MnemoSchemaNode whereType($value)
 * @method static Builder|MnemoSchemaNode whereUpdatedAt($value)
 *
 * @property MnemoSchema|null $schema Mnemo Schema
 * @property MnemoSchemaNodeType|null $node_type Type of Node
 * @property MnemoSchemaNodeGroup|null $group Group of Node
 * @property MnemoSchemaNodeOptions|null $options Options of Node
 * @property MnemoSchemaNodeAppearance|null $appearance Appearance of Node
 * @property MnemoSchemaNodeGeometry|null $geometry Geometry of Node
 * @property MnemoSchemaNodeLink|null $link Link of Node
 * @property MnemoSchemaNodeOptions[]|Collection<MnemoSchemaNodeOptions> $children_options Children Options
 * @property MnemoSchemaNodeCommand[]|Collection<MnemoSchemaNodeCommand> $commands Commands Options
 * @property MnemoSchemaNode[]|Collection<MnemoSchemaNode> $neighbours Neighbour Nodes
 * @property MnemoSchemaLine[]|Collection<MnemoSchemaLine> $from_lines Lines from Node
 * @property MnemoSchemaLine[]|Collection<MnemoSchemaLine> $to_lines Lines to Node
 */
class MnemoSchemaNode extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = [
        'type_id',
    ];

    /**
     * MnemoSchema
     *
     * @return BelongsTo
     */
    public function schema(): BelongsTo
    {
        return $this->belongsTo(MnemoSchema::class, 'schema_id', 'id');
    }

    /**
     * Type of Node
     *
     * @return BelongsTo
     */
    public function node_type(): BelongsTo
    {
        return $this->belongsTo(MnemoSchemaNodeType::class, 'type_id');
    }

    /**
     * Group of Node
     *
     * @return BelongsTo
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(MnemoSchemaNodeGroup::class, 'group_id');
    }

    /**
     * Options of Node
     *
     * @return HasOne
     */
    public function options(): HasOne
    {
        return $this->hasOne(MnemoSchemaNodeOptions::class, 'node_id');
    }

    /**
     * Appearance of Node
     *
     * @return HasOne
     */
    public function appearance(): HasOne
    {
        return $this->hasOne(MnemoSchemaNodeAppearance::class, 'node_id');
    }

    /**
     * Geometry of Node
     *
     * @return HasOne
     */
    public function geometry(): HasOne
    {
        return $this->hasOne(MnemoSchemaNodeGeometry::class, 'node_id');
    }

    /**
     * Link of Node
     *
     * @return HasOne
     */
    public function link(): HasOne
    {
        return $this->hasOne(MnemoSchemaNodeLink::class, 'node_id');
    }

    /**
     * Children Options
     *
     * @return HasMany
     */
    public function children_options(): HasMany
    {
        return $this->hasMany(MnemoSchemaNodeOptions::class, 'parent_id');
    }

    /**
     * Commands
     *
     * @return HasMany
     */
    public function commands(): HasMany
    {
        return $this->hasMany(MnemoSchemaNodeCommand::class, 'node_id');
    }

    /**
     * Neighbour Nodes
     *
     * @return HasMany
     */
    public function neighbours(): HasMany
    {
        return $this->hasMany(MnemoSchemaNode::class, 'schema_id', 'schema_id');
    }

    /**
     * Lines from Node
     *
     * @return HasMany
     */
    public function from_lines(): HasMany
    {
        return $this->hasMany(MnemoSchemaLine::class, 'first_node');
    }

    /**
     * Lines to Node
     *
     * @return HasMany
     */
    public function to_lines(): HasMany
    {
        return $this->hasMany(MnemoSchemaLine::class, 'second_node');
    }
}
