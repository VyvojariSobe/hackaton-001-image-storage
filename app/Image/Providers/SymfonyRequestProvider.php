<?php
namespace Hackaton\ImageStorage\Image\Providers;

use Hackaton\ImageStorage\Exceptions\BadContentTypeException;
use Hackaton\ImageStorage\Exceptions\CantLoadContentException;
use Hackaton\ImageStorage\Exceptions\NoContentSourceFoundException;
use Hackaton\ImageStorage\Image\File;
use Hackaton\ImageStorage\Loaders\ILoader;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

class SymfonyRequestProvider implements IProvider
{
  /** @var ILoader */
    protected $loader;

  /** @var Request */
    protected $request;

    public function __construct(ILoader $loader)
    {
        $this->loader = $loader;
    }

    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    public function createFile()
    {
        $content_type = $this->request->getContentType();

        if ($content_type !== 'form' && $content_type !== 'json') {
            throw new BadContentTypeException(sprintf('Bad Content-Type! Expected "form" or "json", got "%s".', $content_type));
        }

        $parameter_bag = $this->request->request;

        if ($content_type === 'json') {
            $content = $this->request->getContent();
            $content_data = @json_decode($content, true) ?: [];
            $parameter_bag = new ParameterBag($content_data);
        }

        if (($url = $parameter_bag->get('url')) !== null) {
            $content = $this->loader->load($url);

            if ($content === false) {
                throw new CantLoadContentException(sprintf('Can\'t load content from url "%s".', $url));
            }

            return new File(md5("url:$url"), $content);
        }

        if (($content = $parameter_bag->get('content')) !== null) {
            $content = base64_decode($content);

            if ($content === false) {
                throw new CantLoadContentException(sprintf('Can\'t decode base64.'));
            }

            return new File(md5("content:$content"), $content);
        }

        throw new NoContentSourceFoundException('No source found.');
    }
}
