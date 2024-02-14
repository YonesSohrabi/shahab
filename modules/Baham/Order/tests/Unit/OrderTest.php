<?php

namespace Tests\Unit;

use Baham\Order\Models\Order;
use Baham\Transaction\Models\Transaction;
use Baham\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;


class OrderTest extends TestCase
{

    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->refreshDatabase();

        $this->seed();
    }

    public function test_orders_route_should_return_orders_list_for_authenticated_user()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->getJson('api/v1/orders');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'status',
            'data' => [
                'orders'
            ],
            'message'
        ]);

        $response->assertJson([
            'status' => true,
            'message' => "لیست سفارشات با موفقیت دریافت شد."
        ]);
    }


    public function test_orders_route_should_return_error_for_not_authenticated_user()
    {
        $response = $this->getJson('api/v1/orders');

        $response->assertStatus(401);

        $response->assertJson([
            'message' => "Unauthenticated."
        ]);
    }

    public function test_successful_order_registration()
    {
        $user = User::factory()->create([
            'coins' => 20
        ]);
        $this->actingAs($user);

        $data = [
            'count' => 3 // تعداد سفارش مورد نظر
        ];
        $response = $this->postJson('api/v1/orders', $data);

        $response->assertStatus(200);

        $response->assertJson([
            'status' => true,
            'message' => "سفارش با موفقیت ثبت شد"
        ]);
    }

    public function test_insufficient_user_balance()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = [
            'count' => 2
        ];
        $response = $this->postJson('api/v1/orders', $data);

        $response->assertStatus(406);

        $response->assertJson([
            'status' => false,
            'data' => null,
            'message' => "موجودی کاربر کافی نیست"
        ]);
    }

    public function test_invalid_order_count()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = [
            'count' => -3
        ];
        $response = $this->postJson('api/v1/orders', $data);

        $response->assertStatus(422);
    }

    public function test_follow_order_successfully()
    {
        // ایجاد کاربر و سفارش برای تست
        $user = User::factory()->create();
        $order = Order::factory()->create();

        $response = $this->actingAs($user)
            ->putJson("api/v1/orders/".$order->id."/update");

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'data' => null,
                'message' => 'عملیات با موفقیت انجام شد'
            ]);
    }

    public function test_follow_order_with_duplicate_transaction()
    {
        // ایجاد کاربر و سفارش برای تست
        $user = User::factory()->create();
        $order = Order::factory()->create();

        // ایجاد یک معامله برای کاربر و سفارش
        Transaction::create([
            'user_id' => $user->id,
            'order_id' => $order->id,
            'type' => Transaction::TYPE_REWARD,
            'count' => 2
        ]);

        $response = $this->actingAs($user)
            ->putJson("api/v1/orders/".$order->id."/update");

        $response->assertStatus(422)
            ->assertJson([
                'status' => false,
                'data' => null,
                'message' => 'کاربر قبلا این فرد را فالو کرده است'
            ]);
    }

    public function test_follow_order_without_authentication()
    {
        $order = Order::factory()->create();

        $response = $this->putJson("api/v1/orders/".$order->id."/update");

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.'
            ]);
    }




}
