@props(['heading'])

<section class="py-8 max-w-5xl mx-auto">
    <h1 class="text-lg font-bold mb-6 pb-8 border-b">{{ $heading }}</h1>

    <x-panel>
        <div class="flex">
            <aside class="w-48 flex-shrink-0">
                <h4 class="font-semibold mb-4">Dashboard</h4>
                <ul>
                    <li>
                        <a href="/profile" class="{{ request()->routeIs('profile') ? 'text-blue-500' : ''}}">Profile</a>
                    </li>
                    <li>
                        <a href="/my-bookmarks"
                           class="{{ request()->routeIs('my-bookmarks') ? 'text-blue-500' : ''}}">My Bookmarks</a>
                    </li>
                </ul>
                <h4 class="font-semibold mb-4 mt-4">Administration</h4>
                @admin
                <ul>
                    <li>
                        <a href="/admin/posts"
                           class="{{ request()->routeIs('admin.posts.index') ? 'text-blue-500' : ''}}">All Posts</a>
                    </li>
                    <li>
                        <a href="/admin/posts/create"
                           class="{{ request()->routeIs('admin.posts.create') ? 'text-blue-500' : '' }}">New Post</a>
                    </li>
                    {{--}}
                    <li>
                        <a href="/admin/categories"
                           class="{{ request()->routeIs('admin.categories.index') ? 'text-blue-500' : '' }}">Categories</a>
                    </li>
                    <li>
                        <a href="/admin/users"
                           class="{{ request()->routeIs('admin.users.index') ? 'text-blue-500' : '' }}">Users</a>
                    </li>
                    {{--}}
                </ul>
                @endadmin
            </aside>

            <main class="flex-1">
                {{ $slot }}
            </main>
        </div>
    </x-panel>

</section>
