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
namespace CrudGenerator\Utils;

use ReflectionClass;

/**
 * Allow to awake classes
 */
class ClassAwake
{
    /**
     * Awake classes by interface
     * @param string[] $directories Target directory
     * @param string $interfaceNames Interface name
     * @return array
     */
    public function wakeByInterfaces(array $directories, $interfaceNames)
    {
        $classCollection = $this->awake($directories);
        $classes         = array();

        foreach ($classCollection as $className) {
            $reflectionClass = new ReflectionClass($className);
            $interfaces      = $reflectionClass->getInterfaces();

            if (is_array($interfaces) === true && isset($interfaces[$interfaceNames]) === true) {
                $class = str_replace('\\', '', strrchr($className, '\\'));
                $classes[$class] = $className;
            }
        }

        return $classes;
    }

    /**
     * Find classes on directory
     * @param string[] $directories Target directory
     * @return array
     */
    private function awake(array $directories)
    {
        $includedFiles = array();
        foreach ($directories as $directorie) {
            $iterator = new \RegexIterator(
                new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($directorie, \FilesystemIterator::SKIP_DOTS),
                    \RecursiveIteratorIterator::LEAVES_ONLY
                ),
                '/^.+' . preg_quote('.php') . '$/i',
                \RecursiveRegexIterator::GET_MATCH
            );

            foreach ($iterator as $file) {
                $sourceFile = realpath($file[0]);

                require_once $sourceFile;

                $includedFiles[] = $sourceFile;
            }
        }

        $classes  = array();
        $declared = get_declared_classes();

        foreach ($declared as $className) {
            $rc         = new \ReflectionClass($className);
            $sourceFile = $rc->getFileName();

            if (in_array($sourceFile, $includedFiles) === true) {
                $classes[] = $className;
            }
        }

        return $classes;
    }
}
