<?php

namespace App\Http\Interfaces;

use App\Http\Requests\CategoryRequest;
use Illuminate\Http\Request;

interface CategoryInterface
{
    function createCategory(CategoryRequest $request);
    function updateCategory(Request $request, $category_id);
    function getCategory();
}