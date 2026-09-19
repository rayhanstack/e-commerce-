<x-layout.admin title="Component Gallery">
    <x-slot:topbar>
        <div class="navbar navbar-light bg-white shadow-sm px-4 py-3 mb-4 border-bottom">
            <h4 class="mb-0">Component Gallery</h4>
        </div>
    </x-slot:topbar>

    <div class="row">
        <div class="col-12 mb-4">
            <x-ui.card>
                <x-slot:header>Buttons</x-slot:header>
                <div class="d-flex gap-2 mb-3">
                    <x-ui.button variant="primary">Primary</x-ui.button>
                    <x-ui.button variant="secondary">Secondary</x-ui.button>
                    <x-ui.button variant="success">Success</x-ui.button>
                    <x-ui.button variant="danger">Danger</x-ui.button>
                    <x-ui.button variant="warning">Warning</x-ui.button>
                    <x-ui.button variant="info">Info</x-ui.button>
                    <x-ui.button variant="light">Light</x-ui.button>
                    <x-ui.button variant="dark">Dark</x-ui.button>
                </div>
                <div class="d-flex gap-2">
                    <x-ui.button variant="primary" size="sm">Small</x-ui.button>
                    <x-ui.button variant="primary" size="md">Medium</x-ui.button>
                    <x-ui.button variant="primary" size="lg">Large</x-ui.button>
                    <x-ui.button variant="primary" disabled>Disabled</x-ui.button>
                </div>
            </x-ui.card>
        </div>

        <div class="col-md-6 mb-4">
            <x-ui.card>
                <x-slot:header>Form Inputs</x-slot:header>
                
                <div class="mb-3">
                    <x-form.label required>Standard Input</x-form.label>
                    <x-form.input placeholder="Enter something..." />
                </div>

                <div class="mb-3">
                    <x-form.label>Input with Error</x-form.label>
                    <x-form.input error value="Invalid value" />
                    <x-form.input-error :messages="['This field is required.']" />
                </div>
            </x-ui.card>
        </div>

        <div class="col-md-6 mb-4">
            <x-ui.card>
                <x-slot:header>Card Layout</x-slot:header>
                <p>This is a standard card component with header and footer slots.</p>
                <x-slot:footer>
                    <x-ui.button variant="secondary" size="sm">Action</x-ui.button>
                </x-slot:footer>
            </x-ui.card>
        </div>
    </div>
</x-layout.admin>
