<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class wlyDeviceChangeLogs extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_code',
        'device_status',
        'net_status',
        'time',
        'content',
        'output_model_a',
        'output_model_b',
        'output_model_c',
        'input_type_a',
        'input_type_b',
        'input_type_c',
        'output_type_a',
        'output_type_b',
        'output_type_c',
        'temperature',
        'signal_intensity',
        'client_id',
        'loop',
        'event',
        'type',
        'iccid'
    ];
}
