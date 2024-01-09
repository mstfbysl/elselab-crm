<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Invoice;
use App\Models\SystemInvoiceSerie;

class SystemInvoiceSerieCounter extends Model
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
        'invoice_id',
    ];

    /**
     * Getting purchase invoice items's purchase invoice.
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id' , 'id');
    } 

    public function serie()
    {
        return $this->belongsTo(SystemInvoiceSerie::class, 'serie_id', 'id');
    } 
}
