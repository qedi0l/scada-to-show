<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * MnemoSchemaNodeGeometry
 *
 * @property int $id
 * @property int $node_id
 * @property float $x
 * @property float $y
 * @property float $rotation
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|MnemoSchemaNodeGeometry newModelQuery()
 * @method static Builder|MnemoSchemaNodeGeometry newQuery()
 * @method static Builder|MnemoSchemaNodeGeometry query()
 * @method static Builder|MnemoSchemaNodeGeometry whereCreatedAt($value)
 * @method static Builder|MnemoSchemaNodeGeometry whereId($value)
 * @method static Builder|MnemoSchemaNodeGeometry whereNodeId($value)
 * @method static Builder|MnemoSchemaNodeGeometry whereRotation($value)
 * @method static Builder|MnemoSchemaNodeGeometry whereUpdatedAt($value)
 * @method static Builder|MnemoSchemaNodeGeometry whereX($value)
 * @method static Builder|MnemoSchemaNodeGeometry whereY($value)
 *
 * @property MnemoSchemaNode|null $node Node of NodeGeometry
 */
class MnemoSchemaNodeGeometry extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'rotation' => 'integer'
    ];

    /**
     * Node of NodeGeometry
     *
     * @return BelongsTo
     */
    public function node(): BelongsTo
    {
        return $this->belongsTo(MnemoSchemaNode::class, 'node_id');
    }
}
