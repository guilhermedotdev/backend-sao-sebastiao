<?php

use App\Http\Controllers\ArtigoController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ItemLinhaTempoController;
use App\Http\Controllers\LinhaTempoController;
use App\Http\Controllers\LiturgiaController;
use App\Http\Controllers\LocalController;
use App\Http\Controllers\CameraController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DoacaoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::get('/home', [HomeController::class, 'index']);

Route::get('artigos/', [ArtigoController::class, 'index']); // Listar todos os artigos
Route::get('artigos/paginate', [ArtigoController::class, 'paginate']); // Paginado
Route::get('artigos/{id}', [ArtigoController::class, 'show']); // Exibir um artigo

Route::get('item-linha-tempo/{id}', [ItemLinhaTempoController::class, 'show']);
Route::get('linha-tempo/{linhaTempo}', [LinhaTempoController::class, 'show']);

Route::get('/liturgia', [LiturgiaController::class, 'index']);

Route::post('doacao', [DoacaoController::class, 'store']);

Route::get('locais', [LocalController::class, 'index']);
Route::get('locais/geofence', [LocalController::class, 'buscarGeofence']);
Route::get('locais/{local}', [LocalController::class, 'show']);

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');

// Auth
Route::middleware('auth:api')->group(function () {
    Route::post('artigos/', [ArtigoController::class, 'store']); // Criar artigo
    Route::put('artigos/{id}', [ArtigoController::class, 'update']); // Atualizar artigo
    Route::delete('artigos/{id}', [ArtigoController::class, 'destroy']); // Deletar artigo
    
    Route::post('item-linha-tempo', [ItemLinhaTempoController::class, 'store']);
    Route::put('item-linha-tempo/{itemLinhaTempo}', [ItemLinhaTempoController::class, 'update']);
    Route::delete('item-linha-tempo/{itemLinhaTempo}', [ItemLinhaTempoController::class, 'destroy']);
    
    
    Route::post('eventos', [EventoController::class, 'store']);
    Route::put('eventos/{evento}', [EventoController::class, 'update']);
    Route::delete('eventos/{evento}', [EventoController::class, 'destroy']);
    
    Route::post('locais', [LocalController::class, 'store']);
    Route::put('locais/{local}', [LocalController::class, 'update']);
    Route::delete('locais/{local}', [LocalController::class, 'destroy']);

    Route::get('/cameras', [CameraController::class, 'index']); // Listar todas as câmeras
    Route::get('/cameras/{id}', [CameraController::class, 'show']); // Obter uma câmera pelo ID
    Route::post('/cameras', [CameraController::class, 'store']); // Criar uma nova câmera
    Route::put('/cameras/{id}', [CameraController::class, 'update']); // Atualizar uma câmera existente
    Route::delete('/cameras/{id}', [CameraController::class, 'destroy']); // Excluir uma câmera pelo ID

});
