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
    
        $content = strtolower($request->content); // Normaliza para evitar problemas de case
    
        // Verifica se o conteúdo contém alguma palavra proibida sem carregar tudo na memória
        foreach (\App\Models\BadWords::cursor() as $badWord) {
            if (str_contains($content, strtolower($badWord->bad_word))) {
                return response()->json([
                    'error' => 'Seu testemunho contém palavras inadequadas.',
                ], 400);
            }
        }
    
        // Cria o testemunho se não houver palavras proibidas
        $testimony = Testimony::create([
            "content" => $request->content,
            "user_id" => auth()->id(),
        ]);
    
        return response()->json($testimony, 201);
    }
    
}
