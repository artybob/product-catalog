<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories (without pagination).
     */
    public function index()
    {
        $categories = Category::all();

        return CategoryResource::collection($categories);
    }
}
