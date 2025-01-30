<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * MnemoSchemaLineOptions
 *
 * @property int $id
 * @property int $line_id
 * @property string $text
 * @property int $type_id
 * @property int $first_arrow
 * @property int $second_arrow
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|MnemoSchemaLineOptions newModelQuery()
 * @method static Builder|MnemoSchemaLineOptions newQuery()
 * @method static Builder|MnemoSchemaLineOptions query()
 * @method static Builder|MnemoSchemaLineOptions whereCreatedAt($value)
 * @method static Builder|MnemoSchemaLineOptions whereId($value)
 * @method static Builder|MnemoSchemaLineOptions whereLineId($value)
 * @method static Builder|MnemoSchemaLineOptions whereText($value)
 * @method static Builder|MnemoSchemaLineOptions whereUpdatedAt($value)
 *
 * @property MnemoSchemaLine|null $line Line of Options
 * @property MnemoSchemaLineType|null $type Type of Line Options
 * @property MnemoSchemaLineArrowType|null $first_arrow_type First Arrow Type
 * @property MnemoSchemaLineArrowType|null $second_arrow_type Second Arrow Type
 */
class MnemoSchemaLineOptions extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Line of Options
     *
     * @return BelongsTo
     */
    public function line(): BelongsTo
    {
        return $this->belongsTo(MnemoSchemaLine::class, 'line_id');
    }

    /**
     * Type of Line Options
     *
     * @return BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(MnemoSchemaLineType::class, 'type_id');
    }

    /**
     * First Arrow Type
     *
     * @return BelongsTo
     */
    public function first_arrow_type(): BelongsTo
    {
        return $this->belongsTo(MnemoSchemaLineArrowType::class, 'first_arrow');
    }

    /**
     * Second Arrow Type
     *
     * @return BelongsTo
     */
    public function second_arrow_type(): BelongsTo
    {
        return $this->belongsTo(MnemoSchemaLineArrowType::class, 'second_arrow');
    }
}
