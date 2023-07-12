<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Tag;
use App\Models\Style;
use App\Models\User;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/tag', function (Request $request) {
    $query = $request->input('query');
    $tags = Tag::select('name')
        ->where('name', 'like', "%{$query}%")
        ->take(10)
        ->get();

    return response()->json($tags);

});

Route::get('/style', function (Request $request) {
    $query = $request->input('query');
    $styles = Style::select('name')->where('name', 'like', "%{$query}%")
        ->take(10)
        ->get();
    return response()->json($styles);
});

Route::get('/author', function (Request $request) {
    $query = $request->input('query');
    $Users = User::select('id as user_id', 'name')->where('name', 'like', "%{$query}%")
        ->take(10)
        ->get();
    return response()->json($Users);
});

Route::get('/single-author', function (Request $request) {
    $query = $request->input('query');
    $user = User::select('id as user_id','name')->where('id', $query)->first();

    return response()->json($user);
});
