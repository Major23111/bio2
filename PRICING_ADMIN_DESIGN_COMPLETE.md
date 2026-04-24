# Product Pricing Management - AdminPanel Design Document

**Document Version**: 2.0 (Admin Workflow Focused)  
**Last Updated**: April 14, 2026  
**Status**: Design Specification (Ready for Implementation)

---

## Executive Summary

This document describes how admins will manage product pricing in Biogenix. The approach is **workflow-driven** (not architectural):

- **Admin thinks in products**, not variants or price types
- **Linear step-by-step wizard** guides through complete pricing setup in one session
- **One product = one internal variant** (no UI complexity for variants)
- **4-tier pricing structure**: Base → Company Overrides → Bulk Discounts → Review & Save

---

## Development Summary

### Key Principles for This Admin Flow

1. **One Product at a Time**
   - Admin never manages multiple products simultaneously
   - Focused experience on single product pricing
   - Reduces cognitive load and errors

2. **Linear Wizard Approach**
   - Step 1: Select Product (mandatory)
   - Step 2: Base Pricing (mandatory)
   - Step 3: Company Overrides (optional - can skip)
   - Step 4: Bulk Tiers (optional - can skip)
   - Final step: Confirmation & Summary

3. **Real-Time Feedback**
   - Price preview updates as admin types
   - Validation errors show immediately
   - Bulk tier overlap detection before saving
   - No surprises at save time

4. **No Hidden Complexity**
   - Variants hidden from UI (created internally)
   - All operations on "default variant"
   - No variant selection dropdowns
   - Keeps UX simple for admin

5. **Complete Setup in One Visit**
   - Admin sets everything (base + companies + bulk) in one session
   - No need to jump between different admin pages
   - All related data saved together

### File Structure to Create

```
app/Services/AdminPanel/Pricing/
├── PricingAdminService.php         (Main service)
└── PricingCalculationService.php   (Helper: calculate final prices)

app/Http/Controllers/AdminPanel/Pricing/
└── PricingAdminController.php      (Wizard & dashboard controller)

resources/views/admin/pricing/
├── index.blade.php                 (Dashboard - list all products)
├── wizard/
│   ├── layout.blade.php            (Wizard layout with progress bar)
│   ├── step1-select.blade.php      (Product selection)
│   ├── step2-base.blade.php        (Base pricing)
│   ├── step3-companies.blade.php   (Company overrides)
│   ├── step4-bulk.blade.php        (Bulk tiers)
│   └── summary.blade.php           (Final confirmation)
└── components/
    ├── price-preview.blade.php     (Real-time price calculator)
    ├── quantity-tier-form.blade.php (Reusable bulk tier form)
    └── confirmation-modal.blade.php (Delete confirmation)
```

### Key Database Operations

**Creating New Pricing:**
1. Create 1 ProductPrice record (base/public pricing)
2. Create N ProductPrice records (company overrides, if any)
3. Create M ProductBulkPrice records (quantity tiers, if any)
4. All created at once when wizard saves

**Updating Existing Pricing:**
1. Load current ProductPrice/ProductBulkPrice records
2. Update existing records
3. Delete removed records (bulk tiers that admin removed)
4. Create new records (newly added companies/bulk tiers)

**Deleting Pricing:**
1. Soft delete all ProductPrice records for variant
2. Soft delete all ProductBulkPrice records for variant
3. Product remains in system but becomes "unpriceable"

### Implementation Order (Recommended)

**Week 1: Core Structure**
- Create PricingAdminService with basic methods
- Create PricingAdminController
- Create dashboard view (index.blade.php)
- Create wizard layout structure

**Week 2: Product Selection & Base Pricing**
- Implement Step 1 (product selection) 
- Implement Step 2 (base pricing with real-time calculation)
- Add validation and error messages

**Week 3: Company & Bulk Pricing**
- Implement Step 3 (company overrides - add/remove)
- Implement Step 4 (bulk tiers - add/remove/validate)
- Add quantity range overlap detection

**Week 4: Polish & Integration**
- Implement Edit flow (pre-populate wizard)
- Implement Delete flow (soft delete + confirmation)
- Add audit logging
- Test complete workflow

### Real-World Example Walkthrough

**Scenario: Admin adds pricing for "BX-CAT-001"**

