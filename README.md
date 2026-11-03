<p align="center">
   <picture>
       <source srcset="/public/resources/logo_dark.svg" media="(prefers-color-scheme: dark)">
       <img align="center" width="350" alt="Logo" src="/public/resources/logo.svg">
   </picture>
</p>

## Expense Manager
The Expense Manager is a progressive web application that allows you to track all of your expenses and categorise them.
Pretty much every day we pay something and don't see how all the expenses pile up. That's where Expense Manager comes in.
Create new expense types and digitalize all your bills and expenses. Keep track of them and get to know your cost of living.
Create a ledger for yourself or another one that you share with your partner. Keep track and see the stats.


## Installation
1. Run `composer install` in order to install all the necessary dependencies
2. Rename `.env.example` to `.env`
3. In the `.env` file, take all the necessary customizations
    - Your language code (currently supported is `en` and `de`)
    - Database connection strings, only if you don't want to use SQLite
4. Run the command `php artisan setup` to create all tables
5. Point the webserver to the `public` folder 
6. Create your first user (admin user), your first ledger and start using Expense Manager

## Upgrade from Version 1
1. Download the newest version from Github or use Git
2. Run `composer install` in order to install all the necessary dependencies
3. Optionally sync your `.env` file with `.env.example`. Important: keep your `APP_KEY` and DB settings
4. Run the command `php artisan migrate:db-to-v2` to create all tables


## Contributing
If you have got an idea for the Expense Manager or you are finding a bug - simply create new issue or a pull request. I 
am glad to see a response.

And if you want to contribute to the translation or add an entirely new one, very welcomed :-D

### Ideas that are already on the To-Do list
- Add a picture or a PDF of the bill/invoice

## License
Expense Manager is open-sourced software, built with Laravel, licensed under the [MIT license](https://opensource.org/licenses/MIT).
