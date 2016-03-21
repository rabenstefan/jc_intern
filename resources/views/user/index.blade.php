@extends('layouts.app')

@section('title'){{ trans('user.index_title') }}@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>{{ trans('user.index_title') }}</h1>

                <?php
                    $semester = App\Semester::all()->last();
                    $users = App\User::orderBy('voice_id')->where('last_echo', $semester->id)->get();
                ?>
                @foreach($users->groupBy('voice_id')->chunk(2) as $voices)
                    <div class="row">
                        <div class="panel panel-2d">
                            <div class="panel-heading">{{ App\Voice::find(App\Voice::find($voices->first()->first()->voice_id)->super_group)->name }}</div>

                            <div class="panel-body">
                                @foreach ($voices as $voice)
                                    <div class="col-xs-12 col-md-6">
                                        <div class="panel panel-2d">
                                            <div class="panel-heading">
                                                {{ App\Voice::find($voice->first()->voice_id)->name }}
                                            </div>

                                            <table class="table table-condensed">
                                                <thead>
                                                    <tr>
                                                        <th>{{ trans('user.first_name') }}</th>
                                                        <th>{{ trans('user.last_name') }}</th>
                                                        <th>{{ trans('user.email') }}</th>
                                                        @if(Auth::user()->isAdmin())
                                                        <th></th>
                                                        @endif
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($voice as $user)
                                                    <tr>
                                                        <td>{{ $user->first_name }}</td>
                                                        <td>{{ $user->last_name }}</td>
                                                        <td>{{ $user->email }}</td>
                                                        @if(Auth::user()->isAdmin())
                                                        <td class="text-center"><a href="{{ url('user/' . $user->id) }}" title="{{ trans('user.edit') }}"><i class="fa fa-pencil-square-o"></i></a></td>
                                                        @endif
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection