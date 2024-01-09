<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Client;
use App\Models\User;

class Invoice extends Model
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
        'invoice_no',
        'date',
        'type',
        'sub_total',
        'total',
        'tax',
        'status',
        'note'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'sub_total' => 'decimal:2',
        'total' => 'decimal:2',
        'tax' => 'decimal:2',
    ];

    /**
     * Getting invoice's client.
     */
    public function client()
    {
        return $this->hasOne(Client::class, 'id', 'client_id');
    }

    /**
     * Getting invoice's items.
     */
    public function items()
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id', 'id');
    }

    /**
     * Getting invoice's user.
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'created_user_id');
    }
}
