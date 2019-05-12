@section('script')
$(function(){
    var role_users = [];
    $.each($('input[name="users[]"]:checked'), function(){    
        var val = parseInt($(this).val());        
        role_users.push(val);
    });

    $('input[name="role_admins[]"]').each(function(cb){
        var val = parseInt($(this).val());
        if (!role_users.includes(val)) {
            $(this).parent().hide();
        }
    });
    $('input[name="users[]"]').change(function(cb){
        var val = parseInt($(this).val());
        var checked = $(this).prop('checked');
        $('input[name="role_admins[]"][value="' + val + '"]').parent().toggle(checked);
    });
});
@endsection
