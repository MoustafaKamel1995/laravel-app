<?php

// database/migrations/xxxx_xx_xx_xxxxxx_add_hardware_serials_to_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHardwareSerialsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'disk_serial')) {
                $table->string('disk_serial')->nullable();
            }
            if (!Schema::hasColumn('users', 'memory_serial')) {
                $table->string('memory_serial')->nullable();
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
            if (Schema::hasColumn('users', 'disk_serial')) {
                $table->dropColumn('disk_serial');
            }
            if (Schema::hasColumn('users', 'memory_serial')) {
                $table->dropColumn('memory_serial');
            }
        });
    }
}
