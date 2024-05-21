<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupLesson extends Model
{
    use HasFactory;
    protected $table = 'group_lesson';
    
    public $timestamps = false;

    protected $fillable = [
        'lesson_id',
        'group_name',
    ];

    public function lesson() {
        return $this->belongsTo(Lesson::class, 'lesson_id');
    }

    public function group() {
        return $this->belongsTo(Group::class, 'group_name', 'group_name');
    }
}
