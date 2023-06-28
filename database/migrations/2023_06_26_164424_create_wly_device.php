<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 设备最新状态表
        Schema::create('wly_devices', function (Blueprint $table) {
            $table->id();

            $table->string('device_code');
            $table->unsignedTinyInteger('device_status')->default(2);
            $table->unsignedTinyInteger('net_status')->default(2);
            $table->timestamp('time');
            $table->string('content')->default('');

            $table->unsignedTinyInteger('output_model_a')->default(1);
            $table->unsignedTinyInteger('output_model_b')->default(1);
            $table->unsignedTinyInteger('output_model_c')->default(1);
            $table->unsignedTinyInteger('input_type_a')->default(0);
            $table->unsignedTinyInteger('input_type_b')->default(0);
            $table->unsignedTinyInteger('input_type_c')->default(0);
            $table->unsignedTinyInteger('output_type_a')->default(0);
            $table->unsignedTinyInteger('output_type_b')->default(0);
            $table->unsignedTinyInteger('output_type_c')->default(0);
            $table->decimal('temperature', 6, 2)->default(0);
            $table->integer('signal_intensity')->default(0);
            $table->string('client_id')->default('');
            $table->timestamps();

            $table->unique('device_code');//唯一索引
        });

        // 设备心跳或事件记录表
        Schema::create('wly_device_change_logs', function (Blueprint $table) {
            $table->id();
            $table->string('device_code');
            // $table->string('deviceType', 3);
            $table->unsignedTinyInteger('device_status')->default(2);
            $table->unsignedTinyInteger('net_status')->default(2);
            $table->timestamp('time');
            $table->string('content')->default('');
            $table->string('type', 4)->default('0301');

            $table->string('event', 7)->default('offline');
            $table->string('loop', 7)->default('');
            $table->unsignedTinyInteger('output_model_a')->default(1);
            $table->unsignedTinyInteger('output_model_b')->default(1);
            $table->unsignedTinyInteger('output_model_c')->default(1);
            $table->unsignedTinyInteger('input_type_a')->default(0);
            $table->unsignedTinyInteger('input_type_b')->default(0);
            $table->unsignedTinyInteger('input_type_c')->default(0);
            $table->unsignedTinyInteger('output_type_a')->default(0);
            $table->unsignedTinyInteger('output_type_b')->default(0);
            $table->unsignedTinyInteger('output_type_c')->default(0);
            $table->decimal('temperature', 6, 2)->default(0);
            $table->integer('signal_intensity')->default(0);
            $table->string('client_id')->default('');
            $table->string('iccid')->default('');
            $table->timestamps();
        });

        DB::unprepared("ALTER TABLE wly_devices COMMENT = '设备最新状态表';");
        DB::unprepared("ALTER TABLE wly_device_change_logs COMMENT = '设备心跳或事件记录表';");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wly_devices');
        Schema::dropIfExists('wly_device_change_logs');
    }
};
