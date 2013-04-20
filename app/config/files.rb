
namespace :files do
  namespace :dump do
    desc "Dump remote files, download it to local"
    task :remote do
      require "fileutils"
      FileUtils.mkdir_p("backups")
      filename  = "#{application}.remote_files.#{Time.now.to_i}.gz"
      run "cd #{current_path} && tar -zcvf /tmp/#{filename} app/files/*"
      get "/tmp/#{filename}", "backups/#{filename}"
      run "rm /tmp/#{filename}"
      begin
        FileUtils.ln_sf(filename, "backups/#{application}.remote_files.latest.gz")
      rescue NotImplementedError # hack for windows which doesnt support symlinks
        FileUtils.cp_r("backups/#{filename}", "backups/#{application}.remote_files.latest.gz")
      end
    end
    desc "Dump local files"
    task :local do
      require "fileutils"
      FileUtils.mkdir_p("backups")
      filename  = "#{application}.local_files.#{Time.now.to_i}.gz"
      
      `tar -zcvf backups/#{filename} app/files/*`

      begin
        FileUtils.ln_sf(filename, "backups/#{application}.local_files.latest.gz")
      rescue NotImplementedError # hack for windows which doesnt support symlinks
        FileUtils.cp_r("backups/#{filename}", "backups/#{application}.local_files.latest.gz")
      end
    end
  end

  namespace :move do
    desc "Dump remote files, download it to local & use here"
    task :to_local do

      files.dump.remote
      files.dump.local
      require "fileutils"
      filename  = "#{application}.remote_files.latest.gz"

      `rm -Rf app/files/*`
      `tar -zxvf "backups/#{filename}"`
    end

    desc "Dump local files, load it to remote & use there"
    task :to_remote do
      
      uploads.dump.remote
      uploads.dump.local
      require "fileutils"
      filename  = "#{application}.local_files.latest.gz"

      `tar -zcvf backups/#{filename} app/files/*`
      upload("backups/#{filename}", "/tmp/#{filename}", :via => :scp)

      run "rm -Rf #{current_path}/app/files/*"
      run "tar -zxvf '/tmp/#{filename}' -C #{current_path}"

      run "rm /tmp/#{filename}"
    end
  end
end

server "danilosanchi.net", :app