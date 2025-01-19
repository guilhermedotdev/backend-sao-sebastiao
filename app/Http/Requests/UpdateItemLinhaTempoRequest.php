<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateItemLinhaTempoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'fk_id_linha_tempo' => 'required|exists:tb_linha_tempo,id',
            'data' => 'required|date',
            'titulo' => 'required|string|max:255',
            'conteudo' => 'required|string',
        ];
    }
}
