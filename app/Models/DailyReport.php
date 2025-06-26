<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyReport extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'student_id',
        'report_date',
        'memorization_parts',
        'revision_parts',
        'grade',
        'memorization_from_surah',
        'memorization_from_verse',
        'memorization_to_surah',
        'memorization_to_verse',
        'revision_from_surah',
        'revision_from_verse',
        'revision_to_surah',
        'revision_to_verse',
        'notes',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'report_date' => 'date',
        'memorization_parts' => 'decimal:2',
        'revision_parts' => 'decimal:2',
        'grade' => 'decimal:2',
    ];
    
    /**
     * Get the student that owns the report.
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
    
    /**
     * Get the starting surah for the memorization.
     */
    public function fromSurah()
    {
        return $this->belongsTo(Surah::class, 'memorization_from_surah');
    }
    
    /**
     * Get the ending surah for the memorization.
     */
    public function toSurah()
    {
        return $this->belongsTo(Surah::class, 'memorization_to_surah');
    }

    /**
     * Get the starting surah for memorization.
     */
    public function memorization_from_surah()
    {
        return $this->belongsTo(Surah::class, 'memorization_from_surah');
    }

    /**
     * Get the ending surah for memorization.
     */
    public function memorization_to_surah()
    {
        return $this->belongsTo(Surah::class, 'memorization_to_surah');
    }

    /**
     * Get the starting surah for revision.
     */
    public function revision_from_surah()
    {
        return $this->belongsTo(Surah::class, 'revision_from_surah');
    }

    /**
     * Get the ending surah for revision.
     */
    public function revision_to_surah()
    {
        return $this->belongsTo(Surah::class, 'revision_to_surah');
    }
} 