<?php

use App\Models\Comment;
use App\Models\Fundraising\Donor;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->text('content');
            $table->unsignedInteger('user_id')->nullable();
            $table->string('user_name')->nullable();
            $table->unsignedBigInteger('commentable_id');
            $table->string('commentable_type');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->index(['commentable_id', 'commentable_type']);
        });

        Donor::whereNotNull('remarks')->get()->each(function (Donor $donor) {
            $comment = new Comment();
            $comment->content = $donor->remarks;
            $donor->addComment($comment);
        });

        Schema::table('donors', function (Blueprint $table) {
            $table->dropColumn('remarks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('donors', function (Blueprint $table) {
            $table->text('remarks')->nullable()->after('phone');
        });

        Schema::dropIfExists('comments');
    }
};
