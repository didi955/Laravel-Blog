<x-layout>
    <x-setting heading="Profile">
        <form method="POST" action="" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <div>
                <h4 class="font-bold text-xl mb-4 pb-4 border-b">Your Informations</h4>

                <x-form.input name="firstname" :value="auth()->user()->firstname" required/>
                <x-form.input name="lastname" :value="auth()->user()->lastname" required/>
                <x-form.input name="username" :value="auth()->user()->username" required/>
                <x-form.input name="email" type="email" :value="auth()->user()->email" autocomplete="username" required/>

                <div class="flex mt-6">
                    <div class="flex-1">
                        <x-form.input name="avatar" type="file" :value="old('avatar', auth()->user()->avatar)" />
                    </div>

                    <img src="{{ auth()->user()->getAvatarAsset() }}" alt="" class="rounded-xl ml-6" width="100">
                </div>
            </div>
            <div>
                <h4 class="font-bold text-xl mb-4 pt-8 pb-4 border-b">Security</h4>

                <x-form.input name="password" type="password" autocomplete="current-password"/>
                <x-form.input name="password_confirmation" type="password" autocomplete="current-password"/>
            </div>

            <x-form.button>Update</x-form.button>

        </form>
    </x-setting>
</x-layout>
