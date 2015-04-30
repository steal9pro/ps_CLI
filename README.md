# Prestashop CLI
A command-line interface for prestashop CMS

Command for prestashop, which can clean the cache, add hook, change domain and attaching modules on hooks.

## Installation
* Download latest version from git
```
git clone https://github.com/Myrkotyn/ps_CLI.git
```
* Move `Dispatcher.php` to folder with overriding classes
```
mv Dispatcher.php Your-Project/override/classes/
```
* Move `CrudController.php` to folder with overriding controllers of admin
```
mv CrudController.php Your-Project/override/controllers/admin/
```
* Move `console.php` to the root of the project
```
mv console.php Your-Project/
```
* Then you can use the command from terminal
```
php console.php ?
```
