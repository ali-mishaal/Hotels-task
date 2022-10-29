<?php

namespace core;

interface ArrayOperation
{
    public function FetchAndMergeData(): ArrayOperation;

    public function ExtractUniqueData(): ArrayOperation;

    public function SortData(): ArrayOperation;
}