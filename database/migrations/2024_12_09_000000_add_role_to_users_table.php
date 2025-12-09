<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoleToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('user')->after('password');
            }
            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('role');
            }
            if (!Schema::hasColumn('users', 'email_verified')) {
                $table->boolean('email_verified')->default(false)->after('is_active');
            }
            if (!Schema::hasColumn('users', 'verification_token')) {
                $table->string('verification_token')->nullable()->after('email_verified');
            }
            if (!Schema::hasColumn('users', 'verification_token_expires')) {
                $table->timestamp('verification_token_expires')->nullable()->after('verification_token');
            }
            if (!Schema::hasColumn('users', 'verified_at')) {
                $table->timestamp('verified_at')->nullable()->after('verification_token_expires');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
            if (Schema::hasColumn('users', 'is_active')) {
                $table->dropColumn('is_active');
            }
            if (Schema::hasColumn('users', 'email_verified')) {
                $table->dropColumn('email_verified');
            }
            if (Schema::hasColumn('users', 'verification_token')) {
                $table->dropColumn('verification_token');
            }
            if (Schema::hasColumn('users', 'verification_token_expires')) {
                $table->dropColumn('verification_token_expires');
            }
            if (Schema::hasColumn('users', 'verified_at')) {
                $table->dropColumn('verified_at');
            }
        });
    }
}

