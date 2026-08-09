<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Trip;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $trip = Trip::first();
        $query = Gallery::with('member')->where('trip_id', $trip?->id);

        if ($request->has('location') && $request->location !== 'all') {
            $query->where('location_tag', $request->location);
        }

        if ($request->has('best_moment') && $request->best_moment) {
            $query->where('is_best_moment', true);
        }

        $galleries = $query->latest()->get();
        $locations = Gallery::where('trip_id', $trip?->id)
            ->whereNotNull('location_tag')
            ->distinct()
            ->pluck('location_tag');

        $destinations = Destination::pluck('name');

        return view('gallery.index', compact('trip', 'galleries', 'locations', 'destinations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'files.*' => 'required|file|max:51200',
            'location_tag' => 'nullable|string|max:255',
            'caption' => 'nullable|string|max:255',
            'member_id' => 'nullable|exists:members,id',
        ]);

        $trip = Trip::first();
        $uploaded = [];

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $fileType = str_starts_with($file->getMimeType(), 'video/') ? 'video' : 'image';

                // Compress image if it's an image
                $path = $file->store('gallery', 'public');

                if ($fileType === 'image') {
                    try {
                        $fullPath = Storage::disk('public')->path($path);
                        $img = imagecreatefromstring(file_get_contents($fullPath));
                        if ($img) {
                            $width = imagesx($img);
                            $height = imagesy($img);

                            // Resize if larger than 1920px
                            if ($width > 1920) {
                                $newWidth = 1920;
                                $newHeight = (int) ($height * (1920 / $width));
                                $resized = imagecreatetruecolor($newWidth, $newHeight);
                                imagecopyresampled($resized, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                                imagejpeg($resized, $fullPath, 80);
                                imagedestroy($resized);
                            }
                            imagedestroy($img);
                        }
                    } catch (\Exception $e) {
                        // If compression fails, keep the original
                    }
                }

                $gallery = Gallery::create([
                    'trip_id' => $trip->id,
                    'member_id' => $request->member_id,
                    'file_path' => $path,
                    'file_type' => $fileType,
                    'location_tag' => $request->location_tag,
                    'caption' => $request->caption,
                ]);

                $uploaded[] = $gallery;
            }
        }

        return response()->json([
            'success' => true,
            'count' => count($uploaded),
            'galleries' => $uploaded,
        ]);
    }

    public function toggleBestMoment(Gallery $gallery)
    {
        $gallery->update([
            'is_best_moment' => !$gallery->is_best_moment,
        ]);

        return response()->json([
            'success' => true,
            'is_best_moment' => $gallery->is_best_moment,
        ]);
    }

    public function destroy(Gallery $gallery)
    {
        Storage::disk('public')->delete($gallery->file_path);
        $gallery->delete();

        return response()->json(['success' => true]);
    }
}
