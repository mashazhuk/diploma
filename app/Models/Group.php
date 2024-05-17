<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    public function groupLesson() {
        return $this->hasMany(GroupLesson::class, 'group_name', 'group_name');
    }
}
