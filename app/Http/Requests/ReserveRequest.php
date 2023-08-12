<?php

namespace App\Http\Requests;

use App\Models\Permission;
use App\Models\Reserve;
use Illuminate\Foundation\Http\FormRequest;

class ReserveRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can(Permission::RESERVE_BOOKS_PERMISSION);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'book_id' => 'required|exists:books,id',
            'user_id' => 'required|exists:users,id',
            'reserved_date' => 'required|date|before_or_equal:+' . Reserve::MAX_RESERVATION_DAYS . ' days'
        ];
    }
}
