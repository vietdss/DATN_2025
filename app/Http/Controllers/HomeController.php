<?php

namespace App\Http\Controllers;

use App\Services\CategoryService;
use App\Services\ImageService;
use App\Services\ItemService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $itemService;
    protected $categoryService;
    protected $imageService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ItemService $itemService, CategoryService $categoryService, ImageService $imageService)
    {
        $this->itemService = $itemService;
        $this->categoryService = $categoryService;
        $this->imageService = $imageService;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $params = [];
    
        // Lấy 4 item đầu tiên để hiển thị nổi bật
        $items = $this->itemService->getAll()->take(4);
    
        // Lấy danh mục
        $categories = $this->categoryService->getAll();
    
        return view('home', [
            'items' => $items,
            'categories' => $categories
        ]);
    }
    
}
