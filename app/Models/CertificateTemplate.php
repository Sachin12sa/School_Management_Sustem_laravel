<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertificateTemplate extends Model
{
    protected $table = 'certificate_templates';

    protected $fillable = [
        'name', 'applicable_user', 'page_layout', 'photo_style', 'photo_size',
        'top_space', 'bottom_space', 'right_space', 'left_space',
        'signature_image', 'logo_image', 'background_image',
        'content', 'status', 'created_by',
    ];

    public static function getRecord()
    {
        return self::where('is_delete', 0)->orderByDesc('created_at')->get();
    }

    public static function getActive($applicableUser = null)
    {
        $q = self::where('is_delete', 0)->where('status', 1);
        if ($applicableUser) {
            $q->where('applicable_user', $applicableUser);
        }
        return $q->orderBy('name')->get();
    }

    public static function getSingle($id)
    {
        return self::find($id);
    }

    public function getPageLayoutLabelAttribute(): string
    {
        return match($this->page_layout) {
            'A4_landscape'  => 'A4 (Landscape)',
            'A4_portrait'   => 'A4 (Portrait)',
            'A5_landscape'  => 'A5 (Landscape)',
            'A5_portrait'   => 'A5 (Portrait)',
            default         => $this->page_layout,
        };
    }

    public function getApplicableUserLabelAttribute(): string
    {
        return match($this->applicable_user) {
            'student'  => 'Student',
            'employee' => 'Employee',
            default    => ucfirst($this->applicable_user),
        };
    }
}