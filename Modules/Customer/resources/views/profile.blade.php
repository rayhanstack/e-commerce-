<x-layout.storefront title="My Profile">
    <x-slot:header>
        <div class="bg-light border-bottom py-4">
            <div class="container">
                <h2 class="mb-0">My Account</h2>
            </div>
        </div>
    </x-slot:header>

    <div class="container py-5">
        <div class="row">
            <div class="col-md-3">
                <x-ui.card>
                    <div class="list-group list-group-flush">
                        <a href="#" class="list-group-item list-group-item-action active">My Profile</a>
                        <a href="#" class="list-group-item list-group-item-action">Orders</a>
                        <a href="#" class="list-group-item list-group-item-action">Address Book</a>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="list-group-item list-group-item-action text-danger">Logout</button>
                        </form>
                    </div>
                </x-ui.card>
            </div>
            <div class="col-md-9">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <x-ui.card>
                    <x-slot:header>Profile Details</x-slot:header>
                    <form action="{{ route('customer.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <x-form.label required>Full Name</x-form.label>
                                <x-form.input name="name" :value="$user->name" required />
                            </div>
                            <div class="col-md-6 mb-3">
                                <x-form.label required>Email</x-form.label>
                                <x-form.input name="email" :value="$user->email" disabled />
                                <small class="text-muted">Email cannot be changed here.</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <x-form.label>Mobile Number</x-form.label>
                                <x-form.input name="mobile_number" :value="$user->mobile_number" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <x-form.label>Date of Birth</x-form.label>
                                <x-form.input type="date" name="dob" :value="$user->dob" />
                            </div>
                        </div>
                        <x-ui.button type="submit" variant="primary">Save Changes</x-ui.button>
                    </form>
                </x-ui.card>

                <x-ui.card class="mt-4">
                    <x-slot:header>Address Book</x-slot:header>
                    @if($addresses->isEmpty())
                        <p class="text-muted">You haven't saved any addresses yet.</p>
                    @else
                        <ul class="list-group">
                            @foreach($addresses as $address)
                                <li class="list-group-item">
                                    <strong>{{ $address->name }}</strong><br>
                                    {{ $address->address_line_1 }}, {{ $address->city }}
                                </li>
                            @endforeach
                        </ul>
                    @endif
                    <x-ui.button variant="outline-primary" size="sm" class="mt-3">Add New Address</x-ui.button>
                </x-ui.card>
            </div>
        </div>
    </div>
</x-layout.storefront>
