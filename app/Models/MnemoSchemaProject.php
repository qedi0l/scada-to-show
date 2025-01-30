<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * MnemoSchemaProject
 *
 * @property int $id
 * @property string $title
 * @property string|null $short_title
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class MnemoSchemaProject extends Model
{
    use HasFactory;

    protected $guarded = [];
}
