<?php

namespace App\Services\Element;

class AStarPathfindingService
{
    /**
     * Find the shortest path using the A* algorithm.
     *
     * @param array $grid 2D array representing the map (0 = walkable, 1 = obstacle)
     * @param array $start [x, y] start position
     * @param array $end [x, y] end position
     * @return array|null List of [x, y] positions representing the path, or null if no path found
     */
    public function findPath(array $grid, array $start, array $end): ?array
    {
        $openSet = [];
        $closedSet = [];
        $cameFrom = [];
        $gScore = [];
        $fScore = [];
        $rows = count($grid);
        $cols = count($grid[0]);

        $startKey = $this->posKey($start);
        $endKey = $this->posKey($end);

        $openSet[$startKey] = $start;
        $gScore[$startKey] = 0;
        $fScore[$startKey] = $this->heuristic($start, $end);

        while (!empty($openSet)) {
            // Get node in openSet with lowest fScore
            $currentKey = array_reduce(array_keys($openSet), function ($carry, $key) use ($fScore) {
                if ($carry === null || ($fScore[$key] ?? INF) < ($fScore[$carry] ?? INF)) {
                    return $key;
                }
                return $carry;
            });
            $current = $openSet[$currentKey];

            if ($currentKey === $endKey) {
                return $this->reconstructPath($cameFrom, $currentKey);
            }

            unset($openSet[$currentKey]);
            $closedSet[$currentKey] = true;

            foreach ($this->getNeighbors($current, $rows, $cols) as $neighbor) {
                $neighborKey = $this->posKey($neighbor);
                if ($grid[$neighbor[1]][$neighbor[0]] === 1 || isset($closedSet[$neighborKey])) {
                    continue;
                }
                $tentativeG = ($gScore[$currentKey] ?? INF) + 1;
                if (!isset($openSet[$neighborKey]) || $tentativeG < ($gScore[$neighborKey] ?? INF)) {
                    $cameFrom[$neighborKey] = $currentKey;
                    $gScore[$neighborKey] = $tentativeG;
                    $fScore[$neighborKey] = $tentativeG + $this->heuristic($neighbor, $end);
                    $openSet[$neighborKey] = $neighbor;
                }
            }
        }
        return null;
    }

    private function heuristic(array $a, array $b): int
    {
        // Manhattan distance
        return abs($a[0] - $b[0]) + abs($a[1] - $b[1]);
    }

    private function getNeighbors(array $pos, int $rows, int $cols): array
    {
        $neighbors = [];
        $dirs = [[0,1],[1,0],[0,-1],[-1,0]];
        foreach ($dirs as $dir) {
            $nx = $pos[0] + $dir[0];
            $ny = $pos[1] + $dir[1];
            if ($nx >= 0 && $nx < $cols && $ny >= 0 && $ny < $rows) {
                $neighbors[] = [$nx, $ny];
            }
        }
        return $neighbors;
    }

    private function posKey(array $pos): string
    {
        return $pos[0] . ',' . $pos[1];
    }

    private function reconstructPath(array $cameFrom, string $currentKey): array
    {
        $totalPath = [$this->parseKey($currentKey)];
        while (isset($cameFrom[$currentKey])) {
            $currentKey = $cameFrom[$currentKey];
            array_unshift($totalPath, $this->parseKey($currentKey));
        }
        return $totalPath;
    }

    private function parseKey(string $key): array
    {
        return array_map('intval', explode(',', $key));
    }
}