# ps_CLI
Command for prestashop, which can clean the cache, add hook, change domain and attaching modules on hooks.
## INSTALLATION

* Download latest version of console command from git
```
    git clone https://github.com/Myrkotyn/ps_CLI.git
```
* Move Dispatcher.php to folder with overriding classes
```
    mv Dispatcher.php override/classes/
```
* Move CrudController.php to folder with overriding controllers of admin
```
    mv CrudController.php override/controllers/admin/
```
* Stay console.php at root of the project
* Then you can use the command :)
```
"php console.php ?" - all information
```
