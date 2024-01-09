<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\SystemPermission;
class Permission extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_role_id',
        'permission_id',
    ];

    /**
     * Getting permission's role.
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'user_role_id', 'id');
    }

    /**
     * Getting permission's detailed permission.
     */
    public function permission()
    {
        return $this->hasOne(SystemPermission::class, 'id', 'permission_id');
    }
}
