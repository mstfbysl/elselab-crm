<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_role_id',
        'name_surname',
        'email',
        'phone',
        'password',
        'profile_picture',
        'profit_share'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'profile_picture_preview',
        'project_profit',
        'user_expenses'
    ];

    public function hasPermission($permissionId)
    {
        if(request()->user()->user_role_id == 1){
            return true;
        }else{
            return $this->role->whereHas('permissions', function ($query) use ($permissionId) {
                $query->where('permission_id', $permissionId);
            })->exists();
        }
    }
    
    /**
     * Attr: profile_picture_preview
     */
    public function getProfilePicturePreviewAttribute()
    {
        if($this->profile_picture){
            return $this->profile_picture_preview =  env('MIX_APP_URL')."/image/".$this->profile_picture_file->uuid;
        }else{
            return $this->profile_picture_preview = asset(mix('images/logo/app-default-avatar.png'));
        }
    }

    /**
     * Getting user's documents.
     */
    public function documents()
    {
        return $this->hasMany(UserDocument::class, 'user_id', 'id');
    }
    
    /**
     * Getting user's role.
     */
    public function projectOwners()
    {
        return $this->hasMany(ProjectOwner::class);
    }
    
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_owners')
            ->using(ProjectOwner::class)
            ->withPivot('profit_share', 'percentage');
    }
    /**
     * Getting user's role.
     */
    public function user_payments()
    {
        return $this->hasMany(UserPayment::class, 'user_id', 'id');
    }
    /**
     * Getting user's role.
     */
    public function role()
    {
        return $this->hasOne(Role::class, 'id', 'user_role_id');
    }


    /**
     * Getting users's profile picture.
     */
    public function profile_picture_file()
    {
        return $this->hasOne(SystemFile::class, 'id', 'profile_picture');
    }

    public function getProjectProfitAttribute()
    {
            $projectOwners = $this->projectOwners()
            ->with(['project' => function ($query) {
                $query->where('core_status', 3);
            }])
            ->get();
        
        $totalProfit = 0;
        
        foreach ($projectOwners as $projectOwner) {
            $percentage = $projectOwner->percentage;
            $profitShare = $projectOwner->profit_share;
            $project = $projectOwner->project;
            $projectProfit = optional($project)->project_profit;
            if ($projectProfit !== null) {
                $ownerProfit = ($projectProfit / $percentage * 100) / 100 * $profitShare;
                $totalProfit += $ownerProfit;
            }
        }
        
        return $totalProfit;
    }

    public function getUserExpensesAttribute()
    {
        return $this->user_payments->sum('total');
    }
}
