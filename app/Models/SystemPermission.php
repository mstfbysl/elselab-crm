<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Permission;
class SystemPermission extends Model
{
    use HasFactory;

    public function permission()
    {
        return $this->belongsTo(Permission::class, 'id', 'permission_id');
    }
}
