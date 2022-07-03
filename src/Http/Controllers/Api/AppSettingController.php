<?php

namespace Jeffpereira\RealEstate\Http\Controllers\Api;

use Illuminate\Http\Response;
use Jeffpereira\RealEstate\Enum\AppSettingsEnum;
use Jeffpereira\RealEstate\Http\Controllers\Controller;
use Jeffpereira\RealEstate\Http\Requests\AppSettingsRequest;
use Jeffpereira\RealEstate\Services\AppSettingService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Jeffpereira\RealEstate\Http\Resources\AppSettingResource;
use Jeffpereira\RealEstate\Models\AppSettings;
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

    public function show(AppSettings $app_setting)
    {
        return new AppSettingResource($app_setting);
    }

    public function store(AppSettingsRequest $request)
    {
        try {
            $appSetting = $this->appSettingService->create(
                $this->wrapperDataSettings($request)
            );

            return (new AppSettingResource($appSetting, Terminologies::get('all.common.save_data')))
                ->response()->setStatusCode(Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            logger()->error("Erro in store  AppSettingController: " . $th->getMessage(), $th->getTrace());
            return response(['error' => true, 'message' => Terminologies::get('all.common.error_save_data')], Response::HTTP_BAD_REQUEST);
        }
    }

    public function update(AppSettingsRequest $request, AppSettings $app_setting)
    {
        try {
            $appSettings = $this->appSettingService->update(
                array_merge(['id' => $app_setting->id], $this->wrapperDataSettings($request))
            );

            return (new AppSettingResource($appSettings, Terminologies::get('all.common.save_data')))
                ->response()->setStatusCode(200);
        } catch (\Throwable $th) {
            logger()->error("Erro in update AppSettingController: " . $th->getMessage(), $th->getTrace());
            return response(['error' => true, 'message' => Terminologies::get('all.common.error_save_data')], Response::HTTP_BAD_REQUEST);
        }
    }


    public function destroy(AppSettings $app_setting)
    {
        try {
            return $app_setting->delete()
                ? response()->noContent(200)
                : response(['error' => true, 'message' => Terminologies::get('all.app_setting.not_delete')], Response::HTTP_BAD_REQUEST);
        } catch (\Throwable $th) {
            logger("Error destroy AppSettingController: " . $th->getMessage(), $th->getTrace());
            return response(['error' => true, 'message' => $th->getMessage()], 500);
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
