<?php

namespace App\Queries;

use App\Models\User;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class UserQueries
{
    public function getUserProjectProfit($userId)
    {
        $userProjects = Project::ByUser($userId)->get()->map(function($project){
            return [
                $project->id,
                $project->project_profit,
                $project->user->find(1)->percentage,
                $project->user->find(1)->profit_share,
            ];
        });

        dd($userProjects);
        return $userProjects;
        //return User::where('active', true)->get();
    }

    // public function searchUsers($searchTerm)
    // {
    //     return User::where('name', 'like', '%'.$searchTerm.'%')
    //         ->orWhere('email', 'like', '%'.$searchTerm.'%')
    //         ->get();
    // }

    // public function getUsersWithMostPosts()
    // {
    //     return DB::table('users')
    //         ->join('posts', 'users.id', '=', 'posts.user_id')
    //         ->select('users.*', DB::raw('COUNT(posts.id) as post_count'))
    //         ->groupBy('users.id')
    //         ->orderBy('post_count', 'desc')
    //         ->get();
    // }
}