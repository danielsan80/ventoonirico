My Symfony App
========================

This repo is a Symfony (2.1) App. It is not to install, I use it to have several **snapshots**
of a generic Symfony application in several **building phases**.

Each branch is one of this phases. The branch master keep only this README.md to explane each branch.

The idea is to mantain my own repository and fork from a specific snapshot to start with a new application.

If this idea works, my proposal is to make your own repository with your set of snapshots. Else you can fork one of mine.

If you see a mistake in this text I will gratefull if you could correct it an make me a pull request so I can **improve my written english** too.


helloworld
----------------------------------
It's a simple hello world application. One route (/) that write "Hello World!".

To install Symfony I followed this link:
* http://symfony.com/download

Then I did some basic and helpfull thinks:

* Bootstrap,
* Backbone,
* JQuery 
* a default layout is defined,
* .gitignored is set
* The Acme Demo Bundle is removed
* A Main Bundle is created
* ...

users
----------------------------------
Starting from **helloworld** I installed the FOSUserBundle and set my favorite options.
I followed this docs:
* https://github.com/FriendsOfSymfony/FOSUserBundle


admin
----------------------------------
Starting from **users** I installed the SonataAdminBundle and the SonataUserBundle
so I can manage users on powerfull and extendible backend.
I followed this docs:
* http://sonata-project.org/bundles/admin/master/doc/index.html
* http://sonata-project.org/bundles/doctrine-orm-admin/master/doc/index.html
* http://sonata-project.org/bundles/admin/master/doc/reference/installation.html
* http://sonata-project.org/bundles/admin/master/doc/reference/getting_started.html
* http://sonata-project.org/bundles/admin/master/doc/reference/configuration.html
* http://sonata-project.org/bundles/user/master/doc/reference/installation.html
* http://sonata-project.org/bundles/user/master/doc/reference/advanced_configuration.html
