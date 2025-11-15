/**
 * Shopping Cart Management with localStorage
 * Aksesoris Ria - Cart System
 */

class ShoppingCart {
    constructor() {
        this.storageKey = "ria_shopping_cart";
        this.init();
    }

    init() {
        this.renderCart();
        this.attachEventListeners();
        this.updateCartCount();
    }

    // Get cart from localStorage
    getCart() {
        const cart = localStorage.getItem(this.storageKey);
        return cart ? JSON.parse(cart) : [];
    }

    // Save cart to localStorage
    saveCart(cart) {
        localStorage.setItem(this.storageKey, JSON.stringify(cart));
        this.updateCartCount();
        this.renderCart();
    }

    // Add item to cart
    addToCart(product) {
        let cart = this.getCart();
        const existingItemIndex = cart.findIndex(
            (item) =>
                item.id === product.id && item.jenis_id === product.jenis_id
        );

        // Validasi stok sebelum menambahkan
        const stok = product.stok || 0;
        const quantityToAdd = product.quantity || 1;

        if (existingItemIndex > -1) {
            // Update quantity if item exists
            const newQuantity = cart[existingItemIndex].quantity + quantityToAdd;
            if (newQuantity > stok) {
                this.showNotification(
                    `Stok tidak mencukupi. Sisa stok: ${stok}`,
                    "error"
                );
                return false;
            }
            cart[existingItemIndex].quantity = newQuantity;
            // Update stok jika ada perubahan
            if (product.stok !== undefined) {
                cart[existingItemIndex].stok = product.stok;
            }
        } else {
            // Validasi stok untuk item baru
            if (quantityToAdd > stok) {
                this.showNotification(
                    `Stok tidak mencukupi. Sisa stok: ${stok}`,
                    "error"
                );
                return false;
            }
            // Add new item
            cart.push({
                id: product.id,
                nama: product.nama,
                harga: product.harga,
                quantity: quantityToAdd,
                gambar: product.gambar,
                kategori: product.kategori,
                jenis_id: product.jenis_id || null,
                jenis_nama: product.jenis_nama || null,
                // stok: stok,
                stok: product.stok,
            });
        }

        this.saveCart(cart);
        this.showNotification(
            "Produk berhasil ditambahkan ke keranjang!",
            "success"
        );
        return true;
    }

    // Update item quantity
    updateQuantity(productId, jenisId, quantity) {
        let cart = this.getCart();
        const itemIndex = cart.findIndex(
            (item) => item.id === productId && item.jenis_id === jenisId
        );

        if (itemIndex > -1) {
            if (quantity <= 0) {
                this.removeFromCart(productId, jenisId);
                return false;
            }

            // Validasi stok
            const item = cart[itemIndex];
            const stok = item.stok || 0;

            if (quantity > stok) {

                this.showNotification(
                    `Stok tidak mencukupi. Sisa stok: ${stok}`,
                    "error"
                );
                // Kembalikan ke stok maksimal jika melebihi
                cart[itemIndex].quantity = stok;
                this.saveCart(cart);
                alert(`Stok tidak mencukupi. Sisa stok: ${stok}`);
                return false;
            }

            cart[itemIndex].quantity = quantity;
            this.saveCart(cart);
            return true;
        }
        return false;
    }

    // Remove item from cart
    removeFromCart(productId, jenisId) {
        let cart = this.getCart();
        cart = cart.filter(
            (item) => !(item.id === productId && item.jenis_id === jenisId)
        );
        this.saveCart(cart);
        this.showNotification("Produk dihapus dari keranjang", "info");
    }

    // Clear cart
    clearCart() {
        localStorage.removeItem(this.storageKey);
        this.updateCartCount();
        this.renderCart();
    }

    // Get cart total
    getCartTotal() {
        const cart = this.getCart();
        return cart.reduce(
            (total, item) => total + item.harga * item.quantity,
            0
        );
    }

    // Get cart item count
    getCartItemCount() {
        const cart = this.getCart();
        return cart.reduce((total, item) => total + item.quantity, 0);
    }

