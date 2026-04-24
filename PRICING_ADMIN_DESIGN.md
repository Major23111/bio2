# Product Pricing Management - AdminPanel Design Document

**Document Version**: 2.0 (Admin Workflow Focused)  
**Last Updated**: April 14, 2026  
**Status**: Design Specification (Ready for Implementation)

---

## Table of Contents

1. [Admin Workflow Overview](#admin-workflow-overview)
2. [Current System Architecture](#current-system-architecture)
3. [Pricing List Dashboard](#pricing-list-dashboard)
4. [Add Pricing Wizard Flow](#add-pricing-wizard-flow)
5. [Edit & Delete Operations](#edit--delete-operations)
6. [Implementation Details](#implementation-details)
7. [Database Operations](#database-operations)
8. [Service Layer Design](#service-layer-design)

---

## Admin Workflow Overview

### Complete Pricing Setup for One Product

**Scenario**: Admin needs to set up pricing for product "BX-CAT-001 (Catalyst Reagent)"

```
STEP 1: PRICING DASHBOARD
┌──────────────────────────────────────────────┐
│ Pricing Management                           │
│                                              │
│ ┌────────────────────────────────────────┐  │
│ │ Products without pricing:               │  │
│ │ ✓ BX-CAT-001 - Catalyst Reagent  [+]  │  │
│ │ ✓ BX-DIA-002 - Diagnostic Kit    [+]  │  │
│ │                                        │  │
│ │ Products with pricing:                 │  │
│ │ ✓ BX-ENZ-001 [Edit] [View] [Delete]  │  │
│ └────────────────────────────────────────┘  │
└──────────────────────────────────────────────┘
        ↓ Click [+] to add pricing

STEP 2: SELECT PRODUCT
┌──────────────────────────────────────────────┐
│ Add Pricing - Step 1: Select Product         │
│                                              │
│ Select Product: [BX-CAT-001 ▼]             │
│ Product Name: Catalyst Reagent              │
│ Category: Chemical Reagents                 │
│ Current Category GST: 18%                   │
│                                              │
│ ℹ Product Variant: Default (auto-created)   │
│                                              │
│ [Next →]                                    │
└──────────────────────────────────────────────┘
        ↓

STEP 3: BASE/PUBLIC PRICING
┌──────────────────────────────────────────────┐
│ Add Pricing - Step 2: Base Pricing           │
│                                              │
│ Base/Public Pricing (for all customers):    │
│                                              │
│ Base Unit Price: [150.00]                  │
│                                              │
│ Discount Configuration:                     │
│   ○ No Discount                             │
│   ◉ Percentage: [8.5] %                   │
│   ○ Fixed Amount: $[0]                     │
│                                              │
│ Tax Configuration:                          │
│   GST Rate: [18] % (from category)         │
│   ☑ Auto-calculate tax on final price      │
│                                              │
│ PRICE PREVIEW:                              │
│   Base Amount:        $150.00               │
│   Discount (8.5%):    -$12.75               │
│   Subtotal:           $137.25               │
│   Tax @ 18%:          +$24.71               │
│   ─────────────────────────────             │
│   Final Unit Price:   $161.96  ✓            │
│                                              │
│ [Back] [Next →]                             │
└──────────────────────────────────────────────┘
        ↓

STEP 4: B2B COMPANY PRICING (Optional)
┌──────────────────────────────────────────────┐
│ Add Pricing - Step 3: Company Overrides      │
│                                              │
│ Add custom pricing for B2B companies        │
│ (Skip this step if not needed)              │
│                                              │
│ ┌────────────────────────────────────────┐  │
│ │ Add new company pricing:                │  │
│ │                                        │  │
│ │ Company: [Search... ▼]                 │  │
│ │ Base Tier: [Dealer ▼]                 │  │
│ │                                        │  │
│ │ Override as Percentage:                │  │
│ │ [75] % of dealer price                │  │
│ │                                        │  │
│ │ Effective Date: [Date picker]          │  │
│ │ End Date: [leave blank = forever]      │  │
│ │                                        │  │
│ │ [Add Company Pricing]                  │  │
│ └────────────────────────────────────────┘  │
│                                              │
│ Added Company Overrides:                    │
│ ✓ Acme Pharma Corp - 75% (Dealer)         │  
│ ✓ Metro Hospital - 68% (Institutional)   │  
│                                              │
│ [Back] [Next →]                             │
└──────────────────────────────────────────────┘
        ↓

STEP 5: BULK QUANTITY TIERS (Optional)
┌──────────────────────────────────────────────┐
│ Add Pricing - Step 4: Bulk Pricing           │
│                                              │
│ Add quantity-based discounts:               │
│ (Skip this step if using flat pricing)      │
│                                              │
│ ┌────────────────────────────────────────┐  │
│ │ Add new bulk tier:                      │  │
│ │                                        │  │
│ │ Quantity Range:                        │  │
│ │   From: [10] units                     │  │
│ │   To: [24] units                       │  │
│ │                                        │  │
│ │ Discount from Base:                    │  │
│ │   ◉ Percentage: [6.67] %              │  │
│ │   ○ Fixed Amount: $[0]                │  │
│ │                                        │  │
│ │ Base Price Reference: $150.00          │  │
│ │ Calculated Unit Price: $140.00         │  │
│ │                                        │  │
│ │ [Add Bulk Tier]                        │  │
│ └────────────────────────────────────────┘  │
│                                              │
│ Added Bulk Tiers:                           │
│ ✓ 1-9 units: $150.00 (standard)            │
│ ✓ 10-24 units: $140.00 (6.67% off)        │
│ ✓ 25-99 units: $130.00 (13.33% off)       │
│ ✓ 100+ units: $120.00 (20% off)           │
│                                              │
│ [Back] [Save Pricing] [Cancel]              │
└──────────────────────────────────────────────┘
        ↓

FINAL CONFIRMATION
PRICING CREATED ✓
BX-CAT-001 pricing is now active in the system
```

### Key Principles
1. **One product at a time** - Admin focuses on single product
2. **Linear workflow** - Step by step progression
3. **Optional sections** - Can skip company or bulk pricing
4. **Real-time preview** - See final prices as they type
5. **No variant UI** - One default variant per product (managed internally)
6. **All in one flow** - Complete pricing setup without page jumps

---

## Current System Architecture

### How Pricing Works (Admin Needs to Know)

**One Product = One Default Variant (Auto-created)**
```
When you add a product to Biogenix:
1. Product record created (e.g., BX-CAT-001, "Catalyst Reagent")
2. Default ProductVariant auto-created internally
3. All pricing is linked to this variant
4. Admin never sees/manages variants directly
```

**Pricing Data Flow**

```
Admin Sets Pricing for Product
        ↓
Store in ProductPrice table
 - Base amount
 - Discount mode (%, fixed amount)
 - Discount value
 - GST rate
 - Calculated tax
 - Final price
        ↓
Store in ProductBulkPrice table (if bulk tiers added)
 - Min quantity threshold
 - Max quantity threshold
 - Unit price at that tier
        ↓
Customer browses product
        ↓
PriceService.resolveVariantPrice() looks up pricing
        ↓
Customer sees correct price based on:
 - Their user type (B2C, B2B dealer, etc.)
 - Quantity they want
 - Any company overrides
        ↓
Price applies in cart, checkout, PI generation
```

### Price Types Explained (From Admin Perspective)

| Price Type | Applies To | Admin Sets? | Example Use Case |
|-----------|-----------|-----------|------------------|
| **public** | Guests browsing | Yes | Show minimum price to unauthenticated users |
| **retail** | General public | Yes | Standard MSRP for retail customers |
| **logged_in** | B2C who logged in | Yes | Small discount for B2C loyalty |
| **dealer** | Distributor accounts | Yes | Special pricing for resellers (bulk buyers) |
| **institutional** | Hospitals, research labs | Yes | Volume pricing for large institutional buys |
| **company_price** | Specific company override | Yes | Negotiated contract price for one company |

### Important Fields Admin Must Provide

```
For EVERY product pricing setup:
✓ Base Unit Price (amount) - Required
✓ Discount Mode - None / Percentage / Fixed Amount
✓ Discount Value - If discount selected
✓ GST Rate - Default from category (18%), can override
✓ Auto-calculate Tax - Usually Yes

System calculates automatically:
✗ Tax Amount (base × gst_rate ÷ 100)
✗ Discount Amount (amount × discount%)
✗ Final Price = base - discount + tax
```

---

## Pricing List Dashboard

### Purpose
Show admin all products and their pricing status at a glance. Quick access to add, edit, or view pricing.

### Route & Entry Point
**Route**: `GET /adminPanel/pricing`

### Dashboard Layout

```
┌───────────────────────────────────────────────────────────────────┐
│ Pricing Management                                                │
│ Manage product pricing across public, dealer, and company tiers  │
├───────────────────────────────────────────────────────────────────┤
│                                                                   │
│ [Search Products...  ]  [Filter: All Products ▼]  [Sort: A-Z ▼]  │
│                                                                   │
│ STATISTICS                                                        │
│ ┌─────────────┬──────────────┬─────────────┐                    │
│ │ Total       │ With Pricing │ No Pricing  │                    │
│ │ Products    │ (Active)     │ (Needs...)  │                    │
│ │    47       │      36      │      11     │                    │
│ └─────────────┴──────────────┴─────────────┘                    │
│                                                                   │
│ ════════════════════════════════════════════════════════════════  │
│ PRODUCTS WITHOUT PRICING (11 products)                            │
│ ════════════════════════════════════════════════════════════════  │
│                                                                   │
│ ┌─────────────┬────────────────┬──────────┬─────────┬──────────┐ │
│ │ SKU         │ Name           │ Category │ Stock   │ Action   │ │
│ ├─────────────┼────────────────┼──────────┼─────────┼──────────┤ │
│ │ BX-CAT-001  │ Catalyst       │ Reagents │ 450     │ [+] Add  │ │
│ │             │ Reagent        │          │ units   │ Pricing  │ │
│ ├─────────────┼────────────────┼──────────┼─────────┼──────────┤ │
│ │ BX-DIA-002  │ Diagnostic Kit │ Diag.    │ 120     │ [+] Add  │ │
│ │             │                │ Kits     │ units   │ Pricing  │ │
│ ├─────────────┼────────────────┼──────────┼─────────┼──────────┤ │
│ │ BX-ENZ-003  │ Enzyme Powder  │ Chemicals│ 80      │ [+] Add  │ │
│ │             │                │          │ units   │ Pricing  │ │
│ └─────────────┴────────────────┴──────────┴─────────┴──────────┘ │
│                                                                   │
│ ════════════════════════════════════════════════════════════════  │
│ PRODUCTS WITH PRICING (36 products)                               │
│ ════════════════════════════════════════════════════════════════  │
│                                                                   │
│ ┌─────────┬──────────────┬────────┬──────────┬─────────┬────────┐│
│ │ SKU     │ Name         │ Price  │ Discount │ Company │ Bulk   ││
│ │         │              │        │ Tiers    │ Pricing │ Tiers  ││
│ ├─────────┼──────────────┼────────┼──────────┼─────────┼────────┤│
│ │BX-CAT   │ Catalyst     │$150.00│ 3 tiers │ 2 cos.  │ 4      ││
│ │-004     │ (Updated)    │(18%)  │✓        │ ✓       │ ✓      ││
│ │         │              │       │         │         │        ││
│ │ [Edit] [View] [Delete]                                       ││
│ ├─────────┼──────────────┼────────┼──────────┼─────────┼────────┤│
│ │BX-REA   │ HPLC Solvent │$45.00 │ 2 tiers │ None    │ 0      ││
│ │-001     │              │(18%)  │ ✓       │ -       │ -      ││
│ │         │              │       │         │         │        ││
│ │ [Edit] [View] [Delete]                                       ││
│ ├─────────┼──────────────┼────────┼──────────┼─────────┼────────┤│
│ │BX-DIA   │ Blood Test   │$75.00 │ 1 tier  │ 1 co.   │ 0      ││
│ │-005     │ Strip        │(5%)   │ ✓       │ ✓       │ -      ││
│ │         │              │       │         │         │        ││
│ │ [Edit] [View] [Delete]                                       ││
│ └─────────┴──────────────┴────────┴──────────┴─────────┴────────┘│
│                                         [Load More...]            │
│                                                                   │
└───────────────────────────────────────────────────────────────────┘
```

### Dashboard Elements

#### Search & Filter
- **Search**: By SKU or product name (real-time autocomplete)
- **Filter**: 
  - All Products
  - Products without pricing
  - Products with pricing
  - By category
- **Sort**: A-Z, Z-A, Recently updated, Least recently updated

#### Statistics Box
Shows quick overview:
- Total active products
- Products with pricing configured
- Products needing pricing setup

#### Products Without Pricing Table
- **Columns**: SKU | Name | Category | Stock | Action
- **Action Button**: "[+] Add Pricing" 
- **Click Handler**: Navigate to Add Pricing Wizard, pre-select this product

#### Products With Pricing Table
- **Columns**: SKU | Name | Base Price (with tax%) | Discount Tiers | Company Pricing | Bulk Tiers
- **Indicators**:
  - ✓ = Feature is configured
  - - = Feature not configured
  - Number = Count of entries
- **Action Buttons**: 
  - [Edit] - Opens Add Pricing Wizard to edit this product
  - [View] - Read-only view of all pricing details
  - [Delete] - Remove all pricing for this product

### Key Features
1. **No pagination limit shown** - Use pagination internally (25 per page)
2. **Easy at-a-glance status** - See what's configured and what's not
3. **One-click add pricing** - No additional form, goes straight to wizard
4. **Quick access to edit** - Admin can jump back to wizard to modify

---

## Add Pricing Wizard Flow

### Purpose
Step-by-step wizard to guide admin through complete pricing setup for ONE product.

### Routes
```
GET  /adminPanel/pricing/add              - Wizard start (Step 1)
GET  /adminPanel/pricing/add/step/{step}  - Wizard at step N
POST /adminPanel/pricing/save              - Store all pricing data
```

---

### STEP 1: Product Selection

**URL**: `GET /adminPanel/pricing/add` or `GET /adminPanel/pricing/add/step/1`

**Purpose**: Admin selects which product to set pricing for

```
┌─────────────────────────────────────────────────────────────┐
│ Add Product Pricing                                         │
│ ═══════════════════════════════════════════════════════════ │
│ Step 1 of 4: Select Product                                │
│ ═══════════════════════════════════════════════════════════ │
│                                                             │
│ Progress: [████        ] 25%                                │
│                                                             │
│ Select the product to configure pricing for:               │
│                                                             │
│ Product: [BX-CAT-001 ▼]                                   │
│            ↓ autocomplete search                            │
│          Shows: SKU | Name | Category | Stock              │
│                                                             │
│ ──────────────────────────────────────────────────────────  │
│ SELECTED PRODUCT DETAILS (Read-Only)                       │
│ ──────────────────────────────────────────────────────────  │
│                                                             │
│ SKU: BX-CAT-001                                            │
│ Name: Catalyst Reagent                                     │
│ Category: Chemical Reagents                                │
│ Category GST: 18%                                          │
│ Current Stock: 450 units                                   │
│ Product Variant: Default (1 variant - no choice)           │
│                                                             │
│ ℹ Note: Each product can have ONE pricing configuration.   │
│   If you need to update pricing, use [Edit] from the       │
│   dashboard instead.                                       │
│                                                             │
│ [Cancel] ..................... [Next →]                    │
└─────────────────────────────────────────────────────────────┘
```

**Elements:**
- **Product Dropdown**: Searchable, shows only products without pricing (unless editing)
- **Auto-populate Fields**:
  - SKU (read-only)
  - Product Name (read-only)
  - Category (read-only)
  - Category Default GST Rate (read-only, shown as reference)
  - Stock Quantity (read-only)
- **Variant Note**: Explains that one product = one default variant

**Validation:**
- Product must be selected (required)
- If product already has pricing and not in edit mode, show alert: "This product already has pricing. Use [Edit] from dashboard to modify."

**Buttons:**
- [Cancel] - Returns to pricing dashboard
- [Next →] - Proceed to Step 2 with selected product ID

---

### STEP 2: Base/Public Pricing

**URL**: `GET /adminPanel/pricing/add/step/2`

**Purpose**: Admin sets base price, discount, and tax information

```
┌─────────────────────────────────────────────────────────────┐
│ Add Product Pricing                                         │
│ ═══════════════════════════════════════════════════════════ │
│ Step 2 of 4: Base Pricing                                  │
│ ═══════════════════════════════════════════════════════════ │
│                                                             │
│ Progress: [████████          ] 50%                          │
│                                                             │
│ Product: BX-CAT-001 - Catalyst Reagent                     │
│ [← Back to Product Select]                                 │
│                                                             │
│ ──────────────────────────────────────────────────────────  │
│ BASE/PUBLIC PRICING                                        │
│ ──────────────────────────────────────────────────────────  │
│                                                             │
│ This is the standard pricing customers see (if no company  │
│ override or bulk discount applies).                        │
│                                                             │
│ Base Unit Price: [150.00]  ← Required field               │
│ (The price before any discounts)                           │
│                                                             │
│ ──────────────────────────────────────────────────────────  │
│ DISCOUNT CONFIGURATION                                     │
│ ──────────────────────────────────────────────────────────  │
│                                                             │
│ ○ No Discount                                              │
│ ◉ Percentage Discount: [8.5] %                            │
│   (Admin thinks: 150 × 8.5% = $12.75 discount)           │
│ ○ Fixed Amount Discount: $[0]                             │
│                                                             │
│ ──────────────────────────────────────────────────────────  │
│ TAX CONFIGURATION                                          │
│ ──────────────────────────────────────────────────────────  │
│                                                             │
│ GST Rate: [18] %  (from category default)                 │
│ □ Override GST Rate for this product                      │
│   (If checked, enter custom rate below)                    │
│                                                             │
│ ☑ Auto-calculate Tax                                      │
│   (Checked = system calculates tax automatically)         │
│                                                             │
│ ──────────────────────────────────────────────────────────  │
│ LIVE PRICE PREVIEW                                         │
│ ──────────────────────────────────────────────────────────  │
│                                                             │
│ Base Amount:              $150.00                          │
│ Discount (Percentage):-8.5% $12.75                        │
│                           ───────                         │
│ Subtotal:                 $137.25                          │
│                                                             │
│ Tax @ 18%:               $24.71                            │
│                           ───────                         │
│ FINAL UNIT PRICE:         $161.96  ✓                       │
│                                                             │
│ ℹ This is the price customers see listed for this product │
│                                                             │
│ ──────────────────────────────────────────────────────────  │
│ VALIDATION STATUS                                          │
│ ──────────────────────────────────────────────────────────  │
│ ✓ Valid base price                                         │
│ ✓ Valid discount                                           │
│ ✓ Final price > $0.01                                     │
│                                                             │
│ [Back ←] ..................... [Next →]                    │
└─────────────────────────────────────────────────────────────┘
```

**Form Fields:**
1. **Base Unit Price** (required)
   - Type: Number input with step 0.01
   - Min: 0.01, Max: 999999.99
   - Placeholder: "Enter price"

2. **Discount Configuration** (optional)
   - Option 1: No Discount (selected by default)
   - Option 2: Percentage Discount
     - Input field: Max 100%
   - Option 3: Fixed Amount
     - Input field: Max equals base price

3. **GST Rate**
   - Default: Category GST (e.g., 18%)
   - Checkbox to override
   - If override checked, show input field for custom rate
   - Range: 0-100%

4. **Auto-calculate Tax**
   - Checkbox (checked by default)
   - If unchecked, admin must manually enter tax amount

**Real-Time Calculations:**
As admin types, recalculate and update preview:
```javascript
if (autoCalculateTax) {
    taxAmount = (baseAmount - discountAmount) * (gstRate / 100);
}
finalPrice = baseAmount - discountAmount + taxAmount;
```

**Validation on Next:**
- Base price required and > 0
- Final price must be > $0.01
- All fields must be numeric
- If discount is percentage, must be 0-100
- GST rate must be 0-100

---

### STEP 3: Company-Specific Overrides (Optional)

**URL**: `GET /adminPanel/pricing/add/step/3`

**Purpose**: Add custom pricing for specific B2B companies (optional)

```
┌─────────────────────────────────────────────────────────────┐
│ Add Product Pricing                                         │
│ ═══════════════════════════════════════════════════════════ │
│ Step 3 of 4: Company Overrides  (Optional)                 │
│ ═══════════════════════════════════════════════════════════ │
│                                                             │
│ Progress: [████████████        ] 75%                        │
│                                                             │
│ Product: BX-CAT-001 - Catalyst Reagent                     │
│ [← Back to Base Pricing]                                   │
│                                                             │
│ Configure custom pricing for specific B2B companies.       │
│ (You can skip this step if not needed)                     │
│                                                             │
│ ──────────────────────────────────────────────────────────  │
│ ADD NEW COMPANY OVERRIDE                                   │
│ ──────────────────────────────────────────────────────────  │
│                                                             │
│ Company: [Search: "Acme..." ▼]                            │
│          Shows: Company Name | Contact | Business Type    │
│                                                             │
│ Base Tier to Override:                                     │
│ Choose which customer tier this company gets:              │
│ [Dealer ▼]  (Options: Dealer, Institutional, Custom)     │
│                                                             │
│ Override Configuration:                                    │
│ ◉ As Percentage: [75] % of selected tier                 │
│   (Meaning: 75% of dealer price = custom price)          │
│ ○ Fixed Price: $[112.50]  (result of calculation)        │
│                                                             │
│ Effective Period:                                          │
│ From: [Jan 1, 2026      ] (date picker)                  │
│ To:   [Never  / Until] [       ] (date picker or "Never")│
│                                                             │
│ [Add Company Override]                                     │
│                                                             │
│ ──────────────────────────────────────────────────────────  │
│ ADDED COMPANY OVERRIDES (0 so far)                         │
│ ──────────────────────────────────────────────────────────  │
│                                                             │
│ (No company overrides added yet. Skip this step or add     │
│ companies above)                                            │
│                                                             │
│ [Back ←] ..................... [Skip →] [Next with Overrides →]
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

**Important Notes for Admin:**
- Company Overrides are OPTIONAL - can skip to Step 4 if not needed
- Use this when you have a contract with a specific company for special pricing
- Multiple companies can be added for the same product

**Form Fields:**

1. **Company Selection** (optional, repeatable)
   - Searchable dropdown
   - Shows company name, contact info, business type
   
2. **Base Tier** (required if adding company)
   - Dropdown: Dealer | Institutional | Custom
   - "This company will get pricing as [Selected Tier]"

3. **Override Type**
   - Option 1: Percentage of tier price
     - Input: [75]% 
     - Explanation: "Company pays 75% of dealer price"
   - Option 2: Fixed Price
     - Input: $[amount]
     - Auto-calculates based on percentageselection
  
4. **Effective Period**
   - From Date: Date picker (required)
   - To Date: Date picker or "Never" option (optional)
   - Helps with time-limited contracts

**Add Company Button:**
- Validates company selected and override configured
- Adds to list below without saving
- Clears form for adding another company

**List of Added Overrides:**
- Shows table: Company | Tier | Override | Period | Delete
- Each row has delete button to remove from this setup

**Navigation:**
- [Back ←] - Returns to Step 2 (loses unsaved overrides)
- [Skip →] - Skips to Step 4 (no company overrides)
- [Next with Overrides →] - Proceeds to Step 4 with overrides

---

### STEP 4: Bulk Quantity Tiers (Optional)

**URL**: `GET /adminPanel/pricing/add/step/4`

**Purpose**: Add volume-based pricing tiers (optional)

```
┌─────────────────────────────────────────────────────────────┐
│ Add Product Pricing                                         │
│ ═══════════════════════════════════════════════════════════ │
│ Step 4 of 4: Bulk Pricing Tiers  (Optional)                │
│ ═══════════════════════════════════════════════════════════ │
│                                                             │
│ Progress: [████████████████    ] 100%                       │
│                                                             │
│ Product: BX-CAT-001 - Catalyst Reagent                     │
│ [← Back to Company Overrides]                             │
│                                                             │
│ Add quantity-based discounts for bulk purchases.           │
│ (You can skip this step if customers get flat pricing)     │
│                                                             │
│ ──────────────────────────────────────────────────────────  │
│ ADD NEW BULK TIER                                          │
│ ──────────────────────────────────────────────────────────  │
│                                                             │
│ Quantity Range:                                            │
│ From: [10] units                                           │
│ To:   [24] units  (leave blank for unlimited)             │
│ (Meaning: This tier applies when ordering 10-24 units)    │
│                                                             │
│ Discount from Base Price:                                  │
│ Base Price Reference: $150.00                              │
│ ◉ Percentage Discount: [6.67] %                          │
│   Calculated Tier Price: $140.00                           │
│ ○ Fixed Amount Discount: $[0]                             │
│                                                             │
│ [Add Bulk Tier]                                            │
│                                                             │
│ ──────────────────────────────────────────────────────────  │
│ BULK TIERS CREATED (4 so far)                              │
│ ──────────────────────────────────────────────────────────  │
│                                                             │
│ ┌─────────────────────────────────────────────────────────┐ │
│ │Qty Range  │From Base│Discount│Tier Price│Action      │ │
│ │─────────────────────────────────────────────────────────│ │
│ │1-9        │$150.00 │ - (base)│$150.00   │[Delete]   │ │
│ │10-24      │$150.00 │ 6.67%   │$140.00   │[Delete]   │ │
│ │25-99      │$150.00 │ 13.33%  │$130.00   │[Delete]   │ │
│ │100+       │$150.00 │ 20%     │$120.00   │[Delete]   │ │
│ └─────────────────────────────────────────────────────────┘ │
│                                                             │
│ ℹ IMPORTANT: Bulk tiers should be in order and not overlap│
│   System will warn if ranges are invalid.                 │
│                                                             │
│ ──────────────────────────────────────────────────────────  │
│ VALIDATION STATUS                                          │
│ ──────────────────────────────────────────────────────────  │
│ ✓ No overlapping quantity ranges                           │
│ ✓ All tiers in ascending order                            │
│ ✓ Prices decrease as quantity increases ✓                 │
│                                                             │
│ [Back ←] ....................... [Skip →] [Save & Finish]  │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

**Important Notes for Admin:**
- Bulk tiers OPTIONAL - can skip if you want flat pricing
- Use this for volume discounts (buy more, pay less per unit)
- Tiers should be in ASCENDING order by quantity
- System will prevent overlapping ranges

**Form Fields:**

1. **Quantity Range** (required if adding tier)
   - From: [input] units - minimum quantity
   - To: [input] units - maximum quantity (optional, blank = unlimited)

2. **Discount Configuration**
   - Radio: Percentage Discount OR Fixed Amount
   - Input field for discount value
   - Shows calculated tier price preview

3. **Base Price Reference** (read-only)
   - Shows $150.00 (from Step 2)
   - Helps admin understand which price is being discounted

**Add Bulk Tier Button:**
- Validates quantity range (min ≤ max)
- Checks for overlaps with existing tiers
- Adds to list below
- Clears form for next tier

**List of Added Tiers:**
- Shows all tiers in order
- Columns: Quantity Range | Base | Discount | Tier Price | Delete
- Delete button removes individual tier

**Validation:**
- ✓ No overlapping ranges
- ✓ Min ≤ Max for each tier
- ✓ Tiers in ascending quantity order (1-9, 10-24, 25-99, 100+)
- ✓ Prices decrease with higher quantities (recommended but not enforced)

**Navigation:**
- [Back ←] - Returns to Step 3 (loses unsaved bulk tiers)
- [Skip →] - Skips bulk pricing, saves just base + companies
- [Save & Finish] - Saves everything and creates product pricing

---

### Final Summary & Confirmation

```
┌─────────────────────────────────────────────────────────────┐
│ PRICING SETUP COMPLETE ✓                                   │
│                                                             │
│ Product: BX-CAT-001 - Catalyst Reagent                     │
│                                                             │
│ ══════════════════════════════════════════════════════════ │
│ BASE PRICING                                               │
│ ══════════════════════════════════════════════════════════ │
│ Base Unit Price:        $150.00                            │
│ Discount (8.5%):        -$12.75                            │
│ Tax @ 18%:              +$24.71                            │
│ FINAL PRICE:            $161.96  ✓                         │
│                                                             │
│ ══════════════════════════════════════════════════════════ │
│ COMPANY OVERRIDES: 2 set up                               │
│ ══════════════════════════════════════════════════════════ │
│ • Acme Pharma (75% Dealer) - Ongoing                      │
│ • Metro Hospital (68% Institutional) - Until Aug 15,2026  │
│                                                             │
│ ══════════════════════════════════════════════════════════ │
│ BULK PRICING TIERS: 4 configured                          │
│ ══════════════════════════════════════════════════════════ │
│ 1-9:     $150.00 (base)                                    │
│ 10-24:   $140.00 (6.67% off)                              │
│ 25-99:   $130.00 (13.33% off)                             │
│ 100+:    $120.00 (20% off)                                │
│                                                             │
│ This product is now live in the system.                    │
│ Customers will see appropriate pricing based on:           │
│ • Their user type (B2C, Dealer, etc.)                    │
│ • Their company (if override exists)                      │
│ • Quantity they order (bulk tiers)                        │
│                                                             │
│                              [Return to Dashboard]         │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

---

---

## Edit & Delete Operations

### Edit Existing Pricing

**Triggered By**: [Edit] button on dashboard for product with pricing

**Behavior**:
- Same wizard flow as "Add Pricing"
- Pre-populates all existing pricing data
- Step 1: Shows selected product (can't change)
- Step 2: Shows current base pricing
- Step 3: Shows current company overrides
- Step 4: Shows current bulk tiers
- Button text changes to [Update] instead of [Save]
- On save: Updates all existing records instead of creating new

**URL Flow**:
```
GET /adminPanel/pricing/edit/{productId}
  ↓ (redirects with pre-filled data)
GET /adminPanel/pricing/add?productId={productId}&mode=edit
  ↓
Same 4-step wizard, but all fields populated
```

**Important Notes**:
- Admin can modify base pricing
- Can add/remove company overrides
- Can add/remove/modify bulk tiers
- All changes take effect immediately (no publish step)
- Logs who made changes and when

---

### Delete Pricing for Product

**Triggered By**: [Delete] button on dashboard

**Confirmation Modal**:

```
┌─────────────────────────────────────────────────────┐
│ DELETE PRODUCT PRICING?                             │
│                                                     │
│ Are you sure you want to DELETE all pricing for    │
│                                                     │
│ BX-CAT-001 - Catalyst Reagent                      │
│                                                     │
│ This will remove:                                  │
│ ✓ Base pricing ($150.00, 18% tax)                  │
│ ✓ 2 company overrides                              │
│ ✓ 4 bulk pricing tiers                             │
│ ✓ All customer access to this product             │
│                                                     │
│ ⚠ WARNING: Customers will NOT be able to          │
│ purchase this product until pricing is re-added.  │
│                                                     │
│ This action CANNOT be undone.                      │
│                                                     │
│ Type "DELETE" to confirm: [____________________]  │
│                                                     │
│ [Cancel] ........................... [Delete]      │
└─────────────────────────────────────────────────────┘
```

**Behavior**:
- Shows what will be deleted (base, companies, bulk tiers count)
- Requires confirmation with "DELETE" typed in
- On confirmation: Soft delete all pricing records
- Redirects to dashboard with success message
- Product still exists, just becomes "unpriceable"

---

## Implementation Details

### Database Operations

#### Creating Pricing for Product

```php
// Step 1: Admin selects product BX-CAT-001
$product = Product::where('sku', 'BX-CAT-001')->first();
$variant = $product->variants()->first(); // Gets default variant

// Step 2: Admin enters base pricing
// System creates ONE ProductPrice record:
$basePrice = ProductPrice::create([
    'product_variant_id' => $variant->id,
    'price_type' => 'public', // or 'retail', 'logged_in', etc.
    'amount' => 150.00,
    'DiscountType' => 'percent',
    'Discount' => 8.5,
    'gst_rate' => 18.0,
    'tax_amount' => 24.71,  // calculated
    'price_after_gst' => 161.96,  // calculated
    'currency' => 'INR',
    'is_active' => true,
]);

// Step 3: Admin adds company overrides (if any)
foreach ($companiesWithOverrides as $companyId => $override) {
    ProductPrice::create([
        'product_variant_id' => $variant->id,
        'price_type' => 'company_price',
        'company_id' => $companyId,
        'amount' => $override['amount'],
        'DiscountType' => 'percent',
        'Discount' => $override['discount'],
        'gst_rate' => 18.0,
        'tax_amount' => $calculatedTax,
        'price_after_gst' => $calculatedFinal,
        'currency' => 'INR',
        'is_active' => true,
    ]);
}

// Step 4: Admin adds bulk tiers (if any)
foreach ($bulkTiers as $tier) {
    ProductBulkPrice::create([
        'product_variant_id' => $variant->id,
        'min_quantity' => $tier['min_qty'],
        'max_quantity' => $tier['max_qty'],
        'amount' => $tier['unit_price'],
        'applies_to_user_type' => 'all',
        'currency' => 'INR',
        'is_active' => true,
    ]);
}
```

#### Reading Pricing for Display

```php
// Dashboard shows all products with pricing status:
$products = Product::with(['variants.prices', 'variants.bulkPrices'])
    ->getting();

foreach ($products as $product) {
    $variant = $product->variants()->first();
    $basePrice = $variant->prices()->where('price_type', 'public')->first();
    $companies = $variant->prices()->where('price_type', 'company_price')->count();
    $bulkTiers = $variant->bulkPrices()->count();
}
```

#### Updating Pricing

```php
// Edit existing pricing:

// Update base price
$basePrice->update([
    'amount' => 160.00,
    'Discount' => 10.0,
    'price_after_gst' => $recalculatedFinal,
]);

// Add new company
ProductPrice::create([...new company...]);

// Remove company override
$companyPrice->delete();

// Modify bulk tier
$bulkTier->update([
    'min_quantity' => 15,
    'max_quantity' => 30,
    'amount' => 135.00,
]);
```

---

## Service Layer Design

### PricingAdminService Structure

```php
class PricingAdminService
{
    // FETCH OPERATIONS
    public function getProductsWithoutPricing(): Collection
    {
        // Returns products with no ProductPrice records
    }
    
    public function getProductsWithPricing(array $filters = []): Collection
    {
        // Returns products that have pricing configured
        // Includes counts of tiers, companies, bulk prices
    }
    
    public function getProductPricingData(int $productId): array
    {
        // Returns complete pricing setup for displaying in edit wizard
        // Includes base price, companies, bulk tiers
    }
    
    // STORE OPERATIONS
    public function createPricingForProduct(int $productId, array $data): bool
    {
        // Saves complete pricing setup from wizard
        // $data contains:
        //   basePrice (amount, discountType, discount, gstRate)
        //   companies (array of company overrides)
        //   bulkTiers (array of quantity tiers)
    }
    
    public function updatePricingForProduct(int $productId, array $data): bool
    {
        // Updates existing pricing
    }
    
    public function deletePricingForProduct(int $productId): bool
    {
        // Soft delete all pricing for product
    }
    
    // VALIDATION
    public function validateBasePricing(array $data): array
    {
        // Returns validation errors or empty array if valid
        // Validates: amount > 0, discount in range, gst 0-100
    }
    
    public function validateBulkTiers(array $tiers): array
    {
        // Validates quantity ranges don't overlap
        // Validates min ≤ max for each tier
    }
    
    public function validateCompanyPricing(int $companyId, array $data): array
    {
        // Validates company exists
        // Validates override percentage/amount
    }
    
    // CALCULATIONS
    public function calculateFinalPrice(array $baseData): decimal
    {
        // Input: amount, discountType, discount, gstRate
        // Returns: final unit price after discount + tax
        // Logic:
        //   subtotal = amount - (discount% or discount$)
        //   tax = subtotal × gstRate / 100
        //   final = subtotal + tax
    }
}
```

### PricingAdminController Structure

```php
class PricingAdminController extends Controller
{
    // DASHBOARD
    public function index()
    {
        // GET /adminPanel/pricing
        // Shows pricing list dashboard
        $productsWithoutPricing = $this->service->getProductsWithoutPricing();
        $productsWithPricing = $this->service->getProductsWithPricing();
        
        return view('admin.pricing.index', [
            'withoutPricing' => $productsWithoutPricing,
            'withPricing' => $productsWithPricing,
        ]);
    }
    
    // WIZARD STEPS
    public function addStep1()
    {
        // GET /adminPanel/pricing/add
        // Product selection
        $products = Product::where('is_active', true)->get();
        return view('admin.pricing.wizard.step1', compact('products'));
    }
    
    public function addStep2(Request $request)
    {
        // GET /adminPanel/pricing/add/step/2
        // Base pricing, uses productId from session
        $productId = $request->session()->get('pricing_wizard.product_id');
        $product = Product::find($productId);
        return view('admin.pricing.wizard.step2', compact('product'));
    }
    
    public function addStep3(Request $request)
    {
        // GET /adminPanel/pricing/add/step/3
        // Company overrides
        return view('admin.pricing.wizard.step3');
    }
    
    public function addStep4(Request $request)
    {
        // GET /adminPanel/pricing/add/step/4
        // Bulk tiers
        return view('admin.pricing.wizard.step4');
    }
    
    // SAVE
    public function save(Request $request)
    {
        // POST /adminPanel/pricing/save
        // Receives all 4 steps data from wizard
        $validated = $request->validate([
            'product_id' => 'required|exists:products',
            'base_price' => 'required|array',
            'companies' => 'nullable|array',
            'bulk_tiers' => 'nullable|array',
        ]);
        
        $this->service->createPricingForProduct(
            $validated['product_id'],
            $validated
        );
        
        return redirect()->route('admin.pricing.index')
            ->with('success', 'Pricing created successfully');
    }
    
    // VIEW/EDIT/DELETE
    public function view(int $productId)
    {
        // GET /adminPanel/pricing/{productId}/view
        // Read-only view of all pricing
    }
    
    public function edit(int $productId)
    {
        // GET /adminPanel/pricing/{productId}/edit
        // Opens wizard with existing data
    }
    
    public function delete(int $productId)
    {
        // DELETE /adminPanel/pricing/{productId}
        // Soft delete all pricing
    }
}
```

### Routes to Add

```php
// Pricing Management Routes
Route::group(['prefix' => 'adminPanel', 'as' => 'admin.pricing.'], function () {
    // Dashboard
    Route::get('/pricing', [PricingAdminController::class, 'index'])
        ->name('index');
    
    // Add Pricing Wizard
    Route::get('/pricing/add', [PricingAdminController::class, 'addStep1'])
        ->name('add.step1');
    Route::get('/pricing/add/step/{step}', [PricingAdminController::class, 'addStep'])
        ->name('add.step');
    
    // Save Pricing
    Route::post('/pricing/save', [PricingAdminController::class, 'save'])
        ->name('save');
    
    // Edit Pricing
    Route::get('/pricing/{productId}/view', [PricingAdminController::class, 'view'])
        ->name('view');
    Route::get('/pricing/{productId}/edit', [PricingAdminController::class, 'edit'])
        ->name('edit');
    
    // Delete Pricing
    Route::delete('/pricing/{productId}', [PricingAdminController::class, 'delete'])
        ->name('destroy');
});
```  

