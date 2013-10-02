set :application, "ventoonirico"
set :repository,  "git@github.com:danielsan80/ventoonirico.git"
#set :serverName, "sg111.servergrove.com" # The server's hostname
set :serverName, "ventoonirico.danilosanchi.net" # The server's hostname

set :scm,         :git
set :domain,      "ventoonirico.danilosanchi.net"

set :deploy_via,      :remote_cache
set :user,       "ventoonirico"
ssh_options[:port] = 22123
set :deploy_to,   "/var/www/vhosts/ventoonirico.danilosanchi.net/symfony_projects/"


role :web,        domain                         # Your HTTP server, Apache/etc
set  :use_sudo,      false

# Set some paths to be shared between versions
set :shared_children, ["vendor", "app/logs", "app/files", "web/media"]
set :shared_files,    ["app/config/parameters.yml", "vendor", "app/logs", "app/files", "web/media"]
set :shared_file_dir,    "."

set :app_path,    "app"

set :model_manager, "doctrine"
# Or: `propel`
role :app,        domain                         # This may be the same as your `Web` server
role :db,         domain, :primary => true 

set  :keep_releases,  3

# Update vendors during the deploy
#set :use_composer, true
#set :update_vendors,  false
#set :copy_vendors, false
#set :vendors_mode, "install"

#set :assets_install,        false
#set :cache_warmup,          false

before "deploy:finalize_update" do
    run "cd #{release_path} && rm -Rf app/logs"
    run "cd #{release_path} && rm -Rf web/media"
    run "cd #{release_path} && chmod -R 777 app/cache"
end

after "deploy:update", "deploy:cleanup"

after "deploy" do
    deploy.vendors
    deploy.chmod
end

namespace :deploy do
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
        run "cd #{current_path} && chmod -R 777 web/media"
    end
end
