<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('setup')->group(function () {
    Route::get('clear-all', function () {
        try {
            // Capture the output of the Artisan command
            $output = Artisan::output();

            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            Artisan::call('route:clear');

            // Log the output for debugging
            Log::info('Migrate output: '.$output);

            return 'App Cleared successfully';
        } catch (\Exception $e) {

            Log::error('Operation failed: '.$e->getMessage());

            return 'Operation Failed: '.$e->getMessage();
        }
    });

    Route::get('migrate', function () {
        try {
            // Capture the output of the Artisan command
            $output = Artisan::output();

            // Run the migration
            Artisan::call('migrate');

            // Log the output for debugging
            Log::info('Migrate output: '.$output);

            return 'Migrations completed successfully';
        } catch (\Exception $e) {
            // Log the error
            Log::error('Migrate failed: '.$e->getMessage());

            return 'Migrations Failed: '.$e->getMessage();
        }
    });

    Route::get('migrate-fresh', function () {
        try {
            // Capture the output of the Artisan command
            $output = Artisan::output();

            // Run the migration
            Artisan::call('migrate:fresh');

            // Log the output for debugging
            Log::info('Migrate fresh output: '.$output);

            return 'Migrations completed successfully';
        } catch (\Exception $e) {
            // Log the error
            Log::error('Migrate fresh failed: '.$e->getMessage());

            return 'Migrations Failed: '.$e->getMessage();
        }
    });

    Route::get('commands', function () {
        return Artisan::all(); // This will show all registered Artisan commands
    });

    Route::get('seed/{seeder}', function ($seeder) {
        try {
            // Construct the full class name including the namespace
            $seederClass = 'Database\\Seeders\\'.$seeder.'Seeder';

            // Check if the seeder class exists before running it
            if (class_exists($seederClass)) {
                // Run the seeder dynamically using the class name
                Artisan::call('db:seed', ['--class' => $seederClass]);

                // Capture and log the output of the Artisan command
                $output = Artisan::output();
                Log::info($seederClass.' output: '.$output);

                return $seederClass.' seeding completed successfully!';
            } else {
                return response()->json(['message' => 'Seeder class not found.'], 404);
            }
        } catch (\Exception $e) {
            // Log any error that occurs
            Log::error('Seeder failed: '.$e->getMessage());

            return response()->json(['message' => 'Seeder Failed: '.$e->getMessage()], 500);
        }
    });

    Route::get('seed', function () {
        try {
            Artisan::call('db:seed');

            // Capture and log the output of the Artisan command
            $output = Artisan::output();

            return ' seeding completed successfully!';
        } catch (\Exception $e) {
            // Log any error that occurs
            Log::error('Seeder failed: '.$e->getMessage());

            return response()->json(['message' => 'Seeder Failed: '.$e->getMessage()], 500);
        }
    });

    Route::get('schedule-run', function () {
        try {
            // Capture the output of the Artisan command
            $output = Artisan::output();

            Artisan::call('schedule:run');

            // Log the output for debugging
            Log::info('Schedule output: '.$output);

            return 'Schedule ran successfully';
        } catch (\Exception $e) {

            Log::error('Operation failed: '.$e->getMessage());

            return 'Operation Failed: '.$e->getMessage();
        }
    });

    Route::get('artisan/{runner}/{command}', function ($runner, $command) {
        try {
            Artisan::call("$runner:$command");

            // Capture and log the output of the Artisan command
            $output = Artisan::output();

            return ' command completed successfully!';
        } catch (\Exception $e) {
            // Log any error that occurs
            Log::error('Artisan command failed: '.$e->getMessage());

            return response()->json(['message' => 'Artisan command Failed: '.$e->getMessage()], 500);
        }
    });
});
