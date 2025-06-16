<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NoteType;
use App\Enums\StatusEnum;

class NoteTypeSeeder extends Seeder
{
    /**
     * Predefined note types with their statuses.
     *
     * @var array
     */
    protected array $noteTypes = [
        ['name' => 'General', 'status' => StatusEnum::ACTIVE],
        ['name' => 'Meeting', 'status' => StatusEnum::ACTIVE],
        ['name' => 'Personal', 'status' => StatusEnum::ACTIVE],
        ['name' => 'Work', 'status' => StatusEnum::ACTIVE],
        ['name' => 'Study', 'status' => StatusEnum::ACTIVE],
        ['name' => 'Ideas', 'status' => StatusEnum::DEACTIVE],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->noteTypes as $noteType) {
            NoteType::create([
                'name' => $noteType['name'],
                'status' => $noteType['status']->value,
            ]);
        }
    }
}
