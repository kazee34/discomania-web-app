import { computed, ref } from 'vue';

const CART_TOKEN_KEY = 'discomania_cart_token';

export interface CartItem {
    id: number;
    productId: number;
    quantity: number;
    price: number;
    subtotal: number;
    productArtist: string | null;
    productAlbumTitle: string | null;
    productCoverImageUrl: string | null;
}

export interface Cart {
    id: number;
    cartToken: string;
    customerId: number | null;
    sessionId: string | null;
    items: CartItem[];
    totalAmount: number;
}

const cart = ref<Cart | null>(null);
const loading = ref(false);
const error = ref<string | null>(null);

function getStoredToken(): string | null {
    return localStorage.getItem(CART_TOKEN_KEY);
}

function storeToken(token: string): void {
    localStorage.setItem(CART_TOKEN_KEY, token);
}

function clearToken(): void {
    localStorage.removeItem(CART_TOKEN_KEY);
}

async function apiFetch(url: string, options: RequestInit = {}): Promise<Response> {
    return fetch(url, {
        ...options,
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            ...(options.headers ?? {}),
        },
    });
}

async function ensureCart(): Promise<string> {
    const stored = getStoredToken();
    if (stored) return stored;

    const res = await apiFetch('/api/v1/customer/cart', {
        method: 'POST',
        body: JSON.stringify({}),
    });

    const data = await res.json();
    if (!data.success) throw new Error(data.error ?? 'Error creating cart');

    storeToken(data.data.cartToken);
    cart.value = data.data;
    return data.data.cartToken;
}

async function fetchCart(): Promise<void> {
    const token = getStoredToken();
    if (!token) return;

    const res = await apiFetch(`/api/v1/customer/cart/${token}`);
    const data = await res.json();

    if (data.success) {
        cart.value = data.data;
    } else {
        // Token caducado o inválido
        clearToken();
        cart.value = null;
    }
}

async function addItem(productId: number, price: number, quantity = 1): Promise<void> {
    loading.value = true;
    error.value = null;

    try {
        const token = await ensureCart();
        const res = await apiFetch(`/api/v1/customer/cart/${token}/items`, {
            method: 'POST',
            body: JSON.stringify({ product_id: productId, price, quantity }),
        });

        const data = await res.json();
        if (!data.success) throw new Error(data.error ?? 'Error adding item');
        cart.value = data.data;
    } catch (e: any) {
        error.value = e.message;
        throw e;
    } finally {
        loading.value = false;
    }
}

async function updateQuantity(cartItemId: number, quantity: number): Promise<void> {
    const token = getStoredToken();
    if (!token) return;

    const res = await apiFetch(`/api/v1/customer/cart/${token}/items/${cartItemId}`, {
        method: 'PUT',
        body: JSON.stringify({ quantity }),
    });

    const data = await res.json();
    if (data.success) cart.value = data.data;
}

async function removeItem(cartItemId: number): Promise<void> {
    const token = getStoredToken();
    if (!token) return;

    const res = await apiFetch(`/api/v1/customer/cart/${token}/items/${cartItemId}`, {
        method: 'DELETE',
    });

    const data = await res.json();
    if (data.success) cart.value = data.data;
}

async function clearCart(): Promise<void> {
    const token = getStoredToken();
    if (!token) return;

    const res = await apiFetch(`/api/v1/customer/cart/${token}`, { method: 'DELETE' });
    const data = await res.json();
    if (data.success) cart.value = data.data;
}

const itemCount = computed(() =>
    cart.value?.items.reduce((sum, item) => sum + item.quantity, 0) ?? 0,
);

export function useCart() {
    return {
        cart,
        loading,
        error,
        itemCount,
        fetchCart,
        addItem,
        updateQuantity,
        removeItem,
        clearCart,
    };
}