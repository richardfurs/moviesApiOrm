<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="{{asset('css/styles.css')}}">
    <title>Document</title>
</head>
<body>
    <div class="container">
        <h1 style="margin-top: 10px">Search Movies</h1>
        <form class="search" action="/movie" method="post">
            @csrf
            <input name="movie" type="text" value="{{ old('movie') }}" placeholder="Search for movie">
            <input type="submit" value="Search">
            {!! $errors->first('movie', '<p style="color: red">:message</p>') !!}

            @if(is_array(session('data')) == 1)
                <p class="text-center found">MOVIE FOUND!!!</p>
                <div class="alert alert-success found">
                    <div class="row">
                        Title:<div class="col-sm">{{session('data')['title']}}</div>
                        Genres:<div class="col-sm">
                            @foreach(session('data')['genre'] as $genre)
                                {{$genre}}
                            @endforeach
                        </div>
                        Languages:<div class="col-sm">
                            @foreach(session('data')['language'] as $language)
                                {{$language}}
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <p style="color: red">{{ session('data') }}</p>
            @endif
        </form>

        @php
            if(isset(session('movies')['movies'])) {
                $movies = session('movies')['movies'];
            } else {
                $movies = \App\Movie::all();
            }
            $reversed = $movies->reverse();
            $movies = $reversed->all();
            $genres = \App\Genre::all();
            $languages = \App\Language::all();
        @endphp

        <h3>Search history</h3>

        <form method="post" action="/filter">
            @csrf
            <select name="genre">
                <option selected disabled>All genres</option>
                @foreach($genres as $genre)
                    <option value="{{$genre->id}}" {{ old('genre') == $genre->id ? 'selected' : '' }}>{{$genre->genre}}</option>
                @endforeach
            </select>
            <select name="language">
                <option selected disabled>All languages</option>
                @foreach($languages as $language)
                    <option value="{{$language->id}}" {{ old('language') == $language->id ? 'selected' : '' }}>{{$language->language}}</option>
                @endforeach
            </select>
            <input type="submit" value="Filter movies">
        </form>

        @if(isset(session('movies')['errMsg']))
            <p style="color: red">{{session('movies')['errMsg']}}</p>
        @else
            <table>
                <tr>
                    <th>Movie Title</th>
                    <th>Genres</th>
                    <th>Languages</th>
                    <th>Search time</th>
                </tr>
                @foreach($movies as $movie)
                    <tr>
                        <td>{{$movie->title}}</td>
                        <td>
                            @foreach($movie->genres()->get() as $genre)
                                {{$genre->genre}}
                            @endforeach
                        </td>
                        <td>
                            @foreach($movie->languages()->get() as $lang)
                                {{$lang->language}}
                            @endforeach
                        </td>
                        <td>{{$movie->created_at}}</td>
                    </tr>
                @endforeach
            </table>
        @endif
    </div>

</body>
</html>



