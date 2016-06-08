<?php
namespace Hackaton\ImageStorage\Controllers;

use Hackaton\ImageStorage\Container\ContainerAwareTrait;
use Hackaton\ImageStorage\Exceptions\ExcludedImageException;
use Hackaton\ImageStorage\Exceptions\FileNotFoundException;
use Hackaton\ImageStorage\Exceptions\HttpException;
use Hackaton\ImageStorage\Image\Manager;
use Hackaton\ImageStorage\Image\Providers\SymfonyRequestProvider;
use Nette\Utils\ImageException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ImageController
{
    use ContainerAwareTrait;

    public function readAction(Request $request, $key = null, $profile = null)
    {
        if ($key === null) {
            return new JsonResponse(['error' => 'Expected file key!'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        try {
            /** @var Manager $image_manager */
            $image_manager = $this->container->getService('imageManager');
            $stored_file = $image_manager->loadImageFile($key, $profile);

            $response = new BinaryFileResponse($stored_file->getPath());
            $response->prepare($request);
            $response->headers->set('Content-Type', $stored_file->getMimeType());

            return $response;
        } catch (FileNotFoundException $e) {
            if ($this->container->getParameter('useNoImage')) {
                return new RedirectResponse($this->container->getParameter('noImageUrl'));
            }

            $code = Response::HTTP_NOT_FOUND;
        } catch (\Exception $e) {
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return new JsonResponse(['error' => $e->getMessage()], $code);
    }

    public function createAction(Request $request)
    {
        try {
            /** @var Manager $image_manager */
            $image_manager = $this->container->getService('imageManager');

            /** @var SymfonyRequestProvider $provider */
            $provider = $this->container->getService('symfonyRequestProvider');
            $provider->setRequest($request);

            $stored_file = $image_manager->storeFromProvider($provider);

            return new JsonResponse(
                [
                'key'      => $stored_file->getKey(),
                'filesize' => $stored_file->getFilesize(),
                'updated'  => $stored_file->getUpdatedTime()
                ]
            );
        } catch (ImageException $e) {
            $code = Response::HTTP_UNSUPPORTED_MEDIA_TYPE;
        } catch (HttpException $e) {
            $code = $e->getCode();
        } catch (ExcludedImageException $e) {
            $code = Response::HTTP_UNPROCESSABLE_ENTITY;
        } catch (\Exception $e) {
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return new JsonResponse(['error' => $e->getMessage()], $code);
    }

    public function deleteAction(Request $request, $key = null, $profile = null)
    {
        if ($key === null) {
            return new JsonResponse(['error' => 'Expected file key!'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        try {
            /** @var Manager $image_manager */
            $image_manager = $this->container->getService('imageManager');
            $image_manager->deleteImageFile($key, $profile);

            return new JsonResponse(sprintf('File with key "%s" was removed.', $key));
        } catch (FileNotFoundException $e) {
            $code = Response::HTTP_NOT_FOUND;
        } catch (\Exception $e) {
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return new JsonResponse(['error' => $e->getMessage()], $code);
    }
}
