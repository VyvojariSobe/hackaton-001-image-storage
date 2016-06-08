<?php
namespace Hackaton\ImageStorage\Controllers;

use Hackaton\ImageStorage\Container\ContainerAwareTrait;
use Hackaton\ImageStorage\Exceptions\FileNotFoundException;
use Hackaton\ImageStorage\Image\Manager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class InfoController
{
    use ContainerAwareTrait;

    public function readAction(Request $request, $key = null)
    {
        if ($key === null) {
            return new JsonResponse(['error' => 'Expected ID!'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        try {
            /** @var Manager $image_manager */
            $image_manager = $this->container->getService('imageManager');
            $stored_file = $image_manager->loadImageFile($key, 'original');

            return new JsonResponse(
                [
                'id'       => $stored_file->getKey(),
                'filesize' => $stored_file->getFilesize(),
                'updated'  => $stored_file->getUpdatedTime()
                ]
            );
        } catch (FileNotFoundException $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
