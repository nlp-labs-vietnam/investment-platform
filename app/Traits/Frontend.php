<?php

namespace App\Traits;

use App\Models\Blog;
use App\Models\ContentDetails;
use App\Models\Property;

trait Frontend
{
    protected function getSectionsData($sections, $content, $selectedTheme)
    {
        if ($sections == null) {
            $data = ['support' => $content,];
            return view("themes.$selectedTheme.support", $data)->toHtml();
        }

        $contentData = ContentDetails::with('content')
            ->whereHas('content', function ($query) use ($sections, $selectedTheme) {
                $query->whereIn('name', $sections);
            })
            ->get();

            if (in_array('property', $sections) || in_array('latest_property', $sections)) {
                $properties = Property::query()
                    ->with(['address','managetime','favoritedByUser'])
                    ->where('status', 1)
                    ->whereDate('expire_date', '>', now())
                    ->limit(4)
                    ->orderBy('id', 'asc')->get();
            }

        foreach ($sections as $section) {
            $singleContent = $contentData->where('content.theme', $selectedTheme)->where('content.name', $section)->where('content.type', 'single')->first() ?? [];
            if ($section == 'blog') {
                $data[$section] = [
                    'single' => $singleContent ? collect($singleContent->description ?? [])->merge($singleContent->content->only('media')) : [],
                    'multiple' => Blog::with('details')->where('status', 1)->latest()->get()
                ];
            } elseif ($section == 'property' || $section == 'latest_property') {
                $data[$section] = [
                    'single' => $singleContent ? collect($singleContent->description ?? [])->merge($singleContent->content->only('media')) : [],
                    'multiple' => $properties ?? []
                ];
            } else {
                $multipleContents = $contentData->where('content.theme', $selectedTheme)->where('content.name', $section)->where('content.type', 'multiple')->values()->map(function ($multipleContentData) {
                    return collect($multipleContentData->description)->merge($multipleContentData->content->only('media'));
                });

                $data[$section] = [
                    'single' => $singleContent ?
                        collect($singleContent->description ?? [])
                            ->merge($singleContent->content->only('media'))
                            ->put('created_at', $singleContent->created_at)
                        : [],
                    'multiple' => $multipleContents,
                    'image' => isset($singleContent->content->media->image->driver) ? getFile($singleContent->content->media->image->driver, $singleContent->content->media->image->path) : null,
                    'image2' => isset($singleContent->content->media->image2->driver) ? getFile($singleContent->content->media->image2->driver, $singleContent->content->media->image2->path) : null,
                    'image3' => isset($singleContent->content->media->image3->driver) ? getFile($singleContent->content->media->image3->driver, $singleContent->content->media->image3->path) : null,
                ];
            }


            $replacement = view("themes.$selectedTheme.sections.{$section}", $data)->toHtml();

            $content = str_replace('<div class="custom-block" contenteditable="false"><div class="custom-block-content">[[' . $section . ']]</div>', $replacement, $content);
            $content = str_replace('<span class="delete-block">×</span>', '', $content);
            $content = str_replace('<span class="up-block">↑</span>', '', $content);
            $content = str_replace('<span class="down-block">↓</span></div>', '', $content);
            $content = str_replace('<p><br></p>', '', $content);
        }

        return $content;
    }

    protected function handleDatabaseException(\Exception $exception)
    {
        switch ($exception->getCode()) {
            case 404:
                abort(404);
            case 403:
                abort(403);
            case 401:
                abort(401);
            case 503:
                redirect()->route('maintenance')->send();
                break;
            case "42S02":
                die($exception->getMessage());
            case 1045:
                die("Access denied. Please check your username and password.");
            case 1044:
                die("Access denied to the database. Ensure your user has the necessary permissions.");
            case 1049:
                die("Unknown database. Please verify the database name exists and is spelled correctly.");
            case 2002:
                die("Unable to connect to the MySQL server. Check the database host and ensure the server is running.");
//            case 0:
//                die("Unknown connection issue. Verify your connection parameters and server status.");
            default:
                throw $exception;
        }
    }

}
