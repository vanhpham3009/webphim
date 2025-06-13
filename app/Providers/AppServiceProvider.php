<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Country;
use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $category = Category::orderBy('id', 'desc')->where('status', 1)->get();
        $country = Country::orderBy('id', 'desc')->get();
        $genre = Genre::orderBy('id', 'desc')->get();
        $movie_hot_sidebar = Movie::where('status', 0)->orderBy('updated_at', 'desc')->take(5)->get();

        //Admin panel
        $category_total = Category::all()->count();
        $country_total = Country::all()->count();
        $genre_total = Genre::all()->count();
        $movie_total = Movie::all()->count();

        View::share([
            'category_total' => $category_total,
            'country_total' => $country_total,
            'genre_total' => $genre_total,
            'movie_total' => $movie_total,
            'category' => $category,
            'country' => $country,
            'genre' => $genre,
            'movie_hot_sidebar' => $movie_hot_sidebar,
        ]);

        View::composer('*', function ($view) {
            $onlineCount = Cache::get('online_users_count', 0);
            $view->with('onlineCount', $onlineCount);
        });
    }
}
