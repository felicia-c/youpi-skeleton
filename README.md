## Install project

`git pull [remote] [branch]`

`composer install`

`yarn install`

dev:  `yarn encore dev`

prod: `yarn encore production`

**Assets**

dev:  `yarn encore dev` ou `yarn encore dev --watch`

prod: `yarn encore production`

**Database**

`php bin/console doctrine:database:create`

then:

`php bin/console make:migration`

`php bin/console doctrine:migrations:migrate`

