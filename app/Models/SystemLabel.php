<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Rennokki\QueryCache\Traits\QueryCacheable;

class SystemLabel extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'color',
    ];

    public function ProjectLabel()
    {
        return $this->belongsTo(ProjectLabel::class, 'project_id', 'id');
    } 
}
