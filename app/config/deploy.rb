set :application, "ventoonirico"
set :domain,      "ventoonirico.danilosanchi.net"
set :deploy_to,   "/var/www/vhosts/ventoonirico.danilosanchi.net/symfony_projects/"

role :web,        domain
role :app,        domain                    
role :db,         domain, :primary => true 

set :serverName, "sg111.servergrove.com" # The server's hostname
set :repository,  "git@github.com:danielsan80/ventoonirico.git"
set :scm,         :git

set :deploy_via,      :rsync_with_remote_cache
set :user,       "ventoonirico"
ssh_options[:port] = 22123

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
end

after "deploy:update", "deploy:cleanup"

after "deploy" do
    dan.cc
end

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

#        run "cd #{release_path} && rm -Rf app/logs"
#        run "cd #{release_path} && rm -Rf app/sessions"
#        run "cd #{release_path} && rm -Rf app/spool"
#        run "cd #{release_path} && rm -Rf app/files/images/users"
#        run "cd #{release_path} && rm -Rf web/media"

        shared_links.map do |file|
            run "cd #{release_path} && ln -sf #{shared_path}/#{file} #{file}"
        end
#        run "cd #{release_path} && ln -sf #{shared_path}/vendor vendor"
#        run "cd #{release_path} && ln -sf #{shared_path}/app/config app/config"
#        run "cd #{release_path} && ln -sf #{shared_path}/app/logs app/logs"
#        run "cd #{release_path} && ln -sf #{shared_path}/app/sessions app/sessions"
#        run "cd #{release_path} && ln -sf #{shared_path}/app/spool app/spool"
#        run "cd #{release_path} && ln -sf #{shared_path}/app/files/images/users app/files/images/users"
#        run "cd #{release_path} && ln -sf #{shared_path}/web/media web/media"

    end

    desc "Link and update vendors"
    task :vendors do
        run "cd #{release_path} && curl -sS https://getcomposer.org/installer | php"
        run "cd #{release_path} && php composer.phar install"
    end


    desc "chmod writable files and dir"
    task :chmod do
        run "cd #{shared_path} && chmod -R 777 app/logs"
        run "cd #{release_path} && chmod -R 777 app/cache"
        run "cd #{release_path} && chmod -R 777 app/sessions"
        run "cd #{release_path} && chmod -R 777 app/spool"
        run "cd #{shared_path} && chmod -R 777 app/files/images/users"
        run "cd #{shared_path} && chmod -R 777 web/media"
    end

    desc "execute the migrations"
    task :migrations do
        run "cd #{release_path} && php app/console doctrine:migrations:migrate --no-interaction"
    end

    desc "clear cache"
    task :cc do
        run "rm -Rf #{current_path}/app/cache/*"
    end
end
