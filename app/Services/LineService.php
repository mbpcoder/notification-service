<?php

namespace App\Services;

use App\Data\Entities\Line;
use App\Data\Repositories\Line\LineRepository;
use App\Exceptions\EntityNotFoundException;

class LineService
{
    public function __construct(
        private LineRepository $lineRepository,
    )
    {

    }

    public function getLine(int $providerId, string|null $lineNumber): Line
    {
        if ($lineNumber !== null) {
            $line = $this->lineRepository->getLineByProviderIdAndLineNumber($providerId, $lineNumber);

            if ($line === null) {
                throw new EntityNotFoundException(__('Line Not Found'));
            }
        } else {
            // Set Default Line
            $line = $this->lineRepository->getDefaultLineByProviderId($providerId);

            if ($line === null) {
                throw new EntityNotFoundException(__('Default line Not Found'));
            }
        }

        return $line;
    }
}
