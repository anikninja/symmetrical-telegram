<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NoteController;
use App\Http\Controllers\Api\FolderController;
use App\Http\Controllers\Api\LibraryController;
use App\Http\Controllers\Api\BookmarkController;
use App\Http\Controllers\Api\LanguageController;
use App\Http\Controllers\Api\NoteTypeController;


Route::middleware('auth:api')->group(function () {

    Route::apiResource('folders', FolderController::class);
    Route::apiResource('libraries', LibraryController::class);
    Route::apiResource('notes', NoteController::class);
    Route::get('/notes/search', [NoteController::class, 'search'])->name('notes.search');
    Route::get('/notes/{note}/export', [NoteController::class, 'export'])->name('notes.export');

    Route::get('/bookmarks', [BookmarkController::class, 'index']);
    Route::post('/bookmarks', [BookmarkController::class, 'store']);
    Route::delete('/bookmarks/notes/{note}', [BookmarkController::class, 'destroy']);
});

Route::get('note-types', [NoteTypeController::class, 'index'])->name('note-types.index');
Route::get('languages', LanguageController::class);
