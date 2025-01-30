<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * MnemoSchemaLineType
 *
 * @property int $id
 * @property string $type
 * @property string $line_type_label
 * @method static Builder|MnemoSchemaLineType whereType($value)
 * @method static Builder|MnemoSchemaLineType whereId($value)
 */
class MnemoSchemaLineType extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['type'];
}
