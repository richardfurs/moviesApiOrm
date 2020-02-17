<?php


namespace App\Api;


use App\Genre;
use App\Language;
use App\Movie;

class OmdbService
{

    /**
     * @var OmdbClient
     */
    protected $omdbClient;

    /**
     * OmdbService constructor.
     * @param OmdbClient $omdbClient
     */
    public function __construct(OmdbClient $omdbClient)
    {
        $this->omdbClient = $omdbClient;
    }

    /**
     * Search movies by title.
     *
     * @param $title
     * @return array
     */
    public function searchByTitle($title) {

        $response = $this->omdbClient->searchByTitle($title);

        if ($response->haveError()) {
            return $response->getError();
        }

        $body = $response->getResponse();

        $movie = new Movie();
        $movie->title = $body->Title;
        $movie->save();

        $data = [];
        $data['language'] = explode(', ', $body->Language);
        $data['genre'] = explode(', ', $body->Genre);
        $data['title'] = $body->Title;

        foreach($data['language'] as $lang) {
            $langExists = Language::where('language', $lang)->exists();
            if($langExists) {
                $langId = Language::where('language', $lang)->first()->id;
                $movie->languages()->attach($langId);
            } else {
                $movie->languages()->firstOrCreate([
                    'language' => $lang
                ]);
            }
        }
        foreach($data['genre'] as $genre) {
            $genreExists = Genre::where('genre', $genre)->exists();
            if($genreExists) {
                $genreId = Genre::where('genre', $genre)->first()->id;
                $movie->genres()->attach($genreId);
            } else {
                $movie->genres()->firstOrCreate([
                    'genre' => $genre
                ]);
            }
        }

        return $data;
    }
}
