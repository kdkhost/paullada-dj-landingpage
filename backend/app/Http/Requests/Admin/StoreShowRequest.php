<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreShowRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'data_evento' => 'required|date',
            'hora_evento' => 'required|string|max:10',
            'local' => 'required|string|max:255',
            'cidade' => 'required|string|max:255',
            'ingressos_disponiveis' => 'nullable|integer|min:0',
            'preco_ingresso' => 'nullable|numeric|min:0',
            'link_ingresso' => 'nullable|url|max:500',
            'status' => 'nullable|in:active,inactive,done',
            'destaque' => 'nullable|boolean',
            'foto_cartaz' => 'nullable|image|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'titulo.required' => 'O título é obrigatório.',
            'descricao.required' => 'A descrição é obrigatória.',
            'data_evento.required' => 'A data do evento é obrigatória.',
            'data_evento.date' => 'A data do evento deve ser uma data válida.',
            'hora_evento.required' => 'A hora do evento é obrigatória.',
            'local.required' => 'O local é obrigatório.',
            'cidade.required' => 'A cidade é obrigatória.',
            'ingressos_disponiveis.integer' => 'Ingressos disponíveis deve ser um número inteiro.',
            'ingressos_disponiveis.min' => 'Ingressos disponíveis não pode ser negativo.',
            'preco_ingresso.numeric' => 'O preço do ingresso deve ser um valor numérico.',
            'preco_ingresso.min' => 'O preço do ingresso não pode ser negativo.',
            'foto_cartaz.image' => 'O arquivo deve ser uma imagem.',
            'foto_cartaz.max' => 'A imagem não pode ultrapassar 2MB.',
        ];
    }
}
