@if(isset($prefix)) {{ $prefix }}:@endif 
@if($person->gender == 'f')@icon(female)
@elseif($person->gender == 'm')@icon(male)
@endif
{{ strtoupper($person->family_name) }} {{ $person->name }}@if(isset($person->date_of_birth)), {{ $person->date_of_birth }} (age {{ $person->age }})@endif
@if($person->nationality != null), {{ $person->nationality }}@endif