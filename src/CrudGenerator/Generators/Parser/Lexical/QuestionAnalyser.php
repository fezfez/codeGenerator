<?php
/**
  * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Generators\Parser\Lexical;

use CrudGenerator\Generators\Parser\Lexical\MalformedGeneratorException;

class QuestionAnalyser
{
    /**
     * @param array $question
     * @throws MalformedGeneratorException
     * @return array
     */
    public function checkIntegrity(array $question)
    {
        $questionDefinition = $this->getDefinition();
        $question           = $this->parseMandatory($questionDefinition, $question);
        $question           = $this->parseOptional($questionDefinition, $question);
        $question['setter'] = 'set' . ucfirst($question['dtoAttribute']);

        if ($question['type']->is(QuestionTypeEnum::ITERATOR_WITH_PREDEFINED_RESPONSE) === true) {
            if (isset($question['iteration']) === false) {
                throw new MalformedGeneratorException(
                    sprintf('"Iteration" must be set in %s', json_encode($question))
                );
            } elseif (isset($question['iteration']['response']) === false) {
                throw new MalformedGeneratorException(
                    sprintf('"Iteration.response" must be set in %s', json_encode($question))
                );
            } elseif (isset($question['iteration']['response']['predefined']) === false) {
                throw new MalformedGeneratorException(
                    sprintf('"Iteration.response.predefined" must be set in %s', json_encode($question))
                );
            } elseif (is_array($question['iteration']['response']['predefined']) === false) {
                throw new MalformedGeneratorException(
                    sprintf('"predefinedResponse" must be an array in %s', json_encode($question))
                );
            }
        }

        return $question;
    }

    /**
     * @param array $questionDefinition
     * @param array $question
     * @throws \InvalidArgumentException
     * @throws MalformedGeneratorException
     * @return array
     */
    private function parseOptional(array $questionDefinition, array $question)
    {
        foreach ($questionDefinition['optional'] as $tag => $definition) {
            if (isset($question[$tag]) === false) {
                $question = $this->parseOptionalForNonExistantKey($question, $tag, $definition);
            } elseif (isset($definition['enum']) === true) {
                try {
                    $question[$tag] = new $definition['enum']($question[$tag]);
                } catch (\UnexpectedValueException $e) {
                    throw new MalformedGeneratorException($e->getMessage());
                }
            }
        }

        return $question;
    }

    /**
     * @param array $question
     * @param string $tag
     * @param array $definition
     * @throws \InvalidArgumentException
     * @return array
     */
    private function parseOptionalForNonExistantKey(array $question, $tag, array $definition)
    {
        if (array_key_exists('default', $definition) === true) {
            $question[$tag] = $definition['default'];
        } elseif (array_key_exists('enum', $definition) === true) {
            $question[$tag] = new $definition['enum']();
        } else {
            throw new \InvalidArgumentException(
                sprintf('No default and no enum for %s', json_encode($definition))
            );
        }

        return $question;
    }

    /**
     * @param array $questionDefinition
     * @param array $question
     * @return array
     */
    private function parseMandatory(array $questionDefinition, array $question)
    {
        foreach ($questionDefinition['mandatory'] as $tag) {
            if (isset($question[$tag]) === false) {
                $this->throwException($question, $tag);
            }
        }

        return $question;
    }

    /**
     * @return array
     */
    private function getDefinition()
    {
        return array(
            'mandatory' => array(
                'dtoAttribute'
            ),
            'optional' => array(
                'required' => array(
                    'default' => false
                ),
                'helpMessage' => array(
                    'default' => null
                ),
                'responseType' => array(
                    'enum' => 'CrudGenerator\Generators\Parser\Lexical\QuestionResponseTypeEnum'
                ),
                'type' => array(
                    'enum' => 'CrudGenerator\Generators\Parser\Lexical\QuestionTypeEnum'
                )
            )
        );
    }

    /**
     * @param array $question
     * @param string $missingAttr
     * @throws MalformedGeneratorException
     */
    private function throwException(array $question, $missingAttr)
    {
        throw new MalformedGeneratorException(
            sprintf(
                'Question "%s" does not have property "%s"',
                json_encode($question),
                $missingAttr
            )
        );
    }
}
