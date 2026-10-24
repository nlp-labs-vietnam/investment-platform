<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Property;
use App\Traits\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image as ImageIntervention;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PropertyImageController extends Controller
{
    use Upload;

    /**
     * AJAX — Xóa một ảnh gallery riêng lẻ
     */
    public function destroy(Request $request, $id)
    {
        abort_if(!$request->ajax(), 404);

        $image = Image::where('id', $id)
            ->where('imageable_type', Property::class)
            ->firstOrFail();

        $this->fileDelete($image->driver, $image->image);
        $image->delete();

        return response()->json(['success' => true, 'message' => __('Image deleted successfully.')]);
    }

    /**
     * AJAX — Cập nhật thứ tự sắp xếp ảnh (drag-and-drop)
     * Request body: { ids: [3, 1, 5, 2] }  — thứ tự mong muốn
     */
    public function reorder(Request $request)
    {
        abort_if(!$request->ajax(), 404);

        $validator = Validator::make($request->all(), [
            'ids'   => 'required|array|min:1',
            'ids.*' => 'required|integer|exists:images,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        DB::transaction(function () use ($request) {
            foreach ($request->ids as $order => $imageId) {
                Image::where('id', $imageId)
                    ->where('imageable_type', Property::class)
                    ->update(['sort_order' => $order]);
            }
        });

        return response()->json(['success' => true, 'message' => __('Image order updated.')]);
    }

    /**
     * AJAX — Cập nhật alt text và caption cho một ảnh
     * Request body: { alt_text: '...', caption: '...' }
     */
    public function updateMeta(Request $request, $id)
    {
        abort_if(!$request->ajax(), 404);

        $validator = Validator::make($request->all(), [
            'alt_text' => 'nullable|string|max:191',
            'caption'  => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $image = Image::where('id', $id)
            ->where('imageable_type', Property::class)
            ->firstOrFail();

        $image->update([
            'alt_text' => $request->alt_text,
            'caption'  => $request->caption,
        ]);

        return response()->json(['success' => true, 'message' => __('Image metadata updated.')]);
    }

    /**
     * AJAX — Crop ảnh và replace file cũ
     * Request body: { image_data: 'data:image/webp;base64,...' }
     */
    public function crop(Request $request, $id)
    {
        abort_if(!$request->ajax(), 404);

        $validator = Validator::make($request->all(), [
            'image_data' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $image = Image::where('id', $id)
            ->where('imageable_type', Property::class)
            ->firstOrFail();

        // Giải mã base64 từ canvas Cropper.js
        $imageData = $request->image_data;
        if (!preg_match('/^data:image\/(\w+);base64,/', $imageData, $matches)) {
            return response()->json(['success' => false, 'message' => __('Invalid image data.')], 422);
        }

        $base64 = substr($imageData, strpos($imageData, ',') + 1);
        $decoded = base64_decode($base64);

        if (!$decoded) {
            return response()->json(['success' => false, 'message' => __('Could not decode image data.')], 422);
        }

        $driver = $image->driver ?: config('filesystems.default');
        $directory = config('filelocation.property.path');
        $newPath   = $directory . '/' . Str::random(30) . '.webp';

        // Xóa file cũ
        if (Storage::disk($driver)->exists($image->image)) {
            Storage::disk($driver)->delete($image->image);
        }

        // Lưu ảnh đã crop dưới dạng WebP
        $img = ImageIntervention::make($decoded)->encode('webp', 90);
        Storage::disk($driver)->put($newPath, $img);

        $image->update([
            'image'  => $newPath,
            'driver' => $driver,
        ]);

        return response()->json([
            'success' => true,
            'message' => __('Image cropped successfully.'),
            'src'     => asset(getFile($driver, $newPath)),
        ]);
    }
}
