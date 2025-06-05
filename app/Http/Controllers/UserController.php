<?php

namespace App\Http\Controllers;

use App\Services\ItemService;
use App\Services\UserService;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected $userService;
    protected $itemService;

    public function __construct(UserService $userService, ItemService $itemService)
    {
        $this->userService = $userService;
        $this->itemService = $itemService;
    }

    public function index(Request $request)
    {
        $users = $this->userService->searchUsers($request);
        return view('user.index', ['users' => $users]);
    }

    public function profile($id, Request $request)
    {
        $user = $this->userService->getById($id);
        
        // Sử dụng method mới với filter
        $items = $this->userService->getUserItemsWithFilter($id, $request);

        $sharedCount = \App\Models\Item::where('user_id', $id)->where('is_approved', 1)->count();
        $receivedCount = \App\Models\Transaction::where('receiver_id', $id)->where('status', 'completed')->count();
        
        return view('user.profile', [
            'user' => $user,
            'items' => $items,
            'sharedCount' => $sharedCount,
            'receivedCount' => $receivedCount,
            'currentSearch' => $request->get('search', ''),
            'currentStatus' => $request->get('status', '')
        ]);
    }

    public function update()
    {
        $user = auth()->user();
        return view('user.account-setting', ['user' => $user]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);
        
        $this->userService->updateProfile($user, $validated);
        
        if ($request->hasFile('profile_image')) {
            $request->validate([
                'profile_image' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
            ]);
            
            $this->userService->uploadProfileImage($user, $request->file('profile_image'));
        }
        
        return redirect()->back()->with('success', 'Thông tin hồ sơ đã được cập nhật!');
    }
    
    public function updatePassword(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);
        
        if (!Hash::check($request->current_password, $user->password)) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => ['current_password' => 'Mật khẩu hiện tại không chính xác']
                ]);
            }
            
            return redirect()->back()
                ->withErrors(['current_password' => 'Mật khẩu hiện tại không chính xác'])
                ->withInput();
        }
        
        try {
            $this->userService->updatePassword($user, $request->new_password);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Mật khẩu đã được cập nhật thành công!'
                ]);
            }
            
            return redirect()->back()->with('success', 'Mật khẩu đã được cập nhật thành công!');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => ['error' => 'Đã xảy ra lỗi khi cập nhật mật khẩu. Vui lòng thử lại.']
                ]);
            }
            
            return redirect()->back()
                ->withErrors(['error' => 'Đã xảy ra lỗi khi cập nhật mật khẩu. Vui lòng thử lại.'])
                ->withInput();
        }
    }

    public function removeProfileImage()
    {
        $user = auth()->user();
        
        if ($user->profile_image) {
            // Xóa ảnh trên Cloudinary
            if (strpos($user->profile_image, 'cloudinary') !== false) {
                $publicId = $this->userService->getPublicIdFromUrl($user->profile_image);
                if ($publicId) {
                    Cloudinary::destroy($publicId);
                }
            }
            
            // Cập nhật database
            $user->update(['profile_image' => null]);
        }
        
        return redirect()->back()->with('success', 'Ảnh đại diện đã được xóa!');
    }

    public function delete()
    {
        $user = auth()->user();
        
        // Xóa các dữ liệu liên quan nếu cần
        
        auth()->logout();
        $user->delete();
        
        return redirect()->route('home')->with('success', 'Tài khoản của bạn đã được xóa!');
    }

    public function updateActivity()
    {
        $userId = Auth::id();   
        
        // Store last activity time in cache with 10 minute expiration
        Cache::put('user-online-' . $userId, true, now()->addMinutes(10));
        
        return response()->json(['success' => true]);
    }
}
