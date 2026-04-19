<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;

class BsdService
{
    protected string $baseUrl = 'https://sports.bzzoiro.com/api';
    protected string $apiToken;

    public function __construct()
    {
        $this->apiToken = env('BZZOIRO_API_TOKEN');
    }

    /**
     * Создаёт настроенный HTTP-клиент с таймаутами и повторными попытками
     */
protected function client(): PendingRequest
{
    return Http::withHeaders([
        'Authorization' => 'Token ' . $this->apiToken,
    ])
    ->timeout(30)           // общий таймаут 30 секунд
    ->connectTimeout(10)    // соединение 10 секунд
    ->retry(2, 1000);       // 2 попытки, пауза 1 сек
}

    // Базовый метод для выполнения запросов
    private function request(string $endpoint, array $params = [])
    {
        return $this->client()->get($this->baseUrl . $endpoint, $params);
    }

    // Получить список лиг
    public function getLeagues()
    {
        return $this->request('/leagues/');
    }

    // Получить список команд (можно фильтровать по лиге)
    public function getTeams(array $params = [])
    {
        return $this->request('/teams/', $params);
    }

    // Получить матчи (события)
    public function getEvents(array $params = [])
    {
        return $this->request('/events/', $params);
    }

    // Получить лайв-события
    public function getLiveEvents()
    {
        return $this->request('/live/');
    }

    // Получить ML-прогнозы (с CatBoost)
    public function getPredictions(array $params = [])
    {
        return $this->request('/predictions/', $params);
    }
}