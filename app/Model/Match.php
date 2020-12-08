<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\Match
 *
 * @property int $id
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
 * @mixin \Eloquent
 */
final class Match extends Model
{
    protected $table = 'matches';
}