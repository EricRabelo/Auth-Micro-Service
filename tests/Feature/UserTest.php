<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Factory as Faker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test if can create a user
     *
     * @return void
     */
    public function test_create_user()
    {
        $faker = Faker::create();

        $user = User::create([
            'name' => $faker->name,
            'email' => $faker->email,
            'password' => bcrypt($faker->password),
        ]);

        $this->assertDatabaseHas('users', [
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    /**
     * Test if can update a user
     *
     * @return void
     */
    public function test_update_user()
    {
        $faker = Faker::create();

        $user = User::factory()->create([
            'name' => $faker->name,
            'email' => $faker->email,
            'password' => bcrypt($faker->password),
        ]);

        $this->assertDatabaseHas('users', [
            'name' => $user->name,
            'email' => $user->email,
        ]);

        $user->name = $faker->name;
        $user->email = $faker->email;
        $user->save();

        $this->assertDatabaseHas('users', [
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    /**
     * Test if can delete a user
     *
     * @return void
     */
    public function test_delete_user()
    {
        $faker = Faker::create();

        $user = User::factory()->create([
            'name' => $faker->name,
            'email' => $faker->email,
            'password' => bcrypt($faker->password),
        ]);

        $this->assertDatabaseHas('users', [
            'name' => $user->name,
            'email' => $user->email,
        ]);

        $user->delete();

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }
}
