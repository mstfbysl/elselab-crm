<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Project;
use App\Models\SystemLabel;

class ProjectLabel extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'project_id',
        'label_id',
    ];

    /**
     * Getting project services's project.
     */
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    } 

    /**
     * Getting project services's rental.
     */
    public function system_label()
    {
        return $this->hasMany(SystemLabel::class, 'id', 'label_id');
    } 
}
