<?php

use App\Models\Accounting\Transaction;
use App\Models\Accounting\Project;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountingProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounting_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreign('parent_id')->references('id')->on('accounting_projects')->cascadeOnDelete();
            $table->timestamps();
        });

        if (Setting::has('accounting.transactions.projects')) {
            collect(Setting::get('accounting.transactions.projects'))->each(function ($name) {
                Project::firstOrCreate(
                    ['name' => $name],
                    ['name' => $name]
                );
            });
            Setting::forget('accounting.transactions.projects');
        }

        Schema::table('accounting_transactions', function (Blueprint $table) {
            $table->foreignId('project_id')->nullable()->after('secondary_category');
            $table->foreign('project_id')->references('id')->on('accounting_projects')->nullOnDelete();
        });

        $hasTransactions = Transaction::exists();
        if ($hasTransactions) {
            Transaction::whereNotNull('project')
                ->get()
                ->each(function ($transaction) {
                    $project = Project::firstOrCreate(
                        ['name' => $transaction->project],
                        ['name' => $transaction->project]
                    );
                    $transaction->project()->associate($project);
                    $transaction->save();
                });
        }

        Schema::table('accounting_transactions', function (Blueprint $table) {
            $table->dropColumn('project');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accounting_transactions', function (Blueprint $table) {
            $table->string('project_temp')->after('secondary_category')->nullable();
        });

        Transaction::whereNotNull('project_id')
            ->get()
            ->each(function ($transaction) {
                $transaction->project_temp = $transaction->project->name;
                $transaction->save();
            });

        Schema::table('accounting_transactions', function (Blueprint $table) {
            $table->renameColumn('project_temp', 'project');
        });

        Schema::table('accounting_transactions', function (Blueprint $table) {
            $table->index(['project']);
            $table->dropForeign(['project_id']);
            $table->dropColumn('project_id');
        });

        Setting::set('accounting.transactions.projects', Project::pluck('name'));

        Schema::dropIfExists('accounting_projects');
    }
}
