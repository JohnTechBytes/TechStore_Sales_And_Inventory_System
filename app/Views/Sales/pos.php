<?= $this->extend('theme/template') ?>
<?= $this->section('content') ?>

<style>
    /* Cart & existing styles */
    .cart-item-img { width: 40px; height: 40px; object-fit: cover; border-radius: 8px; margin-right: 8px; }
    .selected-product-card { border: 2px solid #0d6efd; background: #f8fcff; border-radius: 1rem; padding: 1rem; margin-top: 1.5rem; }
    .search-wrapper { position: relative; }

    /* --- ENHANCED AUTOCOMPLETE VISIBILITY --- */
    .autocomplete-results {
        position: absolute;
        z-index: 9999;              /* on top of everything */
        width: 100%;
        max-height: 400px;          /* larger height */
        overflow-y: auto;
        background: white;
        border: 2px solid #0d6efd;  /* blue border for visibility */
        border-radius: 0.5rem;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        margin-top: 5px;
        font-size: 1rem;            /* base font bigger */
    }
    .autocomplete-item {
        padding: 12px 16px;         /* more padding for easier click */
        cursor: pointer;
        border-bottom: 1px solid #e9ecef;
        font-size: 1rem;
        transition: background 0.1s ease;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
    }
    .autocomplete-item strong {
        font-size: 1rem;
        color: #212529;
    }
    .autocomplete-item:last-child {
        border-bottom: none;
    }
    .autocomplete-item:hover {
        background-color: #e2f0ff;   /* light blue hover */
        border-left: 3px solid #0d6efd;
    }
    /* Optional: add a small price tag style */
    .autocomplete-item span.price {
        font-weight: bold;
        color: #0d6efd;
        background: #e9ecef;
        padding: 4px 8px;
        border-radius: 20px;
        font-size: 0.85rem;
    }
    /* Improve the search input itself */
    #productSearch {
        border-radius: 2rem;
        padding: 10px 16px;
        font-size: 1rem;
        border: 1px solid #ced4da;
        transition: 0.2s;
    }
    #productSearch:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13,110,253,0.25);
        outline: none;
    }
</style>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <h1><i class="fas fa-shopping-cart"></i> Point of Sale</h1>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h3 class="card-title"><i class="fas fa-search text-primary"></i> Select Product</h3>
                        </div>
                        <div class="card-body">
                            <div class="search-wrapper">
                                <input type="text" id="productSearch" class="form-control" placeholder="🔍 Click to see all products, or type to search..." autocomplete="off">
                                <div id="autocompleteResults" class="autocomplete-results" style="display: none;"></div>
                            </div>
                            <div id="selectedProductPreview" class="selected-product-card" style="display: none;">
                                <div class="row align-items-center">
                                    <div class="col-3 col-md-2">
                                        <img id="previewImg" class="img-fluid rounded" src="" style="max-height: 80px; object-fit: cover;">
                                    </div>
                                    <div class="col-5 col-md-6">
                                        <h6 id="previewName" class="mb-1"></h6>
                                        <strong id="previewPrice" class="text-primary"></strong>
                                    </div>
                                    <div class="col-4 col-md-4">
                                        <div class="input-group input-group-sm mb-2">
                                            <button class="btn btn-outline-secondary" id="previewQtyDec">−</button>
                                            <input type="number" id="previewQty" class="form-control text-center" value="1" min="1" style="width: 60px;">
                                            <button class="btn btn-outline-secondary" id="previewQtyInc">+</button>
                                        </div>
                                        <button id="previewAddToCart" class="btn btn-primary btn-sm w-100"><i class="fas fa-cart-plus"></i> Add</button>
                                    </div>
                                </div>
                            </div>
                            <div class="text-muted mt-3 small">Click the search box to see all products, or start typing to filter.</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <!-- Cart sidebar (unchanged) -->
                    <div class="card shadow-sm sticky-top" style="top:20px;">
                        <div class="card-header bg-warning text-white"><h3 class="card-title"><i class="fas fa-shopping-cart"></i> Cart</h3></div>
                        <div class="card-body p-0">
                            <table class="table table-sm table-bordered mb-0">
                                <thead class="thead-light"><tr><th>Item</th><th>Qty</th><th>Subtotal</th><th></th></tr></thead>
                                <tbody id="cartBody"><tr><td colspan="4" class="text-center">Cart is empty</td></tr></tbody>
                                <tfoot id="cartFooter" style="display:none;"><tr><td colspan="2"><strong>Total</strong></td><td colspan="2"><strong id="cartTotal">₱0.00</strong></td></tr></tfoot>
                            </table>
                        </div>
                        <div class="card-footer bg-light">
                            <form id="checkoutForm" action="<?= base_url('sales/checkout') ?>" method="post">
                                <?= csrf_field() ?>
                                <input type="hidden" name="cart_data" id="cartData">
                                <input type="text" name="customer_name" class="form-control mb-2" placeholder="Customer name (optional)">
                                <select name="payment_method" class="form-control mb-2" required>
                                    <option value="cash">💵 Cash</option>
                                    <option value="card">💳 Card</option>
                                    <option value="online">📱 Online</option>
                                </select>
                                <button type="submit" class="btn btn-success btn-block checkout-btn" id="checkoutBtn" disabled>Checkout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let cart = [];
