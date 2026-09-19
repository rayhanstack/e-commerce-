<x-layout.admin title="Thermal Barcode Label Generator">
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            .printable-sticker-area, .printable-sticker-area * {
                visibility: visible;
            }
            .printable-sticker-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .no-print {
                display: none !important;
            }
            .sticker-card {
                page-break-inside: avoid;
                break-inside: avoid;
                margin-bottom: 5px;
            }
        }

        .sticker-card {
            width: 220px;
            height: 130px;
            border: 1px dashed #cbd5e1;
            padding: 8px;
            background: #fff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            text-align: center;
            font-family: sans-serif;
            overflow: hidden;
        }

        .sticker-title {
            font-size: 11px;
            font-weight: bold;
            line-height: 1.1;
            max-height: 24px;
            overflow: hidden;
        }

        .sticker-variant {
            font-size: 9px;
            color: #64748b;
        }

        .sticker-price {
            font-size: 12px;
            font-weight: 800;
            color: #0f172a;
        }

        .sticker-barcode svg {
            max-width: 100%;
            height: 38px;
        }

        .sticker-code {
            font-family: monospace;
            font-size: 9px;
            letter-spacing: 1px;
        }
    </style>

    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">Barcode & Thermal Label Studio</h1>
            <p class="text-muted small mb-0">Generate SVG Code128 & QR stickers for thermal roll printers.</p>
        </div>
        @if(count($labelItems) > 0)
            <button onclick="window.print()" class="btn btn-success px-4 fw-bold shadow-sm d-inline-flex align-items-center gap-2">
                <i class="fa-solid fa-print"></i> Print Labels ({{ count($labelItems) }})
            </button>
        @endif
    </div>

    <div class="row g-4 no-print mb-4">
        <div class="col-md-12">
            <x-ui.card>
                <form action="{{ route('admin.barcode.print') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <x-form.label for="product_id" :required="true">Select Product to Print</x-form.label>
                        <select name="product_id" id="product_id" class="form-select" required>
                            <option value="">-- Choose Product --</option>
                            @foreach($products as $prod)
                                <option value="{{ $prod->id }}" {{ request('product_id') == $prod->id ? 'selected' : '' }}>
                                    {{ $prod->name }} (SKU: {{ $prod->sku }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <x-form.label for="quantity">Sticker Copies</x-form.label>
                        <input type="number" name="quantity" id="quantity" class="form-control" value="{{ request('quantity', 4) }}" min="1" max="100">
                    </div>

                    <div class="col-md-4">
                        <x-ui.button type="submit" variant="primary" class="w-100 fw-bold d-inline-flex align-items-center justify-content-center gap-2">
                            <i class="fa-solid fa-qrcode"></i> Generate Preview
                        </x-ui.button>
                    </div>
                </form>
            </x-ui.card>
        </div>
    </div>

    <!-- Live Sticker Sheet Preview -->
    @if(count($labelItems) > 0)
        <div class="printable-sticker-area">
            <x-ui.card>
                <h6 class="fw-bold mb-3 no-print text-muted d-flex align-items-center gap-2">
                    <i class="fa-solid fa-eye text-primary"></i> Label Sheet Preview (50mm x 25mm Thermal Roll Layout):
                </h6>
                <div class="d-flex flex-wrap gap-3 justify-content-start">
                    @foreach($labelItems as $item)
                        <div class="sticker-card rounded shadow-sm">
                            <div class="sticker-title text-uppercase text-secondary">{{ config('app.name', 'STORE') }}</div>
                            <div class="sticker-title text-truncate w-100 text-dark">{{ $item['title'] }}</div>
                            @if($item['variant'])
                                <div class="sticker-variant">{{ $item['variant'] }}</div>
                            @endif
                            
                            <div class="sticker-barcode my-1">
                                {!! $item['barcodeSvg'] !!}
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center w-100 px-1">
                                <span class="sticker-code text-muted">{{ $item['barcode'] }}</span>
                                <span class="sticker-price">৳{{ $item['price'] }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-ui.card>
        </div>
    @else
        <div class="text-center py-5 no-print card-modern p-5">
            <i class="fa-solid fa-barcode display-1 text-light-emphasis mb-3 d-block"></i>
            <h5 class="fw-bold text-secondary">No Barcode Preview Loaded</h5>
            <p class="text-muted small">Select a product above and click "Generate Preview" to render thermal barcode stickers.</p>
        </div>
    @endif
</x-layout.admin>
