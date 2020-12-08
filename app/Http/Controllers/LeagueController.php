<?php

namespace App\Http\Controllers;

use App\Contract\HttpException;
use App\DTO\NextWeekDTO;
use App\Http\Requests\NextWeekRequest;
use App\Model\Team;
use App\UseCase\NextWeekUseCase;
use Illuminate\Http\Response;

final class LeagueController extends Controller
{
    public function leagueTable()
    {
        $teams = Team::all();

        return view('league_table')->with(['teams' => $teams]);
    }

    /**
     * @param NextWeekRequest $request
     * @param NextWeekUseCase $nextWeekUseCase
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function nextWeek(NextWeekRequest $request, NextWeekUseCase $nextWeekUseCase)
    {
        try {
            $startSeasonDTO = new NextWeekDTO($request->validated());
            $responseData = $nextWeekUseCase->startSeason($startSeasonDTO);
        } catch (HttpException $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }

        return response()->json($responseData, Response::HTTP_CREATED);
    }
}