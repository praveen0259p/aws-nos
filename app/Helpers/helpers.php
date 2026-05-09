<?php
use App\Models\MenuItem;
use App\Models\State;
use App\Models\District;
use App\Models\PortalRegistration;
use Carbon\Carbon;
if (! function_exists('getMenu')) {
    function getMenu()
    {
       return MenuItem::whereNull('parent_id')->where(['active'=> 1,'is_main'=> 1])->orderBy('order_index')->with('childrenRecursive')->get();
    }
}
if (!function_exists('categoryOptions')) {
    function categoryOptions() {
        return [
            'SC' => 'Scheduled Castessssssss23423',
            'DN' => 'Denotified Nomadic/Semi-Nomadic Tribes',
            'TA' => 'Traditional Artisans',
            'LA' => 'Landless Agriculture Labourer'
        ];
    }
}
if (!function_exists('getAllState')) {
    function getAllState() {
        $state = State::orderBy('StateName','ASC')->pluck('StateName', 'StateCode');;
        return $state;
    }
}
if (!function_exists('getDistrictsByStateId')) {
    function getDistrictsByStateId($StateCode) {
        $districts = District::where(['StateCode'=>$StateCode])->orderBy('DistrictName','ASC')->pluck('DistrictName','DistrictCode');;
        return $districts;
    }
}

if (!function_exists('getActiveRegistrationButton')) {
    function getActiveRegistrationButton()
    {
        $currentTimestamp = Carbon::now();
        ///dd(Carbon::now()->year);
        $portal = PortalRegistration::where('active', 1)
        ->where('year',Carbon::now()->year)
        ->where('registration_open_date', '<=', $currentTimestamp)
        ->where('registration_close_date', '>=', $currentTimestamp)
        ->first();
        ///dd($portal);
        return $portal;
    }
}
if (!function_exists('genderOptions')) {
    function genderOptions() {
        return [
            '1' => 'Male',
            '2' => 'Female',
            '3' => 'Others'
        ];
    }
}