<x-layout.auth title="Admin Login">
    <div class="text-center mb-4">
        <h2 class="fw-bold text-primary">Admin Panel</h2>
        <p class="text-muted">Sign in to manage the store</p>
    </div>

    <x-ui.card>
        <form method="POST" action="{{ route('admin.login.post') }}">
            @csrf

            <div class="mb-3">
                <x-form.label for="email" required>Email Address</x-form.label>
                <x-form.input id="email" type="email" name="email" :value="old('email')" required autofocus />
                <x-form.input-error :messages="$errors->get('email')" />
            </div>

            <div class="mb-4">
                <x-form.label for="password" required>Password</x-form.label>
                <x-form.input id="password" type="password" name="password" required />
                <x-form.input-error :messages="$errors->get('password')" />
            </div>
            
            <div class="mb-4 form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember">Remember me</label>
            </div>

            <x-ui.button type="submit" variant="dark" block>Secure Log In</x-ui.button>
        </form>
    </x-ui.card>
</x-layout.auth>
