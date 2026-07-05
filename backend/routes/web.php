<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ShowController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\SocialLinkController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\UploadController;

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::resource('shows', ShowController::class)->names('admin.shows');
    Route::resource('gallery', GalleryController::class)->names('admin.gallery');
    Route::resource('social-links', SocialLinkController::class)->names('admin.social-links');
    Route::resource('site-settings', SiteSettingController::class)->names('admin.site-settings');
    Route::get('analytics', [AnalyticsController::class, 'index'])->name('admin.analytics');
    Route::get('uploads', [UploadController::class, 'index'])->name('admin.uploads.index');
    Route::get('uploads/list', [UploadController::class, 'list'])->name('admin.uploads.list');
    Route::post('uploads/upload', [UploadController::class, 'upload'])->name('admin.uploads.upload');
    Route::post('uploads/batch-delete', [UploadController::class, 'batchDestroy'])->name('admin.uploads.batch-destroy');
    Route::delete('uploads/{media}', [UploadController::class, 'destroy'])->name('admin.uploads.destroy');
});
