<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Record extends Model
{
  use HasFactory;

  protected $fillable = [
    'title',
    'description',
    'status',
    'category_id',
    'created_by',
  ];

  protected function casts(): array
  {
    return [
      'created_at' => 'datetime',
      'updated_at' => 'datetime',
    ];
  }

  public function category(): BelongsTo
  {
    return $this->belongsTo(Category::class);
  }

  public function creator(): BelongsTo
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  public function scopeSearch(Builder $query, ?string $search): Builder
  {
    if ($search) {
      $query->where('title', 'like', "%{$search}%")
            ->orWhere('description', 'like', "%{$search}%");
    }
    return $query;
  }

  public function scopeStatus(Builder $query, ?string $status): Builder
  {
    return $query->when($status, fn($q) => $q->where('status', $status));
  }

  public function scopeCategory(Builder $query, ?int $categoryId): Builder
  {
    return $query->when($categoryId, fn($q) => $q->where('category_id', $categoryId));
  }
}