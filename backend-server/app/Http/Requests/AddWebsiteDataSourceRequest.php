<?php

namespace App\Http\Requests;



use Illuminate\Foundation\Http\FormRequest;

class AddWebsiteDataSourceRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'website' => 'required|string|url',
        ];
    }
    public function getWebsite(): string
    {
        return $this->get('website');
    }

}
