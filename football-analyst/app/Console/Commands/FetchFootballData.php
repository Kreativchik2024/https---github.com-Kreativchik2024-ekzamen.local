<?php

namespace App\Console\Commands;

use App\Models\League;
use App\Models\Team;
use App\Models\Fixture;
use App\Services\BsdService;
use Illuminate\Console\Command;
use Carbon\Carbon;

class FetchFootballData extends Command
{
    protected $signature = 'fetch:football-data {--start-date= : Дата начала загрузки в формате Y-m-d}';
    protected $description = 'Fetch historical fixtures from Bzzoiro Sports Data API';

    protected BsdService $bsd;

    public function __construct(BsdService $bsd)
    {
        parent::__construct();
        $this->bsd = $bsd;
    }

    public function handle()
    {
        $this->info('Fetching leagues...');
        $this->fetchLeagues();

        $this->info('Fetching teams...');
        $this->fetchTeams();

        $this->info('Fetching historical fixtures...');
        $this->fetchHistoricalFixtures();

        $this->info('✅ All data fetched successfully.');
    }

    protected function fetchLeagues()
    {
        $response = $this->bsd->getLeagues();
        if (!$response->successful()) {
            $this->error('Failed to fetch leagues');
            return;
        }
        foreach ($response->json('results') ?? [] as $data) {
            League::updateOrCreate(
                ['external_id' => $data['id']],
                [
                    'name'      => $data['name'],
                    'country'   => $data['country'] ?? null,
                    'type'      => $data['type'] ?? 'league',
                    'logo_url'  => $data['logo'] ?? null,
                    'is_active' => true,
                ]
            );
        }
        $this->info('Leagues synced.');
    }

    protected function fetchTeams()
    {
        $response = $this->bsd->getTeams();
        if (!$response->successful()) {
            $this->error('Failed to fetch teams');
            return;
        }
        foreach ($response->json('results') ?? [] as $data) {
            Team::updateOrCreate(
                ['external_id' => $data['id']],
                [
                    'name'       => $data['name'],
                    'short_code' => $data['short_code'] ?? null,
                    'country'    => $data['country'] ?? null,
                    'logo_url'   => $data['logo'] ?? null,
                ]
            );
        }
        $this->info('Teams synced.');
    }

    protected function fetchHistoricalFixtures()
    {
        // Определяем начальную дату
        if ($startDateOption = $this->option('start-date')) {
            $start = Carbon::createFromFormat('Y-m-d', $startDateOption);
            if (!$start) {
                $this->error('Invalid start date format. Use Y-m-d.');
                return;
            }
        } else {
            $start = Carbon::now()->subYear(); // по умолчанию 1 год назад
        }

        $end = Carbon::now();
        $interval = \DateInterval::createFromDateString('1 day');
        $periods = new \DatePeriod($start, $interval, $end);

        foreach ($periods as $period) {
            $date = $period->format('Y-m-d');
            $this->info("Fetching fixtures for date: {$date}");
            $this->fetchFixturesForDay($date);
            sleep(2); // пауза между днями
        }
    }

