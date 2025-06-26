<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class CheckForUpdatesJob implements ShouldBeEncrypted, ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        try {
            if (isDev() || isCloud()) {
                return;
            }
            $settings = instanceSettings();
            
            // Check if using custom registry (e.g., ghcr.io/kivilaid/coolify)
            if (isUsingCustomRegistry()) {
                $this->checkCustomRegistry($settings);
            } else {
                // Original logic for official registry
                $this->checkOfficialRegistry($settings);
            }
        } catch (\Throwable $e) {
            // Consider implementing a notification to administrators
            ray($e->getMessage());
        }
    }
    
    private function checkCustomRegistry($settings): void
    {
        $latest_version = getLatestVersionFromGitHub();
        $current_version = config('constants.coolify.version');
        
        if ($latest_version && version_compare($latest_version, $current_version, '>')) {
            // New version available
            $settings->update(['new_version_available' => true]);
            
            // Create a versions.json file in the expected format
            $versions = [
                'coolify' => [
                    'v4' => [
                        'version' => $latest_version
                    ]
                ]
            ];
            File::put(base_path('versions.json'), json_encode($versions, JSON_PRETTY_PRINT));
        } else {
            $settings->update(['new_version_available' => false]);
        }
    }
    
    private function checkOfficialRegistry($settings): void
    {
        $response = Http::retry(3, 1000)->get('https://cdn.coollabs.io/coolify/versions.json');
        if ($response->successful()) {
            $versions = $response->json();

            $latest_version = data_get($versions, 'coolify.v4.version');
            $current_version = config('constants.coolify.version');

            if (version_compare($latest_version, $current_version, '>')) {
                // New version available
                $settings->update(['new_version_available' => true]);
                File::put(base_path('versions.json'), json_encode($versions, JSON_PRETTY_PRINT));
            } else {
                $settings->update(['new_version_available' => false]);
            }
        }
    }
}
