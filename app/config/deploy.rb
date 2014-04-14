server "158.58.169.11", :app, :web, :db, :primary => true

set :application, "ventoonirico"
set :domain,      "ventoonirico.danilosanchi.net"
set :deploy_to,   "/var/www/ventoonirico"

#role :web,        domain
#role :app,        domain                    
#role :db,         domain, :primary => true 

set :serverName, "158.58.169.11" # The server's hostname
set :repository,  "git@github.com:danielsan80/ventoonirico.git"
set :scm,         :git

set :deploy_via,      :rsync_with_remote_cache
set :user,       "root"
ssh_options[:port] = 22

set :shared_dirs,   ["app/config", "app/logs", "app/sessions", "app/spool", "app/files/images/users", "web/media"]
set :shared_links,  ["app/logs", "app/sessions", "app/spool", "app/files/images/users", "web/media", "app/config/parameters.yml"]

set  :use_sudo,      false

set  :keep_releases,  3

after "deploy:setup" ,"dan:setup"

before "deploy:finalize_update" do
    dan.symlinks
    run "cd #{release_path} && chmod -R 777 app/cache"
    run "cp -R #{current_path}/vendor #{release_path}/"
    dan.chmod
    dan.vendors
    dan.migrations
    dan.cc
    dan.cache_warmup
    dan.chmod
end

after "deploy:update", "deploy:cleanup"

namespace :dan do

    desc "Setup the shared dir"
    task :setup do
      dirs = []
      dirs += shared_dirs.map { |d| File.join(shared_path, d) }
      run "#{try_sudo} mkdir -p #{dirs.join(' ')} && #{try_sudo} chmod g+w #{dirs.join(' ')}"
    end

    desc "create the symlinks"
    task :symlinks do
        files = []
        files += shared_links.map { |d| File.join(release_path, d) }
        run "#{try_sudo} rm -Rf #{files.join(' ')}"

        shared_links.map do |file|
            run "cd #{release_path} && ln -sf #{shared_path}/#{file} #{file}"
        end
    end

    desc "Install vendors"
    task :vendors do
        run "cd #{release_path} && curl -sS https://getcomposer.org/installer | php"
        run "cd #{release_path} && php composer.phar install"
    end

    desc "chmod writable files and dir"
    task :chmod do
        run "cd #{release_path} && chmod -R 777 app/cache"
        run "cd #{shared_path} && chmod -R 777 app/logs app/sessions app/spool"
        run "cd #{shared_path} && chmod -R 777 app/files/images/users"
        run "cd #{shared_path} && chmod -R 777 web/media"
    end

    desc "execute the migrations"
    task :migrations do
        run "cd #{release_path} && php app/console doctrine:migrations:migrate --no-interaction"
    end

    desc "cache warmup"
    task :cache_warmup do
        run "cd #{release_path} && php app/console --env=prod cache:warmup"
    end

    desc "clear cache"
    task :cc do
#        run "cd #{release_path} && php app/console apc:cc"
        run "cd #{release_path} && rm -Rf app/cache/*"
        run "cd #{release_path} && chmod -R 777 app/cache"
    end

end
