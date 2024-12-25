<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// Use System
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
// Use Models
use App\Models\Movies;
// Use Resource
use App\Http\Resources\MoviesResource;

class MoviesController extends Controller
{
    public function index()
    {
        $movies = Movies::latest()->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $movies,
        ], 200);
    }

    public function show($id)
    {
        $movies = Movies::find($id);

        return new MoviesResource(true, 'Detail movies!', $movies);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:png,jpg,webp|max:2048',
            'title' => 'required|string',
            'description' => 'required|string',
            'release_date' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        DB::beginTransaction();

        try {
            $image = $request->file('image');
            $image->storeAs('images/movies', $image->hashName(), 'public');

            $movies = Movies::create([
                'image' => $image->hashName(),
                'title' => $request->title,
                'description' => $request->description,
                'release_date' => $request->release_date,
            ]);

            DB::commit();

            return new MoviesResource(true, 'Movies Berhasil ditambahkan', $movies);

        } catch (\Exception $e) {
            DB::rollBack();

            if (isset($image)) {
                Storage::delete('images/movies/' . $image->hashName(), 'public');
            }

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'nullable|image|mimes:png,jpg,webp|max:2048',
            'title' => 'required|string',
            'description' => 'required|string',
            'release_date' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        DB::beginTransaction();

        try {
            $movie = Movies::findOrFail($id);

            if ($request->hasFile('image')) {
                if ($movie->image) {
                    Storage::delete('storage/images/movies/' . $movie->image);
                }

                $image = $request->file('image');
                $image->storeAs('storage/images/movies', $image->hashName());
                $movie->image = $image->hashName();
            }

            // Update data lainnya
            $movie->title = $request->title;
            $movie->description = $request->description;
            $movie->release_date = $request->release_date;
            $movie->save();

            DB::commit();

            return new MoviesResource(true, 'Movies Berhasil diperbarui', $movie);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $movie = Movies::findOrFail($id);

            if ($movie->image) {
                Storage::delete('storage/images/movies/' . $movie->image);
            }

            $movie->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Movie berhasil dihapus.'
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }
}
