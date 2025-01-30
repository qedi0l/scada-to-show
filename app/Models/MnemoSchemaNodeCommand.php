<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * MnemoSchemaNodeCommand
 *
 * @property int $id
 * @property int $node_id
 * @property int $parameter_code
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property MnemoSchemaNode|null $node Node of Command
 */
class MnemoSchemaNodeCommand extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Node of Command
     *
     * @return BelongsTo
     */
    public function node(): BelongsTo
    {
        return $this->belongsTo(MnemoSchemaNode::class, 'node_id');
    }
}
