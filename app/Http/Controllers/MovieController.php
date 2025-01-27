<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class MovieController extends Controller
{
    public function index() {
    $jsonFile = storage_path("app/movies.json");
    $jsonFilteredFile = storage_path("app/filtered_movies.json");

    if (!file_exists($jsonFile)) {
        // Crear el archivo JSON si no existe
        Storage::disk('local')->put('movies.json', json_encode([]));
    }
    if (!file_exists($jsonFilteredFile)) {
        // Crear el archivo JSON si no existe
        Storage::disk('local')->put('filtered_movies.json', json_encode([]));
    }


    // Leer el contenido del archivo movies.json
    $movies = json_decode(file_get_contents($jsonFile), true);
    file_put_contents($jsonFilteredFile, json_encode($movies));
    $filteredJsonFile = json_decode(file_get_contents($jsonFilteredFile), true);


    // Agregar registros para depuración
    Log::info('Movies: ' . json_encode($movies));

    return view('index', ['movies' => $movies]);
}
    public function store(Request $request) {
        Log::info( "Please work 2.");

        $request->validate([
            'nombrePelicula' => 'required|string|max:36|min:3',
            'anyoPelicula' => 'required|integer|min:1980|max:' . date('Y'),
            'categoriaPelicula' => 'required|string',
            'caratulaPelicula' => 'required|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);


        $name = $request->input('nombrePelicula');
        $age = $request->input('anyoPelicula');
        $category = $request->input('categoriaPelicula');
        $image = $request->file('caratulaPelicula');
        
        if (!Storage::disk('public')->exists('uploads')) {
            Storage::disk('public')->makeDirectory('uploads');
        }


        $path = $image->store('uploads', 'public');

        $jsonFile = storage_path('app/movies.json');
        $filteredJsonFile = storage_path('app/filtered_movies.json');
        $movies = json_decode(file_get_contents($jsonFile), true);
        $filteredMovies = json_decode(file_get_contents($filteredJsonFile), true);

        $newMovie = [
            'id'=> count($movies) + 1,
            'name' => $name,
            'year' => $age,
            'genre' => $category,
            'image' => Storage::url($path)
        ];
        $movies[] = $newMovie;
        $filteredMovies[] = $newMovie;


        file_put_contents($jsonFile, json_encode($movies));
        file_put_contents($filteredJsonFile, json_encode($filteredMovies));

        return redirect()->route('index');

    }
    public function destroy($id) {
        $jsonFile = storage_path('app/movies.json');
        $filteredJsonFile = storage_path('app/filtered_movies.json');
        $movies = json_decode(file_get_contents($jsonFile), true);
        $filteredMovies = json_decode(file_get_contents($filteredJsonFile), true);

        // Filtrar las películas eliminando la que tiene el ID especificado
        $movies = array_filter($movies, function($movie) use ($id) {
            return $movie['id'] != $id;
        });

        $filteredMovies = array_filter($filteredMovies, function($movie) use ($id) {
            return $movie['id'] != $id;
        });

        // Reordenar los IDs
        $movies = array_values($movies);
        foreach ($movies as $index => $movie) {
            $movies[$index]['id'] = $index + 1;
        }

        $filteredMovies = array_values($filteredMovies);
        foreach ($filteredMovies as $index => $movie) {
            $filteredMovies[$index]['id'] = $index + 1;
        }

        file_put_contents($jsonFile, json_encode($movies));
        file_put_contents($filteredJsonFile, json_encode($filteredMovies));

        return response()->json(['message' => 'Movie deleted successfully'], 200);
    }
    public function search($search = null, $gendre = null, $minAge = null, $maxAge = null) {
        Log::info('Search term: ' . $search);

        $jsonFile = storage_path('app/movies.json');
        $filteredJsonFile = storage_path('app/filtered_movies.json');
        $movies = json_decode(file_get_contents($jsonFile), true);

        $filteredMovies = $this->filterMovies($movies, $search, $gendre, $minAge, $maxAge);

        file_put_contents($filteredJsonFile, json_encode(array_values($filteredMovies)));

        Log::info('Search term: ' . $search);

        return view('index', ['movies' => $filteredMovies]);
    }

    private function filterMovies($movies, $search, $gendre, $minAge, $maxAge) {
        $filteredMovies = $movies;

        if ($search != "all") {
            $filteredMovies = $this->filterByName($filteredMovies, $search);
        }

        if ($gendre != "all") {
            $filteredMovies = $this->filterByGenre($filteredMovies, $gendre);
        }

        if ($minAge != "all" && $maxAge != "all") {
            $filteredMovies = $this->filterByYear($filteredMovies, $minAge, $maxAge);
        }

        return $filteredMovies;
    }

    private function filterByName($movies, $search) {
        return array_filter($movies, function($movie) use ($search) {
            return empty($search) || stripos($movie['name'], $search) !== false;
        });
    }

    private function filterByGenre($movies, $gendre) {
        return array_filter($movies, function($movie) use ($gendre) {
            return empty($gendre) || (isset($movie['genre']) && stripos($movie['genre'], $gendre) !== false);
        });
    }

    private function filterByYear($movies, $minAge, $maxAge) {
        return array_filter($movies, function($movie) use ($minAge, $maxAge) {
            $year = intval($movie['year']);
            return (is_null($minAge) || $year >= $minAge) && (is_null($maxAge) || $year <= $maxAge);
        });
    }
    // public function filterGenre($gendre = null) {
    //     $jsonFile = storage_path('app/movies.json');
    //     $filteredJsonFile = storage_path('app/filtered_movies.json');
    //     $movies = json_decode(file_get_contents($jsonFile), true);


    //     $filteredMovies = array_filter($movies, function($movie) use ($gendre) {
    //         $matchesGendre = empty($gendre) || (isset($movie['genre']) && stripos($movie['genre'], $gendre) !== false);
    //         return $matchesGendre;
    //     });


    //     file_put_contents($filteredJsonFile, json_encode(array_values($filteredMovies)));

    //     return view('index', ['movies' => $filteredMovies]);
    // }
    // public function ageSearch($minAge = null, $maxAge = null) {
    //     $jsonFile = storage_path('app/movies.json');
    //     $filteredJsonFile = storage_path('app/filtered_movies.json');
    //     $movies = json_decode(file_get_contents($filteredJsonFile), true);
    //     $filteredMovies = array_filter($movies, function($movie) use ($minAge, $maxAge) {
    //         $year = intval($movie['year']);
    //         $matchesMinAge = is_null($minAge) || $year >= $minAge;
    //         $matchesMaxAge = is_null($maxAge) || $year <= $maxAge;
    //         return $matchesMinAge && $matchesMaxAge;
    //     });
    //     file_put_contents($filteredJsonFile, json_encode(array_values($filteredMovies)));
    //     return view('index', data: ['movies' => $filteredMovies]);}
    public function showAllMovies(){
        $jsonFile = storage_path('app/movies.json');
        $filteredJsonFile = storage_path('app/filtered_movies.json');
        $movies = json_decode(file_get_contents($jsonFile), true);

        file_put_contents($filteredJsonFile, json_encode(array_values($movies)));
        return view('index', data: ['movies' => $movies]);}
    }
