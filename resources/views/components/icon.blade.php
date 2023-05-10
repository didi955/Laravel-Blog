@props(['name'])

@if ($name === 'down-arrow')
    <svg {{ $attributes(['class' => 'transform -rotate-90']) }} width="22"
         height="22" viewBox="0 0 22 22">
        <g fill="none" fill-rule="evenodd">
            <path stroke="#000" stroke-opacity=".012" stroke-width=".5" d="M21 1v20.16H.84V1z">
            </path>
            <path fill="#222"
                  d="M13.854 7.224l-3.847 3.856 3.847 3.856-1.184 1.184-5.04-5.04 5.04-5.04z"></path>
        </g>
    </svg>
@elseif($name === 'up-arrow')
    <svg {{ $attributes(['class' => 'transform rotate-90']) }} width="22"
         height="22" viewBox="0 0 22 22">
        <g fill="none" fill-rule="evenodd">
            <path stroke="#000" stroke-opacity=".012" stroke-width=".5" d="M21 1v20.16H.84V1z">
            </path>
            <path fill="#222"
                  d="M13.854 7.224l-3.847 3.856 3.847 3.856-1.184 1.184-5.04-5.04 5.04-5.04z"></path>
        </g>
    </svg>
@elseif($name === 'bookmark')
    <svg width="24" height="24" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M6 6C6 4.89543 6.89543 4 8 4H16C17.1046 4 18 4.89543 18 6V21L12 15L6 21V6Z" stroke="#000000"
              stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
@elseif($name === 'bookmarked')
    <svg width="24" height="24" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path
            d="M5 6c0-1.4 0-2.1.272-2.635a2.5 2.5 0 0 1 1.093-1.093C6.9 2 7.6 2 9 2h6c1.4 0 2.1 0 2.635.272a2.5 2.5 0 0 1 1.092 1.093C19 3.9 19 4.6 19 6v13.208c0 1.056 0 1.583-.217 1.856a1 1 0 0 1-.778.378c-.349.002-.764-.324-1.593-.976L12 17l-4.411 3.466c-.83.652-1.245.978-1.594.976a1 1 0 0 1-.778-.378C5 20.791 5 20.264 5 19.208V6z"
            fill="#000000"/>
    </svg>
@endif
