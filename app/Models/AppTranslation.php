<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppTranslation extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'translations';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'language_id',
        'translation_key',
        'translation_value',
    ];
    
    /**
     * Get the language that the translation belongs to.
     */
    public function language()
    {
        return $this->belongsTo(Language::class);
    }
    
    /**
     * Get a translation value by key and language code.
     *
     * @param string $key
     * @param string $languageCode
     * @param string $default
     * @return string
     */
    public static function getTranslation($key, $languageCode, $default = '')
    {
        try {
            $translation = self::whereHas('language', function ($query) use ($languageCode) {
                    $query->where('code', $languageCode);
                })
                ->where('translation_key', $key)
                ->first();
                
            return $translation ? $translation->translation_value : $default;
        } catch (\Exception $e) {
            // Return default value in case of any error
            \Log::error('Translation fetch error: ' . $e->getMessage());
            return $default;
        }
    }
}