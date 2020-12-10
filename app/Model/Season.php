<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

/**
 * App\Model\Season
 *
 * @property int $id
 * @property int|null $winner_id
 * @property int|null $matches_number
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Model\Match[] $matches
 * @property-read int|null $matches_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Model\Team[] $teams
 * @property-read int|null $teams_count
 * @method static \Illuminate\Database\Eloquent\Builder|Season newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Season newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Season query()
 * @method static \Illuminate\Database\Eloquent\Builder|Season whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Season whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Season whereMatchesNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Season whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Season whereWinnerId($value)
 */
final class Season extends Model
{
    protected $table = 'seasons';
    private const DEFAULT_RELATIONS = [
        'matches', 'teams'
    ];

    public const TEAMS_IN_SEASON = 4;
    public const TOTAL_MATCHES = 12;

    public function matches(): hasMany
    {
        return $this->hasMany(Match::class, 'season_id');
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'seasons_teams', 'season_id', 'team_id');
    }

    /**
     * @param Collection $teams
     * @return Season
     */
    public static function getOrCreate(Collection $teams)
    {
        /** find season without winner */
        if (!$season = Season::query()->whereNull('winner_id')->with(self::DEFAULT_RELATIONS)->first()) {
            /** create new season */
            $season = Season::query()->create();

            /** insert teams for season in pivot table */
            DB::table('seasons_teams')->insert(array_map(function ($teamId) use ($season) {
                return [
                    'season_id' => $season->id,
                    'team_id'   => $teamId
                ];
            }, $teams->pluck('id')->toArray()));
        };

        return $season;
    }

    public function getPredictions()
    {
        $teams = $this->teams->sortByDesc(function ($team) {
            return $team->pts;
        });
        $predictions = [];

        /** @var Team $team */
        foreach ($teams as $team) {
            $predictions[] = [
                'name' => $team->name,
                'prediction' => (int) (
                    (($team->wins * 10) + ($team->draws * 2) - ($team->loses * 5) - (($team->pts - $teams->max('pts')) * 2))
                )
            ];
        }

        return $predictions;
    }

//    public function maxMatchesPlayed(): bool
//    {
//        return $this->matches_count <= self::TOTAL_MATCHES;
//    }
}