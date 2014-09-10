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
namespace CrudGenerator\MetaData;

use CrudGenerator\MetaData\MetaDataSourceCollection;
use CrudGenerator\Utils\ClassAwake;

/**
 * Find all MetaDataSource allow in project
 *
 * @author Stéphane Demonchaux
 */
class MetaDataSourceFinder
{
    /**
     * @var ClassAwake Class awake
     */
    private $classAwake = null;
    /**
     * @var MetaDataSourceHydrator
     */
    private $metaDataSourceHydrator = null;

    /**
     * Find all adapters allow in project
     * @param ClassAwake $classAwake
     * @param MetaDataSourceHydrator $metaDataSourceHydrator
     */
    public function __construct(ClassAwake $classAwake, MetaDataSourceHydrator $metaDataSourceHydrator)
    {
        $this->classAwake             = $classAwake;
        $this->metaDataSourceHydrator = $metaDataSourceHydrator;
    }

    /**
     * Find all adpater in the projects
     *
     * @return MetaDataSourceCollection
     */
    public function getAllAdapters()
    {
        $classCollection = $this->classAwake->wakeByInterfaces(
            array(
                __DIR__ . '/Sources/'
            ),
            'CrudGenerator\MetaData\Sources\MetaDataDAOFactoryInterface'
        );

        $adapterCollection = new MetaDataSourceCollection();

        foreach ($classCollection as $className) {
            $adapterCollection->append(
                $this->metaDataSourceHydrator->adapterNameToMetaDataSource(
                    $className
                )
            );
        }

        return $adapterCollection;
    }
}
