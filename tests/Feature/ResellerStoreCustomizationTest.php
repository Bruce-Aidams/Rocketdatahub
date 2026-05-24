<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Bundle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResellerStoreCustomizationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Reseller can update storefront branding configuration.
     */
    public function test_reseller_can_update_branding_settings()
    {
        $reseller = User::factory()->create([
            'role' => User::ROLE_RETAIL_SELLER,
            'is_verified' => true,
            'is_active' => true,
            'store_active' => true,
            'referral_code' => 'TESTSTORE1'
        ]);

        $response = $this->actingAs($reseller)
            ->post(route('reseller.store.branding'), [
                'storefront_title' => 'My Exclusive Tech VTU Hub',
                'storefront_description' => 'Cheapest data bundles ever!',
                'storefront_theme_color' => '#ff0055',
                'storefront_bg_color' => '#121212',
                'storefront_logo_url' => 'https://cloudtech.com/assets/logo.png',
            ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Storefront branding updated successfully.');

        $reseller = $reseller->fresh();
        $this->assertIsArray($reseller->settings);
        $this->assertEquals('My Exclusive Tech VTU Hub', $reseller->settings['storefront_title']);
        $this->assertEquals('Cheapest data bundles ever!', $reseller->settings['storefront_description']);
        $this->assertEquals('#ff0055', $reseller->settings['storefront_theme_color']);
        $this->assertEquals('#121212', $reseller->settings['storefront_bg_color']);
        $this->assertEquals('https://cloudtech.com/assets/logo.png', $reseller->settings['storefront_logo_url']);
    }

    /**
     * Reseller customization rejects invalid hexadecimal colors.
     */
    public function test_branding_updates_rejects_invalid_colors()
    {
        $reseller = User::factory()->create([
            'role' => User::ROLE_RETAIL_SELLER,
            'is_verified' => true,
            'is_active' => true
        ]);

        $response = $this->actingAs($reseller)
            ->post(route('reseller.store.branding'), [
                'storefront_theme_color' => 'red', // not hex
                'storefront_bg_color' => '#xyz123', // invalid hex characters
            ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['storefront_theme_color', 'storefront_bg_color']);
    }

    /**
     * Reseller customization rejects invalid logo URLs.
     */
    public function test_branding_updates_rejects_invalid_logo_url()
    {
        $reseller = User::factory()->create([
            'role' => User::ROLE_RETAIL_SELLER,
            'is_verified' => true,
            'is_active' => true
        ]);

        $response = $this->actingAs($reseller)
            ->post(route('reseller.store.branding'), [
                'storefront_logo_url' => 'not-a-valid-url',
            ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['storefront_logo_url']);
    }

    /**
     * Guest user visiting public storefront sees reseller's dynamic customizations.
     */
    public function test_guest_can_view_reseller_storefront_customizations()
    {
        $reseller = User::factory()->create([
            'role' => User::ROLE_RETAIL_SELLER,
            'is_verified' => true,
            'is_active' => true,
            'store_active' => true,
            'referral_code' => 'STOREFONT3',
            'store_name' => 'Legacy Store',
            'settings' => [
                'storefront_title' => 'Dynamic Branding Hub',
                'storefront_description' => 'Fast delivery is guaranteed.',
                'storefront_theme_color' => '#10b981',
                'storefront_bg_color' => '#0b0f19',
                'storefront_logo_url' => 'https://cloudtech.com/brand/logo.svg'
            ]
        ]);

        // Create an active bundle to ensure storefront loads networks properly
        Bundle::create([
            'network' => 'MTN',
            'name' => 'MTN 2GB',
            'price' => 10.00,
            'cost_price' => 8.00,
            'data_amount' => '2GB',
            'is_active' => true
        ]);

        $response = $this->get(route('store.show', 'STOREFONT3'));

        $response->assertStatus(200);
        $response->assertSee('Dynamic Branding Hub');
        $response->assertSee('Fast delivery is guaranteed.');
        $response->assertSee('#10b981');
        $response->assertSee('background-color: #0b0f19');
        $response->assertSee('https://cloudtech.com/brand/logo.svg');
    }
}