let savedCart = localStorage.getItem('pos_cart');
if(savedCart) { try { cart = JSON.parse(savedCart); } catch(e) {} }

function renderCart() {
    let tbody = '', total = 0;
    if(cart.length === 0) {
        tbody = '<tr><td colspan="4" class="text-center">Cart is empty</td></tr>';
        document.getElementById('cartFooter').style.display = 'none';
        document.getElementById('checkoutBtn').disabled = true;
    } else {
        cart.forEach((item, idx) => {
            let subtotal = item.price * item.qty;
            total += subtotal;
            tbody += `<tr>
                <td><img src="${item.img}" class="cart-item-img" onerror="this.src='<?= base_url('assets/img/no-image.png') ?>'"> ${escapeHtml(item.name)}</td>
                <td><div class="input-group input-group-sm"><button class="btn btn-outline-secondary qty-minus" data-index="${idx}">−</button><input type="number" class="form-control form-control-sm text-center cart-qty" data-index="${idx}" value="${item.qty}" min="1" style="width:60px;"><button class="btn btn-outline-secondary qty-plus" data-index="${idx}">+</button></div></td>
                <td>₱${subtotal.toFixed(2)}</td>
                <td><button class="btn btn-sm btn-danger remove-item" data-index="${idx}"><i class="fas fa-trash"></i></button></td>
            </tr>`;
        });
        document.getElementById('cartFooter').style.display = '';
        document.getElementById('checkoutBtn').disabled = false;
        document.getElementById('cartTotal').innerText = '₱' + total.toFixed(2);
    }
    document.getElementById('cartBody').innerHTML = tbody;
    localStorage.setItem('pos_cart', JSON.stringify(cart));
    attachCartEvents();
}

function attachCartEvents() {
    document.querySelectorAll('.qty-minus').forEach(btn => { btn.onclick = () => { let idx = parseInt(btn.dataset.index); if(cart[idx].qty>1) { cart[idx].qty--; renderCart(); } }; });
    document.querySelectorAll('.qty-plus').forEach(btn => { btn.onclick = () => { let idx = parseInt(btn.dataset.index); cart[idx].qty++; renderCart(); }; });
    document.querySelectorAll('.cart-qty').forEach(input => { input.onchange = () => { let idx = parseInt(input.dataset.index); let newQty = parseInt(input.value); if(isNaN(newQty) || newQty<1) newQty=1; cart[idx].qty = newQty; renderCart(); }; });
    document.querySelectorAll('.remove-item').forEach(btn => { btn.onclick = () => { let idx = parseInt(btn.dataset.index); cart.splice(idx,1); renderCart(); }; });
}

function addToCart(id, name, price, qty, img) {
    let existing = cart.find(item => item.id == id);
    if(existing) existing.qty += qty;
    else cart.push({ id, name, price, qty, img });
    renderCart();
    let btn = document.getElementById('previewAddToCart');
    let orig = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-check"></i> Added!';
    setTimeout(() => btn.innerHTML = orig, 800);
}

