<x-head.tinymce-config/>

<x-layout>
    <x-setting heading="Publish New Post">
        <form method="POST" action="/admin/posts" enctype="multipart/form-data" id="form">
            @csrf

            <x-form.input name="title" required/>
            <x-form.input name="slug" required/>
            <x-form.input name="thumbnail" type="file" required/>
            <x-form.textarea name="excerpt" class="markdown"
                      required>{{ old('excerpt') }}</x-form.textarea>
            <x-form.textarea name="body" class="markdown"
                        required>{{ old('body') }}</x-form.textarea>

            <x-form.field>
                <x-form.label name="category"/>
                <select name="category_id" id="category" class="border border-gray-400 p-2 rounded w-1/3" required>
                    @foreach ($categories as $category)
                        <option
                            value="{{ $category->id }}"
                            {{ old('category_id') == $category->id ? 'selected' : '' }}
                        >{{ ucwords($category->name) }}</option>
                    @endforeach
                </select>
                <x-form.error name="category"/>
            </x-form.field>

            <div x-data="{ showDateInput: false, dateValue: null }">
                <x-form.field>
                    <input class="mr-1" type="checkbox" id="publish" checked autocomplete="off"
                           x-on:click="showDateInput = !showDateInput">
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

                <button type="submit" class="bg-gray-200 text-gray-600 uppercase font-semibold text-xs py-1.5 px-4 rounded-2xl hover:bg-gray-300 ml-auto mr-20">
                    Save as Draft
                </button>
            </div>
        </form>
    </x-setting>
</x-layout>
