<?php

namespace App\Models\Accounting;

use Dyrynda\Database\Support\NullableFields;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Category extends Model
{
    use HasFactory;
    use NullableFields;

    protected $table = 'accounting_categories';

    protected $fillable = [
        'name',
        'description',
        'enabled',
    ];

    protected $nullable = [
        'description',
    ];

    protected $casts = [
        'enabled' => 'boolean',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function getIsRootAttribute(): bool
    {
        return $this->parent_id == null;
    }

    public function scopeIsRoot(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    public function scopeForParent(Builder $query, int $parentId): Builder
    {
        return $query->where('parent_id', $parentId);
    }

    public function getPathElements(): Collection
    {
        $elements = collect();
        $elements->push($this);
        $elem = $this;
        while ($elem->parent != null) {
            $elements->prepend($elem->parent);
            $elem = $elem->parent;
        }

        return $elements;
    }

    public static function getNested(?int $parent = null, int $indentation = 0, bool $enabledOnly = false): array
    {
        $results = [];
        $items = self::query()
            ->select('id', 'name')
            ->when($enabledOnly, fn ($q) => $q->where('enabled', true))
            ->orderBy('name', 'asc')
            ->when($parent !== null, fn ($q) => $q->forParent($parent), fn ($q) => $q->isRoot())
            ->get();
        foreach ($items as $item) {
            $results[$item['id']] = [
                'name' => $item['name'],
                'indentation' => $indentation,
            ];
            $children = self::getNested($item['id'], $indentation + 1, $enabledOnly);
            foreach ($children as $k => $v) {
                $results[$k] = $v;
            }
        }

        return $results;
    }

    public static function queryByParent(?int $parent = null, ?int $exclude = null): Collection
    {
        return self::query()
            ->select('id', 'name', 'description', 'enabled')
            ->orderBy('name', 'asc')
            ->when($exclude !== null, fn ($q) => $q->where('id', '!=', $exclude))
            ->when($parent !== null, fn ($q) => $q->forParent($parent), fn ($q) => $q->isRoot())
            ->get()
            ->map(function ($e) use ($exclude) {
                $e['children'] = self::queryByParent($e['id'], $exclude);

                return $e;
            });
    }
}
