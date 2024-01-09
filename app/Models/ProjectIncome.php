<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Project;
use App\Models\Invoice;
use App\Models\InvoiceItem;

class ProjectIncome extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'project_id',
        'user_id',
        'invoice_item_id',
    ];
    
    protected $appends = [
        'total'
    ];
    /**
     * Getting project expense's project.
     */
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    } 

    /**
     * Getting project expense's purchase invoice item.
     */
    public function item()
    {
        return $this->hasOne(InvoiceItem::class, 'id', 'invoice_item_id');
    } 

    public function getTotalAttribute(){
        return $this->item->quantity * $this->item->cost;
    }
}
