<?php

namespace App\Model;

use App\DTO\NextWeekDTO;
use App\Exceptions\TeamsNotFoundException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\Model\Team
 *
 * @property int $id
 * @property string $name
 * @property int $attack
 * @property int $middle
 * @property int $defense
 * @property int $overall
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Team newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Team newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Team query()
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereAttack($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereDefense($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereMiddle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereOverall($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Model\Season[] $season
 * @property-read int|null $season_count
 */
final class Team extends Model
{
    protected $table = 'teams';

    public function attackDefenseDiff(Team $otherTeam): int
    {
        return $this->attack - $otherTeam->defense;
    }

    public function defenseAttackDiff(Team $otherTeam): int
    {
        return $this->defense - $otherTeam->attack;
    }

    public function middleDiff(Team $otherTeam): int
    {
        return $this->middle - $otherTeam->middle;
    }

    public function season(): BelongsToMany
    {
        return $this->belongsToMany(Season::class, 'seasons_teams', 'team_id', 'season_id');
    }

    /**
     * @param NextWeekDTO $nextWeekDTO
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     * @throws TeamsNotFoundException
     */
    public static function getTeamsForNextWeek(NextWeekDTO $nextWeekDTO)
    {
        $teams = Team::query()->whereIn('id', $nextWeekDTO->getTeamIds())->get();

        if ($teams->count() !== 4) {
            $foundIds = $teams->pluck('id')->toArray();
            throw new TeamsNotFoundException(array_diff($nextWeekDTO->getTeamIds(), $foundIds));
        }

        return $teams;
    }
}