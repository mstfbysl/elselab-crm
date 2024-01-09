<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Client;
use App\Models\PurchaseInvoiceItem;
class PurchaseInvoice extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'client_id',
        'created_user_id',
        'title',
        'serie',
        'date',
        'total',
        'total_with_vat',
        'used_total',
        'is_paid',
        'is_accountant',
        'document'
    ];

    protected $appends = [
        'remaining_total',
        'assigned_total',
        'is_finished'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'total' => 'decimal:2',
    ];

    /**
     * Getting purchase invoice's file.
     */
    public function file()
    {
        return $this->hasOne(SystemFile::class, 'id', 'document');
    }

    /**
     * Getting purchase invoice's client.
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    /**
     * Getting purchase invoice's user.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Getting purchase invoice's items.
     */
    public function items()
    {
        return $this->hasMany(PurchaseInvoiceItem::class, 'purchase_invoice_id', 'id');
    }
    public function user_payments()
    {
        return $this->hasMany(UserPayment::class, 'purchase_invoice_id', 'id');
    }

    public function company_expenses()
    {
        return $this->hasMany(CompanyExpense::class, 'purchase_invoice_id', 'id');
    }

    public function project_expenses()
    {
        return $this->hasMany(ProjectExpense::class, 'purchase_invoice_id', 'id');
    }

    public function getAssignedTotalAttribute()
    {
        return $this->user_payments->sum('total') + $this->company_expenses->sum('total') + $this->project_expenses->sum('total');
    }
    /**
     * Getting remaining total attribute.
     */
    public function getRemainingTotalAttribute()
    {
        return $this->total - $this->used_total;
    }

    public function getIsFinishedAttribute()
    {
        if($this->remaining_total == 0 and $this->assigned_total == $this->total){
            return 1;
        }else{
            return 0;
        }
    }

    public static function filterByType($request)
    {

        $purchase_invoices = PurchaseInvoice::orderBy('id', 'desc')->get();
        if(isset($request->type) and $request->type < 2){
            $purchase_invoices = $purchase_invoices->filter(function ($purchase_invoice) use ($request) {
                return $purchase_invoice->is_finished == $request->type;
            });
        }
        return $purchase_invoices;
    }
}
