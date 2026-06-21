<x-guest-layout>
    <div class="border-b border-outline-variant pb-sm mb-6 flex items-center gap-2">
        <span class="material-symbols-outlined text-primary">person_add</span>
        <h3 class="font-semibold text-primary">Create New Account</h3>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <label for="name" class="block font-mono text-[10px] text-on-surface-variant mb-1.5 uppercase">Full Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" 
                   class="w-full text-sm bg-surface-container-lowest border border-outline-variant rounded p-2 text-primary focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-xs text-error" />
        </div>

        <div>
            <label for="email" class="block font-mono text-[10px] text-on-surface-variant mb-1.5 uppercase">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" 
                   class="w-full text-sm bg-surface-container-lowest border border-outline-variant rounded p-2 text-primary focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs text-error" />
        </div>

        <div>
            <label for="password" class="block font-mono text-[10px] text-on-surface-variant mb-1.5 uppercase">Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password" 
                   class="w-full text-sm bg-surface-container-lowest border border-outline-variant rounded p-2 text-primary focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs text-error" />
        </div>

        <div>
            <label for="password_confirmation" class="block font-mono text-[10px] text-on-surface-variant mb-1.5 uppercase">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" 
                   class="w-full text-sm bg-surface-container-lowest border border-outline-variant rounded p-2 text-primary focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-xs text-error" />
        </div>

        <div class="pt-4">
            <button type="submit" class="btn-primary w-full py-2.5 text-sm rounded font-semibold uppercase tracking-wider flex justify-center items-center gap-2">
                Register <span class="material-symbols-outlined text-sm">how_to_reg</span>
            </button>
        </div>

        <div class="text-center mt-6 pt-4 border-t border-outline-variant/50">
            <span class="text-xs text-on-surface-variant">Sudah memiliki akun?</span>
            <a href="{{ route('login') }}" class="text-xs text-primary font-semibold hover:underline ml-1">Masuk di sini</a>
        </div>
    </form>
</x-guest-layout>