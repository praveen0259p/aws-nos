<?php

namespace App\Traits;

use App\Models\Asset;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait UploadAssetTrait
{
    public function uploadAssetToPublic($file, $folder)
    {
        $size = $file->getSize();
        $destinationPath = public_path($folder);
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }
        $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
            . '.' . $file->getClientOriginalExtension();
        $file->move($destinationPath, $filename);
        $asset = Asset::create([
            'url'  => $folder . '/' . $filename,
            'size' => $size,
        ]);
        return $asset->id;
    }
    public function updateAssetInPublic($file, $assetId, $folder)
    {
        $asset = Asset::find($assetId);
        if ($asset) {
            $oldFilePath = public_path($asset->url);
            if (file_exists($oldFilePath) && is_file($oldFilePath)) {
                unlink($oldFilePath);
            }
            $size = $file->getSize();
            $destinationPath = public_path($folder);
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                . '.' . $file->getClientOriginalExtension();
            $file->move($destinationPath, $filename);
            $asset->update([
                'url'  => $folder . '/' . $filename,
                'size' => $size,
            ]);
            return $asset->id;
        }
    }
    public function deleteAssetById($asset_id)
    {
        try {
            $asset = Asset::findOrFail($asset_id);
            if ($asset->url && File::exists(public_path($asset->url))) {
                File::delete(public_path($asset->url));
            }
            $asset->delete();
            return response()->json([
                'success' => true,
                'message' => 'Asset deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Asset could not be deleted.'
            ], 400);
        }
    }
}
