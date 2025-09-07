<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First check the structure of the user table's id column
        $userTableInfo = DB::select(DB::raw('DESCRIBE user'));
        $idType = 'int';
        
        foreach ($userTableInfo as $column) {
            if ($column->Field === 'id') {
                $idType = str_contains($column->Type, 'bigint') ? 'bigint' : 'int';
                break;
            }
        }
        
        // Add the column with the correct type
        Schema::table('course', function (Blueprint $table) use ($idType) {
            if ($idType === 'bigint') {
                $table->unsignedBigInteger('adminid')->after('id');
            } else {
                $table->unsignedInteger('adminid')->after('id');
            }
        });
        
        // If you have existing courses, assign them to an admin user
        // First, find an admin user
        $admin = DB::table('user')->where('role', 'admin')->first();
        
        if (!$admin) {
            // If no admin exists, use the first user (you might want to create an admin first)
            $admin = DB::table('user')->first();
            if (!$admin) {
                throw new Exception('No users found in the user table. Please create at least one user before running this migration.');
            }
        }
        
        // Update existing courses
        DB::table('course')->update(['adminid' => $admin->id]);
        
        // Now add the foreign key constraint
        Schema::table('course', function (Blueprint $table) {
            $table->foreign('adminid')
                  ->references('id')
                  ->on('user')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course', function (Blueprint $table) {
            // Drop foreign key first
            $table->dropForeign(['adminid']);
            
            // Drop the column
            $table->dropColumn('adminid');
        });
    }
};