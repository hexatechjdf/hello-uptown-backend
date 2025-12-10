<?php


namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DiningRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Admin middleware will protect routes
    }

    public function rules(): array
    {
        return [
            'heading' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|file|image|max:4096',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'contact_number' => 'nullable|string|max:20',
            'price' => 'nullable|numeric|min:0',
            'address' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'date' => 'nullable|date',
            'day' => 'nullable|string|max:20',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'status' => 'nullable|in:active,draft,expired',
        ];
    }
}

?>