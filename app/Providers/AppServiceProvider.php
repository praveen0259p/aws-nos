<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use App\Models\FormFieldOption;
use App\Models\User;
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
        //$this->insertPmu();
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
    protected function insertPmu()
    {
        $contacts = [
            ['name' => 'Ms Vaishali Chopra', 'phone' => '9818016433', 'email' => 'vaishali.nisd@gmail.com'],
            ['name' => 'Ms Pratima Mukharjee', 'phone' => '7701881243', 'email' => 'pratima.nisd@gmail.com'],
            ['name' => 'Ms Gunjan Sharma', 'phone' => '9871679662', 'email' => 'gunjan.nisd@gmail.com'],
            ['name' => 'Ms Dimple Yadav', 'phone' => '9289999293', 'email' => 'dimple.nisd@gmail.com'],
            ['name' => 'Mr P Ajay Reddy', 'phone' => '8800632477', 'email' => 'p.reddy.nisd@gmail.com'],
            ['name' => 'Ms Kirtika Rudra', 'phone' => '8789269376', 'email' => 'kirtika.nisd@gmail.com'],
            ['name' => 'Ms Amisha Yadav', 'phone' => '8219752033', 'email' => 'amisha1854.nisd@gmail.com'],
            ['name' => 'Ms Ragini Jha', 'phone' => '9818927243', 'email' => 'nisdragini@gmail.com'],
            ['name' => 'Ms Kritika', 'phone' => '7503628055', 'email' => 'kritika.nisd@gmail.com'],
            ['name' => 'Ms Priyanka', 'phone' => '8802942650', 'email' => 'priyankayadav.nisd@gmail.com'],
            ['name' => 'Ms Riya', 'phone' => '8383981677', 'email' => 'riyayadav.nisd@gmail.com'],
            ['name' => 'Mr Aditya Singh', 'phone' => '9161197678', 'email' => 'aditya.nisd@gmail.com'],
            ['name' => 'Ms Manisha', 'phone' => '9717032301', 'email' => 'manisha.nisd@gmail.com'],
            ['name' => 'Ms Anvesha Tiwari', 'phone' => '9235772575', 'email' => 'anvesha.nisd@gmail.com'],
            ['name' => 'Mr Christhu Raj', 'phone' => '7010428431', 'email' => 'christy.nisd@gmail.com'],
            ['name' => 'Ms Indu Verma', 'phone' => '8474961251', 'email' => 'indu.nisd@gmail.com'],
            ['name' => 'Ms Jaya', 'phone' => '7011336057', 'email' => 'jayagola.nisd@gmail.com'],
            ['name' => 'Ms Akashara', 'phone' => '8429426950', 'email' => 'akshara.nisd@gmail.com'],
            ['name' => 'Ms Soujanya', 'phone' => '8792426202', 'email' => 'soujanyaambali.nisd@gmail.com'],
            ['name' => 'Ms Deepthi', 'phone' => '6304541930', 'email' => 'jaisri.nisd@gmail.com'],
            ['name' => 'Athulya K R', 'phone' => '8075113723', 'email' => 'athulya.nisd@gmail.com'],
        ];

        $user=User::latest('id')->first();
        //dd($user['scheme_id']);
        //dd($user->user_name);
        foreach($contacts as $contact)
        {
            //echo $contact['name'];echo"<br>";
            User::create([
                'scheme_id'        => $user['scheme_id'],
                'bo_id'            => ($user['bo_id'])+1,
                'user_name'        => $contact['name'],
                'is_pmu_official'  => 'Y',
                'is_active'        => 1,
                'first_name'       => $contact['name'],
                'middle_name'      => null,
                'last_name'        => $contact['name'],
                'mobile_no'        => $contact['phone'],
                'email'            => $contact['email'],
                'role_name'        => $user['role_name'],
                'role_type'        => $user['role_type'],
                'district_id'      => $user['district_id'],
            ]);
        }
        //User::Create()
    }
}
