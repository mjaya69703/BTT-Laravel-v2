<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// Use System
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
// Use Models
use App\Models\Movies;
use App\Models\Reviews;
// Use Resource
use App\Http\Resources\ReviewsResource;

class ReviewsController extends Controller
{
    public function index($id)
    {
        $review = Reviews::where('movie_id', $id)->latest()->paginate(10);
        $movies = Movies::where('id', $id)->first();

        return new ReviewsResource(true, 'List Rating movies '. $movies->title, $review);

    }

    public function show($id)
    {
        $review = Reviews::find($id);

        return new ReviewsResource(true, 'Detail reviews!' . $review->movies->name, $review);
    }
    
    public function store(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        DB::beginTransaction();

        try {

            $review = Reviews::create([
                'user_id' => Auth::user()->id,
                'movie_id' => $id,
                'rating' => $request->rating,
                'review' => $request->review,
            ]);

            DB::commit();

            return new ReviewsResource(true, 'Reviews Berhasil ditambahkan', $review);

        } catch (\Exception $e) {
            DB::rollBack();


            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }
    public function update(Request $request, $id, $id_rating)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string',
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
    
        $review = Reviews::find($id_rating);
    
        if (!$review) {
            return response()->json([
                'success' => false,
                'message' => 'Review tidak ditemukan'
            ], 404);
        }
    
        if ($review->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak diizinkan untuk mengedit ulasan ini'
            ], 403);
        }
    
        DB::beginTransaction();
    
        try {
            $review->update([
                'rating' => $request->rating,
                'review' => $request->review,
            ]);
    
            DB::commit();
    
            return new ReviewsResource(true, 'Review berhasil diperbarui', $review);
    
        } catch (\Exception $e) {
            DB::rollBack();
    
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function destroy($id, $id_rating)
    {
        $review = Reviews::find($id_rating);
    
        if (!$review) {
            return response()->json([
                'success' => false,
                'message' => 'Review tidak ditemukan'
            ], 404);
        }
    
        if ($review->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak diizinkan untuk menghapus ulasan ini'
            ], 403);
        }
    
        DB::beginTransaction();
    
        try {
            $review->delete();
    
            DB::commit();
    
            return response()->json([
                'success' => true,
                'message' => 'Review berhasil dihapus'
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
