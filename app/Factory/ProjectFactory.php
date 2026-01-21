<?php

namespace App\Factory;

use App\DTO\ProjectDTO;
use App\Models\Project;
use App\Models\Type;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ProjectFactory
{
    public function create(ProjectDTO $dto): Project
    {
        return Project::updateOrCreate(
            $dto->unique(),
            $dto->toArray(),
        );
    }
}
