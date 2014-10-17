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

use CrudGenerator\Generators\GeneratorDataObject;

/**
 * Context interface used for the interaction with the user.
 *
 */
interface ContextInterface
{
    /**
     * Ask question
     * @param string $text
     * @param string $uniqueKey
     * @param string $defaultResponse
     * @param boolean $required
     * @param string $type
     * @return string
     */
    public function ask(
        $text,
        $uniqueKey,
        $defaultResponse = null,
        $required = false,
        $helpMessage = null,
        $type = null
    );

    /**
     * Ask question in collection
     *
     * @param QuestionWithPredefinedResponse $questionResponseCollection
     *
     * @return ResponseContext
     */
    public function askCollection(QuestionWithPredefinedResponse $questionResponseCollection);

    /**
     * Ask question in confirm mode ex : "Do you want to eat the banana ?" yes/no
     *
     * @param string $text
     * @param string $uniqueKey
     * @return boolean
     */
    public function confirm($text, $uniqueKey);

    /**
     * Create a menu. The idea is to create an "tree decision"
     *
     * @param string $text
     * @param string $uniqueKey
     * @param callable $runner
     * @return void
     */
    public function menu($text, $uniqueKey, callable $runner);

    /**
     * Log a text
     *
     * @param string|array $text
     * @param string $name
     * @return void
     */
    public function log($text, $name = null);

    /**
     * Publish the generatorDto
     *
     * @param GeneratorDataObject $generator
     * @return void
     */
    public function publishGenerator(GeneratorDataObject $generator);
}
