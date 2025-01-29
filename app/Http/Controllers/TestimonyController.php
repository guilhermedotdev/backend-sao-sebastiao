<?php
namespace App\Http\Controllers;

use App\Models\Testimony;
use Illuminate\Http\Request;

class TestimonyController extends Controller
{
    public function index()
    {
        // Como só há um tópico de testemunhos, podemos retornar diretamente o primeiro
        $testimony = Testimony::with('user', 'comments')->get();
        return response()->json([
            "testemunhos" => $testimony
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        // Verifica se o tópico já existe (único tópico)
        $testimony = Testimony::create([
            "content" => $request->content,
            "user_id" => auth()->id(),
        ]);
        
        return response()->json($testimony, 201);
    }
}
