<?php

namespace App\Support;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;
use App\Models\Tenant;

class TenantPathGenerator implements PathGenerator
{
    public function getPath(Media $media): string
    {
        return $this->getBasePath($media) . '/';
    }

    public function getPathForConversions(Media $media): string
    {
        return $this->getBasePath($media) . '/conversions/';
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getBasePath($media) . '/responsive-images/';
    }

    protected function getBasePath(Media $media): string
    {
        $tenantId = 'global';
        $modelName = 'other';
        
        $model = $media->model;
        if ($model) {
            $modelName = strtolower(class_basename($model));
            
            if (isset($model->tenant_id)) {
                $tenantId = 'tenant_' . $model->tenant_id;
            } elseif ($model instanceof Tenant) {
                $tenantId = 'tenant_' . $model->id;
            }
        }

        return $tenantId . '/' . $modelName . '/' . $media->getKey();
    }
}
