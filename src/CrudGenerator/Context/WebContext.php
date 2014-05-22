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

use Silex\Application;

class WebContext implements ContextInterface, \JsonSerializable
{
    /**
     * @var Application
     */
    private $application = null;
    /**
     * @var array
     */
    private $question    = array();

    /**
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\Context\ContextInterface::ask()
    */
    public function ask($text, $key, $defaultResponse = null, $required = false, $helpMessage = null)
    {
        if (!isset($this->question['question'])) {
            $this->question['question'] = array();
        }
        $this->question['question'][] = array(
            'text'            => $text,
            'dtoAttribute'    => $key,
            'defaultResponse' => $defaultResponse,
            'required'        => $required
        );

        return $this->application->offsetGet('request')->request->get($key);
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\Context\ContextInterface::ask()
    */
    public function askCollection($text, $uniqueKey, array $collection, $defaultResponse = null, $required = false, $helpMessage = null)
    {
        $this->question[$uniqueKey . 'Collection'] = $collection;

        return $this->application->offsetGet('request')->request->get($uniqueKey);
    }

    /**
     * @param string $text
     * @param string $uniqueKey
     * @return boolean
     */
    public function confirm($text, $uniqueKey)
    {

    }

    /**
     * @param string $text
     */
    public function log($text)
    {

    }

    /* (non-PHPdoc)
     * @see JsonSerializable::jsonSerialize()
     */
    public function jsonSerialize()
    {
        return $this->question;
    }
}
