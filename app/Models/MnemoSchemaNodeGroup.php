<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\MnemoSchemaNodeGroup
 *
 * @property int $id
 * @property string $title
 * @property string $svg_url
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|MnemoSchemaNodeGroup newModelQuery()
 * @method static Builder|MnemoSchemaNodeGroup newQuery()
 * @method static Builder|MnemoSchemaNodeGroup query()
 * @method static Builder|MnemoSchemaNodeGroup whereCreatedAt($value)
 * @method static Builder|MnemoSchemaNodeGroup whereId($value)
 * @method static Builder|MnemoSchemaNodeGroup whereSvgUrl($value)
 * @method static Builder|MnemoSchemaNodeGroup whereTitle($value)
 * @method static Builder|MnemoSchemaNodeGroup whereUpdatedAt($value)
 *
 * @property MnemoSchemaNode[]|Collection<MnemoSchemaNode> $nodes Nodes of Group
 */
class MnemoSchemaNodeGroup extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Nodes of Group
     *
     * @return HasMany
     */
    public function nodes(): HasMany
    {
        return $this->hasMany(MnemoSchemaNode::class, 'group_id');
    }
}
