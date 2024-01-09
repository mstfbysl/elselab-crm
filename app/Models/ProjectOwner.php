<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Project;
use App\Models\User;

class ProjectOwner extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'project_id',
        'user_id',
        'profit_share',
        'percentage',
    ];

    protected $appends = [
        'user_profit'
    ];

    public function project()
    {
        return $this->hasOne(Project::class, 'id', 'project_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }

    public function getUserProfitAttribute()
    {
        $project_profit = $this->project->project_profit;

        if($project_profit < 0){
            return 0;
        }else{
            return ($project_profit / $this->percentage * 100) / 100 * $this->profit_share;
        }        
    }

}
