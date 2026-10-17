<?php

namespace App\Http\Controllers;

use App\Models\Song;
use Illuminate\Http\Request;

class SongController extends Controller
{
    private const FILTERABLE = ['genre', 'artist', 'release_year'];

    private function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'artist' => 'required|string|max:255',
            'genre' => 'required|string|max:100',
            'duration_seconds' => 'required|integer|min:1',
            'release_year' => 'required|integer|min:1900|max:2100',
        ];
    }

    public function index(Request $request)
    {
        $query = Song::query();

        foreach (self::FILTERABLE as $field) {
            if ($request->filled($field)) {
                $query->where($field, $request->query($field));
            }
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $song = Song::create($request->validate($this->rules()));

        return response()->json($song, 201);
    }

    public function show(Song $song)
    {
        return response()->json($song);
    }

    public function update(Request $request, Song $song)
    {
        $song->update($request->validate($this->rules()));

        return response()->json($song);
    }

    public function destroy(Song $song)
    {
        $song->delete();

        return response()->noContent();
    }
}
