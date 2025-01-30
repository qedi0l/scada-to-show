<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\MnemoSchemaLineAppearance
 *
 * @property int $id
 * @property int $line_id
 * @property string $color
 * @property int $opacity
 * @property int $width
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|MnemoSchemaLineAppearance newModelQuery()
 * @method static Builder|MnemoSchemaLineAppearance newQuery()
 * @method static Builder|MnemoSchemaLineAppearance query()
 * @method static Builder|MnemoSchemaLineAppearance whereColor($value)
 * @method static Builder|MnemoSchemaLineAppearance whereCreatedAt($value)
 * @method static Builder|MnemoSchemaLineAppearance whereId($value)
 * @method static Builder|MnemoSchemaLineAppearance whereLineId($value)
 * @method static Builder|MnemoSchemaLineAppearance whereUpdatedAt($value)
 */
class MnemoSchemaLineAppearance extends Model
{
    use HasFactory;

    protected $guarded = [];
}
