<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IdCardTemplate extends Model
{
    protected $table = 'id_card_templates';

    protected $fillable = [
        'name', 'applicable_user', 'layout_width', 'layout_height',
        'photo_style', 'photo_size', 'top_space', 'bottom_space',
        'left_space', 'right_space', 'signature_image', 'logo_image',
        'background_image', 'accent_color', 'text_color', 'extra_content',
    ];

    public static function getAll()
    {
        return self::where('is_delete', 0)->orderByDesc('id')->get();
    }

    public static function getForUser($applicableUser)
    {
        return self::where('is_delete', 0)
            ->where('applicable_user', $applicableUser)
            ->orderByDesc('id')
            ->get();
    }

    public static function getSingle($id)
    {
        return self::find($id);
    }

    public function getLayoutLabelAttribute(): string
    {
        return "Width {$this->layout_width}mm × Height {$this->layout_height}mm";
    }
}