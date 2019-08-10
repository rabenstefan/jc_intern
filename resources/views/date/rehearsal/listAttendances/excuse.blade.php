<div id="excuse-list">
                            <?php
                            // Get all second level voices.
                            $voices = App\Models\Voice::getParentVoices();
                            ?>
                            @foreach($voices as $voice)
                                <div class="row" id="{{ trans('nav.users') }}-{{ $voice->name }}">
                                    <div class="col-xs-12">
                                        <div class="panel panel-2d">
                                            <div class="panel-heading">
                                                {{ $voice->name }}
                                            </div>

                                            <div class="panel-body">
                                                @foreach($voice->children as $sub_voice)
                                                    <div class="col-xs-12">
                                                        <div class="panel panel-2d">
                                                            <div class="panel-heading">
                                                                {{ $sub_voice->name }}
                                                            </div>


                                                            <div class="row">
                                                                <?php
                                                                /** @var App\Models\Voice $sub_voice */
                                                                $users = \App\Models\User::getUsersOfVoice($sub_voice->id, true);
                                                                foreach($users as $user){
                                                                    if($user->missedRehearsal($currentRehearsal->id)){    
                                                                ?>
                                                                        @include('date.rehearsal.listAttendances.excuse_user_entry', ['user' => $user, 'currentRehearsal' => $currentRehearsal])
                                                                <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>