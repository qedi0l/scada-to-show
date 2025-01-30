<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;


/**
 * MnemoSchemaLine
 *
 * @property int $id
 * @property int $schema_id
 * @property int $first_node
 * @property int $second_node
 * @property int $first_position
 * @property int $second_position
 * @property int $source_position
 * @property int $target_position
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|MnemoSchemaLine newModelQuery()
 * @method static Builder|MnemoSchemaLine newQuery()
 * @method static Builder|MnemoSchemaLine query()
 * @method static Builder|MnemoSchemaLine whereCreatedAt($value)
 * @method static Builder|MnemoSchemaLine whereFirstNode($value)
 * @method static Builder|MnemoSchemaLine whereId($value)
 * @method static Builder|MnemoSchemaLine whereSchemaId($value)
 * @method static Builder|MnemoSchemaLine whereSecondNode($value)
 * @method static Builder|MnemoSchemaLine whereUpdatedAt($value)
 *
 * @property MnemoSchema|null $schema Schema of Line
 * @property MnemoSchemaLineAppearance|null $appearance Appearance of Line
 * @property MnemoSchemaLineOptions|null $options Options of Line
 */
class MnemoSchemaLine extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Schema of Line
     *
     * @return BelongsTo
     */
    public function schema(): BelongsTo
    {
        return $this->belongsTo(MnemoSchema::class, 'schema_id');
    }

    /**
     * Appearance of Line
     *
     * @return HasOne
     */
    public function appearance(): HasOne
    {
        return $this->hasOne(MnemoSchemaLineAppearance::class, 'line_id');
    }

    /**
     * Options of Line
     *
     * @return HasOne
     */
    public function options(): HasOne
    {
        return $this->hasOne(MnemoSchemaLineOptions::class, 'line_id');
    }
}
