<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\SystemFile;
use App\Models\SystemInvoiceSerie;
use App\Models\SystemCurrency;

class SystemSetting extends Model
{ 
    protected $appends = ['logo_preview', 'favicon_preview'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'app_name',
        'logo',
        'favicon',
        'company_is_vat_member',
        'company_vat_number',
        'company_currency',
        'company_name',
        'company_code',
        'company_address',
        'company_phone',
        'company_email',
        'company_bank_name',
        'company_bank_iban',
        'company_bank_code',
    ];

    /**
     * Attr: profile_picture_preview
     */
    public function getLogoPreviewAttribute()
    {
        if($this->logo_file){
            return $this->logo_preview = env('MIX_APP_URL')."/image/".$this->logo_file->uuid;
        }else{
            return $this->logo_preview = asset(mix('images/logo/app-logo.png'));
        }
    }

    /**
     * Attr: favicon_preview
     */
    public function getFaviconPreviewAttribute()
    {
        if($this->favicon){
            return $this->favicon_preview = env('MIX_APP_URL')."/image/".$this->favicon_file->uuid;
        }else{
            return $this->favicon_preview = asset(mix('images/logo/app-favicon.png'));
        }
    }

    /**
     * Getting system's favicon.
     */
    public function logo_file()
    {
        return $this->hasOne(SystemFile::class, 'id', 'logo');
    }

    /**
     * Getting system's favicon.
     */
    public function favicon_file()
    {
        return $this->hasOne(SystemFile::class, 'id', 'favicon');
    }

    /**
     * Getting system's currency detail.
     */
    public function currency()
    {
        return $this->hasOne(SystemCurrency::class, 'id', 'company_currency');
    }
}
