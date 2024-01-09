<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Project;
use App\Models\Rental;

class ProjectRental extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'project_id',
        'rental_id',
        'quantity',
        'price'
    ];

    protected $appends = [
        'total',
        'title',
        'serial',
        'original_price'
    ];
    /**
     * Getting project rentals's project.
     */
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    } 

    /**
     * Getting project rentals's rental.
     */
    public function rental()
    {
        return $this->hasOne(Rental::class, 'id', 'rental_id');
    } 

    /**
     * Getting Total service price * qty.
     */
    public function getTotalAttribute(){
        return $this->quantity * $this->cost;
    }

    public function getTitleAttribute(){
        return $this->rental->title;
    }

    public function getSerialAttribute(){
        return $this->rental->serial;
    }

    public function getOriginalPriceAttribute(){
        return $this->rental->price;
    }

}
