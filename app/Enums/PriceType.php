<?php

namespace App\Enums;

// This enum defines all available price types used across the product pricing system.
enum PriceType: string
{
    // Company-specific pricing for authorized resellers and bulk buyers.
    case CompanyPrice = 'company_price';

    // Price tier for B2B customers.
    case B2B = 'b2b';

    // Price tier for B2C customers.
    case B2C = 'b2c';

    // Public website price.
    case Public = 'public';

    // Get all price types available for a guest user.
    public static function visibleForGuest(): array
    {
        return [
            self::Public->value,
        ];
    }

    // Get all price types available for a logged-in B2C customer.
    public static function visibleForB2cUser(): array
    {
        return [
            self::B2C->value,
            self::Public->value,
        ];
    }

    // Get all price types available for an institutional/B2B buyer.
    public static function visibleForB2bUser(): array
    {
        return [
            self::CompanyPrice->value,
            self::B2B->value,
            self::Public->value,
        ];
    }

    // Check if this price type represents company-level pricing.
    public function isCompanyPrice(): bool
    {
        return $this === self::CompanyPrice;
    }

    // Get pricing stage for coupon calculations.
    public function pricingStage(): string
    {
        return $this->isCompanyPrice() ? 'company_price' : 'base_price';
    }
}
