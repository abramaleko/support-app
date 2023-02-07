<?php

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
        Schema::create('issues', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('issue_id');
            $table->string('title');
            $table->longText('description');
            $table->foreignId('issue_category_id')->constrained('issues_categories');
            $table->foreignId('issued_by')->constrained('users');
            $table->tinyInteger('status')->default(1)->comment('1-open,2-inprogress,3-closed');
            $table->foreignId('assigned_to')->nullable()->constrained('users');
            $table->longText('closing_description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('issues');
    }
};
