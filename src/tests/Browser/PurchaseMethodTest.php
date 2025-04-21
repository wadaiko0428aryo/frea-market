<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Item;

class PurchaseMethodTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function test_purchase_method_is_changes_are_reflected()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::create([
            'name' => 'test',
            'price' => '100',
            'description' => 'test data',
            'condition' => 'good',
            'image' => 'test.jpg',
            'user_id' => $user->id,
        ]);


        $this->browse(function (Browser $browser) use ($user, $item) {
            $browser->loginAs($user)
                    ->visit("/purchase/{$item->id}")
                    ->select('purchase_method', 'コンビニ払い')  // selectタグのname属性
                    ->pause(500)  // JavaScriptの反映待ち
                    ->assertSeeIn('@subtotal-method', 'コンビニ払い')  // subtotal-methodはbladeで定義されたdata-testidやidなど
                    ->select('purchase_method', 'カード払い')
                    ->pause(500)
                    ->assertSeeIn('@subtotal-method', 'カード払い');
        });
    }
}
