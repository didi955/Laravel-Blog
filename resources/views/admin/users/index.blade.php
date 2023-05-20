<x-layout>
    <x-setting heading="Manage Users">
        <div class="flex flex-col">
            <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                    <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($users as $user)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 mr-2">
                                                <img class="h-10 w-10 rounded-full" src="{{ $user->getAvatarAsset() }}" alt="avatar">
                                            </div>
                                            <div class="text-sm font-medium text-gray-900 truncate block max-w-xs">
                                                <p>
                                                    {{ $user->username }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-2 whitespace-nowrap">
                                        <p>
                                            {{ $user->firstname . ' ' . $user->lastname }}
                                        </p>
                                    </td>
                                    <td class="px-2 whitespace-nowrap">
                                        <p>
                                            {{ $user->email }}
                                        </p>
                                    </td>
                                    <td class="px-2 whitespace-nowrap">
                                        <p>
                                            {{ $user->role->name }}
                                        </p>
                                    </td>
                                    @if($user->id !== auth()->user()->id)
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="#" class="text-xs text-red-600 font-semibold">Ban</a>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </x-setting>
</x-layout>
