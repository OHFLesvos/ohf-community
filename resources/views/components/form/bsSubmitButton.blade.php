<button type="submit" class="btn btn-primary">
    @isset($icon) <x-icon :icon="$icon"/> @endisset
    {{ $label }}
</button>
