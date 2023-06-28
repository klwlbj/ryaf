<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class wlyDevice extends Model
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
    ];

    public const EVENT_LINK_OUT_ON                 = 1941;
    public const EVENT_LINK_OUT_OFF                = 3941;
    public const EVENT_FEEDBACK_OUT_ON             = 1943;
    public const EVENT_FEEDBACK_OUT_OFF            = 3943;
    public const EVENT_NOT_LINK_OUT_ON             = 1944;
    public const EVENT_NOT_LINK_OUT_OFF            = 3944;
    public const EVENT_FEEDBACK_SHORT_OUT_ON       = 1945;
    public const EVENT_FEEDBACK_DISCONNECT_OUT_OFF = 3945;
    public const EVENT_OFFLINE                     = 'offline';
}
