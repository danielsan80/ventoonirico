<?php

use Idephix\Idephix;
use Idephix\Extension\Deploy\Deploy;
use Idephix\Extension\PHPUnit\PHPUnit;
use Idephix\Extension\Project\Project;
use Idephix\SSH\SshClient;

$idx = new Idephix();

$idx->
    add('clean',
        function() use ($idx)
        {
            $idx->local("rm -rf app/cache/* app/logs/*");
            $idx->local("rm -rf web/media/cache/*");

        })->
    add('cc',
        function() use ($idx)
        {
            $idx->local("rm -rf app/cache/*");
        })->
    add('chmod',
        function() use ($idx)
        {
            $dirs = "app/cache app/logs app/files app/sessions web/media";
            $idx->local("chmod -R 777 ".$dirs);
            $idx->local("setfacl -Rn -m u:www-data:rwX -m u:`whoami`:rwX ".$dirs);
            $idx->local("setfacl -dRn -m u:www-data:rwX -m u:`whoami`:rwX ".$dirs);
            $idx->local("ulimit -n 1000");
        })->
    add('test:run',
        function($log = '', $show=false, $kill=false) use ($idx)
        {
            $idx->local('rm -Rf app/cache/*');
            if ($show) {
                $idx->runTask('selenium:start', '--show');
            } else {
                $idx->runTask('selenium:start');
            }
            sleep(2);
            $idx->local('phpunit -c app/');
            if ($kill) {
                $idx->runTask('selenium:stop', '--kill');
            } else {
                $idx->runTask('selenium:stop');
            }
        })->
    add('test:run-no-selenium',
        function($log = '') use ($idx)
        {
            $idx->local('rm -Rf app/cache/*');
            $idx->local('phpunit -c app --exclude-group=mink');
        })->
    add('test:run-only-selenium',
        function($log = '', $show=false, $kill=false) use ($idx)
        {
            $idx->local('rm -Rf app/cache/*');

            if ($show) {
                $idx->runTask('selenium:start', '--show');
            } else {
                $idx->runTask('selenium:start');
            }

            sleep(2);
            $idx->local('phpunit -c app --group=mink');

            if ($kill) {
                $idx->runTask('selenium:stop', '--kill');
            } else {
                $idx->runTask('selenium:stop');
            }
        })->
    add('vendors:install',
        function() use ($idx)
        {
            $idx->local('composer install');
        })->
    add('asset:install',
        function() use ($idx)
        {
            $idx->local("app/console assets:install web --symlink");
            $idx->local("app/console assetic:dump");
        })->
    add('database:reset',
        function() use ($idx)
        {
            $idx->local("app/console doctrine:database:drop --force");
            $idx->local("app/console doctrine:database:create");
            $idx->local("app/console doctrine:fixtures:load --no-interaction");
        })->
    add('database:migrate',
        function() use ($idx)
        {
            $idx->local("app/console doctrine:migrations:migrate --no-interaction");
        })->
    add('database:fixtures',
        function() use ($idx)
        {
            $idx->local("app/console doctrine:fixtures:load --no-interaction");
        })->
    add('build',
        function($show=false, $kill=false) use ($idx)
        {
            $idx->runTask('clean');
            $idx->runTask('chmod');
            $idx->runTask('vendors:install');
            $idx->runTask('database:reset');
            $idx->runTask('database:migrate');
            $idx->runTask('database:migrate');
            $idx->runTask('test:run', $show?'--show':$kill?'--kill':null, $show && $kill?'--kill':null);
        })->
    add('selenium:start',
        function($show=false) use($idx){
            file_put_contents('/tmp/selenium.log', '');
            if ($show) {
                $idx->local('java -jar bin/selenium-server-standalone-2.38.0.jar >/tmp/selenium.log 2>&1 &');
            } else {
                $idx->local('xvfb-run java -jar bin/selenium-server-standalone-2.38.0.jar >/tmp/selenium.log 2>&1 &');
            }
        })->
    add('selenium:stop',
        function($kill=false) use($idx){
            $idx->local('curl http://localhost:4444/selenium-server/driver/?cmd=shutDownSeleniumServer');
            if ($kill) {
                try {
                    $idx->local("killall Xvfb firefox");
                } catch (\Exception $e) {
                }
            }
        })
        
;
        
$idx->addLibrary('deploy', new Deploy);
$idx->addLibrary('project', new Project);
$idx->run();