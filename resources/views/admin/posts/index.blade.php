<x-layout>
    <x-setting heading="Manage Posts">
        <div class="flex flex-col">
            <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                    <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($posts as $post)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 mr-2">
                                                <img class="h-10 w-10 rounded-full" src="{{ asset('storage/' . $post->thumbnail) }}" alt="thumbnail">
                                            </div>
                                            <div class="text-sm font-medium text-gray-900 truncate block max-w-xs">
                                                <a href="/posts/{{ $post->slug }}">
                                                    {{ $post->title }}
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-2 whitespace-nowrap">
                                        <div class="px-1.5 py-0.5 border border-blue-300 rounded-full text-blue-300 text-xs uppercase font-semibold">{{ $post->category->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($post->isPublished())
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Published : {{ $post->published_at->format('F j, Y, g:i a') }}
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Published in : {{ $post->published_at->diffForHumans() }}
                                            </span>

                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium font-semibold">
                                        <a href="/admin/posts/{{ $post->id }}/edit" class="text-blue-500 hover:text-blue-600">Edit</a>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <form method="POST" action="/admin/posts/{{ $post->id }}">
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
            {{ $posts->links() }}
        </div>
    </x-setting>
</x-layout>
