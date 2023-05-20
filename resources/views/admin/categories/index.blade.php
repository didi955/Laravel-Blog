<x-layout>
    <x-setting heading="Manage Categories">
        <div class="flex flex-col">
            <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                    <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($categories as $category)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 mr-2">
                                                <x-category-button :category="$category" />
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <form method="POST" action="/admin/categories/{{ $category->slug }}">
                                            @csrf
                                            @method('DELETE')

                                            <button class="text-xs text-red-600 font-semibold">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-4">
            {{ $categories->links() }}
        </div>
        <div>
            <h2 class="text-lg font-bold mb-4">Add New Category</h2>

            <form method="POST" action="/admin/categories" class="max-w-4xl mx-auto">
                @csrf
                <x-form.input name="name" />
                <x-form.button>Add</x-form.button>
            </form>
        </div>
    </x-setting>

</x-layout>
