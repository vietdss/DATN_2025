<?php

namespace App\Services;

use App\Models\Image;
use Cloudinary\Cloudinary;
use Illuminate\Support\Facades\Log;

class ImageService
{
    protected $cloudinary;

    public function __construct()
    {
        // Initialize Cloudinary with credentials from .env
        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key' => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
            ],
        ]);
    }

    public function getAll()
    {
        return Image::all();
    }

    public function getById($id)
    {
        return Image::findOrFail($id);
    }

    public function create($data)
    {
        return Image::create($data);
    }

    public function update($id, $data)
    {
        $image = Image::findOrFail($id);
        $image->update($data);
        return $image;
    }

    public function delete($id)
    {
        $image = Image::findOrFail($id);
        
        // Delete from Cloudinary if public_id exists
        if (!empty($image->public_id)) {
            try {
                $this->cloudinary->uploadApi()->destroy($image->public_id);
            } catch (\Exception $e) {
                Log::error('Failed to delete image from Cloudinary: ' . $e->getMessage());
            }
        }
        
        return $image->delete();
    }

    /**
     * Upload image to Cloudinary and create record in database
     * 
     * @param \Illuminate\Http\UploadedFile $file
     * @param int $itemId
     * @return Image
     */
    public function uploadAndCreate($file, $itemId)
    {
        try {
            // Upload to Cloudinary
            $result = $this->cloudinary->uploadApi()->upload(
                $file->getRealPath(),
                [
                    'folder' => 'items',
                    'resource_type' => 'image',
                ]
            );

            // Create image record in database
            return $this->create([
                'item_id' => $itemId,
                'image_url' => $result['secure_url'],
                'public_id' => $result['public_id'],
            ]);
        } catch (\Exception $e) {
            Log::error('Cloudinary upload failed: ' . $e->getMessage());
            throw $e;
        }
    }
}