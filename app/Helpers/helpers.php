<?php

use App\Models\MenuItem;
use App\Models\State;
use App\Models\District;
use App\Models\ApplicationWindow;
use App\Models\Application;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
if (! function_exists('getMenu')) {
    function getMenu()
    {
        return MenuItem::whereNull('parent_id')->where(['active' => 1])->orderBy('order_index')->with('childrenRecursive')->get();
    }
}
if (!function_exists('categoryOptions')) {
    function categoryOptions()
    {
        return [
            'SC' => 'Scheduled Caste',
            'DN' => 'Denotified Nomadic/Semi-Nomadic Tribes',
            'TA' => 'Traditional Artisans',
            'LA' => 'Landless Agriculture Labourer'
        ];
    }
}
if (!function_exists('getAllState')) {
    function getAllState()
    {
        $state = State::orderBy('StateName', 'ASC')->pluck('StateName', 'StateCode');;
        return $state;
    }
}
if (!function_exists('getDistrictsByStateId')) {
    function getDistrictsByStateId($StateCode)
    {
        $districts = District::where(['StateCode' => $StateCode])->orderBy('DistrictName', 'ASC')->pluck('DistrictName', 'DistrictCode');;
        return $districts;
    }
}
if (!function_exists('genderOptions')) {
    function genderOptions()
    {
        return [
            '1' => 'Male',
            '2' => 'Female',
            '3' => 'Others'
        ];
    }
}

// if (!function_exists('getActiveRegistrationButton')) {
//     function getActiveRegistrationButton()
//     {
//         $currentTimestamp = Carbon::now();
//         ///dd(Carbon::now()->year);
//         //dd($currentTimestamp);
//         $portal = ApplicationWindow::where('active', 1)
//             ->whereDate('application_open_date', '<=', $currentTimestamp)
//             ->whereDate('application_close_date', '>=', $currentTimestamp)
//             ->latest()
//             ->first();
//         //dd($portal);
//         return $portal;
//     }
// }

if (!function_exists('getActiveRegistrationButton')) {
    function getActiveRegistrationButton()
    {
        $window = ApplicationWindow::where('active', 1)->latest()->first();
        return $window;
    }
}
if (! function_exists('generateRegistrationNumber')) {
    function generateRegistrationNumber(int $userId): string
    {
        $regNumber = str_pad($userId, 4, '0', STR_PAD_LEFT);
        $currentYear = Carbon::now()->format('Y');
        $nextYearLast2 = Carbon::now()->addYear()->format('y');
        $datePart = $currentYear . $nextYearLast2;
        return 'NOS' . $regNumber . $datePart;
    }
}
if (!function_exists('generateApplicationNo')) {
    function generateApplicationNo(): string
    {
        $prefix = 'APP';
        $activeWindow = getActiveRegistrationButton();
        if (! $activeWindow) {
            throw new \Exception('No active application window found');
        }
        $windowId = $activeWindow->id;
        $year = Carbon::parse($activeWindow->created_at)->year;
        $appId = DB::transaction(function () use ($prefix, $windowId, $year) {
            $lastApp = DB::table('applications')
                ->where('window_id', $windowId)
                ->orderBy('id', 'desc')
                ->lockForUpdate()
                ->first();
            if ($lastApp && isset($lastApp->application_number)) {
                $parts = explode('-', $lastApp->application_number);
                $lastNumber = (int) end($parts);
                $nextNumber = $lastNumber + 1;
            } else {
                $nextNumber = 1;
            }
            $numberFormatted = str_pad($nextNumber, 8, '0', STR_PAD_LEFT);
            return "{$prefix}-{$windowId}-{$year}-{$numberFormatted}";
        });
        return $appId;
    }
}

if (!function_exists('lastUpdated')) {
    function lastUpdated()
    {
        $latestFile = collect(File::allFiles(base_path()))
        ->reject(fn ($file) =>
            str_contains($file->getRealPath(), 'vendor') ||
            str_contains($file->getRealPath(), 'storage') ||
            str_contains($file->getRealPath(), 'bootstrap/cache')
        )
        ->sortByDesc(fn ($file) => $file->getMTime())
        ->first();

        return $latestFile ? [
            'name' => $latestFile->getFilename(),
            'path' => $latestFile->getRealPath(),
            'updated_at' => Carbon::createFromTimestamp($latestFile->getMTime())
                ->format('d F Y'),
        ] : null;

    }
}
