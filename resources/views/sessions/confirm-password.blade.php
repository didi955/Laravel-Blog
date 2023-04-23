<x-layout>
    <section class="px-6 py-8">
        <x-panel class="max-w-xl mx-auto mt-10">
            <h1 class="text-center font-bold text-xl">Confirm Your Password</h1>
            <p class="text-center text-sm mt-4">
                Please confirm your password before continuing.
            </p>
            <form method="POST" action="/confirm-password" class="mt-10">
                @csrf
                <x-form.input name="password" type="password" required>Password</x-form.input>
                <x-form.button>Confirm</x-form.button>
            </form>
        </x-panel>
    </section>
</x-layout>
