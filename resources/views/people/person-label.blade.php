@if(isset($prefix)) {{ $prefix }}:@endif
<x-icon-gender :gender="$person->gender"/>
{{ $person->name }} {{ strtoupper($person->family_name) }}@if(isset($person->date_of_birth)), {{ $person->date_of_birth }} (age {{ $person->age }})@endif
@if($person->nationality != null), {{ $person->nationality }}@endif
@if(isset($suffix)) <small class="text-muted">{{ $suffix }}</small>@endif
