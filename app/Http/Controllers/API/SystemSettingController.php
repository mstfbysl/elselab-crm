<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\RespondTrait;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

use App\Models\SystemFile;
use App\Models\SystemSetting;
class SystemSettingController extends Controller
{
    use RespondTrait;

    public function detail(Request $request)
    {
        $settings = SystemSetting::first();

        if(!$settings){
            return $this->respondFail('An error occured!', 500);
        }

        return $this->respondSuccess($settings);
    }

    public function save_logo(Request $request)
    {
        $request->validate([
            'logo' => 'required'
        ]);
        
        $logo = SystemSetting::first()->logo;
        
        if($logo AND $request->logo){// Remove old Logo
            if($logo != $request->logo){ // If old Logo 
                $old_file = SystemFile::find($logo);
                Storage::delete($old_file->path);
                SystemFile::find($logo)->delete();
            }
        }

        $update = SystemSetting::first()->update([
            'logo' => $request->logo
        ]);
        
        if($update){
            //Update Session
            session(['logo' => $request->logo]);
            Artisan::call('cache:clear');
            return $this->respondSuccess($update, 'Settings are saved successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }

    public function save_company(Request $request)
    {
        $request->validate([
            'company_name' => 'required',
        ]);

        $companyDetails = SystemSetting::first();
    
        // Define the setting fields you want to update
        $settingFields = [
            'company_is_vat_member', 'company_vat_number', 'company_currency',
            'company_name', 'company_code', 'company_address', 'company_email',
            'company_phone', 'company_bank_name', 'company_bank_iban', 'company_bank_code'
        ];
    
        // Loop through the setting fields and update both the model and the session
        foreach ($settingFields as $field) {
            if ($request->has($field)) {
                $companyDetails->$field = $request->$field;
                session([$field => $request->$field]);
            }
        }
        
        $update = $companyDetails->save();
        
        if ($update) {
            Artisan::call('cache:clear');
            return $this->respondSuccess($update, 'Company settings are saved successfully.');
        } else {
            return $this->respondFail('An error occurred!', 500);
        }
    }
}
