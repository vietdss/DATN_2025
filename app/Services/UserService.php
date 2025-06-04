<?php

namespace App\Services;

use App\Models\User;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class UserService
{
    public function getAll()
    {
        return User::all();
    }

    /**
     * Cập nhật thông tin user
     */
    public function update(User $user, array $data): User
    {
        $user->update($data);
        return $user;
    }

    /**
     * Tìm user theo ID
     */
    public function getById($id)
    {
        return User::findOrFail($id);
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        return $user->delete();
    }

    public function searchUsers(Request $request)
    {
        $query = User::withCount('items')
            ->addSelect([
                'last_shared_at' => Item::select(DB::raw('MAX(created_at)'))
                    ->whereColumn('user_id', 'users.id')
            ]);

        // Tìm theo tên nếu có
        if ($request->filled('searchName')) {
            $query->where('name', 'like', '%' . $request->searchName . '%');
        }

        // Lọc theo hoạt động nếu có
        if ($request->filled('searchActivity')) {
            $days = match ($request->searchActivity) {
                'active' => 7,
                'veryactive' => 3,
                'superactive' => 1,
                default => null,
            };

            if ($days) {
                $query->having('last_shared_at', '>=', now()->subDays($days));
            }
        }

        // Sắp xếp nếu có yêu cầu, mặc định theo tên
        if ($request->filled('searchSort')) {
            switch ($request->searchSort) {
                case 'items':
                    $query->orderByDesc('items_count');
                    break;
                case 'newest':
                    $query->orderByDesc('created_at');
                    break;
                case 'oldest':
                    $query->orderBy('created_at');
                    break;
                default:
                    $query->orderBy('created_at');
            }
        } else {
            // Nếu không có sort nào -> mặc định theo tên
            $query->orderBy('name');
        }
        
        if(Auth::check()){
            $query->where('id', '!=', Auth::id());
        }
        
        return $query->paginate(5);
    }

    /**
     * Lấy items của user với filter và search
     */
    public function getUserItemsWithFilter($userId, Request $request)
    {
        $query = Item::where('user_id', $userId)
            ->with('images')
            ->orderBy('created_at', 'desc');

        // Tìm kiếm theo tên
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'Còn hàng':
                    $query->where('is_approved', true)
                          ->where('status', '!=', 'Taken');
                    break;
                case 'Hết hàng':
                    $query->where('status', 'Taken');
                    break;
                case 'Chờ duyệt':
                    $query->where('is_approved', false);
                    break;
            }
        }

        return $query->paginate(4)->appends($request->query());
    }

    // Các phương thức hiện có...

    public function updateProfile(User $user, array $data)
    {
        $user->update($data);
        return $user;
    }

    public function updatePassword(User $user, string $newPassword)
    {
        $user->update([
            'password' => Hash::make($newPassword)
        ]);
        return $user;
    }

    public function uploadProfileImage(User $user, $image)
    {
        // Nếu người dùng đã có ảnh đại diện, xóa ảnh cũ trên Cloudinary
        if ($user->profile_image && strpos($user->profile_image, 'cloudinary') !== false) {
            // Lấy public_id từ URL
            $publicId = $this->getPublicIdFromUrl($user->profile_image);
            if ($publicId) {
                // Xóa ảnh cũ
                Cloudinary::destroy($publicId);
            }
        }
        
        // Tải ảnh mới lên Cloudinary
        $uploadedImage = Cloudinary::upload($image->getRealPath(), [
            'folder' => 'profile_images',
            'transformation' => [
                'width' => 500,
                'height' => 500,
                'crop' => 'fill',
                'gravity' => 'face'
            ]
        ]);

        // Lưu URL của ảnh vào database
        $user->update([
            'profile_image' => $uploadedImage->getSecurePath()
        ]);

        return $user;
    }

    /**
     * Lấy public_id từ URL Cloudinary
     */
    public function getPublicIdFromUrl($url)
    {
        // URL Cloudinary có dạng: https://res.cloudinary.com/cloud_name/image/upload/v1234567890/folder/public_id.jpg
        $pattern = '/\/v\d+\/([^\.]+)/';
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }
        return null;
    }
}
