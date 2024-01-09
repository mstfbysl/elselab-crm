<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\PurchaseInvoice;
use App\Models\Project;
class Client extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'name',
        'code',
        'address',
        'phone',
        'email',
        'vat_number',
        'comment'
    ];

    /**
     * Getting client's purchase invoices.
     */
    public function purchase_invoices()
    {
        return $this->hasMany(PurchaseInvoice::class, 'client_id', 'id');
    }

    /**
     * Getting client's projects.
     */
    public function projects()
    {
        return $this->hasMany(Project::class, 'client_id', 'id');
    }
}
