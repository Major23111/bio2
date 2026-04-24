# 🤖 Biogenix — AI Agent Prompt Bank

> **Purpose:** Copy-paste these prompts to an AI coding agent (Antigravity, Cursor, Windsurf, etc.) to implement the remaining backend functionality for the Biogenix Laravel project.
>
> **Usage:** Give one prompt at a time. Each prompt is self-contained with full context. After the agent completes one prompt, verify it works, then move to the next.
>
> **Order:** Follow the numbered sequence — earlier prompts establish security and patterns that later prompts depend on.

---

## PHASE 1: Security & Foundation

---

### Prompt 1 — Secure Admin Routes & Clean Imports

```
## Task: Secure Admin Panel Routes & Fix Broken Imports

### Context
This is a Laravel 12 project at the root directory. The admin panel routes in `routes/web.php` (lines 118-143) are completely unprotected — any unauthenticated user can access `/adminPanel/*` URLs. Also, there are 3 controller imports at the top of `web.php` that reference classes that don't exist yet:
- `AdminUserManagementController` (line 3)
- `DashboardController` (line 8)
- `ProductCrudController` (line 18)

### Requirements
1. **Comment out or remove** the 3 broken imports from `routes/web.php` until those controllers are actually created.
2. **Remove the duplicate route** on line 120 (`admin.dashboard` is defined twice on lines 119-120).
3. **Wrap ALL admin routes** (lines 118-143) in a route group with `['middleware' => ['auth'], 'prefix' => 'adminPanel', 'as' => 'admin.']`.
4. **Create a new middleware** `app/Http/Middleware/EnsureUserIsAdmin.php` that checks `auth()->user()->isAdmin()` (the `isAdmin()` method already exists on `App\Models\Authorization\User` and returns true for 'admin' and 'delegated_admin' user types). If the user is not admin, redirect to `/` with an error flash message.
5. **Register** this middleware in `bootstrap/app.php` (or wherever middleware is registered in this Laravel 12 project).
6. **Add the admin middleware** to the admin route group.
7. **Update the admin sidebar** (`resources/views/admin/partials/sidebar.blade.php`) to show the actual logged-in user's name and email instead of the hardcoded "Super Admin" / "admin@biogenix.com". The user object is available via `auth()->user()`.
8. **Update the sidebar logout button** to actually submit a POST to the existing `logout` route (same pattern used in `resources/views/partials/header.blade.php` lines 108-111).

### Existing Patterns to Follow
- Look at the existing customer-side `@auth` / `@endauth` pattern in `resources/views/layouts/app.blade.php`
- Look at existing middleware in `app/Http/Middleware/` for the coding style
- The User model is at `app/Models/Authorization/User.php`

### Do NOT
- Change any admin blade view content
- Modify any existing customer-facing routes
- Create any new controllers yet
```

---

## PHASE 2: Admin Core Controllers

---

### Prompt 2 — Admin Dashboard Controller (Real Data)

```
## Task: Create Admin Dashboard Controller with Real Database Data

### Context
This is a Laravel 12 project. The admin dashboard view is at `resources/views/admin/dashboard.blade.php`. It currently displays hardcoded KPI cards and a mock orders table. I need a controller that fetches real data from the database and passes it to this view.

### Existing Models & Tables
- `App\Models\Order\Order` — has `status` field (use `App\Enums\OrderStatus` enum), `created_at`, relationships: `items`, `user`, `orderAddresses`
- `App\Models\Authorization\User` — has `user_type` field ('b2c', 'b2b', 'admin', 'delegated_admin')
- `App\Models\SupportTicket\SupportTicket` — has `status`, `priority`, `created_at`
- `App\Models\Product\Product` — product catalog
- `App\Models\Order\OrderItem` — has `line_total`, `quantity`

### Requirements
1. **Create** `app/Http/Controllers/Admin/DashboardController.php`
2. **Implement** an `index()` method that queries:
   - Total orders count + today's orders count
   - Total revenue (sum of order `grand_total` or sum of `OrderItem.line_total`)
   - Pending dispatch count (orders with status like 'pending' or 'processing')
   - Same-day delivery count (urgent orders)
   - 5 most recent priority orders for the table (with customer name, product type, total value, status)
   - Revenue data for the chart (weekly/monthly breakdown)
   - Open support tickets count
3. **Update** `routes/web.php` — replace the `Route::view('/adminPanel/dashboard', ...)` with `Route::get('/adminPanel/dashboard', [AdminDashboardController::class, 'index'])` inside the admin route group.
4. **Update** `resources/views/admin/dashboard.blade.php` to use the passed variables instead of hardcoded values. Keep the exact same HTML structure and Tailwind classes — only replace the hardcoded text/numbers with `{{ $variable }}` blade syntax.
5. Pass percentage change values as well (compare current period vs previous period).

### Patterns to Follow
- Look at how `app/Http/Controllers/Home/HomeController.php` passes data to views
- Look at how `app/Http/Controllers/Order/OrderController.php` queries orders
- Follow the existing try-catch + Log::error pattern used in all controllers (see `app/Http/Controllers/Controller.php` for base methods)
- Use the existing `admin.layout` Blade layout (the dashboard view already extends it)

### Do NOT
- Change the visual design or Tailwind classes
- Create any new migration files
- Modify any customer-facing code
```

---

### Prompt 3 — Admin Product Management CRUD

```
## Task: Create Admin Product Management Backend (Full CRUD)

### Context
This is a Laravel 12 project. Admin product pages exist at:
- `resources/views/admin/products/index.blade.php` (product listing with search, category filter, pagination — currently all hardcoded)
- `resources/views/admin/products/create.blade.php` (product creation form — currently does nothing on submit)

### Existing Models & Services
- `App\Models\Product\Product` — main product model with relationships: `category`, `subcategory`, `images`, `prices`, `specifications`, `variants`, `technicalResources`
- `App\Models\Product\Category`, `Subcategory`, `ProductImage`, `ProductPrice`, `ProductSpecification`, `ProductVariant`, `VariantAttribute`, `ProductTechnicalResource`
- `App\Services\Product\ProductCatalogService` (15KB) — has methods for listing/filtering products
- `App\Services\Product\ProductDetailService` (8KB) — has methods for fetching product details
- `App\Services\Product\ProductUtilityService` (9KB) — utility methods

### Existing Storefront Controller
- `app/Http/Controllers/Product/ProductController.php` — read-only storefront controller. DO NOT modify this.

### Requirements
1. **Create** `app/Http/Controllers/Admin/ProductController.php` (namespaced under Admin to avoid conflict with existing ProductController)
2. **Implement methods:**
   - `index()` — List products with search, category filter, status filter, and pagination (use Laravel's built-in `->paginate()`)
   - `create()` — Show the create form, passing categories and subcategories
   - `store()` — Validate and create a new product with: name, SKU, description, category_id, subcategory_id, base price, stock quantity, status, images (file upload), specifications (key-value pairs)
   - `edit($productId)` — Show edit form pre-populated with product data
   - `update($productId)` — Validate and update product
   - `destroy($productId)` — Soft delete or hard delete a product
3. **Create** `app/Services/Admin/ProductAdminService.php` for the business logic (follow the pattern of existing services in `app/Services/`)
4. **Update routes**: Replace the `Route::view` lines for `admin.products` and `admin.products.create` with proper controller routes. Add routes for store, edit, update, destroy.
5. **Update** `resources/views/admin/products/index.blade.php`:
   - Replace hardcoded product rows with a `@foreach($products as $product)` loop
   - Wire up the search input to submit a GET request with a `search` query param
   - Wire up category pills to filter by category
   - Replace hardcoded pagination with `{{ $products->links() }}`
6. **Update** `resources/views/admin/products/create.blade.php`:
   - Wire the form `action` to `route('admin.products.store')`
   - Add `@csrf` if not already present
   - Populate category/subcategory dropdowns from passed data
   - Handle validation errors with `@error` directives
7. **Handle image uploads** using `App\Services\Utility\FileHandlingService` (already exists at `app/Services/Utility/FileHandlingService.php`)

### Patterns
- Follow the controller pattern in `app/Http/Controllers/SupportTicket/SupportTicketController.php` (try-catch, Log::error, redirect back with errors)
- Use `decrypt.route` middleware for encrypted IDs (existing pattern)
- Use the existing admin layout (`admin.layout`)

### Do NOT
- Modify the storefront `ProductController`
- Change visual design / Tailwind CSS classes
- Create new database migrations
```

---

### Prompt 4 — Admin Order Management Backend

```
## Task: Create Admin Order Management Backend

### Context
This is a Laravel 12 project. Admin order pages exist at:
- `resources/views/admin/orders/index.blade.php` (30KB) — order listing with status pills, search, sort, pagination — all hardcoded
- `resources/views/admin/orders/details.blade.php` (32KB) — single order details — hardcoded

### Existing Models & Services
- `App\Models\Order\Order` — fields: `order_number`, `status`, `payment_status`, `grand_total`, `user_id`, `created_at`. Relationships: `user`, `items`, `orderAddresses`
- `App\Models\Order\OrderItem` — fields: `product_id`, `quantity`, `unit_price`, `line_total`
- `App\Models\Order\OrderAddress` — shipping/billing addresses
- `App\Enums\OrderStatus` — enum with status values
- `App\Services\Order\OrderLifecycleService` (34KB) — extensive order lifecycle management
- `App\Services\Order\OrderFormatterService` (5KB) — formats order data for views
- `App\Services\Order\OrderCalculationService` (7KB)
- Existing `OrderController` at `app/Http/Controllers/Order/OrderController.php` — customer-facing. DO NOT modify.

### Requirements
1. **Create** `app/Http/Controllers/Admin/OrderController.php`
2. **Implement methods:**
   - `index(Request $request)` — List all orders with: search (by order number, customer name), status filter, date range filter, pagination. Use `Order::with(['user', 'items'])->latest()->paginate(20)`
   - `show($orderId)` — Show full order details with items, addresses, customer info, order timeline
   - `updateStatus($orderId, Request $request)` — Update order fulfillment status (Pending → Processing → Dispatched → Delivered, or Cancelled). Use existing `OrderLifecycleService` if applicable.
   - `exportCsv(Request $request)` — Generate and download CSV of filtered orders (replace the client-side JS CSV export with a proper server-side export)
3. **Update routes**: Replace `Route::view` for `admin.orders` and `admin.orders.view` with controller routes. Add routes for status update and CSV export.
4. **Update** `resources/views/admin/orders/index.blade.php`:
   - Replace hardcoded rows with `@foreach($orders as $order)` loop
   - Wire status pills to filter via GET params
   - Wire search to submit GET request
   - Replace pagination with `{{ $orders->links() }}`
   - Wire "View" action icon to `route('admin.orders.view', encrypt($order->id))`
5. **Update** `resources/views/admin/orders/details.blade.php`:
   - Replace hardcoded data with `$order` variable
   - Add a status update dropdown/button that POSTs to the status update route
   - Show real order items, addresses, and customer information

### Patterns
- Look at existing `OrderController@showOrderCrud` for how orders are queried and formatted
- Use `OrderFormatterService` for consistent data formatting
- Follow the admin layout pattern extending `admin.layout`
- Use `decrypt.route` middleware for encrypted route parameters

### Do NOT
- Modify the customer-facing OrderController
- Change the visual design
- Create new migrations
```

---

### Prompt 5 — Admin Customer Management Backend

```
## Task: Create Admin Customer Management Backend

### Context
This is a Laravel 12 project. Admin customer pages exist at:
- `resources/views/admin/customers/index.blade.php` (29KB) — customer listing — hardcoded
- `resources/views/admin/customers/directory.blade.php` (20KB) — customer directory — hardcoded
- `resources/views/admin/users/index.blade.php` (25KB) — user management page (exists but has no route defined)

### Existing Models
- `App\Models\Authorization\User` — fields: `name`, `email`, `phone`, `user_type` ('b2c', 'b2b', 'admin', 'delegated_admin'), `status`, `company_id`, `approved_at`, `approved_by_user_id`, `created_at`
- `App\Models\Authorization\Company` — B2B company info
- `App\Models\Order\Order` — to show customer order history/stats
- `App\Models\SupportTicket\SupportTicket` — to show customer ticket count

### Requirements
1. **Create** `app/Http/Controllers/Admin/CustomerController.php`
2. **Implement methods:**
   - `index(Request $request)` — List all B2C + B2B customers (exclude admin/delegated_admin). Search by name, email, phone. Filter by user_type, status, date range. Include counts: total orders, total spent, open tickets per customer. Paginate.
   - `directory(Request $request)` — Alphabetical directory listing of customers grouped by first letter.
   - `show($userId)` — Detailed customer profile: personal info, company (for B2B), order history summary, support ticket summary, addresses.
   - `updateStatus($userId, Request $request)` — Approve/suspend/reactivate a customer account. For B2B: set `approved_at` and `approved_by_user_id`.
   - `users(Request $request)` — Admin user management page listing admin + delegated_admin users.
3. **Update routes**: Replace `Route::view` for `admin.customers` and `admin.customer-directory`. Add route for `admin.users` pointing to the users view.
4. **Update views** to render real data instead of hardcoded content (same pattern as other admin views).

### Patterns
- Follow the same controller + service pattern used elsewhere
- Use Eloquent `withCount()` for efficient counting
- Use the `DataVisibilityService` at `app/Services/Authorization/DataVisibilityService.php` if applicable for scoping data

### Do NOT
- Create new migrations
- Modify customer-facing profile pages
```

---

### Prompt 6 — Admin Support Ticket Management Backend

```
## Task: Create Admin Support Ticket Management Backend

### Context
This is a Laravel 12 project. Admin support ticket pages exist at:
- `resources/views/admin/support-tickets/index.blade.php` (29KB) — ticket listing — hardcoded
- `resources/views/admin/support-tickets/ui-fields-modification.blade.php` (31KB) — category/field config — hardcoded

### Existing Models & Services
- `App\Models\SupportTicket\SupportTicket` — fields: `subject`, `description`, `status`, `priority`, `category_slug`, `owner_user_id`, `assigned_admin_user_id`, `created_at`. Relationships: `owner` (User), `assignedAdmin` (User), `comments`, `attachments`, `histories`
- `App\Models\SupportTicket\SupportTicketComment` — fields: `support_ticket_id`, `user_id`, `body`, `is_internal_note`
- `App\Models\SupportTicket\SupportTicketAttachment` — file attachments
- `App\Models\SupportTicket\SupportTicketHistory` — status/assignment changes
- `App\Models\SupportTicket\SupportTicketCategory` — category definitions
- `App\Services\SupportTicket\SupportTicketService` (11KB) — existing service for customer-side ticket operations

### Customer-side Controller
- `app/Http/Controllers/SupportTicket/SupportTicketController.php` (11KB) — has `index`, `store`, `show`, `downloadAttachment`. DO NOT modify.

### Requirements
1. **Create** `app/Http/Controllers/Admin/SupportTicketController.php`
2. **Implement methods:**
   - `index(Request $request)` — List ALL tickets across all users. Filter by status (open, in-progress, resolved, closed), priority (low, medium, high, urgent), category, assigned admin. Search by subject, ticket ID, customer name. Paginate.
   - `show($ticketId)` — Full ticket detail with conversation thread (comments), attachments, history timeline, customer info
   - `addComment($ticketId, Request $request)` — Admin posts a reply. Create a `SupportTicketComment` with `user_id` = admin user, `is_internal_note` = false. Also support internal notes with `is_internal_note` = true.
   - `updateStatus($ticketId, Request $request)` — Change ticket status. Log the change in `SupportTicketHistory`.
   - `assignTicket($ticketId, Request $request)` — Assign ticket to an admin user. Log in history.
   - `manageCategories(Request $request)` — CRUD for `SupportTicketCategory` (for the ui-fields-modification page)
3. **Create** `app/Services/Admin/SupportTicketAdminService.php` for business logic
4. **Update routes** and **update views** to use real data

### Patterns
- Look at how the customer-side `SupportTicketController@store` works
- Follow existing try-catch + logging pattern
- Use `SupportTicketHistory` model to audit all admin actions

### Do NOT
- Modify the customer-facing SupportTicketController
- Create new migrations (all tables exist)
```

---

## PHASE 3: Admin Operations

---

### Prompt 7 — Admin Pricing Management Backend

```
## Task: Create Admin Pricing Management Backend

### Context
Admin pricing page: `resources/views/admin/pricing/index.blade.php` (22KB) — static.

### Existing Models
- `App\Models\Pricing\ProductBulkPrice` — bulk pricing tiers per product
- `App\Models\Pricing\Coupon` — discount coupons
- `App\Services\Pricing\PriceService` (22KB) — pricing calculation logic
- `App\Services\Coupon\CouponService` (16KB) — coupon validation/application

### Requirements
1. **Create** `app/Http/Controllers/Admin/PricingController.php`
2. **Implement:**
   - `index()` — List all products with their pricing tiers, bulk prices, and active coupons
   - `updateBulkPrice($productId, Request $request)` — Create/update bulk pricing tiers for a product (quantity ranges and corresponding prices)
   - `couponIndex()` — List all coupons
   - `createCoupon(Request $request)` — Create a new coupon with: code, type (percentage/flat), value, min order, max discount, expiry date, usage limit, status
   - `updateCoupon($couponId, Request $request)` — Update coupon
   - `deleteCoupon($couponId)` — Soft delete coupon
3. **Update routes and views** to use real data

### Do NOT
- Modify PriceService or CouponService (customer-facing)
- Create new migrations
```

---

### Prompt 8 — Admin PI/Quotation Management Backend

```
## Task: Create Admin Proforma Invoice Management Backend

### Context
Admin PI pages:
- `resources/views/admin/pi-quotation.blade.php` (20KB) — PI request listing — static
- `resources/views/admin/pi-quotation-create.blade.php` (42KB) — create/approve PI — static

### Existing Models & Services
- `App\Models\Proforma\ProformaInvoice` — fields include `status`, `owner_user_id`, `approved_by_user_id`, `approved_at`, `pi_number`, `grand_total`
- `App\Models\Proforma\ProformaInvoiceItem` — line items
- `App\Services\Proforma\ProformaInvoiceService` (2KB) — basic service
- Customer-side: `ProformaInvoiceController` handles request submission

### Requirements
1. **Create** `app/Http/Controllers/Admin/ProformaInvoiceController.php`
2. **Implement:**
   - `index(Request $request)` — List all PI requests with status filter (pending, approved, rejected), customer info, date, total. Paginate.
   - `show($piId)` — Full PI details with line items and customer info
   - `approve($piId, Request $request)` — Approve a PI request, set `approved_at`, `approved_by_user_id`, generate a PDF using the existing `InvoiceService` at `app/Services/Invoice/InvoiceService.php`
   - `reject($piId, Request $request)` — Reject with reason
   - `downloadPdf($piId)` — Generate and download the approved PI as PDF (use `InvoiceService` and `views/invoice/quotation-pdf.blade.php`)
3. **Update routes and views**

### Do NOT
- Modify customer-facing ProformaInvoiceController
- Create new migrations
```

---

### Prompt 9 — Wire RBAC Controller to Admin Routes

```
## Task: Wire Existing RBAC Controller to Admin Panel Routes

### Context
The RBAC controller already exists and is fully functional: `app/Http/Controllers/Authorization/RoleAndPermissionController.php` (198 lines). It has methods for: getRole, addRole, updateRole, deleteRole, createPermission, updatePermission, deletePermission, upsertPermissionsForRole.

The corresponding service also exists: `app/Services/Authorization/RolePermissionAdminCrudService.php` (8KB).

However, the admin RBAC routes in `routes/web.php` (lines 135-143) all use `Route::view()` — they render static blade views instead of using the controller.

### Admin RBAC Views (7 pages)
- `admin/RolePermissions/index.blade.php` (32KB)
- `admin/RolePermissions/add-role.blade.php` (10KB)
- `admin/RolePermissions/add-permission.blade.php` (8KB)
- `admin/RolePermissions/assign-dept-role.blade.php` (9KB)
- `admin/RolePermissions/add-override.blade.php` (14KB)
- `admin/RolePermissions/add-delegation.blade.php` (10KB)
- `admin/RolePermissions/grant-impersonation.blade.php` (12KB)

### Requirements
1. **Replace** the `Route::view()` calls in the admin route group with proper controller routes pointing to `RoleAndPermissionController`
2. **Add new routes** for the controller methods that don't have routes yet (POST for addRole, PUT for updateRole, DELETE for deleteRole, etc.)
3. **Update** `admin/RolePermissions/index.blade.php` to display real roles and permissions from the database. The controller's `getRole()` method already returns `rolePageData()` with: roles, permissions, editingRole, editingPermission, editingRolePermissionIds.
4. **Update** the add-role and add-permission views to have proper form actions pointing to the controller routes with `@csrf`
5. The remaining views (assign-dept-role, add-override, add-delegation, grant-impersonation) may need new controller methods — create them if needed, or leave them as static placeholders with a TODO comment.

### Patterns
- The controller already follows the project's standard pattern
- Use the admin route group with auth middleware

### Do NOT
- Modify the RoleAndPermissionController (it's already complete)
- Modify the RolePermissionAdminCrudService
- Create new migrations
```

---

### Prompt 10 — Admin FAQ Management Backend

```
## Task: Create Admin FAQ Management Backend

### Context
- Admin FAQ view exists: `resources/views/admin/faqs/index.blade.php` (16KB) — static
- Customer FAQ: `app/Http/Controllers/Faq/FaqController.php` reads FAQs from DB
- FAQ model exists: `App\Models\Faq\Faq` (check in `app/Models/Faq/`)
- FAQ service exists: `app/Services/Faq/FaqService.php`

### Requirements
1. **Create** `app/Http/Controllers/Admin/FaqController.php`
2. **Implement CRUD**: list, create, update, delete, reorder FAQs
3. **Add admin route** for FAQ management
4. **Update the admin FAQ view** to display real data and wire forms
5. Add a route in the admin sidebar (`admin/partials/sidebar.blade.php`) if not already listed

### Do NOT
- Modify the customer-facing FaqController
- Create new migrations
```

---

## PHASE 4: Transactional Features

---

### Prompt 11 — Payment Gateway Integration

```
## Task: Integrate Razorpay Payment Gateway

### Context
This is a Laravel 12 B2B/B2C e-commerce platform for biogenix supplies. Currently, orders are created in the database without any payment processing. The checkout flow is at:
- `app/Http/Controllers/Checkout/CheckoutController.php` (14KB) — `submitUserCheckoutOrder()` creates orders
- `app/Services/Checkout/CheckoutService.php` (25KB) — checkout business logic
- `resources/views/checkout.blade.php` (101KB) — checkout page UI
- `App\Models\Order\Order` has a `payment_status` field

### Requirements
1. **Install** `razorpay/razorpay` PHP SDK via composer
2. **Add** Razorpay credentials to `.env` and `config/services.php`
3. **Create** `app/Services/Payment/RazorpayService.php` with methods:
   - `createOrder($amount, $currency, $receipt)` — Create a Razorpay order
   - `verifyPayment($razorpayOrderId, $razorpayPaymentId, $razorpaySignature)` — Verify payment signature
4. **Modify** `CheckoutController@submitUserCheckoutOrder`:
   - After validating cart and calculating total, create a Razorpay order
   - Return the Razorpay order ID to the frontend
   - Add a new method `verifyPayment(Request $request)` that verifies the Razorpay callback, updates `payment_status` on the Order, and redirects to order confirmation
5. **Update** `checkout.blade.php`:
   - Load Razorpay checkout.js
   - After the user clicks "Place Order", open the Razorpay payment modal
   - On success, POST the payment details to the verify endpoint
   - Show appropriate error messages on payment failure
6. **Add routes** for payment verification
7. **Handle COD (Cash on Delivery)** as an alternative — if user selects COD, create the order with `payment_status = 'cod_pending'`

### Environment
- Currency: INR
- Test mode keys should work in `.env.example`

### Do NOT
- Change the order total calculation logic
- Modify the cart system
- Break the existing checkout UI design
```

---

### Prompt 12 — Email Notification Triggers

```
## Task: Implement Transactional Email Notifications

### Context
This is a Laravel 12 project. An email notification service exists:
- `app/Services/Notification/EmailNotificationService.php` (3KB) — currently only handles password reset
- `app/Services/Notification/Providers/` — notification provider directory
- Email templates exist at `resources/views/email-template/auth/` and `resources/views/email-template/order/`

### Requirements
1. **Expand** `EmailNotificationService` with new methods:
   - `sendOrderConfirmation(User $user, Order $order)` — Send after successful order placement
   - `sendOrderStatusUpdate(User $user, Order $order, string $oldStatus, string $newStatus)` — Send when admin updates order status
   - `sendSupportTicketAcknowledgment(User $user, SupportTicket $ticket)` — Send after ticket creation
   - `sendSupportTicketReply(User $user, SupportTicket $ticket, SupportTicketComment $comment)` — Send when admin replies
   - `sendPIApprovalNotification(User $user, ProformaInvoice $pi)` — Send when admin approves/rejects PI
   - `sendMeetingConfirmation(User $user, $meetingData)` — Send after meeting booking
2. **Create Laravel Mailable classes** in `app/Mail/` for each notification type
3. **Create email templates** in `resources/views/email-template/` matching the existing template design (check the auth templates for the HTML email style)
4. **Wire triggers** into existing flows:
   - In `CheckoutService` or `OrderLifecycleService` → call `sendOrderConfirmation` after order creation
   - In `SupportTicketService` → call `sendSupportTicketAcknowledgment` after ticket creation
   - In `BookMeetingController` → call `sendMeetingConfirmation` after booking
5. **Use Laravel's queue system** (`ShouldQueue`) for all emails to avoid blocking the request

### Do NOT
- Change existing email template styling
- Break existing password reset email functionality
```

---

### Prompt 13 — Customer Order Tracking UI

```
## Task: Build Order Tracking Timeline UI

### Context
The customer order detail view exists at `resources/views/order/show.blade.php` but it's a stub (only 1KB). The route `orders.show` exists and points to `OrderController@getOrderById` which already fetches the order.

There's also a customer orders listing at `resources/views/userProfile/orders/index.blade.php` with an order modal at `resources/views/userProfile/orders/order-modal.blade.php`.

### Requirements
1. **Rebuild** `resources/views/order/show.blade.php` with a premium order tracking page:
   - Order summary header (order number, date, payment status, total)
   - Visual progress timeline showing order status: Placed → Confirmed → Processing → Dispatched → In Transit → Delivered
   - Highlight the current status step
   - Order items table with product name, quantity, unit price, line total
   - Shipping and billing addresses
   - Estimated delivery date (if available)
   - Action buttons: Download Invoice, Reorder, Contact Support
2. **Ensure** the controller passes all needed data (it likely already does via `OrderFormatterService`)
3. **Use the existing layout** `layouts/app.blade.php` (storefront layout)
4. **Follow the existing UI style** — use the same Tailwind CSS design tokens, rounded cards, and premium aesthetic as other pages like `checkout.blade.php`

### Do NOT
- Create new routes (the route already exists)
- Modify the controller
- Change the existing customer orders listing UI
```

---

### Prompt 14 — Admin Settings & Remaining Infrastructure

```
## Task: Build Admin Global Settings and Sync Monitor Backends

### Context
Two admin pages need backends:
- `resources/views/admin/global-settings/index.blade.php` (7KB) — system configuration
- `resources/views/admin/sync-monitor/index.blade.php` (16KB) — sync/job monitoring

### Requirements

#### Global Settings
1. **Create** a `settings` database table (migration) with columns: `key` (string, unique), `value` (text, nullable), `group` (string, for categorization), `type` (string: 'string', 'boolean', 'number', 'json'), `updated_at`
2. **Create** `App\Models\Setting.php` model
3. **Create** `app/Http/Controllers/Admin/SettingsController.php` with:
   - `index()` — Show all settings grouped by category
   - `update(Request $request)` — Batch update settings
4. **Seed initial settings**: site name, site tagline, contact email, support phone, GST percentage, default currency, maintenance mode toggle, max upload size, order auto-cancel hours
5. **Update routes and views**

#### Sync Monitor
1. **Create** `app/Http/Controllers/Admin/SyncMonitorController.php`
2. **Implement:**
   - `index()` — Show recent Laravel job/queue history, failed jobs (from `failed_jobs` table if exists), and cache status
   - Use Laravel's built-in `DB::table('failed_jobs')` if the jobs table exists
   - Show last run time of any scheduled tasks
3. **Update routes and views**

### Do NOT
- This is lower priority — keep it simple and functional
- Don't over-engineer the sync monitor
```

---

## Quick Reference: File Map

| Layer | Customer-Side (Done) | Admin-Side (Needed) |
|---|---|---|
| **Controllers** | `Home`, `Product`, `Cart`, `Checkout`, `Order`, `Profile`, `CustomerAddress`, `Proforma`, `Quotation`, `SupportTicket`, `BookMeeting`, `ContactUs`, `Faq`, `Quiz`, `SignupEmailOtp` | `Admin/DashboardController`, `Admin/ProductController`, `Admin/OrderController`, `Admin/CustomerController`, `Admin/SupportTicketController`, `Admin/PricingController`, `Admin/ProformaInvoiceController`, `Admin/FaqController`, `Admin/SettingsController`, `Admin/SyncMonitorController` |
| **Services** | `Cart`, `Checkout`, `Coupon`, `Invoice`, `Notification`, `Order` (4 files), `Pricing`, `Product` (3 files), `Profile` (2 files), `Proforma`, `Quotation`, `SupportTicket`, `Authorization` (4 files), `Utility` (3 files) | `Admin/ProductAdminService`, `Admin/SupportTicketAdminService`, `Payment/RazorpayService` |
| **Models** | 35+ models across 12 domains | `Setting` (new) |
| **Migrations** | 22 migrations (all run) | 1 new (settings table) |
