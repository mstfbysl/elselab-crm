<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\PurchaseInvoice;
use App\Models\Rental;

class PurchaseInvoiceRental extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'purchase_invoice_id',
        'rental_id',
        'serial',
        'title',
        'cost',
        'quantity',
    ];

    /**
     * Getting purchase invoice rental's total_cost attribute.
     */
    protected function getTotalCostAttribute()
    {
        return $this->quantity * $this->cost; 
    }

    /**
     * Getting purchase invoice rental's rental.
     */
    public function rental()
    {
        return $this->belongsTo(Rental::class, 'rental_id', 'id');
    } 

    /**
     * Getting purchase invoice rental's invoice.
     */
    public function invoice()
    {
        return $this->belongsTo(PurchaseInvoice::class, 'purchase_invoice_id', 'id');
    } 

}
