@extends('layouts.app')

@section('title'){{ trans('user.index_title') }}@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>{{ trans('user.index_title') }}</h1>

                <?php
                    // Get all users that have echoed in this semester.
                    $semester = App\Semester::all()->last();
                    $users = App\User::orderBy('voice_id')->where('last_echo', $semester->id)->get();

                    // Set flags for voice grouping.
                    $new_super_group = true;
                    $first_super_group = true;
                ?>
                @foreach($users->groupBy('voice_id') as $users_in_voice)
                    <?php
                        // Juggling with voice super groups to output the voices neatly.
                        $current_super_group = App\Voice::find($users_in_voice->first()->voice_id)->super_group;
                        $new_super_group = empty($last_super_group) || $last_super_group != $current_super_group;
                        $last_super_group = $current_super_group;
                    ?>
                    {{-- End of the row. Do not output before first group, otherwise there is no row to end. --}}
                    @if($new_super_group && !$first_super_group)
                            </div>
                        </div>
                    </div>
                    @endif
                    {{-- Start new row for new super group. --}}
                    @if($new_super_group)
                    <div class="row">
                        <div class="panel panel-2d">
                            <div class="panel-heading">{{ App\Voice::find($last_super_group)->name }}</div>

                            <div class="panel-body">
                    @endif
                                    <div class="col-xs-12">
                                        <div class="panel panel-2d">
                                            <div class="panel-heading">
                                                {{ App\Voice::find($users_in_voice->first()->voice_id)->name }}
                                            </div>

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
                                                @foreach($users_in_voice as $user)
                                                    <tr>
                                                        <td>{{ $user->first_name }}</td>
                                                        <td>{{ $user->last_name }}</td>
                                                        <td>{{ $user->email }}</td>
                                                        <td>{{ $user->phone }}</td>
                                                        <td>{{ $user->address_street . ' ' . $user->address_city }}</td>
                                                        @if(Auth::user()->isAdmin())
                                                        <td class="text-center"><a href="{{ url('user/' . $user->id) }}" title="{{ trans('user.edit') }}"><i class="fa fa-pencil-square-o"></i></a></td>
                                                        @endif
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                    <?php $first_super_group = false; /* At this point no other group be the first group anymore. */ ?>
                @endforeach
            </div>
        </div>
    </div>
@endsection