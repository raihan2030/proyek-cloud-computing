<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="border-b border-outline-variant pb-sm mb-6 flex items-center gap-2">
        <span class="material-symbols-outlined text-primary">login</span>
        <h3 class="font-semibold text-primary">Access Your Account</h3>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block font-mono text-[10px] text-on-surface-variant mb-1.5 uppercase">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" 
                   class="w-full text-sm bg-surface-container-lowest border border-outline-variant rounded p-2 text-primary focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs text-error" />
        </div>

        <div>
            <label for="password" class="block font-mono text-[10px] text-on-surface-variant mb-1.5 uppercase">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password" 
                   class="w-full text-sm bg-surface-container-lowest border border-outline-variant rounded p-2 text-primary focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs text-error" />
        </div>

        <div class="flex items-center justify-between mt-2">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" name="remember" class="rounded border-outline-variant bg-surface-container-lowest text-primary focus:ring-primary">
                <span class="ms-2 text-xs font-mono text-on-surface-variant">Remember me</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-xs font-mono text-on-surface-variant hover:text-primary hover:underline transition-colors" href="{{ route('password.request') }}">
                    Forgot password?
                </a>
            @endif
        </div>

        <div class="pt-4">
            <button type="submit" class="btn-primary w-full py-2.5 text-sm rounded font-semibold uppercase tracking-wider flex justify-center items-center gap-2">
                Log in <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </button>
        </div>

        <div class="text-center mt-6 pt-4 border-t border-outline-variant/50">
            <span class="text-xs text-on-surface-variant">Belum punya akun?</span>
            <a href="{{ route('register') }}" class="text-xs text-primary font-semibold hover:underline ml-1">Daftar sekarang</a>
        </div>
    </form>
</x-guest-layout>