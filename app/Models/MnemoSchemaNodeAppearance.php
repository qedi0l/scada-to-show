<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * MnemoSchemaNodeAppearance
 *
 * @property int $id
 * @property int $node_id
 * @property int $width
 * @property int $height
 * @property string $svg_url
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $min_svg
 * @method static Builder|MnemoSchemaNodeAppearance newModelQuery()
 * @method static Builder|MnemoSchemaNodeAppearance newQuery()
 * @method static Builder|MnemoSchemaNodeAppearance query()
 * @method static Builder|MnemoSchemaNodeAppearance whereCreatedAt($value)
 * @method static Builder|MnemoSchemaNodeAppearance whereHeight($value)
 * @method static Builder|MnemoSchemaNodeAppearance whereId($value)
 * @method static Builder|MnemoSchemaNodeAppearance whereNodeId($value)
 * @method static Builder|MnemoSchemaNodeAppearance whereSvgUrl($value)
 * @method static Builder|MnemoSchemaNodeAppearance whereUpdatedAt($value)
 * @method static Builder|MnemoSchemaNodeAppearance whereWidth($value)
 *
 * @property MnemoSchemaNode|null $node Node of Appearance
 */
class MnemoSchemaNodeAppearance extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Node of Appearance
     *
     * @return BelongsTo
     */
    public function node(): BelongsTo
    {
        return $this->belongsTo(MnemoSchemaNode::class, 'node_id');
    }
}
