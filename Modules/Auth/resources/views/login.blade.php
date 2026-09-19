<x-layout.auth title="Log In">
    <x-ui.card>
        <x-slot:header>
            <div class="text-center py-3">
                <h3 class="fw-bold text-primary mb-1">Welcome Back</h3>
                <p class="text-muted mb-0">Sign in to your account</p>
            </div>
        </x-slot:header>

        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            <div class="mb-3">
                <x-form.label for="email" required>Email Address</x-form.label>
                <x-form.input id="email" type="email" name="email" :value="old('email')" required autofocus />
                <x-form.input-error :messages="$errors->get('email')" />
            </div>

            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <x-form.label for="password" required>Password</x-form.label>
                    <a href="#" class="text-decoration-none small">Forgot Password?</a>
                </div>
                <x-form.input id="password" type="password" name="password" required />
                <x-form.input-error :messages="$errors->get('password')" />
            </div>

            <div class="mb-4 form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember">Remember me</label>
            </div>

            <x-ui.button type="submit" variant="primary" block>Log In</x-ui.button>
        </form>

        <div class="mt-4 text-center">
            <p class="text-muted">Don't have an account? <a href="{{ route('register') }}" class="text-decoration-none fw-medium">Create Account</a></p>
        </div>
    </x-ui.card>
</x-layout.auth>
