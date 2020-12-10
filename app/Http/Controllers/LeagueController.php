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
        $teams = Team::query()->orderBy('name')->get();

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
            $nextWeekDTO = new NextWeekDTO($request->validated());
            $weekResult = $nextWeekUseCase->play($nextWeekDTO);
        } catch (HttpException $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }

        return response()->json($weekResult->toArray(), Response::HTTP_CREATED);
    }
}