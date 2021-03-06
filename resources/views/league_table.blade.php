@extends('header')

@section('content')
{{--  Team selection  --}}
<div class="row">
    <h4>Choose 4 teams to participate in league: </h4>
    <input type="button" id="team-selector-opener" value="Open selector"/>
    <input type="button" id="submit-teams" value="Submit">
    <br/>
    <div id="team-selector">
        @foreach ($teams as $i => $team)
            @php /** @var \App\Model\Team $team */ @endphp

            @if ($i % 5 === 0) <div class="row"> @endif
                <div class="col-md-2 team">
                    <input aria-label="team-id" class="team-checkbox" type="checkbox" id="{{$team->id}}">
                    <span class="team-name">{{ $team->name }}</span> (
                    <span class="team-power">A</span> {{$team->attack}},
                    <span class="team-power">M</span> {{$team->middle}},
                    <span class="team-power">D</span> {{$team->defense}},
                    <span class="team-power">O</span> {{$team->overall}})
                </div>
                @if (($i + 1) % 5 === 0) </div> <br/>@endif
        @endforeach
    </div>
</div>
<br/>
<div class="row">
    <div class="col-md-10">
        {{--  League table  --}}
        <div class="league-table">
            <div class="row">
                <div class="col-md-8 table-header">
                    League table
                </div>
                <div class="col-md-4 table-header">
                    Match results
                </div>
            </div>
            <br/>
            <div class="row">
                <div class="col-md-2">
                    Teams
                </div>
                <div class="col-md-1">PTS</div>
                <div class="col-md-1">P</div>
                <div class="col-md-1">W</div>
                <div class="col-md-1">D</div>
                <div class="col-md-1">L</div>
                <div class="col-md-1">GD</div>
                <div class="col-md-4 text-center"><span id="week-number"></span> Week Match Result</div>
            </div>
            <hr/>
            <div class="teams">
                @foreach(range(0, 3) as $j)
                    <div class="row">
                        <div class="col-md-2">
                            <span class="selected-team team-{{ $j }}"></span>
                        </div>
                        <div class="col-md-1 PTS">0</div>
                        <div class="col-md-1 Plays">0</div>
                        <div class="col-md-1 Wins">0</div>
                        <div class="col-md-1 Draws">0</div>
                        <div class="col-md-1 Loses">0</div>
                        <div class="col-md-1 GoalDifference">0</div>
                        @if ($j < 2)
                            <div class="col-md-4 match-result-{{ $j }}">
                                <span class="host-name match-span"></span>
                                <span class="result match-span"></span>
                                <span class="guest-name match-span"></span>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
            <hr/>
            <div class="row">
                <div class="col-md-2 offset-md-10">
                    <input type="submit" id="next-week" value="Next Week"/>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2 predictions">
        Predictions
        @foreach(range(0, 3) as $k)
        <div class="row" id="prediction-{{ $k }}">
            <span class="prediction-team-name"></span>
            <span class="team-prediction"></span>
        </div>
        @endforeach
    </div>
</div>
@endsection
