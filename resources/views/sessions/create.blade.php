<x-layout>
    <section class="px-6 py-8">
        <main class="max-w-lg mx-auto mt-12">
            <x-panel>
                <h1 class="text-center font-bold text-xl">Log In !</h1>

                <form method="POST" action="/login" class="mt-10">
                    @csrf

                    <x-form.input name="email" type="email" autocomplete="username" required>Email</x-form.input>
                    <x-form.input name="password" type="password" autocomplete="current-password" required>Password</x-form.input>

                    <x-form.field>
                        <input class="mr-1" type="checkbox" name="remember" id="remember">
                        <label class="text-xs text-gray-700 font-bold uppercase" for="remember">
                            Remember Me
                        </label>
                    </x-form.field>

                    <x-form.button>Log In</x-form.button>
                </form>

                <div class="mt-10 text-center">
                    <a class="text-xs text-blue-500 hover:underline" href="{{ route('password.request') }}">Forgot Your Password?</a>
                </div>
            </x-panel>
        </main>
    </section>
</x-layout>
