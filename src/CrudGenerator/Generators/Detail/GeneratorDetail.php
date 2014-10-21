<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Generators\Detail;

use Github\Api\Repo;
use Github\Api\Markdown;
use Packagist\Api\Result\Result;

/**
 * Find all generator allow in project
 *
 * @author Stéphane Demonchaux
 */
class GeneratorDetail
{
    /**
     * @var Repo
     */
    private $repository = null;

    /**
     * @var Markdown
     */
    private $markdown = null;

    /**
     * @param Repo $repository
     * @param Markdown $markdown
     */
    public function __construct(Repo $repository, Markdown $markdown)
    {
        $this->repository = $repository;
        $this->markdown   = $markdown;
    }

    /**
     * @param Result $package
     * @return string
     */
    public function find(Result $package)
    {
        $repository     = str_replace('https://github.com/', '', $package->getRepository());
        $packageExplode = explode('/', $repository);

        $data = $this->repository->readme($packageExplode[0], $packageExplode[1]);

        return array(
            'readme' => $this->markdown->render(base64_decode($data['content'])),
            'github' => $package->getRepository()
        );
    }
}
