<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use App\Models\FormFieldOption;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //$this->insertState();
        //$this->insertDistrict();
    }
    protected function insertState()
    {
        $states = DB::connection('scwnew')->table('states')->get();
        $JsonState = [];
        foreach ($states as $state) {
            $JsonState[] = [
                'id' => $state->id,
                'StateCode' => $state->StateCode,
                'MapCode' => $state->MapCode,
                'StateName' => $state->StateName,
                // 'name_old' => $state->StateName_old,
                // 'short_name' => $state->ShortName,
                // 'created_by' => $state->CreatedBy,
                // 'created_on' => $state->CreatedOn,
                'actve' => 1,
            ];
        }
        FormFieldOption::find(3)->update(['values' => $JsonState]);
        dd($JsonState);
    }
    protected function insertDistrict()
    {
        $districts = DB::connection('scwnew')->table('districts')->get();
        //dd($districts);
        $JsonDistrict = [];
        foreach ($districts as $district) {
            $JsonDistrict[] = [
                'id' => $district->id,
                'StateCode' => $district->StateCode,
                //'state_name' => $district->StateName,
                'DistrictCode' => $district->DistrictCode,
                'DistrictName' => $district->DistrictName,
                // 'district_name_copy' => $district->DistrictName_copy,
                // 'created_by' => $district->CreatedBy,
                // 'created_on' => $district->CreatedOn,
                'actve' => 1,
            ];
        }
        FormFieldOption::find(4)->update(['values' => $JsonDistrict]);
        dd($JsonDistrict);
    }
    
}
