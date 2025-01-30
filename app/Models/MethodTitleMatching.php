<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Method Title Matching
 *
 * @property int $id
 * @property string $frontend_method_title
 * @property string $receiver_title
 * @property string $concrete_command_title
 * @property string|null $undo_receiver_title
 */
class MethodTitleMatching extends Model
{
    use HasFactory;

    protected $fillable = ['fronted_method_title', 'receiver_title', 'concrete_command_title', 'undo_receiver_title'];
}
