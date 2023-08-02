<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Post;
use Elegant\Sanitizer\Laravel\SanitizesInput;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

/**
 * @property-read string $post
 */
class PostRequest extends FormRequest
{
    use SanitizesInput;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $post = Post::where('slug', $this->route('post'))->first();

        return [
            'title' => 'required',
            'thumbnail' => $post && $post->exists ? ['image', 'max:1024'] : 'required|image|max:1024',
            'excerpt' => 'required',
            'body' => 'required',
            'category_id' => [Rule::exists('categories', 'id')],
            'published_at' => ['nullable', 'date', 'after:now', 'date_format:Y-m-d\TH:i'],
        ];
    }

    /**
     * @return array<string, \Elegant\Sanitizer\Contracts\Filter|string>
     */
    public function filters(): array
    {
        return [
            'title' => 'trim|strip_tags|cast:string',
            'excerpt' => 'trim|cast:string|strip_tags',
            'body' => 'trim|cast:string|escape_script_tag',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [

        ];
    }
}
