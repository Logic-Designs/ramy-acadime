<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeTrait extends Command
{
    protected $signature = 'make:trait {name}';
    protected $description = 'Create a new trait';

    public function handle()
    {
        $name = $this->argument('name');
        $traitName = $this->getTraitName($name);
        $traitPath = app_path("Traits/{$traitName}.php");

        // Check if the trait already exists
        if (File::exists($traitPath)) {
            $this->error("Trait {$traitName} already exists!");
            return;
        }

        // Create the Traits directory if it doesn't exist
        if (!File::exists(app_path('Traits'))) {
            File::makeDirectory(app_path('Traits'));
        }

        // Create the trait file
        $this->createTraitFile($traitPath, $traitName);

        $this->info("Trait {$traitName} created successfully.");
    }

    protected function getTraitName($name)
    {
        // Format the name to PascalCase (e.g., my_trait -> MyTrait)
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $name)));
    }

    protected function createTraitFile($path, $name)
    {
        $content = "<?php\n\nnamespace App\Traits;\n\ntrait {$name}\n{\n    // Your trait methods here\n}\n";

        File::put($path, $content);
    }
}
