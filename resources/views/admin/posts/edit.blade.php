@props(['post', 'categories'])

<x-head.tinymce-config/>

<x-layout>
    <x-setting :heading="'Edit Post: ' . $post->title">
        <form method="POST" action="/admin/posts/{{ $post->slug }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <x-form.input name="title" :value="old('title', $post->title)" required />

            <div class="flex mt-6">
                <div class="flex-1">
                    <x-form.input name="thumbnail" type="file" :value="old('thumbnail', $post->thumbnail)" />
                </div>

                <img src="{{ asset('storage/' . $post->thumbnail) }}" alt="" class="rounded-xl ml-6" width="100"/>
            </div>

            <x-form.textarea name="excerpt" class="markdown"
                             required>{{ old('excerpt', $post->excerpt) }}</x-form.textarea>
            <x-form.textarea name="body" class="markdown"
                             required>{{ old('body', $post->body) }}</x-form.textarea>

            <x-form.field>
                <x-form.label name="category"/>

                <select name="category_id" id="category_id" class="border border-gray-400 p-2 rounded w-1/3">
                    @foreach ($categories as $category)
                        <option
                            value="{{ $category->id }}"
                            {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}
                        >{{ ucwords($category->name) }}</option>
                    @endforeach
                </select>

                <x-form.error name="category"/>
            </x-form.field>

            <div x-data="{ showDateInput: {{ $post->isPublished() && !$errors->has('published_at') ? 'false' : 'true' }}, dateValue: '{{ $post->published_at->startOfMinute() ?? '' }}' }">
                <x-form.field>
                    <input class="mr-1" type="checkbox" id="publish"  {{ $post->isPublished() && !$errors->has('published_at') ? 'checked' : '' }} autocomplete="off" x-on:click="showDateInput = !showDateInput">
                    <label class="text-sm text-gray-700 font-bold uppercase" for="publish">
                        Publish Now
                    </label>
                </x-form.field>

                <template x-if="showDateInput">
                    <x-form.input type="datetime-local" name="published_at" class="mt-2" x-model="dateValue"/>
                </template>
            </div>
            <div class="flex items-center">
                <x-form.button>Publish</x-form.button>

                <button type="submit" formaction="/admin/posts/{{ $post->slug }}/draft" class="bg-gray-200 text-gray-600 uppercase font-semibold text-xs py-1.5 px-4 rounded-2xl hover:bg-gray-300 ml-auto mr-20">
                    Save as Draft
                </button>
            </div>
        </form>
    </x-setting>
</x-layout>
