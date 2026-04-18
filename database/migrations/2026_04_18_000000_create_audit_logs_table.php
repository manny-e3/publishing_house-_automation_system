<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('audit_logs', function (Blueprint $row) {
            $row->id();
            $row->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $row->string('action');
            $row->string('model_type')->nullable();
            $row->string('model_id')->nullable();
            $row->json('changes')->nullable();
            $row->string('ip_address')->nullable();
            $row->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('audit_logs');
    }
};
