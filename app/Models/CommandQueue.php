<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Command Queue
 *
 * @property string $receiver_title
 * @property string $command_title
 * @property string $command_json Json
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $schema_id
 */
class CommandQueue extends Model
{
    use HasFactory;

    protected $fillable = [
        'receiver_title',
        'command_title',
        'command_json',
        'schema_id'
    ];

    protected $casts = [
        'command_json' => 'array'
    ];
}
