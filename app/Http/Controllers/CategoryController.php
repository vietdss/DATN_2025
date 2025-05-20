<?php

namespace App\Http\Controllers;

use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
    }

    public function store(Request $request)
    {
    }


    public function update($id,Request $request)
    {
    }
    public function destroy($id)
    {
    }
}
