<?php
/**
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */
namespace CrudGenerator\Generators\Finder;

use CrudGenerator\MetaData\DataObject\MetaDataInterface;

/**
 * Find all generator allow in project
 *
 * @author Stéphane Demonchaux
 */
class GeneratorFinderCache implements GeneratorFinderInterface
{
    /**
     * @var GeneratorFinder
     */
    private $generatorFinder = null;
    /**
     * @var array
     */
    private $directories = null;
    /**
     * @var boolean
     */
    private $noCache = null;

    /**
     * @param GeneratorFinder $generatorFinder
     * @param array $directories
     * @param boolean $noCache
     */
    public function __construct(GeneratorFinder $generatorFinder, array $directories, $noCache = false)
    {
        $this->generatorFinder = $generatorFinder;
        $this->directories     = $directories;
        $this->noCache         = false;
    }

    /**
     * Find all adapters allow in project
     *
     * @return array
     */
    public function getAllClasses(MetaDataInterface $metadata = null)
    {
        $cacheFilename  = $this->directories['Cache'] . DIRECTORY_SEPARATOR;
        $cacheFilename .= md5('genrator_getAllClasses' . ($metadata !== null) ? get_class($metadata) : '');

        if (is_file($cacheFilename) === true && $this->noCache === false) {
            $data = unserialize(file_get_contents($cacheFilename));
        } else {
            $data = $this->generatorFinder->getAllClasses($metadata);
            file_put_contents($cacheFilename, serialize($data));
        }

        return $data;
    }

    /**
     * @param string $name
     * @throws \InvalidArgumentException
     * @return string
     */
    public function findByName($name)
    {
        $generatorCollection = $this->getAllClasses();

        foreach ($generatorCollection as $generatorFile => $generatorName) {
            if ($generatorName === $name) {
                return $generatorFile;
            }
        }

        throw new \InvalidArgumentException(sprintf('Generator name "%s" does not exist', $name));
    }
}
