<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class UploadJsonFilesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
          'jsonfiles' => 'required',
        ];
    }
}
