<x-layout>
    <section class="px-6 py-8">
        <main class="max-w-lg mx-auto mt-12 bg-gray-100 border border-gray-200 rounded-xl p-8">
            <h1 class="text-center font-bold text-xl">Register !</h1>

            <form method="POST" action="/register" class="mt-10">
                @csrf

                <x-form.input name="firstname" required>First Name</x-form.input>
                <x-form.input name="lastname" required>Last Name</x-form.input>
                <x-form.input name="username" required>Username</x-form.input>
                <x-form.input name="email" type="email" autocomplete="username" required>Email</x-form.input>
                <x-form.input name="password" type="password" required autocomplete="new-password">Password</x-form.input>
                <x-form.input name="password_confirmation" type="password" autocomplete="new-password" required>Password Confirmation</x-form.input>

                <x-form.button>Register</x-form.button>

            </form>
        </main>
    </section>
</x-layout>