```
Step 1: Dashboard
└─ Admin clicks "[+] Add Pricing" on BX-CAT-001
   │
   └─→ Redirects to Step 1: Select Product

Step 2: Product Selection  
└─ Product auto-filled: BX-CAT-001
└─ Shows: Name, Category, Stock, Default Variant (auto)
└─ Admin clicks [Next →]

Step 3: Base Pricing
└─ Admin enters:
   ├─ Base Price: 150.00
   ├─ Discount: Percentage 8.5%
   └─ GST: 18% (auto-filled from category)
└─ Real-time preview: Final Price = $161.96
└─ Admin clicks [Next →]

Step 4: Company Overrides (Optional)
└─ Admin clicks "Add Company" 3 times:
   ├─ Company 1: Acme Pharma (Dealer, 75%, Jan 1 - Forever)
   ├─ Company 2: Metro Hospital (Institutional, 68%, Feb 15 - Aug 15)
   └─ Company 3: Bio Lab (Dealer, 70%, Apr 1 - Forever)
└─ Each company shows calculated price
└─ Admin clicks [Next →]

Step 5: Bulk Tiers (Optional)
└─ Admin clicks "Add Tier" 4 times:
   ├─ Tier 1: 1-9 units = $150.00 (base)
   ├─ Tier 2: 10-24 units = $140.00 (6.67% off)
   ├─ Tier 3: 25-99 units = $130.00 (13.33% off)
   └─ Tier 4: 100+ units = $120.00 (20% off)
└─ System detects: No overlap, prices decrease ✓
└─ Admin clicks [Save & Finish]

Step 6: Database Operations
└─ System creates:
   ├─ 1 ProductPrice (public, $161.96)
   ├─ 3 ProductPrice (company_price, one per company)
   └─ 4 ProductBulkPrice (quantity tiers)
└─ Total: 8 database records created
└─ All linked to: BX-CAT-001's default variant

Step 7: Success & Return to Dashboard
└─ Shows: "Pricing created successfully"
└─ Dashboard now shows BX-CAT-001 with:
   ├─ Base Price: $150.00
   ├─ Company Pricing: 3 companies configured
   └─ Bulk Tiers: 4 tiers configured
└─ Admin can now [Edit] or [Delete] if needed
```

### Critical Implementation Notes

**Variant Management:**
- When product created → auto-create 1 default variant internally
- Admin NEVER sees variant UI
- All pricing operations use this default variant
- Future: If variant UI needed, it would be separate feature

**Price Type Handling:**
- Currently: Use "public" as default base price type
- All 6 price types exist in system but admin only manages "public" + "company_price"
- Other types (retail, logged_in, dealer, institutional) managed separately in future if needed

**Bulk Tier Order:**
- System should suggest auto-generated tiers: 1-9, 10-24, 25-99, 100+
- Admin can customize ranges freely
- System validates: no overlap, ascending order (recommended)

**Currency:**
- Default: INR for all prices
- Stored in database but admin doesn't select it
- Future enhancement if multi-currency needed

### Testing Scenarios

```
Test Case 1: Add pricing with all features
├─ Product: BX-CAT-001
├─ Base: $150 with 8.5% discount
├─ Companies: 2 overrides
├─ Bulk: 4 tiers
└─ Expected: All 8 records created successfully

Test Case 2: Edit existing pricing
├─ Load existing pricing
├─ Modify base price: $150 → $175
├─ Add new company override
├─ Remove one bulk tier
└─ Expected: ProductPrice updated, new record created, 1 deleted

Test Case 3: Delete all pricing
├─ Confirm deletion (type "DELETE")
├─ Expected: All 8 ProductPrice/ProductBulkPrice soft-deleted
├─ Product still exists in system
└─ Expected: Product becomes unpriceable

Test Case 4: Validation errors
├─ Enter base price: -10 (invalid)
├─ System shows: "Price must be greater than 0"
├─ Enter overlapping bulk tiers: 10-20, 15-25
├─ System shows: "Quantity ranges overlap"
└─ Expected: Form prevents saving

Test Case 5: Bulk tier order
├─ Add tiers in random order: 100+, 10-24, 1-9
├─ System should sort and prevent overlap
├─ Expected: Warning "Tiers should be in ascending order"
```

### Success Metrics

✅ Admin can add complete pricing for product in <5 minutes  
✅ All pricing data saved to database with correct relationships  
✅ Price calculations always accurate (base - discount + tax)  
✅ Bulk tiers prevent order gaps and overlaps  
✅ Company overrides properly scoped to companies  
✅ Edit flow works seamlessly (pre-populates all data)  
✅ Delete flow safe (confirmation + audit log)  
✅ Code follows Biogenix patterns (simple, fresh-friendly)  
✅ No variant complexity exposed to admin  

---

## Service Layer Methods

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
        // Updates existing pricing for product
    }
    
    public function deletePricingForProduct(int $productId): bool
    {
        // Soft delete all pricing records
    }
    
    // CALCULATION HELPERS
    public function calculateFinalPrice(float $amount, string $discountType, float $discount, float $gstRate): array
    {
        // Returns: [discountAmount, taxAmount, finalPrice]
    }
    
    public function validateBulkTierRanges(array $tiers): array
    {
        // Returns errors array if overlaps detected
    }
}
```

---

## Document Revision History

| Version | Date | Changes |
|---------|------|---------|
| 2.0 | Apr 14, 2026 | Complete rewrite: Admin workflow focus, linear wizard, step-by-step guide |
| 1.0 | Apr 14, 2026 | Initial version: Architecture-focused, section-based UI design |

**Status**: ✅ Ready for Development
