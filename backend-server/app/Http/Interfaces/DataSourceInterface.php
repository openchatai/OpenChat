<?php

namespace App\Http\Interfaces;

interface DataSourceInterface
{
    public function getNormalizedText(): string;
}
