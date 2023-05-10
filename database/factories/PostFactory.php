<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use App\Utilities\PostStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{

    protected $model = \App\Models\Post::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'thumbnail' => $this->faker->imageUrl(640, 480, 'animals'),
            'title' => $this->faker->sentence(),
            'slug' => $this->faker->slug(),
            'status' => PostStatus::PUBLISHED->value,
            'published_at' => $this->faker->dateTimeBetween('-1 year'),
            'excerpt' => '<p>' . implode('</p><p>', $this->faker->paragraphs(2)) . '</p>',
            'body' => '<p>' . implode('</p><p>', $this->faker->paragraphs(6)) . '</p>',
        ];
    }
}
