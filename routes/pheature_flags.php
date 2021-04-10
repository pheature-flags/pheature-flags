<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Pheature\Crud\Psr7\Toggle\DeleteFeature;
use Pheature\Crud\Psr7\Toggle\GetFeature;
use Pheature\Crud\Psr7\Toggle\GetFeatures;
use Pheature\Crud\Psr7\Toggle\PatchFeature;
use Pheature\Crud\Psr7\Toggle\PostFeature;

Route::get('/features', [GetFeatures::class, 'handle'])->name('get_features');
Route::get('/features/{feature_id}', [GetFeature::class, 'handle'])->name('get_feature');
Route::post('/features/{feature_id}', [PostFeature::class, 'handle'])->name('post_feature');
Route::patch('/features/{feature_id}', [PatchFeature::class, 'handle'])->name('patch_feature');
Route::delete('/features/{feature_id}', [DeleteFeature::class, 'handle'])->name('delete_feature');
