<?php

namespace tecdynamics\installer\Controllers;

use Illuminate\Routing\Controller;
use tecdynamics\installer\Helpers\EnvironmentManager;
use tecdynamics\installer\Helpers\FinalInstallManager;
use tecdynamics\installer\Helpers\InstalledFileManager;
use tecdynamics\installer\Events\LaravelInstallerFinished;
use tecdynamics\installer\Helpers\DatabaseManager;
use DB;

class FinalController extends Controller
{
    /**
     * Update installed file and display finished view.
     *
     * @param InstalledFileManager $fileManager
     * @return \Illuminate\View\View
     */
    public function finish(DatabaseManager $databaseManager,InstalledFileManager $fileManager, FinalInstallManager $finalInstall, EnvironmentManager $environment)
    {

        $response = $databaseManager->migrateAndSeed();
        $finalMessages = $finalInstall->runFinal();

        if($finalMessages != ''){

          if(config('app.url') == 'production'){
            $environments = 'Live';
          }else{
            $environments = 'Maintenance';
          }

          DB::table('settings')->where('key', 'external_website_link')->update([	 'value' => config('app.url')]);
          DB::table('settings')->where('key', 'app_name')->update(['value' => config('app.name')]);
          DB::table('settings')->where('key', 'environment')->update(['value' => $environments]);

          $finalStatusMessage = $fileManager->update();
        }else{
          $finalStatusMessage = 'Error Check Your Database Credentials. You Might Be something Missing!';
        }
        $finalEnvFile = $environment->getEnvContent();

        event(new LaravelInstallerFinished);
        return view('vendor.installer.finished', compact('finalMessages', 'finalStatusMessage', 'finalEnvFile'));
    }
}
