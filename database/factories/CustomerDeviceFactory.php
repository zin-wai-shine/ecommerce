<?php

namespace Database\Factories;

use App\Models\Customer;
use Carbon\Carbon;
use Cassandra\Custom;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerDevice>
 */
class CustomerDeviceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $actionType = ['register', 'login'];
        $browser = ['Chrome', 'Opera', 'FireFox', 'Microsoft Edge'];
        $OS = ["Windows", "linux", "macOS Big Sur", "Ubuntu", "Android", "iOS"];
        $device = 'Desktop / '.$browser[array_rand($browser)].' / '.$OS[array_rand($OS)].' / SYSTEM';
        $customer_id = Customer::inRandomOrder()->value('customer_id');

        return [
            'action_type' => $actionType[array_rand($actionType)],
            'device' => $device,
            'date' => Carbon::now(),
            'ip' => fake()->localIpv4,
            'customer_id' => $customer_id
        ];
    }
}
