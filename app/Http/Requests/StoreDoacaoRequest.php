<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDoacaoRequest extends FormRequest
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
            'nome' => 'required|string|max:255',
            'cpf' => 'required', // Validação CPF (certifique-se de ter a validação correta no Laravel)
            'email' => 'required|email|max:255', // Email único na tabela 'usuarios'
            'valor' => 'required|numeric|min:0', // Valor deve ser um número maior ou igual a 0
        ];
    }
}
