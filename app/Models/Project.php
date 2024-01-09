<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

use App\Models\Client;
use App\Models\ProjectOwner;
use App\Models\ProjectLabel;

use App\Models\ProjectExpense;
use App\Models\ProjectService;
use App\Models\ProjectRental;

use App\Models\ProjectIncome;
class Project extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'client_id',
        'title',
        'total_ownerness',
        'start_date',
        'finish_date',
        'used_ownerness'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $appends = [
        'project_expense',
        'project_income',
        'project_profit',
        'remaining_ownerness',
        'expected_profit'
    ];

    /**
     * Getting project's client.
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    /**
     * Getting project's expenses.
     */
    public function expenses()
    {
        return $this->hasMany(ProjectExpense::class, 'project_id', 'id');
    }

    /**
     * Getting project's services.
     */
    public function services()
    {
        return $this->hasMany(ProjectService::class, 'project_id', 'id');
    }

    /**
     * Getting project's labels.
     */
    public function labels()
    {
        return $this->hasMany(ProjectLabel::class, 'project_id', 'id');
    }

    /**
     * Getting project's incomes.
     */    
    public function incomes()
    {
        return $this->hasMany(ProjectIncome::class, 'project_id', 'id');
    }

    /**
     * Getting project's incomes.
     */    
    public function rentals()
    {
        return $this->hasMany(ProjectRental::class, 'project_id', 'id');
    }
    /**
     * Getting project's users.
     */
    public function owners()
    {
        return $this->hasMany(ProjectOwner::class, 'project_id', 'id');
    }
    
    /**
     * Getting total project expenses.
     */
    public function getProjectExpenseAttribute() 
    {
        return $this->expenses->sum('total');
    }

    /**
     * Getting total project incomes.
     */
    public function getProjectIncomeAttribute() 
    {
        return $this->incomes->sum('total');
    }
    /**
     * Getting total project Profit.
     */
    public function getProjectProfitAttribute() 
    {
        return $this->incomes->sum('total') - ($this->expenses->sum('total'));
    }

    /**
     * Getting remaining_ownerness.
    */
    public function getRemainingOwnernessAttribute()
    {
        return $this->total_ownerness - $this->used_ownerness;
    }

    public function getExpectedProfitAttribute()
    {
        return $this->expenses->sum('total') + $this->services->sum('total') + $this->rentals->sum('total');
    }
    
    public function scopeByUser($query, $userId)
    {
        return $query->with('users')->whereHas('users', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        });
    }

}
