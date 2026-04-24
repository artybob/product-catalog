<?php

namespace App\Services;

use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Illuminate\Support\Facades\Log;

class CategoryService
{
    protected $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getAllCategories()
    {
        try {
            return $this->categoryRepository->getAll();
        } catch (\Exception $e) {
            Log::error('Failed to fetch categories: ' . $e->getMessage());
            throw new \RuntimeException('Unable to fetch categories');
        }
    }

    public function getCategoriesWithProducts()
    {
        try {
            return $this->categoryRepository->getWithProducts();
        } catch (\Exception $e) {
            Log::error('Failed to fetch categories with products: ' . $e->getMessage());
            throw new \RuntimeException('Unable to fetch categories');
        }
    }
}
