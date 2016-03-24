<div class="table-responsive">
    <table class="table table-condensed">
        <thead>
        <tr>
            <th style="width: 11%;">{{ trans('user.first_name') }}</th>
            <th style="width: 12%;">{{ trans('user.last_name') }}</th>
            <th style="width: 30%;">{{ trans('user.email') }}</th>
            <th style="width: 18%;">{{ trans('user.phone') }}</th>
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
                <td>{{ $user->email }} <a href="mailto:{{ $user->email }}" title="{{ trans('user.send_mail') }}" class="pull-right text-large"><i class="fa fa-envelope-o"></i></a></td>
                <td>{{ $user->phone }} <a href="tel:{{ $user->phone }}" title="{{ trans('user.call_phone') }}" class="pull-right text-large"><i class="fa fa-phone"></i></a></td>
                <td>{{ $user->address_street . ' ' . $user->address_city }} <a href="https://www.google.com/maps/search/{{ $user->address_street . ' ' . $user->address_city }}/" title="{{ trans('user.address_search') }}" target="_blank" class="pull-right text-large"><i class="fa fa-map-o"></i></a></td>
                @if(Auth::user()->isAdmin())
                    <td class="text-center"><a href="{{ url('user/' . $user->id) }}" title="{{ trans('user.edit') }}" class="btn btn-xs btn-2d"><i class="fa fa-pencil-square-o"></i></a></td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
</div>