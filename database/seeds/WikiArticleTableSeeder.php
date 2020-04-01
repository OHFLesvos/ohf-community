<?php

use App\Models\Collaboration\WikiArticle;
use App\Tag;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class WikiArticleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        factory(WikiArticle::class, 100)->create()->each(function ($a) {
            $a->syncTags(factory(Tag::class, mt_rand(1, 5))
                ->make()
                ->pluck('name')
                ->map(fn ($n) => ucfirst($n))
                ->toArray());
        });
    }
}
