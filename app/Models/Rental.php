<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\PurchaseInvoiceRental;
class Rental extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'price',
        'used_quantity'
    ];

    protected $appends = [
        'serial'
    ];
    
    public function getUsageCountAttribute()
    {
        return $this->rentals->count();
    }

    public function getRemainingQuantityAttribute()
    {
        return $this->rentals->sum('quantity') - $this->used_quantity;
    }

    public function getTotalQuantityAttribute()
    {
        return $this->rentals->sum('quantity');
    }

    public function rentals()
    {
        return $this->hasMany(PurchaseInvoiceRental::class, 'rental_id', 'id');
    }

    public function getSerialAttribute(){
        if($this->rentals->count() > 0){
            return $this->rentals->first()->serial;
        }else{
            return '';
        }
       
    }

}