    // Update cart count in UI
    updateCartCount() {
        const count = this.getCartItemCount();
        const cartCountElements = document.querySelectorAll(
            ".count-box, .count-cart"
        );

        cartCountElements.forEach((element) => {
            element.textContent = count;
            if (count > 0) {
                element.style.display = "flex";
            } else {
                element.style.display = "none";
            }
        });
    }

    // Render cart in offcanvas
    renderCart() {
        const cart = this.getCart();
        const cartContainer = document.querySelector(
            "#shoppingCart .product-list-wrap"
        );
        const emptyCart = document.querySelector(
            "#shoppingCart .minicart-empty"
        );
        const cartFooter = document.querySelector(
            "#shoppingCart .popup-footer"
        );

        if (!cartContainer) return;

        if (cart.length === 0) {
            cartContainer.innerHTML = "";
            if (emptyCart) emptyCart.style.display = "block";
            if (cartFooter) cartFooter.style.display = "none";
        } else {
            if (emptyCart) emptyCart.style.display = "none";
            if (cartFooter) cartFooter.style.display = "block";

            cartContainer.innerHTML = cart
                .map(
                    (item) => `
                <li class="file-delete">
                    <div class="card-product style-row row-small-2 align-items-center">
                        <div class="card-product-wrapper">
                            <a href="/produk/${item.id}" class="product-img">
                                <img class="lazyload" src="${item.gambar
                        }" alt="${item.nama}">
                            </a>
                        </div>
                        <div class="card-product-info">
                            <div class="box-title">
                                <a href="/produk/${item.id
                        }" class="name-product body-md-2 fw-semibold text-secondary link">
                                    ${item.nama}
                                </a>
                                ${item.jenis_nama
                            ? `<p class="body-text-3 text-secondary mb-1">${item.jenis_nama}</p>`
                            : ""
                        }
                                <p class="price-wrap fw-medium">
                                    <span class="new-price price-text fw-medium">Rp. ${this.formatPrice(
                            item.harga
                        )}</span>
                                </p>
                                <div class="wg-quantity mt-2">
                                    <button class="btn-quantity btn-decrease-cart"
                                        data-id="${item.id}"
                                        data-jenis="${item.jenis_id || ""}"
                                        ${item.quantity <= 1 ? 'disabled style="opacity: 0.5; cursor: not-allowed; pointer-events: none;"' : ''}>
                                        <i class="icon-minus"></i>
                                    </button>
                                    <input class="quantity-product" type="text"
                                        value="${item.quantity}"
                                        data-id="${item.id}"
                                        data-jenis="${item.jenis_id || ""}"
                                        data-stok="${item.stok || 0}"
                                        readonly>
                                    <button class="btn-quantity btn-increase-cart"
                                        data-id="${item.id}"
                                        data-jenis="${item.jenis_id || ""}"
                                        data-stok="${item.stok || 0}"
                                        ${item.quantity >= (item.stok || 0) ? 'disabled style="opacity: 0.5; cursor: not-allowed; pointer-events: none;"' : ''}>
                                        <i class="icon-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <span class="icon-close remove remove-cart link"
                            data-id="${item.id}"
                            data-jenis="${item.jenis_id || ""}"></span>
                    </div>
                </li>
            `
                )
                .join("");

            // Update total
            const totalElement = document.querySelector(
                "#shoppingCart .cart-total .tf-totals-price"
            );
            if (totalElement) {
                totalElement.textContent = `Rp. ${this.formatPrice(
                    this.getCartTotal()
                )}`;
            }

            // Attach event listeners to new elements
            this.attachCartItemEvents();
            // Update button states
            this.updateButtonStates();
        }
    }

    // Format price to Indonesian format
    formatPrice(price) {
        return new Intl.NumberFormat("id-ID").format(price);
    }

