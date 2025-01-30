<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Mnemo Schema Node Link
 *
 * @property int $node_id Node ID
 * @property int $schema_id Schema ID
 *
 * @property MnemoSchemaNode|null $node Node
 * @property MnemoSchema|null $schema Schema
 *
 * @property-read string $url URL to Schema
 */
class MnemoSchemaNodeLink extends Model
{
    use HasFactory;

    /**
     * Node
     *
     * @return BelongsTo
     */
    public function node(): BelongsTo
    {
        return $this->belongsTo(MnemoSchemaNode::class, 'node_id');
    }

    /**
     * Schema
     *
     * @return BelongsTo
     */
    public function schema(): BelongsTo
    {
        return $this->belongsTo(MnemoSchema::class, 'schema_id');
    }

    /**
     * Get Url to Schema
     *
     * @return string
     */
    public function getUrlAttribute(): string
    {
        return config('app.url') . '/schemas/' . $this->schema_id;
    }
}
