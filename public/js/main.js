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
      $('.team-' + j).html($(this).next().html());
      j++;
    });
  });
}

function enableNextWeek() {
  let data = { 'team_ids': [1, 2, 3, 4] };

  $('#next-week').click(function () {
    // $('.team-checkbox:checkbox:checked').each(function () {
    //   data.team_ids.push($(this).attr('id'));
    // });

    // if (data.team_ids.length !== 4) {
    //   alert('Choose and submit 4 teams');
    //   return;
    // }

    $.ajax({
      type: 'POST',
      url: 'api/next-week',
      data: JSON.stringify(data),
      contentType: 'application/json; charset=utf-8',
      dataType: 'json',

      success: function (data) {
        console.log(data);
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