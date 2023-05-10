<x-layout>
    <section class="px-6 py-8">
        <x-panel class="max-w-xl mx-auto mt-10">
            <h1 class="text-center font-bold text-xl">Forgot Your Password?</h1>
            <p class="text-center text-sm mt-4">
                No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
            </p>
            <form method="POST" action="{{ route('password.email') }}" class="mt-10">
                @csrf
                <x-form.input name="email" type="email" autocomplete="username" required>Email</x-form.input>
                <x-form.button class-field="text-center">Send Password Reset Link</x-form.button>
            </form>
        </x-panel>
    </section>
</x-layout>
