<?php
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Input\InputOption;
/*
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
 * and is licensed under the MIT license
 */

chdir(realpath('./'));

if (!(@include_once __DIR__ . '/../vendor/autoload.php') && !(@include_once __DIR__ . '/../../../autoload.php')) {
    throw new RuntimeException('Error: vendor/autoload.php could not be found. Did you run php composer.phar install?');
}

if(is_dir('data') === false) {
    mkdir('data/');
}
if(is_dir('data/crudGenerator') === false) {
    mkdir('data/crudGenerator');
}
if(is_dir('data/crudGenerator/History') === false) {
    mkdir('data/crudGenerator/History');
}
if(is_dir('data/crudGenerator/Config') === false) {
    mkdir('data/crudGenerator/Config');
}

if(is_writable('data/crudGenerator') === false) {
    throw new Exception('data/crudGenerator is not writable');
}
if(is_writable('data/crudGenerator/History') === false) {
    throw new Exception('data/crudGenerator/History is not writable');
}
if(is_writable('data/crudGenerator/Config') === false) {
    throw new Exception('data/crudGenerator/Config is not writable');
}

$output = new Symfony\Component\Console\Output\ConsoleOutput();
$input  = new Symfony\Component\Console\Input\ArrayInput(array());
$cli    = CrudGenerator\Service\CliFactory::getInstance($output);
$cli->run($input, $output);
