<div class="table-responsive">
    <table class="table table-condensed">
        <thead>
        <tr>
            <th style="width: 13%;">{{ trans('user.first_name') }}</th>
            <th style="width: 13%;">{{ trans('user.last_name') }}</th>
            <th style="width: 30%;">{{ trans('user.email') }}</th>
            <th style="width: 15%;">{{ trans('user.phone') }}</th>
            <th style="width: 25%;">{{ trans('user.address') }}</th>
            @if(Auth::user()->isAdmin())
                <th style="width: 4%;"></th>
            @endif
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->first_name }}</td>
                <td>{{ $user->last_name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->phone }}</td>
                <td>{{ $user->address_street . ' ' . $user->address_city }}</td>
                @if(Auth::user()->isAdmin())
                    <td class="text-center"><a href="{{ url('user/' . $user->id) }}" title="{{ trans('user.edit') }}" class="btn btn-xs btn-2d"><i class="fa fa-pencil-square-o"></i></a></td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
</div>