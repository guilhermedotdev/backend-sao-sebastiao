<?php
namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Testimony;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, $testimonyId)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        // Verifica se o testemunho existe
        $testimony = Testimony::findOrFail($testimonyId);

        // Criação do comentário
        $comment = Comment::create([
            'content' => $request->content,
            'user_id' => auth()->id,
            'testimony_id' => $testimony->id,
        ]);

        return response()->json($comment, 201);
    }
}
