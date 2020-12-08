<?php

namespace App\Model;

use App\DTO\StartSeasonDTO;
use App\Exceptions\TeamsNotFoundException;
use Illuminate\Database\Eloquent\Model;

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

    /**
     * @param StartSeasonDTO $startSeasonDTO
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     * @throws TeamsNotFoundException
     */
    public static function getTeamsForNextWeek(StartSeasonDTO $startSeasonDTO)
    {
        $teams = Team::query()->whereIn('id', $startSeasonDTO->getTeamIds())->get();

        if ($teams->count() !== 4) {
            $foundIds = $teams->pluck('id')->toArray();
            throw new TeamsNotFoundException(array_diff($startSeasonDTO->getTeamIds(), $foundIds));
        }

        return $teams;
    }
}