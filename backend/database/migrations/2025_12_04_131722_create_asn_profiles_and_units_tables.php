<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tabel 1: org_units
        Schema::create('org_units', function (Blueprint $table) {
            $table->id('org_unit_id'); // PK
            $table->string('name');
            $table->string('code')->unique();
            $table->foreignId('parent_unit_id')->nullable()->constrained('org_units', 'org_unit_id');
            $table->timestamps();
        });

        // Tabel 2: positions
        Schema::create('positions', function (Blueprint $table) {
            $table->id('position_id'); // PK
            $table->foreignId('org_unit_id')->constrained('org_units', 'org_unit_id'); // FK
            $table->string('title');
            $table->timestamps();
        });

        // Tabel 3: asn_profiles (UUID PK)
        Schema::create('asn_profiles', function (Blueprint $table) {
            $table->uuid('asn_id')->primary(); // UUID PK
            $table->uuid('user_id')->nullable(); // FK to users.user_id (optional link)
            $table->string('nip')->unique();
            $table->string('full_name');
            $table->foreignId('org_unit_id')->constrained('org_units', 'org_unit_id'); // FK
            $table->foreignId('current_position_id')->constrained('positions', 'position_id'); // FK
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asn_profiles');
        Schema::dropIfExists('positions');
        Schema::dropIfExists('org_units');
    }
};
