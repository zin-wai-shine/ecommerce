<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $firstName = fake()->firstName;
        $lastName = fake()->lastName;
        /*$fakeEmail = fake()->unique()->email;
        $removeBySing = substr($fakeEmail, 0, strpos($fakeEmail, '@'));
        $email = $removeBySing.'@gmail.com';*/
        $fullName = $firstName." ".$lastName;
        $email = strtolower($firstName.$lastName).'@gmail.com';
        $phoneNumber = '09'.rand(100000000, 999999999);
        return [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'full_name' => $fullName,
            'email' => $email,
            'phone_number' => $phoneNumber,
            'password' => Hash::make('password')
        ];
    }
}
