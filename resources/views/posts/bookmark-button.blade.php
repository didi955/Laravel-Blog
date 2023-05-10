@props(['post'])

@if(auth()->user()->hasBookmarked($post))
    <form action="/bookmarks/{{ $post->slug }}" method="POST" class="inline-block">
        @csrf
        @method('DELETE')
        <button type="submit"><x-icon name="bookmarked"></x-icon></button>
    </form>
@else
    <form action="/bookmarks/{{ $post->slug }}" method="POST" class="inline-block">
        @csrf
        <button type="submit"><x-icon name="bookmark"></x-icon></button>
    </form>
@endif
