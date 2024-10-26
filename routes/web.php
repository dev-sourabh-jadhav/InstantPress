<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use  App\Http\Controllers\ManageRolesController;
use  App\Http\Controllers\SMTPController;
use  App\Http\Controllers\ManageUsers;
use App\Http\Controllers\ManageSiteController;
use App\Http\Controllers\WP\CreateWordpressController;
use App\Http\Controllers\WP\WPController;
use App\Http\Controllers\WP\WPThemsController;
use App\Http\Controllers\WP\WPVersionController;
use App\Http\Controllers\PluginCategoriesController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/managerole', [ManageRolesController::class, 'index'])->name('managerole');
Route::get('roles', [ManageRolesController::class, 'index'])->name('roles.index');
Route::get('roles/get', [ManageRolesController::class, 'getrole'])->name('getrole');
Route::post('roles/store', [ManageRolesController::class, 'store'])->name('roles.store');
Route::post('roles/update/{id}', [ManageRolesController::class, 'update'])->name('roles.update');
Route::delete('roles/delete/{id}', [ManageRolesController::class, 'destroy'])->name('roles.destroy');

// SMPT SETTING
Route::get('smptsetting', [SMTPController::class, 'showpage'])->name('smptsetting');
Route::post('/mail_settings', [SMTPController::class, 'store'])->name('mail_settings.store');
Route::post('/send-mail', [SMTPController::class, 'sendMail'])->name('send.mail');
Route::get('/admin/smtp-settings', [SMTPController::class, 'edit'])->name('smtp.settings.edit');
Route::post('/admin/smtp-settings', [SMTPController::class, 'update'])->name('smtp.settings.update');
Route::get('/getsmtp', [SMTPController::class, 'getsmtp'])->name('getsmtp');
Route::POST('/smtptoggle/{id}', [SMTPController::class, 'toggleStatus'])->name('smtp.toggle');

//ManageUsers

Route::get('/manageusers', [ManageUsers::class, 'index'])->name('manageusers');
Route::get('/getusers', [ManageUsers::class, 'getusers'])->name('getusers');
Route::post('/manageusers', [ManageUsers::class, 'storeusers'])->name('storeusers');
Route::put('/users/update/{id}', [ManageUsers::class, 'updateusers'])->name('updateusers');
Route::delete('/users/delete/{id}', [ManageUsers::class, 'destroy']);

//ManageSiteController
Route::get('/managesites', [ManageSiteController::class, 'index'])->name('managesites');
Route::post('/storesite', [ManageSiteController::class, 'storesite'])->name('storesite');
Route::get('/showsites', [ManageSiteController::class, 'showsites'])->name('showsites');
Route::get('/users/{id}', [ManageSiteController::class, 'siteedit'])->name('siteedit');
Route::post('/users/updatesite', [ManageSiteController::class, 'updatesite']);
Route::delete('/users/sitedelete/{id}', [ManageSiteController::class, 'destroy'])->name('users.destroy');

//WP MAterial
//PLUGIN
Route::get('/plugins', [WPController::class, 'plugin_index'])->name('plugins');
Route::get('/fetch-plugins', [WPController::class, 'fetchPlugins'])->name('fetch.plugins');
Route::post('/download-plugin', [WPController::class, 'downloadPlugin'])->name('download.plugin');
Route::get('/installed-plugins', [WPController::class, 'listInstalledPlugins']);
Route::post('uploadPlugin', [WPController::class, 'uploadPlugin'])->name('uploadPlugin');
// Themes

Route::get('/themes', [WPThemsController::class, 'themes_index'])->name('themes');
Route::get('/fetch-themes', [WPThemsController::class, 'fetchThemes'])->name('fetch.themes');
Route::post('/download-theme', [WPThemsController::class, 'downloadTheme']);
Route::get('/getthemes', [WPThemsController::class, 'getthemes'])->name('getthemes');
Route::post('uploadthemes', [WPThemsController::class, 'uploadthemes'])->name('uploadthemes');
//Create wordpress
Route::get('/wp-version', [WPVersionController::class, 'version_page'])->name('wp-version');
Route::post('/versionstore', [WPVersionController::class, 'version_store'])->name('version_store');
Route::get('/getversions', [WPVersionController::class, 'getversions'])->name('getversions');

// Create WORDPRESS
Route::get('/wordpress-version', [CreateWordpressController::class, 'wordpress_version']);
Route::get('/get-plugins', [CreateWordpressController::class, 'getPlugins']);
Route::post('/download-wordpress', [CreateWordpressController::class, 'downloadWordPress']);
Route::get('/plugins_categories', [CreateWordpressController::class, 'showPlugins'])->name('plugins.show');
Route::get('/plugins/byCategory/{id}', [CreateWordpressController::class, 'getByCategory'])->name('plugins.byCategory');
//Manage_plugin_categories
Route::post('/extractplugin', [CreateWordpressController::class, 'extractplugin']);
Route::get('/themesforextract', [CreateWordpressController::class, 'themesforextract'])->name('themesforextract');
Route::post('/extract-themes', [CreateWordpressController::class, 'extractthemes'])->name('extraxt-themes');
Route::post('/create-database', [CreateWordpressController::class, 'createDatabase'])->name('create.database');


Route::resource('/plugin_categories', PluginCategoriesController::class);


Route::get('/session-details', [CreateWordpressController::class, 'getAdminDetails']);



 // 'login_url' => "http://localhost/wp-sites/WPALL-Sites/" . $uniqueFolderName,