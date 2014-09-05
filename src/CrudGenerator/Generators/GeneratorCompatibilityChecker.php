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
namespace CrudGenerator\Generators;

use CrudGenerator\MetaData\DataObject\MetaDataInterface;

class GeneratorCompatibilityChecker
{
/**
     * Check in the metadata instance is in allowed generator type
     *
     * @param MetaData $metadata
     * @param array $process
     * @throws \InvalidArgumentException
     */
    public function metadataAllowedInGenerator(MetaDataInterface $metadata, array $process)
    {
        $metadataAllowed = false;
        foreach ($process['metadataTypeAccepted'] as $metadataType) {
            if (true === is_a($metadata, $metadataType)) {
                $metadataAllowed = true;
            }
        }

        if (false === $metadataAllowed) {
            throw new \InvalidArgumentException(
                sprintf('The metadata of type "%s" are not allowed in this generator ', get_class($metadata))
            );
        }
    }
}
