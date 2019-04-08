<?php

namespace Modules\Wiki\Database\Seeders;

use App\Tag;

use Modules\Wiki\Entities\WikiArticle;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

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

        factory(WikiArticle::class, 100)->create()->each(function($a){
            $a->syncTags(factory(Tag::class, mt_rand(1, 5))
                ->make()
                ->pluck('name')
                ->map(function($n){ return ucfirst($n); })
                ->toArray());
        });
    }
}
