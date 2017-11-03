<form method="POST" action="{{ $action }}" class="d-inline">
    {{ csrf_field() }}
    {{ method_field('DELETE') }}
    {{ Form::bsDeleteButton($label, $icon, $confirmation) }}
</form>