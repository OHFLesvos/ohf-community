<?php

namespace App\Http\Resources\Accounting;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Transaction extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);
        $data['category_full_name'] = $this->category->getPathElements()->pluck('name')->join(' » ');
        $data['project_full_name'] = $this->when($this->project !== null, fn () => $this->project->getPathElements()->pluck('name')->join(' » '));
        $data['supplier'] = $this->whenLoaded('supplier', fn () => new Supplier($this->supplier));
        $data['can_update'] = $request->user()->can('update', $this->resource);
        $data['can_delete'] = $request->user()->can('delete', $this->resource);
        $audit = $this->audits()->first();
        $data['creating_user'] = $this->when(isset($audit) && isset($audit->getMetadata()['user_name']), fn () => $audit->getMetadata()['user_name']);
        $data['controller_name'] = $this->when($this->controlled_by !== null, fn () => optional($this->controller)->name);
        $data['can_undo_controlling'] = $request->user()->can('undoControlling', $this->resource);
        $data['can_book_externally'] = $request->user()->can('book-accounting-transactions-externally');
        $data['can_undo_booking'] = $request->user()->can('undoBooking', $this->resource);
        $data['external_url'] = $this->when($this->external_id !== null, fn () => $this->externalUrl);
        $data['receipt_pictures'] = $this->when(!empty($this->receipt_pictures), fn () => collect($this->receipt_pictures)
            ->filter(fn ($picture) => Storage::exists($picture))
            ->map(function ($picture) {
                $isImage = Str::startsWith(Storage::mimeType($picture), 'image/');
                $thumbnail = $isImage
                    ? (Storage::exists(thumb_path($picture))
                        ? Storage::url(thumb_path($picture))
                        : Storage::url($picture))
                    : (Storage::exists(thumb_path($picture, 'jpeg'))
                        ? Storage::url(thumb_path($picture, 'jpeg'))
                        : null);
                return [
                    'type' => $isImage ? 'image' : 'file',
                    'url' => Storage::url($picture),
                    'mime_type' => Storage::mimeType($picture),
                    'file_size' => bytes_to_human(Storage::size($picture)),
                    'thumbnail_url' => $thumbnail,
                    'thumbnail_size' => $thumbnail !== null ? config('accounting.thumbnail_size') : null,
                ];
            }), []);

        return $data;
    }
}
