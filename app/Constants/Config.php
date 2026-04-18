<?php

namespace App\Constants;

// This class consolidates all application-wide constants to eliminate magic numbers and strings.
final class Config
{
    // Pagination settings for various list views.
    public const CATALOG_PRODUCTS_PER_PAGE = 15;
    public const ORDERS_PER_PAGE = 20;
    public const ADDRESSES_PER_PAGE = 50;

    // Default currency for all price calculations and displays.
    public const DEFAULT_CURRENCY = 'INR';

    // Session storage keys.
    public const GUEST_CART_SESSION_KEY = 'guest_cart_session_id';
    public const ORDER_REFERENCE_SESSION_KEY = 'order_reference';

    // GST (Goods and Services Tax) rate for India.
    public const DEFAULT_GST_RATE = 18.0;

    // Default pagination parameters when not specified.
    public const DEFAULT_PAGE_NUMBER = 1;
    public const DEFAULT_ITEMS_PER_PAGE = 15;

    // Cart operation constraints.
    public const MIN_CART_QUANTITY = 1;
    public const MAX_BULK_DISCOUNT_QUANTITIES = 10;

    // File upload constraints.
    public const MAX_UPLOAD_FILE_SIZE_MB = 10;
    public const MAX_UPLOAD_FILE_SIZE_BYTES = self::MAX_UPLOAD_FILE_SIZE_MB * 1024 * 1024;

    // User types for business logic routing.
    public const USER_TYPE_B2B = 'b2b';
    public const USER_TYPE_B2C = 'b2c';
    public const USER_TYPE_INTERNAL = 'internal';
    public const USER_TYPE_ADMIN = 'admin';

    // B2B user subcategories.
    public const B2B_TYPE_DEALER = 'dealer';
    public const B2B_TYPE_DISTRIBUTOR = 'distributor';
    public const B2B_TYPE_INSTITUTIONAL = 'institutional';

    // Portal identifiers for template/view routing.
    public const PORTAL_B2B = 'b2b';
    public const PORTAL_B2C = 'b2c';

    // Default image fallback paths.
    public const FALLBACK_PRODUCT_IMAGE = 'upload/categories/image1.jpg';
    public const FALLBACK_CATEGORY_IMAGES = [
        'upload/categories/image1.jpg',
        'upload/categories/image2.jpg',
        'upload/categories/image5.jpg',
    ];

    // Permission checks cache duration.
    public const PERMISSION_CACHE_DURATION_SECONDS = 3600;

    // Role defaults.
    public const DEFAULT_ROLE_B2B = 'b2b_user';
    public const DEFAULT_ROLE_B2C = 'b2c_customer';
    public const DEFAULT_ROLE_INTERNAL = 'internal_user';
    public const DEFAULT_ROLE_ADMIN = 'admin';
    public const DEFAULT_ROLE_DELEGATED_ADMIN = 'delegated_admin';
}
