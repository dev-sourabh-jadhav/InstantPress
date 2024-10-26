<?php

namespace App\Http\Controllers\WP;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WpMaterial;
use Illuminate\Support\Facades\Log;

class WPThemsController extends Controller
{
    public function themes_index(Request $request)
    {
        return view("wp.themes");
    }

    public function fetchThemes(Request $request)
    {
        $search = $request->input('search') ?? '';

        $url = "https://api.wordpress.org/themes/info/1.2/?action=query_themes";
        $params = [
            'search' => $search,
        ];

        $query_url = $url . '&' . http_build_query($params);
        $response = file_get_contents($query_url);
        $themes = json_decode($response, true);


        if ($themes && isset($themes['themes'])) {
            return response()->json([
                'data' => $themes['themes'],
                'recordsTotal' => $themes['info']['results'],
                'recordsFiltered' => $themes['info']['results']
            ]);
        }
    }


    public function downloadTheme(Request $request)
    {
        $slug = $request->input('slug');
        $name = $request->input('name');
        $description = $request->input('description');

        $url = "https://downloads.wordpress.org/theme/{$slug}.zip";
        $filePath = public_path("wp-themes/{$slug}.zip");

        // Download the file
        file_put_contents($filePath, file_get_contents($url));


        WpMaterial::create([
            'type' => 'wp-themes',
            'name' => $name,
            'description' => $description,
            'file_path' => "wp-themes/{$slug}.zip",
            'status' => 1,
            'slug' => $slug
        ]);

        return response()->json(['message' => 'Theme downloaded successfully!']);
    }


    public function getthemes()
    {
        $getthemes = WpMaterial::where('type', 'wp-themes')->get();

        // Return data wrapped in a 'data' key
        return response()->json(['data' => $getthemes]);
    }

    public function uploadthemes(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'file_path' => 'required|file|mimes:zip',
            'description' => 'nullable|string|max:1000',
        ]);


        if ($request->hasFile('file_path')) {

            $originalFileName = $request->file('file_path')->getClientOriginalName();
            $fileName =  $originalFileName;

            $request->file('file_path')->move(public_path('wp-themes'), $fileName);

            $plugin = new WpMaterial();
            $plugin->name = $request->name;
            $plugin->file_path = 'wp-themes/' . $fileName;
            $plugin->description = $request->description;
            $plugin->type = 'wp-themes';
            $plugin->status = 'installed';


            $plugin->save();

            return redirect()->back()->with('success', 'plugin uploaded successfully.');
        }
    }
}
