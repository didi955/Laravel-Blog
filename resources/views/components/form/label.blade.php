@props(['name', 'for' => null])

<label class="block mb-2 uppercase font-bold text-xs text-gray-700"
       for="{{ $for ?? $name }}"
>
    {{ ucwords($name) }}
</label>

