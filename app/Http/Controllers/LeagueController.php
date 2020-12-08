<?php

namespace App\Http\Controllers;

use App\Contract\ApplicationException;
use App\Http\Requests\StartSeasonRequest;
use App\Model\Team;
use App\UseCase\StartSeasonUseCase;
use Illuminate\Http\Response;

final class LeagueController extends Controller
{
    public function leagueTable()
    {
        $teams = Team::all();

        return view('league_table')->with(['teams' => $teams]);
    }

    public function startSeason(StartSeasonRequest $request, StartSeasonUseCase $startSeasonUseCase)
    {
        try {
            $responseData = $startSeasonUseCase->startSeason($request->validated());
        } catch (ApplicationException $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }

        return response()->json($responseData, Response::HTTP_CREATED);
    }
}