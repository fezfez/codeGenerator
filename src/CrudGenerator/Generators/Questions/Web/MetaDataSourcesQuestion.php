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
namespace CrudGenerator\Generators\Questions\Web;

use CrudGenerator\MetaData\MetaDataSourceFinder;

class MetaDataSourcesQuestion
{
    /**
     * @var MetaDataSourceFinder
     */
    private $metadataSourceFinder = null;

    /**
     * @param MetaDataSourceFinder $metadataSourceFinder
     */
    public function __construct(MetaDataSourceFinder $metadataSourceFinder)
    {
        $this->metadataSourceFinder = $metadataSourceFinder;
    }

    /**
     * Ask wich MetaData Source you want to use
     * @param string $default
     * @return \CrudGenerator\MetaData\MetaDataSource
     */
    public function ask($choice = null)
    {
        if ($choice !== null) {
            $backendSelect = null;
            foreach ($this->metadataSourceFinder->getAllAdapters() as $backend) {
                if ($backend->getFactory() === $choice) {
                    $backendSelect = $backend;
                }
            }

            if (null === $backendSelect) {
                throw new \InvalidArgumentException(sprintf('Invalid %s', $choice));
            }

            return $backendSelect;
        } else {
            $backendArray = array();
            foreach ($this->metadataSourceFinder->getAllAdapters() as $backend) {
                if(!$backend->getFalseDependencies()) {
                    $backendArray[] = array('id' => $backend->getFactory(), 'label' => $backend->getDefinition());
                }
            }
            return $backendArray;
        }
    }
}
