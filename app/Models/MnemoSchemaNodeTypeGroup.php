<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * MnemoSchemaNodeTypeGroup
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $short_title
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property MnemoSchemaNodeType[]|Collection<MnemoSchemaNodeType> $types Types of Group
 *
 */
class MnemoSchemaNodeTypeGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'short_title'
    ];

    /**
     * Types Of Group
     *
     * @return HasMany
     */
    public function types(): HasMany
    {
        return $this->hasMany(MnemoSchemaNodeType::class, 'node_type_group_id');
    }
}
