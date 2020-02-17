<?php

namespace App\Http\Controllers;

use App\Api\OmdbService;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    /**
     * @var OmdbService
     */
    protected $omdbService;
    /**
     * @var
     */
    protected $movies;

    public function __construct(OmdbService $omdbService)
    {
        $this->omdbService = $omdbService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->flash();

        $request->validate([
            'movie' => 'required',
        ]);

        $data = $this->omdbService->searchByTitle($request->input('movie'));

        return redirect('/')->with('data', $data);
    }

    /**
     * Filter movies.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function filter(Request $request) {

        $request->flash();

        $genreId = $request->input('genre');
        $languageId = $request->input('language');

        $movies = $this->filterMovies($genreId, $languageId);

        return redirect('/')->with('movies', $movies);
    }

    /**
     * Return filtered movies with error message if exists.
     *
     * @param $genreId
     * @param $languageId
     * @return array
     */
    public function filterMovies($genreId, $languageId) {

        if($genreId !== null) {
            $genre = \App\Genre::find($genreId);
            $this->movies = $genre->movies;
        }

        if($languageId !== null) {
            $language = \App\Language::find($languageId);
            $this->movies = $language->movies;
        }

        if($genreId !== null && $languageId !== null) {
            $this->movies = \App\Movie::whereHas('genres', function($q) use($genreId) {
                $q->where('genres.id', $genreId);
            })->whereHas('languages', function($q) use($languageId) {
                $q->where('languages.id', $languageId);
            })->get();
        }

        $arr = [
            'movies' => $this->movies
        ];

        if($this->movies !== null && count($this->movies) < 1) {
            $arr['errMsg'] = 'No movies found!';
        }

        return $arr;
    }
}
