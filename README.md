<p align="center"><img src="public/images/logo.svg"></p>

## Expense Manager
The Expense Manager is a web application that should give you a simple way to track all of your expenses. Mostly every
day we pay something in cash and don't see how much we actually spend on different sort of things. That's where Expense
Manager comes in. Create new expense types and digitalize all your bills and expenses. Keep track of them and get to 
know your cost of living.


## Installation
1. Run `composer install` in order to install all the necessary dependencies
2. Rename `.env.example` to `.env`
3. In the `.env` file, take all the necessary customizations
    - Your language code (currently supported is `en` and `de`)
    - Currency
    - Decimal point and thousand separator
    - Date format
    - Database connection strings
4. Run the command `php artisan setup:expensemanager` to create all tables and set up the default user
5. Point the webserver to the `public` folder 
6. Log in with the user `admin` and the password `12345678` and start using Expense Manager 


## Contributing
If you have got an idea for the Expense Manager or you are finding a bug - simply create new issue or a pull request. I 
am glad to see a response.

And if you want to contribute to the translation or add an entirely new one, very welcomed :-D

### Ideas that are already on the To-Do list
- Add a picture or a PDF of the bill/invoice
- Search functionality and filtering
- Drop Bootstrap CSS and use Tailwind (maybe, if there is time)
- Updater


## License
Expense Manager is open-sourced software, built with Laravel, licensed under the [MIT license](https://opensource.org/licenses/MIT).
