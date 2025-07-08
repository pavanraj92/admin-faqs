<?php

namespace admin\faqs\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Config;

class Faq extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'question',
        'answer',
        'status'
    ];

    public function scopeFilter($query, $question)
    {
        if ($question) {
            return $query->where('question', 'like', '%' . $question . '%');
        }
        return $query;
    }
    /**
     * filter by status
     */
    public function scopeFilterByStatus($query, $status)
    {
        if (!is_null($status)) {
            return $query->where('status', $status);
        }

        return $query;
    }

    public static function getPerPageLimit(): int
    {
        return Config::has('get.admin_page_limit')
            ? Config::get('get.admin_page_limit')
            : 10;
    }
}

