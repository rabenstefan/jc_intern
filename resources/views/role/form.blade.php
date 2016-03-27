<tr{!! isset($role) ? ' id="role-'.$role->id.'"' : ' id="new-role"' !!}>
    @if(isset($role))
        {!! Form::open(['url' => route('role.update', $role->id), 'method' => 'PUT']) !!}
    @else
        {!! Form::open(['url' => route('role.store'), 'method' => 'POST']) !!}
    @endif
    <td>
        @if(isset($role))
        <span class="swap-to-input btn">
            {{ $role->label }}&nbsp;<i class="fa fa-pencil-square-o"></i>
        </span>
        @endif
        {{ Form::text('label', isset($role) ? $role->label : null, ['class' => 'form-control form-control-2d']) }}
    </td>
    <td>{{ Form::checkboxInput2d('can_plan_rehearsal', isset($role) ? $role->can_plan_rehearsal : null) }}</td>
    <td>{{ Form::checkboxInput2d('can_plan_gig', isset($role) ? $role->can_plan_gig : null) }}</td>
    <td>{{ Form::checkboxInput2d('can_send_mail', isset($role) ? $role->can_send_mail : null) }}</td>
    <td>{{ Form::checkboxInput2d('can_configure_system', isset($role) ? $role->can_configure_system : null) }}</td>
    <td>{{ Form::checkboxInput2d('only_own_voice', isset($role) ? $role->only_own_voice : null) }}</td>
    <td>{{ Form::button('<i class="fa fa-floppy-o"></i>', ['type' => 'submit', 'class' => 'btn btn-2d', 'title' => trans('role.save')]) }}</td>
    {!! Form::close() !!}
</tr>