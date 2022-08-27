<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'corporate_name' => $this->faker->company,
            'razao_social' => $this->faker->company,
            'fantasy_name' => $this->faker->company,
            'cpf_cnpj' => $this->faker->unixTime($max = 'now'),
            'type_of_person' => 'juridica',
            'zipcode' => $this->faker->postcode,
            'address' => $this->faker->address,
            'address_number' => $this->faker->buildingNumber,
            'city' => $this->faker->city,
            'state' => $this->faker->stateAbbr,
            'district' => $this->faker->name,
            'email' => $this->faker->email,
            'cpf_cnpj' => $this->faker->unixTime($max = 'now'),
            'phone' => $this->faker->phoneNumber,
            'whatsapp' => $this->faker->phoneNumber,
            'telegram' => $this->faker->userName(),
            'email_notify' => '1',
            'whatsapp_notify' => '1',
            'telegram_notify' => '1',
            'status' => '1'
        ];
    }
}
