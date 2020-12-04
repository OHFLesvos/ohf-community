<?php

namespace Database\Factories;

use App\Models\Comment;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'content' => $this->faker->sentence,
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (Comment $comment) {
            $rnd = mt_rand(0, 100);
            if ($rnd < 75) {
                $user = User::inRandomOrder()->limit(1)->first();
                if ($user !== null) {
                    $comment->setUser($user);
                }
            } elseif ($rnd < 95) {
                $comment->user_name = $this->faker->name;
            }
        });
    }
}