function escapeHtml(str) { return str.replace(/[&<>]/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;'})[m]); }

// -------- Product search with "show all on focus" --------
let currentSelectedProduct = null;
const searchInput = document.getElementById('productSearch');
const resultsDiv = document.getElementById('autocompleteResults');
const previewDiv = document.getElementById('selectedProductPreview');
const previewImg = document.getElementById('previewImg');
const previewName = document.getElementById('previewName');
const previewPrice = document.getElementById('previewPrice');
const previewQty = document.getElementById('previewQty');

let debounceTimer;

// Function to fetch products (query can be empty to get all)
function fetchProducts(searchTerm) {
    fetch('<?= base_url('sales/searchProducts') ?>?q=' + encodeURIComponent(searchTerm))
        .then(res => res.json())
        .then(data => {
            if(data.length === 0) {
                resultsDiv.innerHTML = '<div class="autocomplete-item text-muted">No products found</div>';
                resultsDiv.style.display = 'block';
                return;
            }
            let html = '';
       data.forEach(prod => {
    html += `<div class="autocomplete-item" data-id="${prod.id}" data-name="${escapeHtml(prod.name)}" data-price="${prod.selling_price}" data-img="${prod.image_url}">
                <strong>${escapeHtml(prod.name)}</strong>
                <span class="price">₱${parseFloat(prod.selling_price).toFixed(2)}</span>
            </div>`;
});
            resultsDiv.innerHTML = html;
            resultsDiv.style.display = 'block';
            // Attach click events to each result
            document.querySelectorAll('.autocomplete-item').forEach(el => {
                el.addEventListener('click', () => {
                    let id = parseInt(el.dataset.id);
                    let name = el.dataset.name;
                    let price = parseFloat(el.dataset.price);
                    let img = el.dataset.img || '<?= base_url('assets/img/no-image.png') ?>';
                    selectProduct(id, name, price, img);
                    resultsDiv.style.display = 'none';
                    searchInput.value = name;
                });
            });
        })
        .catch(err => { console.error(err); resultsDiv.innerHTML = '<div class="autocomplete-item text-danger">Error loading products</div>'; resultsDiv.style.display = 'block'; });
}

// When the search bar is clicked/focused, show all products
searchInput.addEventListener('focus', function() {
    // Clear any previous timer to avoid conflict
    clearTimeout(debounceTimer);
    // Fetch all products (empty query)
    fetchProducts('');
});

// When user types, filter products (after 300ms debounce)
searchInput.addEventListener('input', function() {
    clearTimeout(debounceTimer);
    let query = this.value.trim();
    if(query.length === 0) {
        // If empty, show all products (same as focus)
        fetchProducts('');
    } else if(query.length >= 1) {
        debounceTimer = setTimeout(() => fetchProducts(query), 300);
    }
});

function selectProduct(id, name, price, img) {
    currentSelectedProduct = { id, name, price, img };
    previewImg.src = img;
    previewImg.onerror = function() { this.src = '<?= base_url('assets/img/no-image.png') ?>'; };
    previewName.innerText = name;
    previewPrice.innerText = '₱' + price.toFixed(2);
    previewQty.value = 1;
    previewDiv.style.display = 'block';
}

// Quantity controls for preview
document.getElementById('previewQtyDec').onclick = () => { let v = parseInt(previewQty.value); if(v>1) previewQty.value = v-1; };
document.getElementById('previewQtyInc').onclick = () => { previewQty.value = parseInt(previewQty.value) + 1; };
document.getElementById('previewAddToCart').onclick = () => {
    if(!currentSelectedProduct) { alert('Select a product first'); return; }
    let qty = parseInt(previewQty.value);
    if(isNaN(qty) || qty<1) qty = 1;
    addToCart(currentSelectedProduct.id, currentSelectedProduct.name, currentSelectedProduct.price, qty, currentSelectedProduct.img);
};

// Hide autocomplete dropdown when clicking outside
document.addEventListener('click', (e) => { if(!searchInput.contains(e.target) && !resultsDiv.contains(e.target)) resultsDiv.style.display = 'none'; });

// Checkout
document.getElementById('checkoutForm').addEventListener('submit', (e) => { if(cart.length===0) { alert('Cart empty'); e.preventDefault(); return false; } document.getElementById('cartData').value = JSON.stringify(cart); localStorage.removeItem('pos_cart'); return true; });

renderCart();
</script>
<?= $this->endSection() ?>