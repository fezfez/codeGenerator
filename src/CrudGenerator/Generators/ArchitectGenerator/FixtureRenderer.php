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

use CrudGenerator\Utils\LoremIpsumGenerator;
use CrudGenerator\MetaData\DataObject\MetaDataColumnDataObject;
use CrudGenerator\View\ViewHelperInterface;

class FixtureRenderer implements ViewHelperInterface
{
    /**
     * @var LoremIpsumGenerator
     */
    private $loremIpsumGenerator = null;

    public function __construct()
    {
        $this->loremIpsumGenerator = new LoremIpsumGenerator();
    }

    /**
     * @param MetaDataColumnDataObject $metadata
     * @return string
     */
    public function render(MetaDataColumnDataObject $metadata)
    {
        if($metadata->getType() == 'integer' || $metadata->getType() == 'float') {
            return rand(0, 50);
        } elseif($metadata->getType() == 'string') {
            return '"' . substr($this->loremIpsumGenerator->getContent($metadata->getLength(), 'plain'), 0, $metadata->getLength()) . '"';
        } elseif($metadata->getType() == 'date') {
            return 'new DateTime()';
        } elseif($metadata->getType() == 'bool') {
            return 'true';
        }
    }
}
