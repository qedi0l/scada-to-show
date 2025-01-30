<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * MnemoSchemaLineArrowType
 *
 * @property int $id
 * @property string $arrow_type_title
 * @property string $arrow_type_label
 */
class MnemoSchemaLineArrowType extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [];
}
