<?php /** @noinspection ALL */

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use App\Utilities\Role;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        Post::factory(15)->create();

    }
}
