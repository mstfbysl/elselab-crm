<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Project;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseInvoiceItem;
class ProjectExpense extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'project_id',
        'purchase_invoice_id',
        'purchase_invoice_item_id',
        'quantity',
    ];

    protected $appends = [
        'total',
        'title',
        'serial',
        'cost'
    ];
    /**
     * Getting project expense's project.
     */
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    } 
    
    public function purchase_invoice()
    {
        return $this->belongsTo(PurchaseInvoice::class, 'id', 'purchase_invoice_id');
    }
    /**
     * Getting project expense's purchase invoice item.
     */
    public function item()
    {
        return $this->hasOne(PurchaseInvoiceItem::class, 'id', 'purchase_invoice_item_id');
    } 

    /**
     * Getting project expense's purchase invoice item.
     */

    public function getTotalAttribute(){
        return $this->quantity * $this->item->cost;
    }

    public function getTitleAttribute(){
        return $this->item->title;
    }

    public function getSerialAttribute(){
        return $this->item->serial;
    }

    public function getCostAttribute(){
        return $this->item->cost;
    }
}