    // Attach event listeners to cart items
    attachCartItemEvents() {
        // Use event delegation to handle dynamically created elements
        const cartContainer = document.querySelector("#shoppingCart .product-list-wrap");
        if (!cartContainer) return;

        // Remove existing listeners by cloning container (cleaner approach)
        if (this._cartEventAttached) {
            return; // Already attached via delegation
        }

        // Use event delegation on the container
        cartContainer.addEventListener("click", (e) => {
            const target = e.target.closest(".remove-cart, .btn-increase-cart, .btn-decrease-cart");
            if (!target) return;

            // Handle remove button
            if (target.classList.contains("remove-cart")) {
                e.preventDefault();
                const id = parseInt(target.dataset.id);
                const jenisId = target.dataset.jenis
                    ? parseInt(target.dataset.jenis)
                    : null;
                this.removeFromCart(id, jenisId);
                return;
            }

            // Handle increase button
            if (target.classList.contains("btn-increase-cart")) {
                e.preventDefault();
                e.stopPropagation();

                // Check disabled state (both attribute and class)
                if (target.disabled || target.classList.contains('disabled') || target.hasAttribute('disabled')) {
                    this.showNotification(
                        `Stok tidak mencukupi. Tidak dapat menambah jumlah.`,
                        "error"
                    );
                    return false;
                }

                const id = parseInt(target.dataset.id);
                const jenisId = target.dataset.jenis
                    ? parseInt(target.dataset.jenis)
                    : null;
                const input = target.parentElement.querySelector(".quantity-product");
                const currentQty = parseInt(input.value) || 0;
                const stok = parseInt(target.dataset.stok) || 0;

                if (currentQty >= stok || stok <= 0) {
                    this.showNotification(
                        `Stok tidak mencukupi. Sisa stok: ${stok}`,
                        "error"
                    );
                    // Update button state
                    this.updateButtonStates();
                    return false;
                }

                const success = this.updateQuantity(id, jenisId, currentQty + 1);
                if (success) {
                    // Update button state setelah update
                    this.updateButtonStates();
                }
                return false;
            }

            // Handle decrease button
            if (target.classList.contains("btn-decrease-cart")) {
                e.preventDefault();
                e.stopPropagation();

                // Check disabled state (both attribute and class)
                if (target.disabled || target.classList.contains('disabled') || target.hasAttribute('disabled')) {
                    return false;
                }

                const id = parseInt(target.dataset.id);
                const jenisId = target.dataset.jenis
                    ? parseInt(target.dataset.jenis)
                    : null;
                const input = target.parentElement.querySelector(".quantity-product");
                const currentQty = parseInt(input.value) || 0;

                if (currentQty > 1) {
                    const success = this.updateQuantity(id, jenisId, currentQty - 1);
                    if (success) {
                        // Update button state setelah update
                        this.updateButtonStates();
                    }
                }
                return false;
            }
        });

        this._cartEventAttached = true;
    }

    // Attach global event listeners
    attachEventListeners() {
        // Add to cart buttons
        document.querySelectorAll(".btn-add-to-cart").forEach((btn) => {
            btn.addEventListener("click", (e) => {
                e.preventDefault();
                this.handleAddToCart(btn);
            });
        });
    }

    // Handle add to cart from product detail page
    handleAddToCart(btn) {
        const productData = {
            id: parseInt(btn.dataset.productId),
            nama: btn.dataset.productNama,
            harga: parseInt(btn.dataset.productHarga),
            gambar: btn.dataset.productGambar,
            kategori: btn.dataset.productKategori,
            stok : parseInt(btn.dataset.productStok) || 0,
            quantity: 1,
            jenis_id: null,
            jenis_nama: null,
        };

        console(`productData: ${JSON.stringify(productData)}`);

        // Get quantity from input
        const quantityInput = document.querySelector(".quantity-product");
        if (quantityInput) {
            productData.quantity = parseInt(quantityInput.value) || 1;
        }

        // Get jenis if exists
        const jenisSelect = document.querySelector(".select-color");
        if (jenisSelect && jenisSelect.value) {
            const selectedOption =
                jenisSelect.options[jenisSelect.selectedIndex];
            productData.jenis_id = parseInt(jenisSelect.value);
            productData.jenis_nama = selectedOption.text;
        }

        this.addToCart(productData);
    }

