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
namespace CrudGenerator\Generators\ArchitectGenerator;

use CrudGenerator\MetaData\DataObject\MetaDataColumn;
use CrudGenerator\View\ViewHelperInterface;
use Faker\Factory;

class FixtureRenderer implements ViewHelperInterface
{
    /**
     * @var \Faker\Generator
     */
    private $faker = null;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    /**
     * @param MetaDataColumn $metadata
     * @return string
     */
    public function render(MetaDataColumn $metadata)
    {
        $data = '';
        if ($metadata->getType() == 'integer' || $metadata->getType() == 'float') {
            $data = $this->faker->randomNumber();
        } elseif ($metadata->getType() == 'string') {
            if ($metadata->getLength() <= 5) {
                $data = '"5555"';
            } else {
                $data = '"' . $this->faker->text($metadata->getLength()) . '"';
            }
        } elseif ($metadata->getType() == 'date') {
            $data = 'new DateTime()';
        } elseif ($metadata->getType() == 'bool') {
            $data = 'true';
        }

        return $data;
    }
}
