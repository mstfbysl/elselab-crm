<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Invoice;

class InvoiceItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'invoice_id',
        'title',
        'description',
        'cost',
        'tax',
        'quantity',
        'is_used',
        'is_used_by',
        'used_date'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'cost' => 'decimal:2',
    ];

    /**
     * Getting sub total attribute.
     */
    public function getSubTotalAttribute()
    {
        return $this->cost * $this->quantity;
    }

    /**
     * Getting tax total attribute.
     */
    public function getTaxTotalAttribute()
    {
        return $this->sub_total / 100 * $this->tax;
    }

    /**
     * Getting total attribute.
     */
    public function getTotalAttribute()
    {
        return $this->sub_total + $this->tax_total;
    }

    /**
     * Getting invoice item's invoice.
     */
    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'id', 'invoice_id');
    }
}
