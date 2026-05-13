CREATE TABLE customers (
    customer_id BIGSERIAL PRIMARY KEY,
    user_id BIGINT UNIQUE NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    phone VARCHAR(50),
    shipping_address TEXT,
    billing_address TEXT,
    birth_date DATE,
    dni_nif VARCHAR(20), -- Para facturación (útil en España)
    newsletter_subscribed BOOLEAN DEFAULT FALSE,
    total_spent DECIMAL(10, 2) DEFAULT 0,
    loyalty_points INTEGER DEFAULT 0,
    last_purchase_date TIMESTAMP WITH TIME ZONE,
    preferences JSONB, -- Para guardar preferencias de género, etc.
    notes TEXT,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- 1. Tabla categories
CREATE TABLE categories (
    category_id SERIAL PRIMARY KEY,
    category_name VARCHAR(100) UNIQUE NOT NULL,
    slug VARCHAR(120) UNIQUE NOT NULL, -- Para URLs amigables
    description TEXT,
    parent_category_id INTEGER REFERENCES categories(category_id) ON DELETE SET NULL,
    image_url TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    sort_order INTEGER DEFAULT 0,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- 2. Tabla products (mejorada)
CREATE TABLE products (
    product_id SERIAL PRIMARY KEY,
    artist VARCHAR(255) NOT NULL,
    album_title VARCHAR(255) NOT NULL,
    slug VARCHAR(280) UNIQUE NOT NULL, -- Para SEO/URLs
    genre VARCHAR(100),
    subgenre VARCHAR(100),
    release_year INTEGER,
    country VARCHAR(100),
    label VARCHAR(255),
    format VARCHAR(50), -- 'Vinyl', 'CD', 'Cassette', 'Digital'
    speed VARCHAR(20), -- '33⅓ RPM', '45 RPM', '78 RPM'
    size VARCHAR(20), -- '7"', '10"', '12"'
    catalog_number VARCHAR(100),
    barcode VARCHAR(50),
    condition VARCHAR(50), -- 'New', 'Used - Mint', etc. (si vendes usado)
    price DECIMAL(10, 2) NOT NULL,
    compare_at_price DECIMAL(10, 2), -- Para mostrar tachado (rebajas)
    stock_quantity INTEGER NOT NULL DEFAULT 0,
    low_stock_threshold INTEGER DEFAULT 5,
    description TEXT,
    tracklist JSONB, -- Lista de canciones en formato JSON
    cover_image_url TEXT,
    additional_images TEXT[], -- Array de URLs
    is_featured BOOLEAN DEFAULT FALSE,
    is_on_sale BOOLEAN DEFAULT FALSE,
    is_new BOOLEAN DEFAULT FALSE,
    views_count INTEGER DEFAULT 0,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Índices para productos
CREATE INDEX idx_products_artist ON products(artist);
CREATE INDEX idx_products_album ON products(album_title);
CREATE INDEX idx_products_genre ON products(genre);
CREATE INDEX idx_products_format ON products(format);
CREATE INDEX idx_products_price ON products(price);

-- 3. Tabla product_categories
CREATE TABLE product_categories (
    product_id INTEGER NOT NULL REFERENCES products(product_id) ON DELETE CASCADE,
    category_id INTEGER NOT NULL REFERENCES categories(category_id) ON DELETE CASCADE,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (product_id, category_id)
);

-- 4. Tabla carts (carritos activos)
CREATE TABLE carts (
    cart_id SERIAL PRIMARY KEY,
    customer_id INTEGER REFERENCES customers(customer_id) ON DELETE SET NULL,
    session_id VARCHAR(100), -- Para invitados
    cart_token UUID DEFAULT gen_random_uuid(), -- Token único para recuperar carrito
    cart_data JSONB, -- Podrías guardar el carrito serializado si quieres
    expires_at TIMESTAMP WITH TIME ZONE, -- Para limpiar carritos abandonados
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- 5. Tabla cart_items
CREATE TABLE cart_items (
    cart_item_id SERIAL PRIMARY KEY,
    cart_id INTEGER NOT NULL REFERENCES carts(cart_id) ON DELETE CASCADE,
    product_id INTEGER NOT NULL REFERENCES products(product_id) ON DELETE CASCADE,
    quantity INTEGER NOT NULL CHECK (quantity > 0),
    price_at_time DECIMAL(10, 2), -- Precio cuando se añadió
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    UNIQUE (cart_id, product_id)
);

-- 6. Tabla orders
CREATE TABLE orders (
    order_id SERIAL PRIMARY KEY,
    order_number VARCHAR(50) UNIQUE NOT NULL, -- Ej: 'ORD-2024-00001'
    customer_id INTEGER NOT NULL REFERENCES customers(customer_id) ON DELETE RESTRICT,
    order_date TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    subtotal DECIMAL(10, 2) NOT NULL,
    shipping_cost DECIMAL(10, 2) DEFAULT 0,
    tax_amount DECIMAL(10, 2) DEFAULT 0,
    discount_amount DECIMAL(10, 2) DEFAULT 0,
    total_amount DECIMAL(10, 2) NOT NULL,
    
    -- Direcciones (copiadas en el momento)
    shipping_address TEXT NOT NULL,
    shipping_city VARCHAR(100),
    shipping_postal_code VARCHAR(20),
    shipping_country VARCHAR(100),
    billing_address TEXT NOT NULL,
    billing_city VARCHAR(100),
    billing_postal_code VARCHAR(20),
    billing_country VARCHAR(100),
    
    -- Estado del pedido
    order_status VARCHAR(50) NOT NULL DEFAULT 'pending',
    payment_status VARCHAR(50) DEFAULT 'pending', -- 'pending', 'paid', 'refunded'
    shipping_status VARCHAR(50) DEFAULT 'not_shipped',
    
    -- Seguimiento
    tracking_number VARCHAR(100),
    shipping_carrier VARCHAR(50),
    estimated_delivery_date DATE,
    
    -- Notas
    customer_notes TEXT,
    admin_notes TEXT,
    
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Índices para orders
CREATE INDEX idx_orders_customer ON orders(customer_id);
CREATE INDEX idx_orders_status ON orders(order_status);
CREATE INDEX idx_orders_order_number ON orders(order_number);
CREATE INDEX idx_orders_date ON orders(order_date);

-- 7. Tabla order_items
CREATE TABLE order_items (
    order_item_id SERIAL PRIMARY KEY,
    order_id INTEGER NOT NULL REFERENCES orders(order_id) ON DELETE CASCADE,
    product_id INTEGER NOT NULL REFERENCES products(product_id) ON DELETE RESTRICT,
    product_snapshot JSONB NOT NULL, -- Copia del producto en el momento de la compra
    quantity INTEGER NOT NULL CHECK (quantity > 0),
    price_per_unit DECIMAL(10, 2) NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- 8. Tabla reviews
CREATE TABLE reviews (
    review_id SERIAL PRIMARY KEY,
    product_id INTEGER NOT NULL REFERENCES products(product_id) ON DELETE CASCADE,
    customer_id INTEGER NOT NULL REFERENCES customers(customer_id) ON DELETE CASCADE,
    rating INTEGER NOT NULL CHECK (rating >= 1 AND rating <= 5),
    title VARCHAR(255),
    comment TEXT,
    pros TEXT, -- Puntos positivos
    cons TEXT, -- Puntos negativos
    is_verified_purchase BOOLEAN DEFAULT FALSE,
    helpful_votes INTEGER DEFAULT 0,
    unhelpful_votes INTEGER DEFAULT 0,
    status VARCHAR(20) DEFAULT 'pending', -- 'pending', 'approved', 'rejected'
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    UNIQUE (product_id, customer_id)
);

-- 9. Tabla wishlists (lista de deseos)
CREATE TABLE wishlists (
    wishlist_id SERIAL PRIMARY KEY,
    customer_id INTEGER NOT NULL REFERENCES customers(customer_id) ON DELETE CASCADE,
    product_id INTEGER NOT NULL REFERENCES products(product_id) ON DELETE CASCADE,
    notes TEXT, -- Por qué lo quieres? :)
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    UNIQUE (customer_id, product_id)
);