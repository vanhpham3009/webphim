<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\EpisodeController;
use App\Http\Controllers\FavouriteController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\LeechMovieController;
use App\Http\Controllers\LinkMovieController;
use App\Http\Controllers\LoginGoogleController;
use App\Http\Controllers\MovieController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [IndexController::class, 'home'])->name('homepage');
Route::get('/tim-kiem', [IndexController::class, 'search'])->name('tim-kiem');
Route::get('/loc-phim', [IndexController::class, 'filter'])->name('filter');
Route::get('/danh-muc/{slug}', [IndexController::class, 'category'])->name('category');
Route::get('/the-loai/{slug}', [IndexController::class, 'genre'])->name('genre');
Route::get('/quoc-gia/{slug}', [IndexController::class, 'country'])->name('country');
Route::get('/chi-tiet/{slug}', [IndexController::class, 'detail'])->name('detail');
Route::get('/nam-phim/{year}', [IndexController::class, 'year'])->name('year');
Route::get('/xem-phim/{slug}/{tap}', [IndexController::class, 'watch']);
Route::get('/tag/{tag}', [IndexController::class, 'tag']);
Route::get('/random', [IndexController::class, 'random'])->name('random');
Route::post('/add-rating', [IndexController::class, 'add_rating'])->name('add-rating');

Auth::routes();
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::post('/', [IndexController::class, 'home'])->name('homepage.post');
Route::post('/filter-topview', [MovieController::class, 'filter_topview'])->name('filter-topview');
Route::get('/filter-topview-default', [MovieController::class, 'filter_topview_default'])->name('filter-topview-default');

Route::post('/yeu-thich', [IndexController::class, 'add_favourite'])->name('yeu-thich');
Route::get('/quan-ly-yeu-thich', [IndexController::class, 'manage_favourite'])->name('manage.favourite');
Route::post('/xoa-yeu-thich', [IndexController::class, 'remove_favourite'])->name('remove.favourite');
Route::post('/xoa-het-yeu-thich', [IndexController::class, 'remove_all_favourite'])->name('remove.all.favourite');
Route::get('/load-yeu-thich', [IndexController::class, 'load_favourites'])->name('load.favourites');

//category
Route::resource('/category', CategoryController::class);
//genre
Route::resource('/genre', GenreController::class);
//country
Route::resource('/country', CountryController::class);
//movie
Route::post('/update-country', [MovieController::class, 'update_country'])->name('update-country');
Route::post('/update-status', [MovieController::class, 'update_status'])->name('update-status');
Route::post('/update-hot', [MovieController::class, 'update_hot'])->name('update-hot');
Route::resource('/movie', MovieController::class);
//linkmovie
Route::resource('/linkmovie', LinkMovieController::class);
//leechmovie
Route::get('/leech-movie', [LeechMovieController::class, 'leech_movie'])->name('leech-movie');
Route::get('/leech-detail/{slug}', [LeechMovieController::class, 'leech_detail'])->name('leech-detail');
Route::post('/leech-store/{slug}', [LeechMovieController::class, 'leech_store'])->name('leech-store');
Route::get('/leech-episode/{slug}', [LeechMovieController::class, 'leech_episode'])->name('leech-episode');
Route::post('/leech-episode-store/{slug}', [LeechMovieController::class, 'leech_episode_store'])->name('leech-episode-store');
Route::post('/watch-leech-detail/{slug}', [LeechMovieController::class, 'watch_leech_detail'])->name('watch-leech-detail');

//episode
Route::resource('/episode', EpisodeController::class);
Route::get('/add-episode/{id}', [EpisodeController::class, 'add_episode'])->name('add-episode');
Route::get('/select-movie', [EpisodeController::class, 'select_movie'])->name('select-movie');
//chart
// Route::get('/chart', [\App\Http\Controllers\ChartController::class, 'index'])->name('chart.index');

Route::get('auth/google', [LoginGoogleController::class, 'redirect'])->name('google-auth');
Route::get('auth/google/call-back', [LoginGoogleController::class, 'callbackGoogle']);
