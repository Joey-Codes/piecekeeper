<?php

namespace App\Http\Controllers;

use App\Http\Requests\PieceRequest;
use App\Http\Resources\PieceResource;
use App\Models\Piece;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PieceController extends Controller
{
    public function index(Request $request)
    {
        return PieceResource::collection($request->user()->pieces);
    }

    public function store(PieceRequest $request)
    {
        $data = $request->validated();

        if (($data['status'] ?? '') !== 'Want to Learn') {
            $maxOrder = $request->user()->pieces()->whereNotNull('sort_order')->max('sort_order') ?? 0;
            $data['sort_order'] = $maxOrder + 1;
        }

        $piece = $request->user()->pieces()->create($data);

        return new PieceResource($piece);
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:pieces,id',
        ]);

        $userId = $request->user()->id;
        $ids = $request->input('ids');

        $count = Piece::whereIn('id', $ids)->where('user_id', $userId)->count();
        if ($count !== count($ids)) {
            abort(403, 'Unauthorized piece in list');
        }

        DB::transaction(function () use ($ids) {
            foreach ($ids as $index => $id) {
                Piece::where('id', $id)->update(['sort_order' => $index + 1]);
            }
        });

        return response()->json(['message' => 'Order updated']);
    }

    public function update(PieceRequest $request, Piece $piece)
    {
        if ($piece->user_id !== $request->user()->id) {
            abort(403);
        }

        $data = $request->validated();
        $newStatus = $data['status'] ?? $piece->status;
        $needsOrder = in_array($newStatus, ['Shelved', 'Want to Learn']);
        $hadOrder = $piece->sort_order !== null;

        if ($needsOrder && $hadOrder) {
            // Moving to shelved/wishlist — remove from rotation
            $oldOrder = $piece->sort_order;
            $data['sort_order'] = null;
            $piece->update($data);

            Piece::where('user_id', $piece->user_id)
                ->where('sort_order', '>', $oldOrder)
                ->decrement('sort_order');
        } elseif (!$needsOrder && !$hadOrder) {
            // Moving to active status — append to end of rotation
            $maxOrder = Piece::where('user_id', $piece->user_id)
                ->whereNotNull('sort_order')
                ->max('sort_order') ?? 0;
            $data['sort_order'] = $maxOrder + 1;
            $piece->update($data);
        } else {
            $piece->update($data);
        }

        return new PieceResource($piece->fresh());
    }

    public function destroy(Request $request, Piece $piece)
    {
        if ($piece->user_id !== $request->user()->id) {
            abort(403);
        }

        $sortOrder = $piece->sort_order;
        $userId = $piece->user_id;

        $piece->delete();

        if ($sortOrder !== null) {
            Piece::where('user_id', $userId)
                ->where('sort_order', '>', $sortOrder)
                ->decrement('sort_order');
        }

        return response()->noContent();
    }
}
