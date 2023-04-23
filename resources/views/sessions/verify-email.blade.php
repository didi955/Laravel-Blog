<x-layout>
    <section class="px-6 py-8">
        <x-panel class="max-w-xl mx-auto mt-10">
            <h1 class="text-center font-bold text-xl">Verify Your Email Address</h1>
            <p class="text-center text-sm mt-4">
                Before proceeding, please check your email for a verification link.
                If you did not receive the email,
                <form method="POST" action="{{ route('verification.send') }}" class="inline ">
                    @csrf
                    <x-form.button>click here to request another</x-form.button>
                </form>
            </p>
        </x-panel>
    </section>
</x-layout>
