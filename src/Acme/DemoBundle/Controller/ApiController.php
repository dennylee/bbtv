<?php

namespace Acme\DemoBundle\Controller;

use Doctrine\Common\Cache\FilesystemCache;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiController
{
    const PLAYERS_KEY = 'players';

    /**
     * Retrieves the list of players from cache.  If no players were found, it'll return
     * the default list of players.
     *
     * @return JsonResponse
     */
    public function getPlayersAction()
    {
        $list = $this->getCacheProvider()->fetch(self::PLAYERS_KEY);

        if (empty($list)) {
            $list = $this->getDefaultPlayers();
            $this->getCacheProvider()->save(self::PLAYERS_KEY, $list);
        }

        return new JsonResponse(['players' => $list]);
    }

    /**
     * Increment the score by 5 for a particular player.
     *
     * @param string $playerId Player ID to increment.
     * @return JsonResponse
     */
    public function incrementAction($playerId)
    {
        $list = $this->getCacheProvider()->fetch(self::PLAYERS_KEY);
        $list[$playerId]['score'] += 5;
        $this->getCacheProvider()->save(self::PLAYERS_KEY, $list);
        return new JsonResponse(['players' => $list]);
    }

    /**
     * There's a better way to do this but this is the quickest and simplest for FileCache.
     * There's a way to specify the cache provider through config.
     *
     * @return FilesystemCache
     */
    private function getCacheProvider()
    {
        return new FilesystemCache('./my_app_cache');
    }

    /**
     * Defines the default list of players.
     *
     * @return array
     */
    private function getDefaultPlayers()
    {
        return [
            'claude-shannon' => ['name' => 'Claude Shannon', 'score' => 11085],
            'carl-friedrich-gauss' => ['name' => 'Carl Friedrich Gauss', 'score' => 11070],
            'ada-lovelace' => ['name' => 'Ada Lovelace', 'score' => 11040],
            'marie-curie' => ['name' => 'Marie Curie', 'score' => 11040],
            'nikola-tesla' => ['name' => 'Nikola Tesla', 'score' => 11015],
            'grace-hopper' => ['name' => 'Grace Hopper', 'score' => 10760]
        ];
    }

} 