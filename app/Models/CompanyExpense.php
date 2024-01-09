<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyExpense extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'purchase_invoice_item_id',
        'purchase_invoice_id',
        'quantity',
        'total'
    ];

    /**
     * Getting company expense's purchase invoice item.
     */
    public function item()
    {
        return $this->hasOne(PurchaseInvoiceItem::class, 'id', 'purchase_invoice_item_id');
    } 

    public function purchase_invoice()
    {
        return $this->belongsTo(PurchaseInvoice::class, 'id', 'purchase_invoice_id');
    }
}
