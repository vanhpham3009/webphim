<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use App\Models\Category;
use App\Models\Country;
use App\Models\Genre;
use App\Models\Episode;
use App\Models\Favourite;
use App\Models\Info;
use App\Models\Movie;
use App\Models\Movie_Genre;
use App\Models\Movie_View;
use App\Models\Rating;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class CreateSiteMap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $sitemap = App::make('sitemap');
        $sitemap->add(URL::to('/'), Carbon::now('Asia/Ho_Chi_Minh'), '1.0', 'daily');

        $categories = Category::orderBy('id', 'desc')->get();
        foreach ($categories as $category) {
            $sitemap->add(
                URL::to('/danh-muc/' . $category->slug),
                Carbon::now('Asia/Ho_Chi_Minh'),
                '0.8',
                'daily'
            );
        }

        $genres = Genre::orderBy('id', 'desc')->get();
        foreach ($genres as $genre) {
            $sitemap->add(
                URL::to('/the-loai/' . $genre->slug),
                Carbon::now('Asia/Ho_Chi_Minh'),
                '0.7',
                'daily'
            );
        }

        $countries = Country::orderBy('id', 'desc')->get();
        foreach ($countries as $country) {
            $sitemap->add(
                URL::to('/quoc-gia/' . $country->slug),
                Carbon::now('Asia/Ho_Chi_Minh'),
                '0.6',
                'daily'
            );
        }

        $movies = Movie::orderBy('id', 'desc')->get();
        foreach ($movies as $movie) {
            $sitemap->add(
                URL::to('/chi-tiet/' . $movie->slug),
                Carbon::now('Asia/Ho_Chi_Minh'),
                '0.9',
                'daily'
            );
        }

        $sitemap->store('xml', 'sitemap');
        if (File::exists(base_path() . '/sitemap.xml')) {
            File::copy(
                public_path('sitemap.xml'),
                base_path('sitemap.xml')
            );
        }
    }
}
