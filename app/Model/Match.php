<?php

namespace App\Model;

use App\Exceptions\InvalidOperationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Model\Match
 *
 * @property int $id
 * @property int $season_id
 * @property int $host_id
 * @property int $host_goals
 * @property int $guest_id
 * @property int $guest_goals
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Match newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Match newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Match query()
 * @method static \Illuminate\Database\Eloquent\Builder|Match whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Match whereGuestGoals($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Match whereGuestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Match whereHostGoals($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Match whereHostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Match whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Match whereUpdatedAt($value)
 * @property-read \App\Model\Team|null $guest
 * @property-read \App\Model\Team|null $host
 * @method static \Illuminate\Database\Eloquent\Builder|Match whereSeasonId($value)
 */
final class Match extends Model
{
    protected $table = 'matches';

    public function setSeasonId(int $seasonId): Match
    {
        $this->season_id = $seasonId;

        return $this;
    }

    public function host(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'host_id');
    }

    public function guest(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'guest_id');
    }

    /**
     * @param Team $hostTeam
     * @param Team $guestTeam
     * @return Match
     * @throws InvalidOperationException
     */
    public function play(Team $hostTeam, Team $guestTeam): Match
    {
        if (!$this->season_id) {
            throw new InvalidOperationException('Season id must be set');
        }

        if ((int) $hostTeam->id === (int) $guestTeam->id) {
            throw new InvalidOperationException('Team can not play with itself');
        }

        $this->host_id = $hostTeam->id;
        $this->guest_id = $guestTeam->id;
        $this->host_goals = 0;
        $this->guest_goals = 0;
        $overtime = mt_rand(0, 10);
        $goalBalance = $this->calculateGoalBalance($hostTeam, $guestTeam);

        for ($i = 0; $i < (90 + $overtime); $i++) {
            if (!$this->calculateScoringProbability()) {
                continue;
            }

            if ($this->getScoringNumber() < $goalBalance) {
                $this->host_goals++;
                $goalBalance--;
            } else {
                $this->guest_goals++;
                $goalBalance++;
            }
        }

        $this->save();
        $this->host = $hostTeam->updateMatchResults($this->host_goals, $this->guest_goals);
        $this->guest = $guestTeam->updateMatchResults($this->guest_goals, $this->host_goals);

        return $this;
    }

    private function calculateScoringProbability(): bool
    {
        $rand = mt_rand(0, 40);

        return ($rand === 3 || $rand === 13); // a goal will be in 2/41 cases
    }

    private function getScoringNumber(): int
    {
        return mt_rand(0, 100);
    }

    private function calculateGoalBalance(Team $hostTeam, Team $guestTeam)
    {
        $startingBalance = 60; // advantage for hosting team

        $balance = $startingBalance
            + $hostTeam->attackDefenseDiff($guestTeam)
            + $hostTeam->defenseAttackDiff($guestTeam)
            + $hostTeam->middleDiff($guestTeam);

        return $balance;
    }
}