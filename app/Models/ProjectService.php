<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Project;
use App\Models\Service;

class ProjectService extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'project_id',
        'service_id',
        'quantity',
        'price',
    ];

    protected $appends = [
        'total',
        'title'
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
    public function service()
    {
        return $this->hasOne(Service::class, 'id', 'service_id');
    } 

    /**
     * Getting Total service price * qty.
     */
    public function getTotalAttribute(){
        return $this->quantity * $this->price;
    }

    public function getTitleAttribute(){
        return $this->service->title;
    }
}
