<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
class UserPayment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'type',
        'total',
        'purchase_invoice_id',
        'purchase_invoice_item_id',
        'quantity',
    ];

    protected $appends = [
        'assigned_itmes',
    ];
    /**
     * Getting user payments's user.
     */

     public function users()
     {
         return $this->belongsTo(User::class, 'id', 'user_id');
     }
     
     public function purchase_invoice()
     {
         return $this->belongsTo(PurchaseInvoice::class, 'id', 'purchase_invoice_id');
     }
    /**
     * Getting user payments's purchase invoice item.
     */
    public function item()
    {
        return $this->hasOne(PurchaseInvoiceItem::class, 'id', 'purchase_invoice_item_id');
    } 
}
