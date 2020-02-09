<p><small class="text-muted">
    @lang('app.current_settings'): {{ $current_num_frequent_visitors }} persons affected, 
    out of {{ $current_num_people }} ({{ round($current_num_frequent_visitors/$current_num_people * 100) }} %)
</small></p>
