<button
    type="submit"
    class="btn btn-danger delete-confirmation"
    data-confirmation="{{ $confirmation }}"
>
    @isset($icon) <x-icon :icon="$icon"/> @endisset
    {{ $label }}
</button>
