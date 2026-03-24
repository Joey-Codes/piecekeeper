<?php

namespace App\Http\Controllers;

use App\Http\Requests\PieceRequest;
use App\Http\Resources\PieceResource;
use App\Models\Piece;
use Illuminate\Http\Request;

class PieceController extends Controller
{
    public function index(Request $request)
    {
        return PieceResource::collection($request->user()->pieces);
    }

    public function store(PieceRequest $request)
    {
        $piece = $request->user()->pieces()->create($request->validated());

        return new PieceResource($piece);
    }

    public function update(PieceRequest $request, Piece $piece)
    {
        if ($piece->user_id !== $request->user()->id) {
            abort(403);
        }

        $piece->update($request->validated());

        return new PieceResource($piece);
    }

    public function destroy(Request $request, Piece $piece)
    {
        if ($piece->user_id !== $request->user()->id) {
            abort(403);
        }

        $piece->delete();

        return response()->noContent();
    }
}
