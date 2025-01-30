<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * MnemoSchemaNode
 *
 * @property int $id
 * @property string $type
 * @property string $hardware_type
 * @property string $title
 * @property string $svg
 * @property string $shortTitle
 * @property bool $service_type
 * @property int $group_id
 * @property int $node_type_group_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|MnemoSchemaNodeType newModelQuery()
 * @method static Builder|MnemoSchemaNodeType newQuery()
 * @method static Builder|MnemoSchemaNodeType query()
 * @method static Builder|MnemoSchemaNodeType whereCreatedAt($value)
 * @method static Builder|MnemoSchemaNodeType whereGroupId($value)
 * @method static Builder|MnemoSchemaNodeType whereId($value)
 * @method static Builder|MnemoSchemaNodeType whereSchemaId($value)
 * @method static Builder|MnemoSchemaNodeType whereTitle($value)
 * @method static Builder|MnemoSchemaNodeType whereType($value)
 * @method static Builder|MnemoSchemaNodeType whereUpdatedAt($value)
 * @method static Builder|MnemoSchemaNodeType whereNodeTypeGroupId($value)
 *
 * @property MnemoSchemaNodeTypeGroup|null $group Node Type Group
 */
class MnemoSchemaNodeType extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Group of Node Type
     *
     * @return BelongsTo
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(MnemoSchemaNodeTypeGroup::class, 'node_type_group_id');
    }
}
