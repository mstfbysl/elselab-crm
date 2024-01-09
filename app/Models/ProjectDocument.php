<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectDocument extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'project_id',
        'title',
        'file_id',
    ];

    /**
     * Getting user document's file.
     */
    public function file()
    {
        return $this->hasOne(SystemFile::class, 'id', 'file_id');
    }
}
