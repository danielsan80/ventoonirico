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

set :shared_children, ["vendor", "web", "app", "app/config", "app/logs", "app/files/images/users", "/web/media"]
set :shared_files,    ["app/config/parameters.yml", "vendor", "app/logs", "app/files/images/users", "web/media"]
set :shared_file_dir,    "."

set  :use_sudo,      false

set  :keep_releases,  3

before "deploy:finalize_update" do
    run "cd #{release_path} && rm -Rf app/logs"
    run "cd #{release_path} && rm -Rf app/files/images/users"
    run "cd #{release_path} && rm -Rf web/media"
    run "cd #{release_path} && chmod -R 777 app/cache"
end

after "deploy:update", "deploy:cleanup"

after "deploy" do
    dan.vendors
    dan.chmod
    dan.migrations
    dan.cc
end

namespace :dan do
    desc "copy old vendors"
    task :copy_vendors do
        run "cd #{shared_path} && rm -Rf _vendor"
        run "cd #{shared_path} && cp -R vendor _vendor"
        run "cd #{current_path} && rm vendor"
        run "cd #{current_path} && ln -sf #{shared_path}/_vendor vendor"
    end

    desc "Link and update vendors"
    task :vendors do
        run "cd #{current_path} && curl -sS https://getcomposer.org/installer | php"
        run "cd #{current_path} && php composer.phar install"
    end


    desc "chmod writable files and dir"
    task :chmod do
        run "cd #{shared_path} && chmod -R 777 app/logs"
        run "cd #{current_path} && chmod -R 777 app/cache"
        run "cd #{shared_path} && chmod -R 777 app/files/images/users"
        run "cd #{shared_path} && chmod -R 777 web/media"
    end

    desc "execute the migrations"
    task :migrations do
        run "cd #{current_path} && php app/console doctrine:migrations:migrate --no-interaction"
    end

    desc "clear cache"
    task :cc do
        run "rm -Rf #{current_path}/app/cache/*"
    end
end
