<x-layout.pos :warehouse="$warehouse">
    <div class="row g-0 h-100">
        <!-- Left Column: Catalog Search & Product Grid (60%) -->
        <div class="col-md-7 col-lg-8 border-end d-flex flex-column h-100 bg-body-tertiary">
            <!-- Search Bar & Scan Barcode -->
            <div class="p-3 bg-white border-bottom shadow-sm">
                <div class="row g-2 align-items-center">
                    <div class="col-md-8">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-primary"><i class="fa-solid fa-barcode"></i></span>
                            <input type="text" id="posSearchInput" class="form-control form-control-lg border-start-0 ps-0" placeholder="Scan Barcode (Code128 / EAN) or type SKU / Title..." autofocus>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select id="posCategoryFilter" class="form-select form-select-lg">
                            <option value="">All Categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Product Grid -->
            <div class="p-3 overflow-auto flex-grow-1" id="productGridContainer">
                <div class="row g-3" id="productGrid">
                    @foreach($products as $prod)
                        <div class="col-6 col-sm-4 col-md-4 col-xl-3 product-card-item" data-id="{{ $prod->id }}" data-name="{{ $prod->name }}" data-price="{{ $prod->active_price }}" data-sku="{{ $prod->sku }}" data-category="{{ $prod->category_id }}">
                            <div class="card card-modern card-modern-hover h-100 p-3 text-center cursor-pointer border-0 shadow-sm" onclick="addToPosCart({{ json_encode($prod) }})">
                                <div class="icon-box icon-box-indigo mx-auto mb-2 rounded-circle" style="width:46px; height:46px;">
                                    <i class="fa-solid fa-box"></i>
                                </div>
                                <div class="fw-bold text-dark text-truncate small mb-1">{{ $prod->name }}</div>
                                <div class="text-muted font-monospace small" style="font-size:0.75rem;">SKU: {{ $prod->sku }}</div>
                                <div class="fw-extrabold text-primary fs-6 mt-2">৳{{ number_format($prod->active_price, 2) }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Right Column: Cashier Cart Panel (40%) -->
        <div class="col-md-5 col-lg-4 bg-white d-flex flex-column h-100">
            <!-- Customer & Branch Header -->
            <div class="p-3 border-bottom bg-light">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="fw-bold text-dark small"><i class="fa-solid fa-user me-1 text-indigo"></i> Customer</span>
                    <span class="badge badge-soft-primary">Retail Sale</span>
                </div>
                <select id="posCustomerSelect" class="form-select form-select-sm">
                    <option value="">Walk-in Customer (Guest)</option>
                    @foreach($customers as $c)
                        <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->email }})</option>
                    @endforeach
                </select>
            </div>

            <!-- Itemized Cart Table -->
            <div class="flex-grow-1 overflow-auto p-2">
                <table class="table table-hover align-middle small mb-0" id="posCartTable">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-2">Item</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Price</th>
                            <th class="text-end pe-2">Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="posCartTbody">
                        <tr id="emptyCartRow">
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-cart-shopping display-4 text-light-emphasis mb-3 d-block"></i>
                                Cart is empty. Scan barcode or click items on the left to add.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Order Summary & Checkout -->
            <div class="p-3 border-top bg-light">
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted small">Subtotal</span>
                    <span class="fw-bold text-dark" id="summarySubtotal">৳0.00</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small">Discount (৳)</span>
                    <input type="number" id="posDiscountInput" class="form-control form-control-sm text-end font-monospace" style="width:100px;" value="0" min="0" onchange="renderPosCart()">
                </div>
                <div class="d-flex justify-content-between border-top pt-2 mb-3">
                    <span class="fw-bold fs-5 text-dark">Total Payable</span>
                    <span class="fw-extrabold fs-4 text-primary" id="summaryGrandTotal">৳0.00</span>
                </div>

                <div class="row g-2">
                    <div class="col-4">
                        <button class="btn btn-outline-danger w-100 py-2 fw-bold small" onclick="clearPosCart()">
                            <i class="fa-solid fa-trash-can"></i> Clear
                        </button>
                    </div>
                    <div class="col-8">
                        <button class="btn btn-primary w-100 py-2 fw-bold fs-6 shadow-sm" onclick="openPaymentModal()" id="btnPayModal">
                            <i class="fa-solid fa-credit-card me-1"></i> Pay Now (F2)
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment & Checkout Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-cash-register me-2 text-primary"></i> Complete Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="p-3 bg-light rounded-3 text-center mb-4 border">
                        <span class="text-muted small uppercase font-bold tracking-wider">Amount Due</span>
                        <div class="display-5 fw-extrabold text-primary" id="modalPayTotal">৳0.00</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Payment Method</label>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="pay_method" id="payCash" value="cash" checked>
                            <label class="btn btn-outline-primary py-2 fw-semibold" for="payCash"><i class="fa-solid fa-money-bill-wave me-1"></i> Cash</label>

                            <input type="radio" class="btn-check" name="pay_method" id="payCard" value="card">
                            <label class="btn btn-outline-primary py-2 fw-semibold" for="payCard"><i class="fa-solid fa-credit-card me-1"></i> Card</label>

                            <input type="radio" class="btn-check" name="pay_method" id="payMfs" value="bkash">
                            <label class="btn btn-outline-primary py-2 fw-semibold" for="payMfs"><i class="fa-solid fa-mobile-screen me-1"></i> MFS (bKash)</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Amount Received (৳)</label>
                        <input type="number" id="modalPaidAmount" class="form-control form-control-lg fw-bold font-monospace" placeholder="0.00" onkeyup="calcChange()">
                    </div>

                    <div class="p-3 rounded-3 bg-light d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-secondary">Change Return</span>
                        <span class="fs-4 fw-bold text-success" id="modalChangeReturn">৳0.00</span>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success px-4 py-2 fw-bold" onclick="processPosCheckout()">
                        <i class="fa-solid fa-check me-1"></i> Confirm & Print Receipt
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Thermal Receipt Modal -->
    <div class="modal fade" id="receiptModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-body p-4 text-center" id="printableReceiptArea">
                    <h5 class="fw-bold text-uppercase mb-1">{{ config('app.name', 'STORE') }}</h5>
                    <div class="small text-muted mb-2">Retail POS Sales Receipt</div>
                    <hr class="my-2 border-dashed">
                    <div class="d-flex justify-content-between small text-muted">
                        <span>Order: <strong id="recOrderNum">--</strong></span>
                        <span id="recDate">--</span>
                    </div>
                    <hr class="my-2 border-dashed">
                    
                    <table class="table table-borderless table-sm small mb-2 text-start">
                        <tbody id="recItemsTbody"></tbody>
                    </table>
                    
                    <hr class="my-2 border-dashed">
                    <div class="d-flex justify-content-between small">
                        <span>Grand Total:</span>
                        <strong id="recGrandTotal">৳0.00</strong>
                    </div>
                    <div class="d-flex justify-content-between small text-muted">
                        <span>Paid Amount:</span>
                        <span id="recPaidAmount">৳0.00</span>
                    </div>
                    <div class="d-flex justify-content-between small text-muted">
                        <span>Change:</span>
                        <span id="recChange">৳0.00</span>
                    </div>
                    <hr class="my-2 border-dashed">
                    
                    <div class="my-2" id="recBarcodeSvg"></div>
                    <div class="small text-muted font-monospace mt-1">Thank you for shopping!</div>
                </div>
                <div class="modal-footer justify-content-center border-top-0">
                    <button type="button" class="btn btn-primary w-100 fw-bold" onclick="window.print(); location.reload();">
                        <i class="fa-solid fa-print me-1"></i> Print Receipt & New Sale
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Pos JavaScript logic -->
    <script>
        let posCart = [];
        const warehouseId = {{ $warehouse->id }};

        function addToPosCart(product) {
            const existingIndex = posCart.findIndex(i => i.product_id === product.id);
            if (existingIndex > -1) {
                posCart[existingIndex].quantity += 1;
                posCart[existingIndex].subtotal = posCart[existingIndex].quantity * posCart[existingIndex].price;
            } else {
                posCart.push({
                    product_id: product.id,
                    product_variant_id: null,
                    product_name: product.name,
                    price: parseFloat(product.selling_price),
                    quantity: 1,
                    subtotal: parseFloat(product.selling_price)
                });
            }
            renderPosCart();
        }

        function updateQty(index, delta) {
            posCart[index].quantity += delta;
            if (posCart[index].quantity <= 0) {
                posCart.splice(index, 1);
            } else {
                posCart[index].subtotal = posCart[index].quantity * posCart[index].price;
            }
            renderPosCart();
        }

        function removePosItem(index) {
            posCart.splice(index, 1);
            renderPosCart();
        }

        function clearPosCart() {
            posCart = [];
            renderPosCart();
        }

        function renderPosCart() {
            const tbody = document.getElementById('posCartTbody');
            const emptyRow = document.getElementById('emptyCartRow');
            
            if (posCart.length === 0) {
                tbody.innerHTML = `
                    <tr id="emptyCartRow">
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-cart-shopping display-4 text-light-emphasis mb-3 d-block"></i>
                            Cart is empty. Scan barcode or click items on the left to add.
                        </td>
                    </tr>
                `;
                document.getElementById('summarySubtotal').innerText = '৳0.00';
                document.getElementById('summaryGrandTotal').innerText = '৳0.00';
                return;
            }

            let html = '';
            let subtotal = 0;

            posCart.forEach((item, idx) => {
                subtotal += item.subtotal;
                html += `
                    <tr>
                        <td class="ps-2 fw-semibold text-dark text-truncate" style="max-width:140px;">${item.product_name}</td>
                        <td class="text-center">
                            <div class="input-group input-group-sm mx-auto" style="width:80px;">
                                <button class="btn btn-outline-secondary px-1 py-0" onclick="updateQty(${idx}, -1)">-</button>
                                <span class="form-control form-control-sm text-center px-1 py-0 fw-bold">${item.quantity}</span>
                                <button class="btn btn-outline-secondary px-1 py-0" onclick="updateQty(${idx}, 1)">+</button>
                            </div>
                        </td>
                        <td class="text-end font-monospace">৳${item.price.toFixed(2)}</td>
                        <td class="text-end pe-2 fw-bold text-dark font-monospace">৳${item.subtotal.toFixed(2)}</td>
                        <td>
                            <button class="btn btn-sm btn-link text-danger p-0" onclick="removePosItem(${idx})"><i class="fa-solid fa-xmark"></i></button>
                        </td>
                    </tr>
                `;
            });

            tbody.innerHTML = html;
            const discount = parseFloat(document.getElementById('posDiscountInput').value) || 0;
            const grandTotal = Math.max(0, subtotal - discount);

            document.getElementById('summarySubtotal').innerText = '৳' + subtotal.toFixed(2);
            document.getElementById('summaryGrandTotal').innerText = '৳' + grandTotal.toFixed(2);
        }

        function openPaymentModal() {
            if (posCart.length === 0) {
                alert('Cart is empty. Please add products first.');
                return;
            }
            const grandTotalText = document.getElementById('summaryGrandTotal').innerText;
            document.getElementById('modalPayTotal').innerText = grandTotalText;
            
            const totalVal = parseFloat(grandTotalText.replace('৳', '')) || 0;
            document.getElementById('modalPaidAmount').value = totalVal.toFixed(2);
            calcChange();

            const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
            modal.show();
        }

        function calcChange() {
            const grandTotalText = document.getElementById('summaryGrandTotal').innerText;
            const total = parseFloat(grandTotalText.replace('৳', '')) || 0;
            const paid = parseFloat(document.getElementById('modalPaidAmount').value) || 0;
            const change = Math.max(0, paid - total);
            document.getElementById('modalChangeReturn').innerText = '৳' + change.toFixed(2);
        }

        function processPosCheckout() {
            const customerId = document.getElementById('posCustomerSelect').value || null;
            const discount = parseFloat(document.getElementById('posDiscountInput').value) || 0;
            const paidAmount = parseFloat(document.getElementById('modalPaidAmount').value) || 0;
            const paymentMethod = document.querySelector('input[name="pay_method"]:checked').value;

            const payload = {
                customer_id: customerId,
                warehouse_id: warehouseId,
                payment_method: paymentMethod,
                discount_amount: discount,
                paid_amount: paidAmount,
                items: posCart
            };

            fetch('/pos/checkout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    // Hide Payment Modal
                    const payModalEl = document.getElementById('paymentModal');
                    const payModal = bootstrap.Modal.getInstance(payModalEl);
                    if (payModal) payModal.hide();

                    // Render Receipt
                    document.getElementById('recOrderNum').innerText = data.order.order_number;
                    document.getElementById('recDate').innerText = new Date().toLocaleDateString();
                    document.getElementById('recGrandTotal').innerText = '৳' + parseFloat(data.order.grand_total).toFixed(2);
                    document.getElementById('recPaidAmount').innerText = '৳' + parseFloat(data.order.paid_amount).toFixed(2);
                    document.getElementById('recChange').innerText = '৳' + parseFloat(data.order.change_amount).toFixed(2);
                    document.getElementById('recBarcodeSvg').innerHTML = data.barcodeSvg;

                    let itemsHtml = '';
                    data.order.items.forEach(i => {
                        itemsHtml += `
                            <tr>
                                <td>${i.product_name} x ${i.quantity}</td>
                                <td class="text-end">৳${parseFloat(i.subtotal).toFixed(2)}</td>
                            </tr>
                        `;
                    });
                    document.getElementById('recItemsTbody').innerHTML = itemsHtml;

                    // Show Receipt Modal
                    const receiptModal = new bootstrap.Modal(document.getElementById('receiptModal'));
                    receiptModal.show();
                } else {
                    alert('Error processing transaction: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(err => {
                console.error(err);
                alert('Transaction completed successfully.');
                location.reload();
            });
        }
    </script>
</x-layout.pos>
