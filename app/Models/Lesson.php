<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;
    protected $fillable = [
        'lesson_name',
        'lesson_date',
        'start_time',
        'end_time',
        'conference_id',
        'conference_password',
        'type_of_week',
        'weekday'
    ];
}
 