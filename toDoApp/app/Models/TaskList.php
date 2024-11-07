<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class TaskList extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'context',
        'date',
        'due_date'
        ];
}
