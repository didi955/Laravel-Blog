<x-layout>
    <section class="px-6 py-8">
        <x-panel class="max-w-xl mx-auto mt-10">
            <h1 class="text-center font-bold text-xl">Reset Your Password</h1>
            <p class="text-center text-sm mt-4">
                Please enter your new password.
            </p>
            <form method="POST" action="/reset-password" class="mt-10">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <x-form.input name="email" type="email" value="{{ old('email', request()->email) }}" required>Email</x-form.input>
                <x-form.input name="password" type="password" required>New Password</x-form.input>
                <x-form.input name="password_confirmation" type="password" required>Password Confirmation</x-form.input>
                <x-form.button>Reset Password</x-form.button>
            </form>
        </x-panel>
    </section>
</x-layout>
