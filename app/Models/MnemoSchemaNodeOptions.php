<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * MnemoSchemaNodeOptions
 *
 * @property int $id
 * @property int|null $parent_id
 * @property string|null $hardware_code
 * @property string|null $parameter_code
 * @property string|null $label
 * @property int $z_index
 * @property int $node_id
 * @property int $tag_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|MnemoSchemaNodeOptions newModelQuery()
 * @method static Builder|MnemoSchemaNodeOptions newQuery()
 * @method static Builder|MnemoSchemaNodeOptions query()
 * @method static Builder|MnemoSchemaNodeOptions whereCreatedAt($value)
 * @method static Builder|MnemoSchemaNodeOptions whereHardwareCode($value)
 * @method static Builder|MnemoSchemaNodeOptions whereId($value)
 * @method static Builder|MnemoSchemaNodeOptions whereNodeId($value)
 * @method static Builder|MnemoSchemaNodeOptions whereParameterCode($value)
 * @method static Builder|MnemoSchemaNodeOptions whereParentId($value)
 * @method static Builder|MnemoSchemaNodeOptions whereUpdatedAt($value)
 * @method static Builder|MnemoSchemaNodeOptions whereZIndex($value)
 *
 * @property MnemoSchemaNode|null $node Node of Options
 * @property MnemoSchemaNode|null $parent_node Parent Node
 */
class MnemoSchemaNodeOptions extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Node of Options
     *
     * @return BelongsTo
     */
    public function node(): BelongsTo
    {
        return $this->belongsTo(MnemoSchemaNode::class, 'node_id');
    }

    /**
     * Parent Node
     *
     * @return BelongsTo
     */
    public function parent_node(): BelongsTo
    {
        return $this->belongsTo(MnemoSchemaNode::class, 'parent_id');
    }
}
