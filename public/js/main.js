start();

function start() {
  let teamSelectorOpener = '#team-selector-opener';
  let teamSelector = '#team-selector';

  enableSelector(teamSelectorOpener, teamSelector);
  enableCheckboxes();
  enableSubmit(teamSelectorOpener, teamSelector);
  enableNextWeek();
}

function enableSelector(teamSelectorOpener, teamSelector) {
  teamSelectorOpener = '#team-selector-opener';
  teamSelector = '#team-selector';

  $(teamSelectorOpener).click(function () {
    $(teamSelectorOpener).val($(teamSelectorOpener).val() === 'Open selector' ? 'Close selector' : 'Open selector');
    $(teamSelector).toggle('display');
  });
}

function enableCheckboxes() {
  $('.team-checkbox').click(function () {
    if ($('.team-checkbox:checkbox:checked').length >= 5) {
      $(this).prop('checked', false);
      alert('Only 4 teams can be selected');
    }
  });
}

function enableSubmit(teamSelectorOpener, teamSelector) {
  $('#submit-teams').click(function () {
    let selectedTeams = '.team-checkbox:checkbox:checked';
    if ($(selectedTeams).length < 4) {
      alert('Select 4 teams');
      return;
    }

    $(teamSelectorOpener).val('Open selector');
    $(teamSelector).hide();
    $('.selected-team').each(function () {
      $(this).html('');
    });

    let j = 0;

    $(selectedTeams).each(function () {
      let selector = '.team-' + j;
      $(selector).html($(this).next().html());
      $(selector).attr('id', 'season-team-' + $(this).attr('id'));
      j++;
    });
  });
}

function enableNextWeek() {
  let data = { 'team_ids': [] };
  let firstMatchPlayed = false;
  let weeksPlayed = 0;

  $('#next-week').click(function () {
    if (data.team_ids.length !== 4) {
      $('.team-checkbox:checkbox:checked').each(function () {
        data.team_ids.push($(this).attr('id'));
      });
    }

    if (!firstMatchPlayed && data.team_ids.length !== 4) {
      alert('Choose and submit 4 teams');
      return;
    }

    $.ajax({
      type: 'POST',
      url: 'api/next-week',
      data: JSON.stringify(data),
      contentType: 'application/json; charset=utf-8',
      dataType: 'json',

      success: function (data) {
        let i = 0;

        for (const [key, match] of Object.entries(data.matches ?? null)) {
          let matchResultSelector = '.match-result-' + i++;
          let result = (match.host_goals ?? 0) + ' - ' + (match.guest_goals ?? 0);

          $(matchResultSelector).find('.host-name').html(match.host_name ?? '');
          $(matchResultSelector).find('.result').html(result);
          $(matchResultSelector).find('.guest-name').html(match.guest_name ?? '');
        }

        for (const [key, team] of Object.entries(data.teams ?? null)) {
          let teamResultSelector = '#season-team-' + team.id;

          $('.team-' + key).first().html(team.name ?? '');
          $(teamResultSelector).closest('.row').find('.PTS').first().html(team.pts ?? 0);
          $(teamResultSelector).closest('.row').find('.Plays').first().html(team.plays ?? 0);
          $(teamResultSelector).closest('.row').find('.Wins').first().html(team.wins ?? 0);
          $(teamResultSelector).closest('.row').find('.Draws').first().html(team.draws ?? 0);
          $(teamResultSelector).closest('.row').find('.Loses').first().html(team.loses ?? 0);
          $(teamResultSelector).closest('.row').find('.GoalDifference').first().html(team.goal_difference ?? 0);
        }

        for (const [key, prediction] of Object.entries(data.predictions ?? null)) {
          $('#prediction-' + key).find('.prediction-team-name').first().html(prediction.name + ":&nbsp");
          $('#prediction-' + key).find('.team-prediction').first().html('%' + prediction.prediction);
        }

        firstMatchPlayed = true;
        weeksPlayed++;

        if (weeksPlayed <= 6) {
          $('#week-number').html(weeksPlayed);
        }
      },

      error: function (errorData) {
        let response = errorData['responseJSON'] ? errorData['responseJSON'] : null;
        let errors = [];

        if (typeof response === 'string' || response instanceof String) {
          console.log(response);
          return;
        }

        for (const [key, value] of Object.entries(response)) {
          let error = value[0] ?? '';
          console.log(key, error);
          errors.push(error);
        }

        alert(errors.toString());
      }
    });
  });
}