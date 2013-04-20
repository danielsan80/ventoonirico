set :application, "ventoonirico"
set :domain,      "ventoonirico.danilosanchi.net"
set :deploy_to,   "/var/www/vhosts/danilosanchi.net/symfony_projects/projects/ventoonirico"
set :app_path,    "app"

set :serverName, "sg111.servergrove.com" # The server's hostname
set :repository,  "git@github.com:danielsan80/ventoonirico.git"
set :scm,         :git
# Or: `accurev`, `bzr`, `cvs`, `darcs`, `subversion`, `mercurial`, `perforce`, or `none`

set :deploy_via,      :rsync_with_remote_cache
set :user,       "danilosa"
ssh_options[:port] = 22123

set :model_manager, "doctrine"
# Or: `propel`

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain                         # This may be the same as your `Web` server
role :db,         domain, :primary => true 

set  :keep_releases,  3
set  :use_sudo,      false

# Update vendors during the deploy
set :use_composer, true
set :update_vendors,  true
set :copy_vendors, true
#set :vendors_mode, "install"

# Set some paths to be shared between versions
set :shared_files,    ["app/config/parameters.yml"]
set :shared_children, [app_path + "/logs", app_path + "/files"]

#after "deploy" do
#    run "cd #{current_path} && rm -Rf app/cache/prod"
#end
