# CMSsimplified for managing articles (CRUD).

The whole client-server communication must be in a JSON format. Manifestation of Domain-Driven Design experience is required.

## Install
1) `git clone https://github.com/kvush/CMSsimpified.git`
2) go to project folder
3) `composer install`
4) `php bin/console doctrine:database:create`
5) `php bin/console doctrine:migrations:migrate`

Run local server, you may need to [install Symfony CLI](https://symfony.com/download)
6) `symfony serve:start`
