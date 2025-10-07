<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScanHistory extends Model
{
    use HasFactory;

    protected $table = 'scan_history';
    protected $primaryKey = 'scan_id';
    public $timestamps = true; // We use scan_timestamp instead
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'user_id','skin_type','result_image_path'
    ];
    /**
     * Get the user that owns the scan history.
     */
    public function user() { return $this->belongsTo(User::class, 'user_id'); }
    public function results() { return $this->hasMany(ScanResult::class, 'scan_id'); }
    public function skinType() { return $this->belongsTo(SkinType::class, 'skin_type', 'skin_type_id'); }

}
