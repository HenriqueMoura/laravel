<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRecordRequest extends FormRequest
{
  public function rules(): array
  {
    return [
      'title' => ['required', 'string', 'max:255'],
      'description' => ['required', 'string'],
      'status' => ['required', 'in:aberto,em progresso,resolvido'],
      'category_id' => ['required', 'exists:categories,id'],
    ];
  }
}

