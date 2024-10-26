<?php

namespace App\Http\Controllers\WP;

use App\Http\Controllers\Controller;
use App\Models\ManageSite;
use App\Models\PluginCategoriesModel;

use App\Models\WpMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CreateWordpressController extends Controller
{
    public function wordpress_version()
    {
        // Retrieve all records of type 'wp-version'
        $wordpress_version = WpMaterial::where('type', 'wp-version')->get();

        // Return the results as a JSON response
        return response()->json($wordpress_version);
    }

    public function showPlugins()
    {
        $pluginCategories = PluginCategoriesModel::all();
        return response()->json(['pluginCategories' => $pluginCategories]);
    }

    public function getPlugins()
    {
        // Fetch plugins from the database
        $plugins = WpMaterial::where('type', 'plugin')->get(['id', 'name', 'file_path']); // Fetch necessary fields

        // Prepare plugins with their paths
        $plugins = $plugins->map(function ($plugin) {
            return [
                'id' => $plugin->id,
                'name' => $plugin->name,
                'path' => asset('wp-plugins/' . basename($plugin->file_path)), // Assuming the file_path includes the full name
            ];
        });

        return response()->json([
            'plugins' => $plugins
        ]);
    }

    public function getByCategory($id)
    {
        // Fetch plugins where category_id matches the provided ID
        $plugins = WpMaterial::where('category_id', $id)
            ->where('type', 'plugin') // Assuming you want only plugins
            ->get();

        // Return the plugins as a JSON response
        return response()->json(['plugins' => $plugins]);
    }

    public function extractplugin(Request $request)
    {
        // Retrieve the unique folder name from the session
        $uniqueFolderName = session('unique_folder_name');

        // Check if the folder name exists
        if (!$uniqueFolderName) {
            return response()->json(['success' => false, 'message' => 'No folder name found.']);
        }

        // Construct the target directory for plugins
        $targetDirectory = base_path("WPALL-Sites/{$uniqueFolderName}/wp-content/plugins");

        // Create the plugins directory if it doesn't exist
        if (!file_exists($targetDirectory)) {
            mkdir($targetDirectory, 0755, true);
        }

        // Retrieve the selected plugins from the request
        $plugins = $request->input('plugins');

        $pluginNames = []; // Array to hold the new plugin names

        foreach ($plugins as $plugin) {
            $filePath = public_path("wp-plugins/" . basename($plugin['filePath'])); // Get the full path to the zip file

            // Check if the file exists before attempting to extract
            if (file_exists($filePath)) {
                $zip = new ZipArchive;

                if ($zip->open($filePath) === TRUE) {
                    // Extract the zip file to the target directory
                    $zip->extractTo($targetDirectory);
                    $zip->close();

                    // Clean up the plugin name
                    $cleanedName = str_replace([' ', '×'], '', $plugin['name']); // Remove spaces and '×'
                    $pluginNames[] = $cleanedName; // Add cleaned plugin name to the array


                } else {
                    return response()->json(['success' => false, 'message' => "Failed to extract {$plugin['filePath']}"]);
                }
            } else {
                return response()->json(['success' => false, 'message' => "File does not exist: {$filePath}"]);
            }
        }

        // Convert the array to a comma-separated string
        $newPluginNamesString = implode(',', $pluginNames);



        // Fetch the existing plugin names from the database
        $site = ManageSite::where('folder_name', $uniqueFolderName)->first();
        $existingPlugins = $site->plugin ? explode(',', $site->plugin) : [];

        // Merge existing plugins with the new plugins (keeping unique values only)
        $allPlugins = array_unique(array_merge($existingPlugins, $pluginNames));

        // Convert the array back to a comma-separated string
        $allPluginNamesString = implode(',', $allPlugins);

        // Update the database with the combined plugin names
        $site->update([
            'plugin' => $allPluginNamesString,
        ]);

        session(['plugin' => $allPluginNamesString]);

        return response()->json(['success' => true, 'message' => 'Plugins extracted and saved successfully!']);
    }




    public function themesforextract()
    {


        $themes = WpMaterial::where('type', 'wp-themes')
            ->select('name', 'file_path', 'id')
            ->get();


        return response()->json(['themes' => $themes]);
    }


    // public function extractthemes(Request $request)
    // {
    //     // Retrieve the unique folder name from the session
    //     $uniqueFolderName = session('unique_folder_name');

    //     // Check if the folder name exists
    //     if (!$uniqueFolderName) {
    //         return response()->json(['success' => false, 'message' => 'No folder name found.']);
    //     }

    //     // Construct the target directory for themes
    //     $targetDirectory = base_path("WPALL-Sites/{$uniqueFolderName}/wp-content/themes");

    //     // Create the themes directory if it doesn't exist
    //     if (!file_exists($targetDirectory)) {
    //         mkdir($targetDirectory, 0755, true);
    //     }

    //     // Retrieve the selected themes from the request
    //     $themes = $request->input('themes');

    //     $themeNames = []; // Array to hold the new theme names

    //     foreach ($themes as $theme) {
    //         $filePath = public_path("wp-themes/" . basename($theme['filePath'])); // Get the full path to the zip file

    //         // Check if the file exists before attempting to extract
    //         if (file_exists($filePath)) {
    //             $zip = new ZipArchive;

    //             if ($zip->open($filePath) === TRUE) {
    //                 // Extract the zip file to the target directory
    //                 $zip->extractTo($targetDirectory);
    //                 $zip->close();

    //                 // Clean up the theme name
    //                 $cleanedName = $theme['name']; 
    //                 $themeNames[] = $cleanedName; // Add cleaned theme name to the array

    //             } else {
    //                 return response()->json(['success' => false, 'message' => "Failed to extract {$theme['filePath']}"]);
    //             }
    //         } else {
    //             return response()->json(['success' => false, 'message' => "File does not exist: {$filePath}"]);
    //         }
    //     }

    //     // Convert the array to a comma-separated string
    //     $newThemeNamesString = implode(',', $themeNames);

    //     // Fetch the existing theme names from the database
    //     $site = ManageSite::where('folder_name', $uniqueFolderName)->first();
    //     $existingThemes = $site->themes ? explode(',', $site->themes) : [];

    //     // Merge existing themes with the new themes (keeping unique values only)
    //     $allThemes = array_unique(array_merge($existingThemes, $themeNames));

    //     // Convert the array back to a comma-separated string
    //     $allThemeNamesString = implode(',', $allThemes);


    //     session(['ThemeNames' => $allThemeNamesString]);
    //     // Update the database with the combined theme names
    //     $site->update([
    //         'themes' => $allThemeNamesString,
    //     ]);

    //     return response()->json(['success' => true, 'message' => 'Themes extracted and saved successfully!']);
    // }


    public function extractthemes(Request $request)
    {
        // Retrieve the unique folder name from the session
        $uniqueFolderName = session('unique_folder_name');

        // Check if the folder name exists
        if (!$uniqueFolderName) {
            return response()->json(['success' => false, 'message' => 'No folder name found.']);
        }

        // Construct the target directory for themes
        $targetDirectory = base_path("WPALL-Sites/{$uniqueFolderName}/wp-content/themes");

        // Create the themes directory if it doesn't exist
        if (!file_exists($targetDirectory)) {
            mkdir($targetDirectory, 0755, true);
        }

        // Retrieve the selected themes from the request
        $themes = $request->input('themes');

        // Assume we are extracting only one theme
        $theme = $themes[0]; // Get the first (and only) theme

        $filePath = public_path("wp-themes/" . basename($theme['filePath'])); // Get the full path to the zip file

        // Check if the file exists before attempting to extract
        if (file_exists($filePath)) {
            $zip = new ZipArchive;

            if ($zip->open($filePath) === TRUE) {
                // Extract the zip file to the target directory
                $zip->extractTo($targetDirectory);
                $zip->close();

                // Clean the theme name by extracting it from the file name
                // Remove the .zip extension
                $cleanedName = pathinfo($theme['filePath'], PATHINFO_FILENAME); // Get the file name without the extension

                // Fetch the existing theme names from the database
                $site = ManageSite::where('folder_name', $uniqueFolderName)->first();
                $existingThemes = $site->themes ? $site->themes : ''; // Retrieve existing themes

                // If existing themes are not empty, append the new theme name
                $allThemeNamesString = $existingThemes ? $existingThemes . ',' . $cleanedName : $cleanedName;

                // Save the theme names in the session
                session(['ThemeNames' => $allThemeNamesString]);

                // Update the database with the combined theme names
                $site->update([
                    'themes' => $allThemeNamesString,
                ]);

                return response()->json(['success' => true, 'message' => 'Theme extracted and saved successfully!']);
            } else {
                return response()->json(['success' => false, 'message' => "Failed to extract {$theme['filePath']}"]);
            }
        } else {
            return response()->json(['success' => false, 'message' => "File does not exist: {$filePath}"]);
        }
    }

    //EXTRAXT WITHOUT WORDPREDD own logic

    public function downloadWordPress(Request $request)
    {
        // Validate inputs
        $request->validate([
            'siteName' => 'required|string|max:255',
            'user_name' => 'required|string|max:255',
            'password' => 'required|string|min:6',
        ]);

        $userId = Auth::id();
        $email = Auth::user()->email;
        $hashedPassword = $request->input('password');

        // Set time limit for script execution
        set_time_limit(180);

        // Generate a unique name for the extraction folder
        $uniqueString = substr(str_shuffle(str_repeat('abcdefghijklmnopqrstuvwxyz', 5)), 0, 5);
        $uniqueFolderName = $userId . "_" . $uniqueString;

        try {
            // Define paths for the zip file and the base directory for extracted sites
            $zipPath = public_path('wp-versions/wordpress-6.6.2.zip'); // Adjust this path as needed
            $wpSitesPath = base_path('WPALL-Sites');

            // Create the base directory if it doesn't exist
            if (!file_exists($wpSitesPath)) {
                mkdir($wpSitesPath, 0755, true);
            }

            // Create the extraction path
            $extractPath = $wpSitesPath . "/{$uniqueFolderName}";
            if (!file_exists($extractPath)) {
                mkdir($extractPath, 0755, true);
            }

            // Extract the zip file
            if ($this->extractZipFile($zipPath, $extractPath)) {
                // Save the site information to the database
                try {
                    $site = ManageSite::create([
                        'site_name' => $request->input('siteName'),
                        'folder_name' => $uniqueFolderName,
                        'user_id' => $userId,
                        'version' => '6.2.2',
                        'site_type' => 'single',
                        'user_name' => $request->input('user_name'),
                        'email' => $email,
                        'password' => $hashedPassword,
                        'login_url' => "http://localhost/wp-sites/WPALL-Sites/" . $uniqueFolderName,
                        'domain_name' => "http://localhost/wp-sites/WPALL-Sites/" . $uniqueFolderName,
                        'db_name' => $uniqueFolderName,
                        'db_user_name' => 'root',
                        'status' => 'RUNNING'
                    ]);

                    if ($site) {
                        $siteId = $site->id;
                        session([
                            'unique_folder_name' => $site->folder_name,
                            'user_id' => $userId,
                            'site_name' => $site->site_name,
                            'user_name' => $site->user_name,
                            'email' => $email,
                            'password' => $hashedPassword,
                            'site_id' => $siteId
                        ]);
                    } else {
                        return response()->json(['success' => false, 'message' => 'Failed to save site to the database.']);
                    }
                } catch (\Exception $e) {
                    return response()->json(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
                }

                // Define paths for wp-config
                $configSamplePath = $extractPath . '/wp-config-sample.php';
                $configPath = $extractPath . '/wp-config.php';

                if (file_exists($configSamplePath)) {
                    // Create wp-config.php and copy content from wp-config-sample.php
                    $wpConfigContent = file_get_contents($configSamplePath);

                    // Modify the wp-config.php content
                    $wpConfigContent = str_replace(
                        ['database_name_here', 'username_here', 'password_here'],
                        [$uniqueFolderName, 'root', ''],
                        $wpConfigContent
                    );

                    // Write the modified content to wp-config.php
                    file_put_contents($configPath, $wpConfigContent);

                    return response()->json(['success' => true, 'message' => 'WordPress extracted successfully!', 'path' => $extractPath]);
                } else {
                    return response()->json(['success' => false, 'message' => 'wp-config-sample.php not found.']);
                }
            } else {
                return response()->json(['success' => false, 'message' => 'Failed to extract WordPress.']);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        } finally {
            // Optional: Clean up temporary files or perform any necessary final actions
        }
    }



    protected function extractZipFile($zipPath, $extractPath)
    {
        $zip = new ZipArchive;
        if ($zip->open($zipPath) === TRUE) {
            $zip->extractTo($extractPath);
            $zip->close();
            return true;
        }
        return false;
    }


    public function createDatabase()
    {
        // Retrieve the unique folder name from the session
        $uniqueFolderName = session('unique_folder_name');

        // Construct the database name
        $databaseName = $uniqueFolderName;

        // Check if session variable exists
        if (!$uniqueFolderName) {
            return response()->json(['error' => 'Session expired or unique folder name missing.'], 400);
        }

        try {
            // Create the database using raw SQL
            DB::statement("CREATE DATABASE IF NOT EXISTS `$databaseName`");

            // Path to the SQL file
            $sqlFilePath = public_path('Import_mysql/wordpress_laravel.sql');

            // Check if the SQL file exists
            if (!file_exists($sqlFilePath)) {
                return response()->json(['error' => 'SQL file not found.'], 404);
            }

            // Read the SQL file
            $sql = file_get_contents($sqlFilePath);

            // Import the SQL into the newly created database
            $this->importSqlToDatabase($databaseName, $sql);

            session()->forget([
                'unique_folder_name',
                'site_name',
                'user_name',
                'password',
                'email',
                'ThemeNames',
            ]);


            // Return a success response without 'database' and 'admin_details'
            return response()->json(['success' => 'Database created and imported successfully!']);
        } catch (\Exception $e) {
            Log::error('Database creation or import failed: ' . $e->getMessage() . ' in file ' . $e->getFile() . ' at line ' . $e->getLine());
            return response()->json(['error' => 'Database creation or import failed: ' . $e->getMessage()], 500);
        }
    }



    // protected function importSqlToDatabase($databaseName, $sql)
    // {

    //     $uniqueFolderName = session('unique_folder_name');
    //     // Create a new connection to the newly created database
    //     $connection = DB::connection('mysql');
    //     $adminDetails = [];

    //     try {
    //         // Switch to the new database without wrapping in a transaction
    //         $connection->statement("USE `$databaseName`");

    //         // Split the SQL into individual statements
    //         $queries = $this->splitSqlStatements($sql);

    //         foreach ($queries as $query) {
    //             $trimmedQuery = trim($query);
    //             if (!empty($trimmedQuery)) {
    //                 // Check if the table exists before creating it
    //                 if (preg_match('/CREATE TABLE `(.*?)`/', $trimmedQuery, $matches)) {
    //                     $tableName = $matches[1];

    //                     // Query information schema to check if the table exists
    //                     $exists = $connection->select(
    //                         "SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?",
    //                         [$databaseName, $tableName]
    //                     );

    //                     // Skip the query if the table exists
    //                     if (!empty($exists)) {
    //                         continue;
    //                     }
    //                 }

    //                 // Log the query being executed for debugging
    //                 Log::info('Executing query: ' . $trimmedQuery);

    //                 // Execute the query without wrapping in a transaction
    //                 $connection->statement($trimmedQuery);
    //             }
    //         }

    //         // Update WordPress database settings (example with admin details)
    //         $siteTitle = session('site_name');
    //         $siteUrl = "http://localhost/wp-sites/WPALL-Sites/" . session('unique_folder_name');
    //         $adminUsername = session('user_name');
    //         $adminPassword = session('password');
    //         $adminEmail = session('email');
    //         $themesName = session('ThemeNames');


    //         // Update the database with MD5 hashed password
    //         $connection->statement("UPDATE wp_options SET option_value=? WHERE option_name='siteurl' OR option_name='home'", [$siteUrl]);
    //         $connection->statement("UPDATE wp_options SET option_value=? WHERE option_name='blogname'", [$siteTitle]);
    //         $connection->statement("UPDATE wp_users SET user_login=?, user_pass=MD5(?), user_email=?, user_nicename=?, user_url=? WHERE ID=1", [
    //             $adminUsername,
    //             $adminPassword,
    //             $adminEmail,
    //             $adminUsername,
    //             $siteUrl,
    //         ]);



    //         // Handle theme updates if provided
    //         if ($themesName) {
    //             $templateExists = $connection->select("SELECT COUNT(*) as count FROM wp_options WHERE option_name='template'")[0]->count;
    //             $stylesheetExists = $connection->select("SELECT COUNT(*) as count FROM wp_options WHERE option_name='stylesheet'")[0]->count;

    //             if ($templateExists) {
    //                 $connection->statement("UPDATE wp_options SET option_value=? WHERE option_name='template'", [$themesName]);
    //             } else {
    //                 $connection->statement("INSERT INTO wp_options (option_name, option_value, autoload) VALUES (?, ?, 'yes')", ['template', $themesName]);
    //             }

    //             if ($stylesheetExists) {
    //                 $connection->statement("UPDATE wp_options SET option_value=? WHERE option_name='stylesheet'", [$themesName]);
    //             } else {
    //                 $connection->statement("INSERT INTO wp_options (option_name, option_value, autoload) VALUES (?, ?, 'yes')", ['stylesheet', $themesName]);
    //             }
    //         }
    //     } catch (\Exception $e) {
    //         Log::error('Database import failed: ' . $e->getMessage());
    //         throw $e; // Re-throw exception to be caught in the main method
    //     }

    //     return $adminDetails; // Return the admin details
    // }

    protected function splitSqlStatements($sql)
    {
        // Use a regex-based approach to avoid splitting on semicolons within strings or comments
        $queries = preg_split('/;[\r\n]+/', $sql);

        return array_filter($queries, function ($query) {
            return !empty(trim($query));
        });
    }

    protected function importSqlToDatabase($databaseName, $sql)
    {
        $uniqueFolderName = session('unique_folder_name');
        $connection = DB::connection('mysql');
        $adminDetails = [];

        try {
            $connection->statement("USE `$databaseName`");

            // Split and execute SQL statements
            $queries = $this->splitSqlStatements($sql);
            foreach ($queries as $query) {
                $trimmedQuery = trim($query);
                if (!empty($trimmedQuery)) {
                    if (preg_match('/CREATE TABLE `(.*?)`/', $trimmedQuery, $matches)) {
                        $tableName = $matches[1];
                        $exists = $connection->select(
                            "SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?",
                            [$databaseName, $tableName]
                        );
                        if (!empty($exists)) continue;
                    }
                    Log::info('Executing query: ' . $trimmedQuery);
                    $connection->statement($trimmedQuery);
                }
            }

            // Update WordPress settings and user details
            $siteTitle = session('site_name');
            $siteUrl = "http://localhost/wp-sites/WPALL-Sites/" . session('unique_folder_name');
            $adminUsername = session('user_name');
            $adminPassword = session('password');
            $adminEmail = session('email');
            $themesName = session('ThemeNames');

            $connection->statement("UPDATE wp_options SET option_value=? WHERE option_name='siteurl' OR option_name='home'", [$siteUrl]);
            $connection->statement("UPDATE wp_options SET option_value=? WHERE option_name='blogname'", [$siteTitle]);
            $connection->statement("UPDATE wp_users SET user_login=?, user_pass=MD5(?), user_email=?, user_nicename=?, user_url=? WHERE ID=1", [
                $adminUsername,
                $adminPassword,
                $adminEmail,
                $adminUsername,
                $siteUrl,
            ]);

            // Theme handling
            if ($themesName) {
                $templateExists = $connection->select("SELECT COUNT(*) as count FROM wp_options WHERE option_name='template'")[0]->count;
                $stylesheetExists = $connection->select("SELECT COUNT(*) as count FROM wp_options WHERE option_name='stylesheet'")[0]->count;

                if ($templateExists) {
                    $connection->statement("UPDATE wp_options SET option_value=? WHERE option_name='template'", [$themesName]);
                } else {
                    $connection->statement("INSERT INTO wp_options (option_name, option_value, autoload) VALUES (?, ?, 'yes')", ['template', $themesName]);
                }

                if ($stylesheetExists) {
                    $connection->statement("UPDATE wp_options SET option_value=? WHERE option_name='stylesheet'", [$themesName]);
                } else {
                    $connection->statement("INSERT INTO wp_options (option_name, option_value, autoload) VALUES (?, ?, 'yes')", ['stylesheet', $themesName]);
                }
            }

            // Plugin handling
            $plugin = session('plugin');
            $pluginArray = explode("\n,", $plugin);
            $pluginFiles = [];

            // Build the array of plugins with sequential keys starting from 0
            foreach ($pluginArray as $pluginItem) {
                $cleanPlugin = ltrim(trim($pluginItem), ',');
                if (!empty($cleanPlugin)) {
                    $pluginName = explode('/', $cleanPlugin)[0];
                    $pluginFile = $pluginName . DIRECTORY_SEPARATOR . $pluginName . ".php";
                    $pluginFiles[] = $pluginFile;
                }
            }

            // Ensure the array keys are sequential integers
            $pluginFiles = array_values($pluginFiles);
            $activePluginsSerialized = str_replace('\\', '/', serialize($pluginFiles));

            // Check if the `active_plugins` option exists and update/insert it
            $activePluginsExists = $connection->select("SELECT COUNT(*) as count FROM wp_options WHERE option_name = 'active_plugins'")[0]->count;

            if ($activePluginsExists) {
                $connection->statement("UPDATE wp_options SET option_value=? WHERE option_name='active_plugins'", [$activePluginsSerialized]);
            } else {
                $connection->statement("INSERT INTO wp_options (option_name, option_value, autoload) VALUES ('active_plugins', ?, 'yes')", [$activePluginsSerialized]);
            }
        } catch (\Exception $e) {
            Log::error('Database import failed: ' . $e->getMessage());
            throw $e;
        }

        return $adminDetails;
    }


    // public function getAdminDetails()
    // {
    //     $siteId = session('site_id');
    //     $siteInfo = ManageSite::find($siteId);
    //     $uniqueFolderName = $siteInfo->folder_name;
    //     $plugin = session('plugin');

    //     $pluginArray = explode("\n", $plugin);
    //     $pluginNames = [];
    //     $pluginFiles = [];

    //     foreach ($pluginArray as $pluginItem) {
    //         $cleanPlugin = ltrim(trim($pluginItem), ',');
    //         if (!empty($cleanPlugin)) {
    //             $pluginName = explode('/', $cleanPlugin)[0];
    //             $pluginNames[] = $pluginName;

    //             // Get only the required part of the plugin file path
    //             $pluginFile = $pluginName . DIRECTORY_SEPARATOR . $pluginName . ".php";
    //             $pluginFiles[] = $pluginFile;
    //         }
    //     }

    //     $pluginpath = serialize($pluginFiles);

    //     $authUser = auth()->user();

    //     if ($authUser->role_id == 1) {
    //         $info = ManageSite::all();
    //     } else {
    //         $info = ManageSite::where('user_id', $authUser->id)->get();
    //     }

    //     $runningCount = $info->where('status', 'RUNNING')->count();

    //     return response()->json([
    //         'info' => $info,
    //         'runningCount' => $runningCount,
    //         'plugins' => $pluginNames,
    //         'uniqueFolderName' => $uniqueFolderName,
    //         'siteId' => $siteId,
    //         'pluginFiles' => $pluginpath
    //     ]);
    // }



    public function getAdminDetails()
    {



        $authUser = auth()->user();
        $id = session('site_id');

        if ($authUser->role_id == 1) {
            $info = ManageSite::all();
        } else {
            $info = ManageSite::where('user_id', $authUser->id)->get();
        }

        $runningCount = $info->where('status', 'RUNNING')->count();
        $stoppedcount = $info->where('status', 'STOP')->count();

        return response()->json([
            'info' => $info,
            'runningCount' => $runningCount,
            'id' => $id,
            'stoppedcount' => $stoppedcount


        ]);
    }
}
