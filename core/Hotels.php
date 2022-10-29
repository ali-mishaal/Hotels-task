<?php

namespace core;
require_once './core/ArrayOperation.php';

class Hotels implements ArrayOperation
{
    private array $hotels = [];
    private array $uniqueData = [];

    public function __construct(private array $data)
    {
    }

    public function FetchAndMergeData(): ArrayOperation
    {
        foreach ($this->data as $item) {
            $hotel = json_decode(file_get_contents($item), true)['hotels'];
            $this->hotels = array_merge($this->hotels, $hotel);
        }

        return $this;
    }

    public function ExtractUniqueData(): ArrayOperation
    {
        foreach ($this->hotels as $hotel) {
            $this->RoomsLoop($hotel);
        }

        $this->uniqueData = array_values($this->uniqueData);

        return $this;
    }

    private function RoomsLoop(array $hotel): void
    {
        if (!isset($this->uniqueData [$hotel['name']]))
            $this->uniqueData [$hotel['name']] = $hotel;
        foreach ($hotel['rooms'] as $room) {
            $this->CheckForRoom($hotel, $room);
        }

    }

    private function CheckForRoom(array $hotel, array $room): void
    {
        $totalOfRoom = $room['total'] ?? $room['totalPrice'];
        foreach ($this->uniqueData[$hotel['name']]['rooms'] as $key => $unique) {
            $totalOfRoomResult = $unique['total'] ?? $unique['totalPrice'];
            $this->uniqueData[$hotel['name']]['rooms'][$key]['total'] = $totalOfRoomResult;
            if ($unique['code'] == $room['code'] && $totalOfRoom < $totalOfRoomResult) {
                $this->uniqueData[$hotel['name']]['rooms'][$key] = $room;
                $this->uniqueData[$hotel['name']]['rooms'][$key]['total'] = $totalOfRoom;

            }
        }

    }

    public function SortData(): ArrayOperation
    {
        $hotels = $this->uniqueData;
        $sortData = [];
        foreach ($hotels as $item) {
            usort($item['rooms'], fn($a, $b) => $a['total'] <=> $b['total']);
            $sortData[] = $item;
        }
        $this->uniqueData = $sortData;

        return $this;
    }

    public function GetData(): array
    {
        $this->FetchAndMergeData()
            ->ExtractUniqueData()
            ->SortData();

        return $this->uniqueData;
    }
}