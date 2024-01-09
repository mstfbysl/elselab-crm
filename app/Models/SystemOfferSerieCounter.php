<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Offer;
use App\Models\SystemOfferSerie;

class SystemOfferSerieCounter extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'serie_id',
        'year',
        'count',
        'offer_id',
    ];

    /**
     * Getting purchase invoice items's purchase invoice.
     */
    public function offer()
    {
        return $this->belongsTo(Offer::class, 'offer_id' , 'id');
    } 

    public function serie()
    {
        return $this->belongsTo(SystemOfferSerie::class, 'serie_id', 'id');
    } 
}
