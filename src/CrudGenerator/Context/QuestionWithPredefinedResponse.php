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

class QuestionWithPredefinedResponse extends SimpleQuestion
{
    /**
     * @var PredefinedResponseCollection
     */
    private $predefinedResponseCollection = null;
    /**
     * @var string
     */
    private $preselectedResponse = null;

    /**
     * @param string $text
     * @param string $uniqueKey
     * @param PredefinedResponseCollection $predefinedResponseCollection
     */
    public function __construct($text, $uniqueKey, PredefinedResponseCollection $predefinedResponseCollection)
    {
        parent::__construct($text, $uniqueKey);
        $this->predefinedResponseCollection = $predefinedResponseCollection;
    }

    /**
     * If this preselected reponse match with
     * a response id this reponse will be automatically
     * selected
     *
     * @param string $value
     * @return QuestionWithPredefinedResponse
     */
    public function setPreselectedResponse($value)
    {
        $this->preselectedResponse = $value;

        return $this;
    }

    /**
     * @return PredefinedResponseCollection
     */
    public function getPredefinedResponseCollection()
    {
        return $this->predefinedResponseCollection;
    }

    /**
     * @return string
     */
    public function getPreselectedResponse()
    {
        return $this->preselectedResponse;
    }
}
