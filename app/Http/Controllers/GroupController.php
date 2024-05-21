<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;

class GroupController extends Controller
{
    public function getGroups() {
        $groups = Group::all()->pluck('group_name');
        return response()->json($groups);
    }
}
