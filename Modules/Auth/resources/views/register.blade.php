<x-layout.auth title="Create Account">
    <x-ui.card>
        <x-slot:header>
            <div class="text-center py-3">
                <h3 class="fw-bold text-primary mb-1">Create Account</h3>
                <p class="text-muted mb-0">Join us to start shopping</p>
            </div>
        </x-slot:header>

        <form method="POST" action="{{ route('register.post') }}">
            @csrf

            <div class="mb-3">
                <x-form.label for="name" required>Full Name</x-form.label>
                <x-form.input id="name" type="text" name="name" :value="old('name')" required autofocus />
                <x-form.input-error :messages="$errors->get('name')" />
            </div>

            <div class="mb-3">
                <x-form.label for="email" required>Email Address</x-form.label>
                <x-form.input id="email" type="email" name="email" :value="old('email')" required />
                <x-form.input-error :messages="$errors->get('email')" />
            </div>

            <div class="mb-3">
                <x-form.label for="mobile_number">Mobile Number <span class="text-muted fw-normal">(Optional)</span></x-form.label>
                <x-form.input id="mobile_number" type="tel" name="mobile_number" :value="old('mobile_number')" />
                <x-form.input-error :messages="$errors->get('mobile_number')" />
            </div>

            <div class="mb-3">
                <x-form.label for="password" required>Password</x-form.label>
                <x-form.input id="password" type="password" name="password" required />
                <x-form.input-error :messages="$errors->get('password')" />
            </div>

            <div class="mb-4">
                <x-form.label for="password_confirmation" required>Confirm Password</x-form.label>
                <x-form.input id="password_confirmation" type="password" name="password_confirmation" required />
            </div>

            <x-ui.button type="submit" variant="primary" block>Create Account</x-ui.button>
        </form>

        <div class="mt-4 text-center">
            <p class="text-muted">Already have an account? <a href="{{ route('login') }}" class="text-decoration-none fw-medium">Log In</a></p>
        </div>
    </x-ui.card>
</x-layout.auth>
