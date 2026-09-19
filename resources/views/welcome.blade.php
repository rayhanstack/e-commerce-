<x-layout.storefront>
    <x-slot:header>
        <div class="bg-primary text-white text-center py-5">
            <h1>Welcome to the Storefront</h1>
            <p>Phase 1 Foundation Setup Complete</p>
        </div>
    </x-slot:header>

    <div class="container py-5">
        <div class="row">
            <div class="col-md-6 offset-md-3 text-center">
                <x-ui.card>
                    <x-slot:header>Component Test</x-slot:header>
                    <p>This is a test of our new component library.</p>
                    <x-ui.button variant="success">Test Button</x-ui.button>
                </x-ui.card>
            </div>
        </div>
    </div>
</x-layout.storefront>
