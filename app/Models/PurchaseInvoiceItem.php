<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\UserPayment;
use App\Models\PurchaseInvoice;
use App\Models\CompanyExpense;
use App\Models\ProjectExpense;
class PurchaseInvoiceItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'client_id',
        'purchase_invoice_id',
        'type',
        'serial',
        'title',
        'quantity',
        'used_quantity',
        'cost',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'quantitiy' => 'integer',
        'used_quantity' => 'integer',
        'cost' => 'decimal:2',
    ];

    protected $appends = [
        'remaining_quantity',
        'total_cost',
        'assigned_to_compnay',
    ];

    public function invoice()
    {
        return $this->belongsTo(PurchaseInvoice::class, 'purchase_invoice_id' , 'id');
    } 

    public function user_payments()
    {
        return $this->belongsTo(UserPayment::class, 'purchase_invoice_item_id' , 'id');
    } 

    public function company_expenses()
    {
        return $this->belongsTo(CompanyExpense::class, 'purchase_invoice_item_id' , 'id');
    } 

    public function project_expenses()
    {
        return $this->belongsTo(ProjectExpense::class, 'purchase_invoice_item_id' , 'id');
    } 

    /**
     * Getting purchase invoice items's remaining_quantitiy attribute.
     */
    protected function getRemainingQuantityAttribute()
    {
        return $this->quantity - $this->used_quantity; 
    }

    /**
     * Getting purchase invoice items's total_cost attribute.
     */
    protected function getTotalCostAttribute()
    {
        return $this->quantity * $this->cost; 
    }

    /**
     * Getting purchase invoice items's items_in_project_expense_sum attribute.
    */

    public function getAssignedToCompnayAttribute()
    {
        return $this->company_expenses;
    }

    // public function getItemsInUserPaymentsAttribute()
    // {
    //     return $this->user_payments->sum('total');
    // }

}
