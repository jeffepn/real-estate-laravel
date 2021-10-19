<?php

namespace Jeffpereira\RealEstate\Http\Controllers\Api;

use Illuminate\Http\Request;
use Jeffpereira\RealEstate\Enum\AppSettingsEnum;
use Jeffpereira\RealEstate\Http\Controllers\Controller;
use Jeffpereira\RealEstate\Http\Requests\AppSettingsRequest;
use Jeffpereira\RealEstate\Services\AppSettingService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Jeffpereira\RealEstate\Http\Resources\AppSettingResource;
use Jeffpereira\RealEstate\Utilities\Terminologies;

class AppSettingController extends Controller
{
    /**
     * @var AppSettingService
     */
    private $appSettingService;

    public function __construct(AppSettingService $appSettingService)
    {
        $this->appSettingService = $appSettingService;
    }

    public function store(AppSettingsRequest $request)
    {
        try {
            $appSetting = $this->appSettingService->create(
                $this->wrapperDataSettings($request)
            );

            return (new AppSettingResource($appSetting, Terminologies::get('all.common.save_data')))
                ->response()->setStatusCode(201);
        } catch (\Throwable $th) {
            logger()->error("Erro in store  AppSettingController: " . $th->getMessage(), $th->getTrace());
            return response(['error' => 'true', 'message' => Terminologies::get('all.common.error_save_data') . $th->getMessage()], 400);
        }
    }


    private function wrapperDataSettings(AppSettingsRequest $request): array
    {
        $wrappers = $this->handleWrapper();
        return Arr::has($wrappers, $request->name)
            ? $this->handleWrapper()[$request->name]($request)
            : Arr::get($request->all(), ['name', 'value']);
    }

    private function handleWrapper(): array
    {
        return [
            AppSettingsEnum::WATTERMARK_IMAGE_PROPERTY => function (AppSettingsRequest $request): array {
                $data = Arr::only($request->all(), 'name');
                $data['value'] = [
                    'image' => Storage::disk(config('realestatelaravel.filesystem.disk'))
                        ->put(
                            'images',
                            $request->image_watter
                        )
                ];
                return $data;
            }
        ];
    }
}
