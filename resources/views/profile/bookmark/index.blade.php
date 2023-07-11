<x-layout>
    <x-setting heading="My Bookmarks">
        <div class="flex flex-col">
            <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                    <div class="shadow overflow-hidden sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($bookmarks as $bookmark)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 mr-2">
                                                <img class="h-10 w-10 rounded-full" src="{{ asset('storage/' . $bookmark->post->thumbnail) }}" alt="post thumbnail">
                                            </div>
                                            <div class="text-sm font-medium text-gray-900 truncate block max-w-xs">
                                                <a href="/posts/{{ $bookmark->post->slug }}">
                                                    {{ $bookmark->post->title }}
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-sm leading-5 font-semibold text-gray-900">
                                                Author : {{ $bookmark->post->author->username }}
                                            </span>
                                    </td>
                                    <td class="px-2 whitespace-nowrap">
                                        <x-category-button :category="$bookmark->post->category" />
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <x-bookmark-button :post="$bookmark->post"/>
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
            {{ $bookmarks->links() }}
        </div>
    </x-setting>
</x-layout>