    // Update button states based on stock
    updateButtonStates() {
        const cart = this.getCart();

        // Update buttons in offcanvas cart
        document.querySelectorAll(".btn-increase-cart").forEach((btn) => {
            const id = parseInt(btn.dataset.id);
            const jenisId = btn.dataset.jenis
                ? parseInt(btn.dataset.jenis)
                : null;
            const input = btn.parentElement.querySelector(".quantity-product");
            const currentQty = parseInt(input.value) || 0;
            const stok = parseInt(btn.dataset.stok) || 0;

            const item = cart.find(
                (item) => item.id === id && item.jenis_id === jenisId
            );

            if (item) {
                const itemStok = item.stok || 0;
                if (currentQty >= itemStok || itemStok <= 0) {
                    btn.disabled = true;
                    btn.setAttribute('disabled', 'disabled');
                    btn.classList.add("disabled");
                    btn.style.opacity = '0.5';
                    btn.style.cursor = 'not-allowed';
                    btn.style.pointerEvents = 'none';
                } else {
                    btn.disabled = false;
                    btn.removeAttribute('disabled');
                    btn.classList.remove("disabled");
                    btn.style.opacity = '1';
                    btn.style.cursor = 'pointer';
                    btn.style.pointerEvents = 'auto';
                }
            }
        });

        document.querySelectorAll(".btn-decrease-cart").forEach((btn) => {
            const input = btn.parentElement.querySelector(".quantity-product");
            const currentQty = parseInt(input.value) || 0;

            if (currentQty <= 1) {
                btn.disabled = true;
                btn.setAttribute('disabled', 'disabled');
                btn.classList.add("disabled");
                btn.style.opacity = '0.5';
                btn.style.cursor = 'not-allowed';
                btn.style.pointerEvents = 'none';
            } else {
                btn.disabled = false;
                btn.removeAttribute('disabled');
                btn.classList.remove("disabled");
                btn.style.opacity = '1';
                btn.style.cursor = 'pointer';
                btn.style.pointerEvents = 'auto';
            }
        });
    }

    // Show notification
    showNotification(message, type = "success") {
        // Create notification element
        const notification = document.createElement("div");
        notification.className = `cart-notification alert alert-${type}`;
        const bgColor = type === "success" ? "#28a745" : type === "error" ? "#dc3545" : "#17a2b8";
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            padding: 15px 20px;
            background: ${bgColor};
            color: white;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            animation: slideIn 0.3s ease-out;
        `;
        notification.textContent = message;

        document.body.appendChild(notification);

        // Remove after 3 seconds
        setTimeout(() => {
            notification.style.animation = "slideOut 0.3s ease-out";
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    // Prepare data for checkout
    prepareCheckoutData() {
        const cart = this.getCart();
        const total = this.getCartTotal();

        return {
            items: cart,
            subtotal: total,
            total: total,
            itemCount: this.getCartItemCount(),
        };
    }
}

// Add CSS animations and disabled button styles
const style = document.createElement("style");
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    /* Prevent clicks on disabled cart buttons */
    .btn-increase-cart[disabled],
    .btn-decrease-cart[disabled],
    .btn-increase-cart.disabled,
    .btn-decrease-cart.disabled {
        pointer-events: none !important;
        opacity: 0.5 !important;
        cursor: not-allowed !important;
    }
    .btn-increase-cart[disabled] *,
    .btn-decrease-cart[disabled] *,
    .btn-increase-cart.disabled *,
    .btn-decrease-cart.disabled * {
        pointer-events: none !important;
    }
`;
document.head.appendChild(style);

// Initialize cart when DOM is ready
let cart;

function initCart() {
    cart = new ShoppingCart();
    window.cart = cart;
}

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initCart);
} else {
    initCart();
}

// Export for use in other scripts
window.ShoppingCart = ShoppingCart;
