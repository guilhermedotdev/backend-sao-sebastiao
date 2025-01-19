<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateArtigoRequest extends FormRequest
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
            'titulo' => 'nullable|string|max:255',
            'url_imagem' => 'nullable|url|max:255',
            'conteudo' => 'nullable|string',
            'fk_id_usuario' => 'nullable|exists:usuarios,id',
            'data_liturgia' => 'nullable|date',
        ];
    }
}
