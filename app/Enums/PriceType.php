<?php

namespace App\Enums;

// This enum defines all available price types used across the product pricing system.
enum PriceType: string
{
    // Company-specific pricing for authorized resellers and bulk buyers.
    case CompanyPrice = 'company_price';

    // Price tier for logged-in B2C and B2B customers.
    case LoggedIn = 'logged_in';

    // Discounted price level for dealer/reseller accounts.
    case Dealer = 'dealer';

    // Price tier for institutional/corporate buyers.
    case Institutional = 'institutional';

    // Standard retail price for public catalog.
    case Retail = 'retail';

    // Public website price (same as retail).
    case Public = 'public';

    // Get all price types available for a specific user.
    public static function visibleForGuest(): array
    {
        return [
            self::Public->value,
            self::Retail->value,
            self::LoggedIn->value,
        ];
    }

    // Get all price types available for a logged-in B2C customer.
    public static function visibleForB2cUser(): array
    {
        return [
            self::Public->value,
            self::Retail->value,
            self::LoggedIn->value,
        ];
    }

    // Get all price types available for a dealer/reseller.
    public static function visibleForDealer(): array
    {
        return [
            self::CompanyPrice->value,
            self::Dealer->value,
            self::LoggedIn->value,
            self::Public->value,
            self::Retail->value,
        ];
    }

    // Get all price types available for an institutional buyer.
    public static function visibleForInstitutional(): array
    {
        return [
            self::CompanyPrice->value,
            self::Institutional->value,
            self::LoggedIn->value,
            self::Public->value,
            self::Retail->value,
        ];
    }

    // Get all price types available for an internal user.
    public static function visibleForInternalUser(): array
    {
        return [
            self::CompanyPrice->value,
            self::Dealer->value,
            self::Institutional->value,
            self::LoggedIn->value,
            self::Public->value,
            self::Retail->value,
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
