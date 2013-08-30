<?php
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


ini_set('display_errors', true);
chdir(realpath('./'));

if (!(@include_once __DIR__ . '/../vendor/autoload.php') && !(@include_once __DIR__ . '/../../../autoload.php')) {
    throw new RuntimeException('Error: vendor/autoload.php could not be found. Did you run php composer.phar install?');
}

if(!is_dir('data')) {
    mkdir('data/');
}
if(!is_dir('data/crudGenerator')) {
    mkdir('data/crudGenerator');
}
if(!is_dir('data/crudGenerator/History')) {
    mkdir('data/crudGenerator/History');
}
if(!is_dir('data/crudGenerator/Config')) {
    mkdir('data/crudGenerator/Config');
}

if(!is_writable('data/crudGenerator')) {
    throw new Exception('data/crudGenerator is not writable');
}
if(!is_writable('data/crudGenerator/History')) {
    throw new Exception('data/crudGenerator/History is not writable');
}
if(!is_writable('data/crudGenerator/Config')) {
    throw new Exception('data/crudGenerator/Config is not writable');
}

$input = new Symfony\Component\Console\Input\ArgvInput();
$output = new Symfony\Component\Console\Output\ConsoleOutput();
$cli = CrudGenerator\Service\CliFactory::getInstance($input, $output);
$cli->run($input, $output);
