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
namespace CrudGenerator\Context;

class PredefinedResponseCollection implements \IteratorAggregate
{
    /**
     * @var \ArrayIterator
     */
    private $collection = null;

    public function __construct()
    {
        $this->collection = new \ArrayIterator(array());
    }
    /**
     * @param PredefinedResponse $value
     * @return \CrudGenerator\Context\PredefinedResponseCollection
     */
    public function append(PredefinedResponse $value)
    {
        $this->collection->append($value);

        return $this;
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return $this->collection;
    }

    /**
     * @param string $idInSearch
     * @throws \Exception
     * @return PredefinedResponse
     */
    public function offsetGetById($idInSearch)
    {
        foreach ($this->collection as $question) {
            /* @var $question PredefinedResponse */
            if ($idInSearch === $question->getId()) {
                return $question;
            }
        }

        throw new \Exception('not found');
    }

    /**
     * @param string $labelInSearch
     * @throws \Exception
     * @return PredefinedResponse
     */
    public function offsetGetByLabel($labelInSearch)
    {
        foreach ($this->collection as $question) {
            /* @var $question PredefinedResponse */
            if ($labelInSearch === $question->getLabel()) {
                return $question;
            }
        }

        throw new \Exception('not found');
    }
}
