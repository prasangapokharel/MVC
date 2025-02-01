<?php

// Enable autoloading
require __DIR__ . '/vendor/autoload.php';

if ($argc < 3) {
    echo "Usage:\n";
    echo "  php cli.php make:controller ControllerName\n";
    echo "  php cli.php make:model ModelName\n";
    echo "  php cli.php make:view ViewName\n";
    exit(1);
}



$command = $argv[1];
$name = $argv[2];

// Define directory mappings
$map = [
    'make:controller' => 'app/Controllers',
    'make:model' => 'app/Models',
    'make:view' => 'app/Views',
];

if (!isset($map[$command])) {
    echo "Invalid command. Available commands:\n";
    echo " - make:controller\n";
    echo " - make:model\n";
    echo " - make:view\n";
    exit(1);
}

$directory = $map[$command];
$filePath = "{$directory}/{$name}.php";

// Check if file already exists
if (file_exists($filePath)) {
    echo "{$name}.php already exists in {$directory}\n";
    exit(1);
}

// Generate boilerplate content based on type
$viewName = str_replace('Controller', '', $name);

$template = match ($command) {
    'make:controller' => "<?php\n\nnamespace Godsu\\Mvc\\Controllers;\n\nclass {$name} {\n    public function index() {\n        echo 'Welcome to {$name} Controller';\n        require __DIR__ . '/../views/{$viewName}.php';\n    }\n}\n",
    'make:model' => "<?php\n\nnamespace Godsu\\Mvc\\Models;\n\nclass {$name} {\n    // Model logic here\n}\n",
    'make:view' => "<h1>Welcome to {$viewName} View!</h1>\n",
};

// Create the primary file (Controller, Model, or View)
if (!is_dir($directory)) {
    mkdir($directory, 0777, true);
}

file_put_contents($filePath, $template);
echo "{$name}.php created in {$directory}\n";

// Automatically create a view if a controller is created
if ($command === 'make:controller') {
    $viewPath = "app/Views/{$viewName}.php";
    file_put_contents($viewPath, "<h1>Welcome to {$viewName} View!</h1>\n");
    echo "{$viewName}.php created in app/Views\n";

    // Update web.php
    $webRoutesPath = 'app/routes/web.php';

    if (file_exists($webRoutesPath)) {
        $routeContent = file_get_contents($webRoutesPath);

        // Add `use` statement if not present
        $useStatement = "use Godsu\\Mvc\\Controllers\\{$name};";
        if (!str_contains($routeContent, $useStatement)) {
            $routeContent = preg_replace("/<\?php/", "<?php\n\n{$useStatement}", $routeContent, 1);
        }

        // Add the route mapping
        $routeKey = strtolower(str_replace('Controller', '', $name));
        $routeMapping = "    '{$routeKey}' => [{$name}::class, 'index'],\n";

        // Insert route mapping before the closing bracket
        $routeContent = preg_replace('/return \[\n/', "return [\n{$routeMapping}", $routeContent, 1);

        file_put_contents($webRoutesPath, $routeContent);
        echo "Route for {$name} added to web.php\n";
    }
}
