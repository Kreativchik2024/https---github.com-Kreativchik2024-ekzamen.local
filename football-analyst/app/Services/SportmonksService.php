<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SportmonksService
{
    protected string $baseUrl = 'https://api.sportmonks.com/v3/football';
    protected string $apiToken;

    public function __construct()
    {
        $this->apiToken = trim(env('SPORTMONKS_API_TOKEN'));
    }

    /**
     * Базовый метод для выполнения GET-запроса к API
     */
    protected function request(string $endpoint, array $params = [])
    {
        $params['api_token'] = $this->apiToken;

        return Http::withoutVerifying()
            ->get($this->baseUrl . $endpoint, $params);
    }

    /**
     * Получить список лиг
     */
    public function getLeagues()
    {
        return $this->request('/leagues');
    }

    /**
     * Получить информацию о конкретной лиге
     */
    public function getLeague(int $leagueId)
    {
        return $this->request("/leagues/{$leagueId}");
    }

    /**
     * Получить команды по ID лиги
     */
    public function getTeamsByLeague(int $leagueId)
    {
        return $this->request('/teams', [
            'leagues' => $leagueId,
        ]);
    }

    /**
     * Получить матчи (фикстуры) за конкретную дату
     */
    public function getFixturesByDate(string $date)
    {
        return $this->request("/fixtures/date/{$date}", [
            'include' => 'localTeam,visitorTeam,league,odds',
        ]);
    }

    /**
     * Получить коэффициенты для конкретного матча
     */
    public function getOddsByFixture(int $fixtureId)
    {
        return $this->request("/odds/fixture/{$fixtureId}");
    }

    /**
     * Получить список букмекеров
     */
    public function getBookmakers()
    {
        return $this->request("/bookmakers");
    }
    public function getCurrentSeasonByLeague(int $leagueId)
{
    return $this->request('/leagues/' . $leagueId, [
        'include' => 'currentSeason',
    ]);
}




public function getSchedulesBySeason(int $seasonId)
{
    return $this->request("/schedules/seasons/{$seasonId}");
}

public function getSeasonsByLeague(int $leagueId)
{
    return $this->request('/seasons', [
        'leagues' => $leagueId,
    ]);
}
}