    protected function fetchFixturesForDay(string $date)
    {
        $limit = 10;
        $offset = 0;
        $totalForDay = 0;

        do {
            $success = false;
            $attempts = 0;
            $maxAttempts = 3;

            while (!$success && $attempts < $maxAttempts) {
                $attempts++;
                try {
                    $response = $this->bsd->getEvents([
                        'date_from' => $date,
                        'date_to'   => $date,
                        'has_odds'  => 'true',
                        'limit'     => $limit,
                        'offset'    => $offset,
                    ]);

                    if ($response->successful()) {
                        $success = true;
                        break;
                    }

                    $this->warn("  ⚠️ Attempt {$attempts} failed for {$date} offset {$offset}. Status: " . $response->status());
                } catch (\Illuminate\Http\Client\ConnectionException $e) {
                    $this->warn("  ⚠️ Attempt {$attempts} connection timeout for {$date} offset {$offset}.");
                }

                if (!$success && $attempts < $maxAttempts) {
                    $this->info("  ↻ Retrying in 5 seconds...");
                    sleep(5);
                }
            }

            if (!$success) {
                $this->error("  ✗ Failed to fetch {$date} at offset {$offset} after {$maxAttempts} attempts. Skipping remaining pages for this day.");
                break;
            }

            $response = $response ?? null;
            if (!$response) break;

            $data = $response->json();
            $events = $data['results'] ?? [];
            $count = count($events);
            $totalForDay += $count;

            $this->info("  ✓ {$date} offset {$offset}: {$count} events");

            foreach ($events as $event) {
                $this->saveFixture($event);
            }

            if ($count < $limit) {
                break;
            }

            $offset += $limit;
            usleep(200000);
        } while (true);

        if ($totalForDay > 0) {
            $this->info("  ✔ Total for {$date}: {$totalForDay} fixtures");
        }
    }

   protected function saveFixture(array $eventData)
{
    // --- Лига ---
    if (!isset($eventData['league']['id'])) {
        $this->warn("  ⚠️ No league data for event {$eventData['id']}, skipping.");
        return;
    }

    $league = League::updateOrCreate(
        ['external_id' => $eventData['league']['id']],
        [
            'name'      => $eventData['league']['name'],
            'country'   => $eventData['league']['country'] ?? null,
            'type'      => $eventData['league']['type'] ?? 'league',
            'logo_url'  => $eventData['league']['logo'] ?? null,
            'is_active' => true,
        ]
    );

    // --- Домашняя команда ---
    $homeTeam = $this->findOrCreateTeam($eventData['home_team'] ?? null);
    if (!$homeTeam) {
        $this->warn("  ⚠️ Could not determine home team for event {$eventData['id']}, skipping.");
        return;
    }

    // --- Гостевая команда ---
    $awayTeam = $this->findOrCreateTeam($eventData['away_team'] ?? null);
    if (!$awayTeam) {
        $this->warn("  ⚠️ Could not determine away team for event {$eventData['id']}, skipping.");
        return;
    }

    // --- Сохраняем фикстуру ---
    Fixture::updateOrCreate(
        ['external_id' => $eventData['id']],
        [
            'league_id'    => $league->id,
            'home_team_id' => $homeTeam->id,
            'away_team_id' => $awayTeam->id,
            'starting_at'  => $eventData['start_date'],
            'status'       => $eventData['status'] ?? 'scheduled',
            'home_score'   => $eventData['home_score'] ?? null,
            'away_score'   => $eventData['away_score'] ?? null,
            'statistics'   => $eventData['stats'] ?? null,
        ]
    );
}

/**
 * Ищет команду по ID или названию. Если не находит — создаёт новую запись.
 */
protected function findOrCreateTeam($teamData): ?Team
{
    if (empty($teamData)) {
        return null;
    }

    // Если есть ID — стандартный случай
    if (isset($teamData['id'])) {
        return Team::updateOrCreate(
            ['external_id' => $teamData['id']],
            [
                'name'       => $teamData['name'] ?? 'Unknown',
                'short_code' => $teamData['short_code'] ?? null,
                'country'    => $teamData['country'] ?? null,
                'logo_url'   => $teamData['logo'] ?? null,
            ]
        );
    }

    // Если ID нет, но есть название
    $name = is_array($teamData) ? ($teamData['name'] ?? null) : $teamData;
    if (!$name) {
        return null;
    }

    // Пробуем найти по названию
    $team = Team::where('name', $name)->first();
    if ($team) {
        return $team;
    }

    // Создаём новую команду без external_id (позже можно будет обновить)
    return Team::create([
        'name'       => $name,
        'short_code' => null,
        'country'    => null,
        'logo_url'   => null,
    ]);
}
}